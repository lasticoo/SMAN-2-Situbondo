<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ColorSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    /**
     * Tampilkan Halaman Daftar Pengumuman Publik SMAN 2 Situbondo (US-05)
     */
    public function index(Request $request)
    {
        $selectedCategory = $request->query('category') ?? $request->query('kategori');
        $searchKeyword = trim($request->query('search') ?? $request->query('q') ?? '');

        // 1. Ambil hitungan kategori secara dinamis dalam 1 query teragregasi
        $rawCounts = Announcement::published()
            ->select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $totalAll = Announcement::published()->count();

        // Standard categories default
        $categoryCounts = [
            'Semua Pengumuman' => $totalAll,
            'Akademik' => 0,
            'Kesiswaan' => 0,
            'Informasi Umum' => 0,
        ];

        // Mapping kategori dinamis dari database
        foreach ($rawCounts as $catName => $count) {
            $cleaned = trim($catName ?: 'Informasi Umum');
            if (isset($categoryCounts[$cleaned])) {
                $categoryCounts[$cleaned] += (int) $count;
            } elseif (strcasecmp($cleaned, 'umum') === 0) {
                $categoryCounts['Informasi Umum'] += (int) $count;
            } else {
                $categoryCounts[$cleaned] = (int) $count;
            }
        }

        // 2. Query Utama Daftar Pengumuman (Pilih kolom esensial untuk performa & hemat memori)
        $query = Announcement::published()
            ->select(['id', 'title', 'category', 'thumbnail_url', 'summary', 'published_at', 'created_at', 'status', 'created_by'])
            ->with(['author:id,name'])
            ->filterCategory($selectedCategory)
            ->search($searchKeyword)
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc');

        $announcements = $query->paginate(5)->withQueryString();

        // 3. Injeksi Pengaturan Warna Tema Dinamis
        $colorSetting = ColorSetting::first();

        return view('user.announcement.index', compact(
            'announcements',
            'categoryCounts',
            'selectedCategory',
            'searchKeyword',
            'colorSetting'
        ));
    }

    /**
     * Tampilkan Halaman Detail Pengumuman Lengkap (US-05 Detail)
     */
    public function show($id)
    {
        // Hanya tampilkan pengumuman yang berstatus published
        $announcement = Announcement::published()
            ->with(['author:id,name'])
            ->where('id', $id)
            ->firstOrFail();

        // Ambil pengumuman terkait lainnya (Optimasi kolom ringan)
        $otherAnnouncements = Announcement::published()
            ->select(['id', 'title', 'category', 'thumbnail_url', 'published_at', 'created_at', 'status'])
            ->where('id', '!=', $announcement->id)
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        // Injeksi Pengaturan Warna Tema Dinamis
        $colorSetting = ColorSetting::first();

        return view('user.announcement.show', compact(
            'announcement',
            'otherAnnouncements',
            'colorSetting'
        ));
    }
}
