<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ColorSetting;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Tampilkan Halaman Media Galeri Video SMAN 2 Situbondo (US-06)
     */
    public function index(Request $request)
    {
        $selectedCategory = trim($request->query('category') ?? $request->query('kategori') ?? '');

        // 1. Ambil Semua Nama Kategori Dinamis yang Ada di Database dari Kolom 'title'
        // Mendukung format awalan seperti [Akademik], [Kesiswaan], [Event] maupun tag judul
        $rawVideos = Video::query()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->select(['id', 'title', 'created_at'])
            ->get();

        $categories = collect();

        foreach ($rawVideos as $vid) {
            $title = $vid->title ?? '';
            // Deteksi pola [Kategori] di awal judul
            if (preg_match('/^\[(.*?)\]/', $title, $matches)) {
                $cat = trim($matches[1]);
                if (!empty($cat) && !$categories->contains($cat)) {
                    $categories->push($cat);
                }
            } elseif (preg_match('/^(Akademik|Kesiswaan|Event|Prestasi|Profil|Ekstrakurikuler|Dokumentasi)[\s\:\-]/i', $title, $matches)) {
                $cat = ucfirst(strtolower(trim($matches[1])));
                if (!empty($cat) && !$categories->contains($cat)) {
                    $categories->push($cat);
                }
            }
        }

        // Jika data video umum belum memiliki tag spesifik di title, siapkan kategori default berdasarkan data
        if ($categories->isEmpty()) {
            $categories = collect(['Akademik', 'Kesiswaan', 'Event']);
        }

        $rawCategories = $categories->unique()->values()->all();

        // 2. Filter Video pada Query
        $query = Video::query();

        if (!empty($selectedCategory) && strcasecmp($selectedCategory, 'Semua') !== 0) {
            $query->where(function ($q) use ($selectedCategory) {
                $q->where('title', 'like', "%[{$selectedCategory}]%")
                  ->orWhere('title', 'like', "%{$selectedCategory}%");
            });
        }

        // 3. Urutkan: Video terbaru di-upload (created_at desc, id desc) selalu tampil paling awal
        $videos = $query->select(['id', 'youtube_url', 'youtube_id', 'thumbnail_url', 'title', 'sort_order', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(6)
            ->withQueryString();

        // 4. Transform / Normalisasi YouTube ID & URL Thumbnail untuk setiap video
        $videos->getCollection()->transform(function ($video) {
            if (empty($video->youtube_id) && !empty($video->youtube_url)) {
                $video->youtube_id = $this->extractYoutubeId($video->youtube_url);
            }
            return $video;
        });

        // 5. Injeksi Pengaturan Warna Tema Dinamis
        $colorSetting = ColorSetting::first();

        return view('user.video.index', compact(
            'videos',
            'rawCategories',
            'selectedCategory',
            'colorSetting'
        ));
    }

    /**
     * Ekstraksi YouTube Video ID dari berbagai variasi URL YouTube
     */
    private function extractYoutubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/ ]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
