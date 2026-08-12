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
        // 1. Fetch Active Banners ordered by sort_order
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // 2. Fetch Active Popup Events within current date range
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

        // 3. Fetch School Profile
        $schoolProfile = SchoolProfile::first();

        // 4. Fetch Top 5 Published News
        $newsList = News::where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        // 5. Fetch Top 5 Published Announcements
        $announcementsList = Announcement::where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        // 6. Fetch Student Statistics (SMADA Fact) - Total, Kelas X, XI, XII
        $studentStats = [
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

        // 7. Fetch Employee Statistics (Guru & Staf)
        $totalEmployees = Employee::where('is_active', true)->count();
        $guruCount = Employee::where('is_active', true)
            ->where(function ($q) {
                $q->where('position', 'like', '%Guru%')
                  ->orWhere('position', 'like', '%Kepala Sekolah%')
                  ->orWhere('position', 'like', '%Wakil Kepala Sekolah%')
                  ->orWhere('position', 'like', '%Pengajar%');
            })->count();
        
        $stafCount = max(0, $totalEmployees - $guruCount);

        $employeeStats = [
            'total' => $totalEmployees,
            'guru' => $guruCount,
            'staf' => $stafCount,
        ];

        // 8. Real Live Fetch for Instagram Posts (@sman2situbondoofficial / 10 Posts)
        $instagramPosts = $this->fetchInstagramFeedPosts();

        // 9. Fetch Theme Colors
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
     * Real Instagram scraping engine for @sman2situbondoofficial (10 Photos, FIFO Slide logic)
     * Rule 7: Direct live scraping without asset/DB fallbacks.
     */
    private function fetchInstagramFeedPosts(): array
    {
        $cachedPosts = Cache::get('instagram_feed_sman2situbondo_fifo', []);
        $newFetchedPhotos = [];
        $username = 'sman2situbondoofficial';

        try {
            $url = "https://www.instagram.com/api/v1/users/web_profile_info/?username={$username}";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-IG-App-ID: 936619743392459',
                'Accept: */*',
                'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
            ]);

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status === 200 && !empty($response)) {
                $json = json_decode($response, true);
                if (isset($json['data']['user']['edge_owner_to_timeline_media']['edges'])) {
                    $edges = $json['data']['user']['edge_owner_to_timeline_media']['edges'];
                    foreach ($edges as $edge) {
                        $node = $edge['node'] ?? [];
                        // 7c. Take ONLY the first photo of the post (display_url / thumbnail_src)
                        $photoUrl = $node['display_url'] ?? $node['thumbnail_src'] ?? null;
                        if ($photoUrl && !in_array($photoUrl, $newFetchedPhotos)) {
                            $newFetchedPhotos[] = $photoUrl;
                        }
                        if (count($newFetchedPhotos) >= 10) break;
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently handle exceptions
        }

        // Secondary fallback to HTML Regex extraction if API header format changes
        if (empty($newFetchedPhotos)) {
            try {
                $ch = curl_init("https://www.instagram.com/{$username}/");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36');
                $html = curl_exec($ch);
                curl_close($ch);

                if (!empty($html)) {
                    preg_match_all('/"(display_url|thumbnail_src)":"([^"]+)"/', $html, $imgMatches);
                    if (!empty($imgMatches[2])) {
                        foreach ($imgMatches[2] as $rawUrl) {
                            $cleanUrl = str_replace(['\\u0026', '\\/'], ['&', '/'], $rawUrl);
                            if (str_contains($cleanUrl, 'scontent') && !in_array($cleanUrl, $newFetchedPhotos)) {
                                $newFetchedPhotos[] = $cleanUrl;
                            }
                            if (count($newFetchedPhotos) >= 10) break;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Silently handle
            }
        }

        // 7e. FIFO Rotation & Cache Logic (Newest post enters Slide 1, Slide 10 drops off)
        if (!empty($newFetchedPhotos)) {
            if (empty($cachedPosts)) {
                $cachedPosts = array_slice($newFetchedPhotos, 0, 10);
            } else {
                foreach (array_reverse($newFetchedPhotos) as $latestPhoto) {
                    if (!in_array($latestPhoto, $cachedPosts)) {
                        array_unshift($cachedPosts, $latestPhoto);
                        if (count($cachedPosts) > 10) {
                            array_pop($cachedPosts); // Slide 10 drops off
                        }
                    }
                }
            }
            Cache::put('instagram_feed_sman2situbondo_fifo', $cachedPosts, 300);
            return $cachedPosts;
        }

        return !empty($cachedPosts) ? array_slice($cachedPosts, 0, 10) : [];
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
