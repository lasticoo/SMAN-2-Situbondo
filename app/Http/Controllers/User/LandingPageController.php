<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Popup;
use App\Models\SchoolProfile;
use App\Models\News;
use App\Models\Announcement;
use App\Models\Student;
use App\Models\Employee;
use App\Models\ColorSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class LandingPageController extends Controller
{
    /**
     * Tampilkan Landing Page Utama Website SMAN 2 Situbondo (US-01)
     */
    public function index()
    {
        // 1. Fetch Active Banners ordered by sort_order (Realtime)
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // 2. Fetch Active Popup Events within current date range (Realtime)
        $today = Carbon::today()->toDateString();
        $activePopups = Popup::where('is_active', true)
            ->where(function ($query) use ($today) {
                $query->whereNull('start_date')
                      ->orWhereDate('start_date', '<=', $today);
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                      ->orWhereDate('end_date', '>=', $today);
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $activePopup = $activePopups->first();

        // 3. Fetch School Profile (Realtime)
        $schoolProfile = SchoolProfile::first();

        // 4. Fetch Top 5 Published News (Realtime)
        $newsList = News::where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        // 5. Fetch Top 5 Published Announcements / Agenda (Realtime)
        $announcementsList = Announcement::where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        // 6. Fetch Student Statistics (SMADA Fact) (Cache-on-Read, Invalidate-on-Write)
        $studentStats = Cache::remember('landing_student_stats', 86400, function () {
            return [
                'total' => Student::where('is_public', true)->count(),
                'kelas_10' => Student::where('is_public', true)
                    ->where(function ($q) {
                        $q->where('class', 'like', 'X-%')
                          ->orWhere('class', 'like', 'X %')
                          ->orWhere('class', 'X')
                          ->orWhere('class', 'like', '10%');
                    })->count(),
                'kelas_11' => Student::where('is_public', true)
                    ->where(function ($q) {
                        $q->where('class', 'like', 'XI-%')
                          ->orWhere('class', 'like', 'XI %')
                          ->orWhere('class', 'XI')
                          ->orWhere('class', 'like', '11%');
                    })->count(),
                'kelas_12' => Student::where('is_public', true)
                    ->where(function ($q) {
                        $q->where('class', 'like', 'XII-%')
                          ->orWhere('class', 'like', 'XII %')
                          ->orWhere('class', 'XII')
                          ->orWhere('class', 'like', '12%');
                    })->count(),
            ];
        });

        // 7. Fetch Employee Statistics (Guru & Staf) (Cache-on-Read, Invalidate-on-Write)
        $employeeStats = Cache::remember('landing_employee_stats', 86400, function () {
            $totalEmployees = Employee::where('is_active', true)->count();
            $guruCount = Employee::where('is_active', true)
                ->where(function ($q) {
                    $q->where('position', 'like', '%Guru%')
                      ->orWhere('position', 'like', '%Kepala Sekolah%')
                      ->orWhere('position', 'like', '%Wakil Kepala Sekolah%')
                      ->orWhere('position', 'like', '%Pengajar%');
                })->count();
            
            $stafCount = max(0, $totalEmployees - $guruCount);

            return [
                'total' => $totalEmployees,
                'guru' => $guruCount,
                'staf' => $stafCount,
            ];
        });

        // 8. Real Live Fetch for Instagram Posts (@sman2situbondoofficial / 10 Posts - Non-blocking Cached 30 Minutes)
        $instagramPosts = Cache::remember('instagram_feed_sman2situbondo_cached_response', 1800, function () {
            return $this->fetchInstagramFeedPosts();
        });

        // 9. Fetch Theme Colors Realtime from Database (100% Dynamic)
        $colorSetting = ColorSetting::first();

        return view('user.landing.index', compact(
            'banners',
            'activePopup',
            'activePopups',
            'schoolProfile',
            'newsList',
            'announcementsList',
            'studentStats',
            'employeeStats',
            'instagramPosts',
            'colorSetting'
        ));
    }

    /**
     * Multi-Tiered Self-Healing Instagram Scraping Engine for @sman2situbondoofficial
     * Fetches live posts, saves to local storage, and loops back from Tier 4 to Tier 1-3 on subsequent refreshes.
     */
    private function fetchInstagramFeedPosts(): array
    {
        $storageDir = public_path('storage/instagram_cache');
        if (!file_exists($storageDir)) {
            @mkdir($storageDir, 0755, true);
        }

        // Check if we are in a brief Tier 4 cooldown period (e.g. 60 seconds after a failed network attempt)
        // Once this 60-second cooldown expires, the next refresh automatically retries Tier 1 -> Tier 2 -> Tier 3!
        $isInRetryCooldown = Cache::has('instagram_tier4_retry_cooldown');

        // If in short cooldown and we have disk files, serve them immediately to avoid network blocking
        if ($isInRetryCooldown) {
            $existingDiskPosts = [];
            for ($i = 1; $i <= 10; $i++) {
                $filename = 'post_' . $i . '.jpg';
                if (file_exists($storageDir . '/' . $filename) && filesize($storageDir . '/' . $filename) > 1000) {
                    $existingDiskPosts[] = '/storage/instagram_cache/' . $filename;
                }
            }
            if (count($existingDiskPosts) >= 10) {
                return $existingDiskPosts;
            }
        }

        $username = 'sman2situbondoofficial';
        $newFetchedPhotos = [];

        // =========================================================================
        // --- TIER 1: Live Instagram Profile Info API (Primary & Alternate App IDs) ---
        // =========================================================================
        $appIds = ['936619743392459', '1217981644879628'];
        foreach ($appIds as $appId) {
            try {
                $url = "https://www.instagram.com/api/v1/users/web_profile_info/?username={$username}";
                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_CONNECTTIMEOUT => 2,
                    CURLOPT_TIMEOUT => 4,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                    CURLOPT_HTTPHEADER => [
                        "X-IG-App-ID: {$appId}",
                        'Accept: */*',
                        'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8',
                        'Sec-Fetch-Mode: cors',
                        'Sec-Fetch-Site: same-origin',
                    ],
                ]);

                $response = curl_exec($ch);
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($status === 200 && !empty($response)) {
                    $json = json_decode($response, true);
                    $edges = $json['data']['user']['edge_owner_to_timeline_media']['edges'] ?? [];
                    if (!empty($edges)) {
                        foreach ($edges as $edge) {
                            $node = $edge['node'] ?? [];
                            $photoUrl = $node['display_url'] ?? $node['thumbnail_src'] ?? null;
                            if ($photoUrl && !in_array($photoUrl, $newFetchedPhotos)) {
                                $newFetchedPhotos[] = $photoUrl;
                            }
                            if (count($newFetchedPhotos) >= 10) break;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Try next App ID or next tier
            }
            if (!empty($newFetchedPhotos)) break;
        }

        // =========================================================================
        // --- TIER 2: Secondary Fallback - Direct Instagram Profile HTML Scraping ---
        // =========================================================================
        if (empty($newFetchedPhotos)) {
            try {
                $ch = curl_init("https://www.instagram.com/{$username}/");
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_CONNECTTIMEOUT => 2,
                    CURLOPT_TIMEOUT => 4,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                ]);
                $response = curl_exec($ch);
                curl_close($ch);

                if (!empty($response)) {
                    preg_match_all('/"(?:display_url|thumbnail_src)":"([^"]+)"/', $response, $matches);
                    if (!empty($matches[1])) {
                        foreach ($matches[1] as $rawUrl) {
                            $cleanUrl = str_replace(['\\u0026', '\\/'], ['&', '/'], $rawUrl);
                            if ($cleanUrl && !in_array($cleanUrl, $newFetchedPhotos)) {
                                $newFetchedPhotos[] = $cleanUrl;
                            }
                            if (count($newFetchedPhotos) >= 10) break;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Try next tier
            }
        }

        // =========================================================================
        // --- TIER 3: Download & Persist Live Photos Locally ---
        // =========================================================================
        if (!empty($newFetchedPhotos)) {
            $savedLocalPosts = [];
            foreach ($newFetchedPhotos as $i => $remoteUrl) {
                $filename = 'post_' . ($i + 1) . '.jpg';
                $localPath = $storageDir . '/' . $filename;

                try {
                    $imgCh = curl_init($remoteUrl);
                    curl_setopt_array($imgCh, [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_TIMEOUT => 6,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                    ]);
                    $imgData = curl_exec($imgCh);
                    $imgStatus = curl_getinfo($imgCh, CURLINFO_HTTP_CODE);
                    curl_close($imgCh);

                    if ($imgStatus === 200 && !empty($imgData)) {
                        @file_put_contents($localPath, $imgData);
                        $savedLocalPosts[] = '/storage/instagram_cache/' . $filename;
                    }
                } catch (\Throwable $e) {
                    // Continue to next photo
                }
            }

            if (!empty($savedLocalPosts)) {
                // Clear any fallback retry flags on success and cache normal sync
                Cache::forget('instagram_tier4_retry_cooldown');
                Cache::put('instagram_feed_sman2situbondo_local', $savedLocalPosts, 1800);
                return $savedLocalPosts;
            }
        }

        // =========================================================================
        // --- TIER 4: Persistent Disk Fallback & Scheduled Retry Trigger ---
        // =========================================================================
        // If live scraping failed, set a short 60s cooldown so on the next refresh after 60s,
        // the system automatically tries Tier 1 -> Tier 2 -> Tier 3 again!
        Cache::put('instagram_tier4_retry_cooldown', true, 60);

        $existingDiskPosts = [];
        for ($i = 1; $i <= 10; $i++) {
            $filename = 'post_' . $i . '.jpg';
            if (file_exists($storageDir . '/' . $filename) && filesize($storageDir . '/' . $filename) > 1000) {
                $existingDiskPosts[] = '/storage/instagram_cache/' . $filename;
            }
        }

        if (!empty($existingDiskPosts)) {
            return $existingDiskPosts;
        }

        return [];
    }

    /**
     * Proxy Instagram image stream to bypass client-side CORS/403 hotlink restrictions
     */
    public function proxyInstagramImage(Request $request)
    {
        $rawUrl = $request->query('url');
        if (empty($rawUrl)) {
            abort(404);
        }

        // Decode URL safe base64
        $url = strtr($rawUrl, '-_~', '+/=');
        $decoded = @base64_decode($url);
        if ($decoded && str_starts_with($decoded, 'http')) {
            $url = $decoded;
        } else {
            $url = $rawUrl;
        }

        // Domain validation
        if (!str_contains($url, 'fbcdn.net') && !str_contains($url, 'cdninstagram.com') && !str_contains($url, 'instagram.com')) {
            abort(403);
        }

        // Cache base64 encoded image string for 24 hours locally (avoids MySQL utf8mb4 binary 1366 SQL error)
        $cacheKey = 'ig_proxy_b64_' . md5($url);
        $base64Data = Cache::remember($cacheKey, 86400, function () use ($url) {
            try {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 8);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36');
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                ]);
                $data = curl_exec($ch);
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($status === 200 && !empty($data)) {
                    return base64_encode($data);
                }
            } catch (\Throwable $e) {
                // Silently handle
            }
            return null;
        });

        if (!$base64Data) {
            abort(404);
        }

        $imageData = base64_decode($base64Data);

        return response($imageData, 200)
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
