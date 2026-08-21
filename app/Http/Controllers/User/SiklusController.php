<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ColorSetting;
use Illuminate\View\View;

class SiklusController extends Controller
{
    /**
     * Tampilkan Halaman Siklus - Sistem Informasi Kelulusan Siswa (US-10)
     * Menampilkan halaman khusus "Fitur Sedang Dalam Pengembangan" yang profesional & interaktif.
     */
    public function index(): View
    {
        // 1. Ambil data tema dinamis (Realtime dari Database)
        $colorSetting = ColorSetting::first();

        return view('user.siklus.index', compact('colorSetting'));
    }
}
