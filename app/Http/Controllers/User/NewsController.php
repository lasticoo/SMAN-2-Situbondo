<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ColorSetting;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    /**
     * Tampilkan Halaman Daftar Berita Publik SMAN 2 Situbondo (US-07)
     */
    public function index(Request $request)
    {
        $selectedCategory = trim($request->query('category') ?? $request->query('kategori') ?? '');
        $searchKeyword = trim($request->query('search') ?? $request->query('q') ?? '');
        $sortOrder = strtolower(trim($request->query('sort') ?? 'terbaru'));

        // 1. Ambil hitungan kategori secara dinamis dari database untuk berita published
        $rawCounts = News::published()
            ->select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $totalAll = News::published()->count();

        // Kategori standar default
        $categoryCounts = [
            'Semua Berita' => $totalAll,
        ];

        // Mapping kategori dinamis dari database
        foreach ($rawCounts as $catName => $count) {
            $cleaned = trim($catName ?: 'Umum');
            if ($cleaned !== '') {
                $categoryCounts[$cleaned] = (int) $count;
            }
        }

        // 2. Query Utama Daftar Berita (Query Builder / Eloquent)
        $query = News::published()
            ->select(['id', 'title', 'category', 'thumbnail_url', 'summary', 'published_at', 'created_at', 'status', 'created_by'])
            ->with(['author:id,name']);

        // Filter Kategori
        if (!empty($selectedCategory) && !in_array(strtolower($selectedCategory), ['all', 'semua', 'semua berita'])) {
            $query->where(function ($q) use ($selectedCategory) {
                $q->where('category', $selectedCategory);
                if (strcasecmp($selectedCategory, 'umum') === 0 || strcasecmp($selectedCategory, 'informasi') === 0) {
                    $q->orWhereNull('category')
                      ->orWhere('category', '');
                }
            });
        }

        // Filter Pencarian Judul, Ringkasan, atau Konten
        if (!empty($searchKeyword)) {
            $query->where(function ($q) use ($searchKeyword) {
                $q->where('title', 'like', "%{$searchKeyword}%")
                  ->orWhere('summary', 'like', "%{$searchKeyword}%")
                  ->orWhere('content', 'like', "%{$searchKeyword}%");
            });
        }

        // Pengurutan (Sorting) - Mendukung published_at null dengan fallback created_at
        if ($sortOrder === 'terlama') {
            $query->orderByRaw('COALESCE(published_at, created_at) ASC')->orderBy('id', 'asc');
        } else {
            // Default: Terbaru
            $query->orderByRaw('COALESCE(published_at, created_at) DESC')->orderBy('id', 'desc');
        }

        $newsList = $query->paginate(6)->withQueryString();

        // 3. Injeksi Pengaturan Warna Tema Dinamis
        $colorSetting = ColorSetting::first();

        return view('user.news.index', compact(
            'newsList',
            'categoryCounts',
            'selectedCategory',
            'searchKeyword',
            'sortOrder',
            'colorSetting'
        ));
    }

    /**
     * Tampilkan Halaman Detail Berita Lengkap (US-07 Detail)
     */
    public function show($id)
    {
        // Hanya tampilkan berita yang berstatus published
        $news = News::published()
            ->with(['author:id,name'])
            ->where('id', $id)
            ->firstOrFail();

        // Ambil berita terkait lainnya
        $otherNews = News::published()
            ->select(['id', 'title', 'category', 'thumbnail_url', 'summary', 'published_at', 'created_at', 'status'])
            ->where('id', '!=', $news->id)
            ->when(!empty($news->category), function ($q) use ($news) {
                $q->where('category', $news->category);
            })
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        // Jika berita se-kategori kurang dari 4, lengkapi dengan berita terbaru umum
        if ($otherNews->count() < 4) {
            $needed = 4 - $otherNews->count();
            $excludeIds = $otherNews->pluck('id')->push($news->id)->all();
            
            $additionalNews = News::published()
                ->select(['id', 'title', 'category', 'thumbnail_url', 'summary', 'published_at', 'created_at', 'status'])
                ->whereNotIn('id', $excludeIds)
                ->orderByRaw('COALESCE(published_at, created_at) DESC')
                ->orderBy('id', 'desc')
                ->take($needed)
                ->get();

            $otherNews = $otherNews->concat($additionalNews);
        }

        // Ambil hitungan kategori untuk sidebar
        $rawCounts = News::published()
            ->select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $totalAll = News::published()->count();
        $categoryCounts = ['Semua Berita' => $totalAll];
        foreach ($rawCounts as $catName => $count) {
            $cleaned = trim($catName ?: 'Informasi');
            $categoryCounts[$cleaned] = (int) $count;
        }

        // Injeksi Pengaturan Warna Tema Dinamis
        $colorSetting = ColorSetting::first();

        return view('user.news.show', compact(
            'news',
            'otherNews',
            'categoryCounts',
            'colorSetting'
        ));
    }
}
