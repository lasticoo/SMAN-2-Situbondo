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

        // 8. Real Live Fetch & Cache for Instagram Posts (@sman2situbondoofficial / 10 Posts)
        $instagramPosts = Cache::remember('instagram_feed_sman2situbondo_live_v5', 300, function () {
            return $this->fetchInstagramFeedPosts();
        });

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
     */
    private function fetchInstagramFeedPosts(): array
    {
        $posts = [];
        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Cache-Control' => 'no-cache',
                    'Sec-Ch-Ua' => '"Not A(Brand";v="99", "Google Chrome";v="121", "Chromium";v="121"',
                    'Sec-Fetch-Dest' => 'document',
                    'Sec-Fetch-Mode' => 'navigate',
                    'Sec-Fetch-Site' => 'none',
                    'Sec-Fetch-User' => '?1',
                    'Upgrade-Insecure-Requests' => '1',
                ])
                ->get('https://www.instagram.com/sman2situbondoofficial/');

            if ($response->successful()) {
                $html = $response->body();

                // 1. Extract all img src tags containing post media
                preg_match_all('/<img[^>]+src="([^"]+)"/i', $html, $imgMatches);
                if (!empty($imgMatches[1])) {
                    foreach ($imgMatches[1] as $src) {
                        $clean = str_replace(['\\u0026', '&amp;', '\\/'], ['&', '&', '/'], $src);
                        if (str_contains($clean, 'scontent') && !str_contains($clean, 's150x150')) {
                            if (!in_array($clean, $posts)) {
                                $posts[] = $clean;
                            }
                            if (count($posts) >= 10) break;
                        }
                    }
                }

                // 2. Fallback regex to capture scontent media links in script payload
                if (count($posts) < 10) {
                    preg_match_all('#https?:\\\\?/\\\\?/scontent[^\s"\'<>]+#i', $html, $rawMatches);
                    if (!empty($rawMatches[0])) {
                        foreach ($rawMatches[0] as $raw) {
                            $clean = str_replace(['\\u0026', '&amp;', '\\/', '\\"'], ['&', '&', '/', ''], $raw);
                            if (str_contains($clean, 'scontent') && !str_contains($clean, 's150x150')) {
                                if (!in_array($clean, $posts)) {
                                    $posts[] = $clean;
                                }
                                if (count($posts) >= 10) break;
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently handle
        }

        // Fallback images if network/IG blocks direct request
        if (count($posts) < 10) {
            $fallbackAssets = [
              
            ];
            $posts = array_merge($posts, array_slice($fallbackAssets, 0, 10 - count($posts)));
        }

        return array_slice($posts, 0, 10);
    }
}
