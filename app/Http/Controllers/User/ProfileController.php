<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use App\Models\ColorSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan Halaman Profil SMAN 2 Situbondo (US-02)
     * Menampilkan Visi, Misi, Tujuan, Sejarah Singkat, & Struktur Organisasi
     * Data diambil secara realtime dari database sehingga setiap perubahan di DB langsung tampil saat refresh.
     */
    public function index(): View
    {
        // 1. Ambil data profil sekolah terkini (realtime)
        $profile = SchoolProfile::orderBy('id', 'asc')->first();

        // 2. Ambil pengaturan warna dinamis terkini (realtime)
        $colorSetting = ColorSetting::first();

        return view('user.profile.index', compact('profile', 'colorSetting'));
    }
}

