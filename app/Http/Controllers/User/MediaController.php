<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ColorSetting;
use App\Models\Gallery;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Tampilkan Halaman Media Galeri Foto Kegiatan SMAN 2 Situbondo (US-06)
     */
    public function index(Request $request)
    {
        $selectedCategory = trim($request->query('category') ?? $request->query('kategori') ?? $request->query('activity') ?? '');

        // 1. Ambil Semua Nama Activity Dinamis yang Ada di Database (Terurut dari yang Paling Baru Di-upload)
        $rawActivities = Gallery::whereNotNull('activity_name')
            ->where('activity_name', '!=', '')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->orderBy('activity_date', 'desc')
            ->pluck('activity_name')
            ->map(function ($name) {
                return trim(preg_replace('/^\[.*?\]\s*/', '', $name));
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        // 2. Filter Activity pada Query
        $query = Gallery::query();

        if (!empty($selectedCategory) && strcasecmp($selectedCategory, 'Semua') !== 0) {
            $query->where('activity_name', 'like', "%{$selectedCategory}%");
        }

        // 3. Urutkan: Foto/Aktivitas terbaru di-upload (created_at desc, id desc, activity_date desc) tampil pertama
        $galleries = $query->select(['id', 'photo_url', 'activity_name', 'activity_date', 'sort_order', 'created_at', 'created_by'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->orderBy('activity_date', 'desc')
            ->paginate(5)
            ->withQueryString();

        // 4. Injeksi Pengaturan Warna Tema Dinamis
        $colorSetting = ColorSetting::first();

        // Siapkan variabel $activities untuk kompatibilitas backward
        $activities = array_merge(['Semua'], $rawActivities);

        return view('user.media.index', compact(
            'galleries',
            'rawActivities',
            'activities',
            'selectedCategory',
            'colorSetting'
        ));
    }
}
