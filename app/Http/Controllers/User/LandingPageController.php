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

        // 2. Fetch Active Popup Event within current date range
        $today = Carbon::today()->toDateString();
        $activePopup = Popup::where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->latest('id')
            ->first();

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

        // 6. Fetch Student Statistics (SMADA Fact)
        $studentStats = [
            'total' => Student::where('is_public', true)->count(),
            'kelas_10' => Student::where('is_public', true)
                ->where(function ($q) {
                    $q->where('class', 'like', 'X %')
                      ->orWhere('class', 'X')
                      ->orWhere('class', 'like', '10%');
                })->count(),
            'kelas_11' => Student::where('is_public', true)
                ->where(function ($q) {
                    $q->where('class', 'like', 'XI %')
                      ->orWhere('class', 'XI')
                      ->orWhere('class', 'like', '11%');
                })->count(),
            'kelas_12' => Student::where('is_public', true)
                ->where(function ($q) {
                    $q->where('class', 'like', 'XII %')
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

        // 8. Fetch Instagram Feed (Atmosfer Sekolah - Top 10 Posts)
        $instagramPosts = Cache::remember('instagram_feed_sman2situbondo', 3600, function () {
            return $this->fetchInstagramFeedPosts();
        });

        // 9. Fetch Theme Colors
        $colorSetting = ColorSetting::first();

        return view('user.landing.index', compact(
            'banners',
            'activePopup',
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
     * Helper method to fetch top 10 Instagram posts from official account
     */
    private function fetchInstagramFeedPosts(): array
    {
        $posts = [];
        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                ])
                ->get('https://www.instagram.com/sman2situbondoofficial/');

            if ($response->successful()) {
                $html = $response->body();
                // Match image URLs from page content
                preg_match_all('/"(https:\/\/scontent[^"]+)"/', $html, $matches);
                if (!empty($matches[1])) {
                    $uniqueUrls = array_unique($matches[1]);
                    foreach ($uniqueUrls as $url) {
                        $cleanUrl = str_replace('\\u0026', '&', $url);
                        $posts[] = $cleanUrl;
                        if (count($posts) >= 10) break;
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently handle connection timeout / block
        }

        // Return max 10 photos array
        return array_slice($posts, 0, 10);
    }
}
