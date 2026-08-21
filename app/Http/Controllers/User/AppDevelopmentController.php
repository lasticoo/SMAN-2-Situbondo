<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ColorSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppDevelopmentController extends Controller
{
    /**
     * Data konfigurasi meta aplikasi sekolah
     */
    protected array $appConfigs = [
        'elearning' => [
            'name' => 'Elearning SMADA',
            'tag' => 'ELEARNING • PEMBELAJARAN DARING DIGITAL',
            'title' => 'Elearning SMAN 2 Situbondo',
            'icon' => 'fas fa-laptop-code',
            'badge_color' => 'blue',
            'description' => 'Portal pembelajaran daring terpadu, materi pembelajaran digital, kelas interaktif, penugasan mandiri, dan evaluasi berbasis web untuk peserta didik serta tenaga pendidik SMAN 2 Situbondo.',
            'progress' => '85%',
            'progress_label' => 'Tahap Pengujian Modul Kelas & Integrasi Server',
            'cards' => [
                [
                    'title' => 'Kelas Maya & Modul Digital',
                    'icon' => 'fas fa-chalkboard-teacher',
                    'desc' => 'Akses materi pelajaran terstruktur lengkap dengan video, presentasi, dan bank modul daring.',
                    'badge' => 'Interaktif & Terpadu'
                ],
                [
                    'title' => 'Ujian & Kuis Online Mandiri',
                    'icon' => 'fas fa-tasks',
                    'desc' => 'Sistem evaluasi CBT mandiri dengan penilaian otomatis dan hasil analisis kompetensi instan.',
                    'badge' => 'CBT & Penilaian Otomatis'
                ],
                [
                    'title' => 'Presensi & Diskusi Terintegrasi',
                    'icon' => 'fas fa-comments',
                    'desc' => 'Forum diskusi kelas bersama guru pengampu dan pencatatan absensi digital per pertemuan.',
                    'badge' => 'Real-time & Tercatat'
                ],
            ],
        ],
        'video-pembelajaran' => [
            'name' => 'Video Pembelajaran Digital',
            'tag' => 'VIDEO PEMBELAJARAN • MEDIA EDUKASI STREAMING',
            'title' => 'Video Pembelajaran SMAN 2 Situbondo',
            'icon' => 'fas fa-play-circle',
            'badge_color' => 'red',
            'description' => 'Platform streaming video edukasi tematik, rekaman praktikum laboratorium, tutorial mata pelajaran, dan microlearning yang diproduksi secara resmi oleh civitas guru SMAN 2 Situbondo.',
            'progress' => '80%',
            'progress_label' => 'Tahap Kurasi Video Pembelajaran & CDN Server',
            'cards' => [
                [
                    'title' => 'Koleksi Video Guru SMADA',
                    'icon' => 'fas fa-video',
                    'desc' => 'Video penjelasan konsep materi per bab oleh guru pengajar berpengalaman dari seluruh mata pelajaran.',
                    'badge' => 'Kualitas HD 1080p'
                ],
                [
                    'title' => 'Praktikum & Laboratorium Sains',
                    'icon' => 'fas fa-flask',
                    'desc' => 'Demonstrasi eksperimen kimia, fisika, biologi, dan komputerisasi secara visual yang jelas.',
                    'badge' => 'Eksperimen Interaktif'
                ],
                [
                    'title' => 'Akses Mudah & Hemat Kuota',
                    'icon' => 'fas fa-bolt',
                    'desc' => 'Streaming berkecepatan tinggi dengan kompresi hemat kuota untuk kemudahan belajar di mana saja.',
                    'badge' => 'Fast Streaming'
                ],
            ],
        ],
        'buku-digital' => [
            'name' => 'Buku Digital & E-Library',
            'tag' => 'BUKU DIGITAL • PERPUSTAKAAN ELEKTRONIK',
            'title' => 'Buku Digital SMAN 2 Situbondo',
            'icon' => 'fas fa-book-open',
            'badge_color' => 'emerald',
            'description' => 'Katalog perpustakaan digital resmi sekolah yang menyediakan buku teks kurikulum merdeka, e-book referensi pengayaan, modul ajar, dan novel literasi pilihan untuk seluruh siswa dan guru.',
            'progress' => '90%',
            'progress_label' => 'Tahap Pengkatalogan Koleksi Buku & Lisensi Digital',
            'cards' => [
                [
                    'title' => 'Buku Teks Kurikulum Nasional',
                    'icon' => 'fas fa-book',
                    'desc' => 'Buku pegangan siswa dan guru kelas X, XI, dan XII lengkap semua mata pelajaran dalam format e-book.',
                    'badge' => 'Kurikulum Merdeka'
                ],
                [
                    'title' => 'Koleksi Pengayaan & Fiksi Edukasi',
                    'icon' => 'fas fa-bookmark',
                    'desc' => 'Karya sastra, ensiklopedia sains, ensiklopedia sejarah, dan referensi literasi komprehensif.',
                    'badge' => 'Ribuan Judul Buku'
                ],
                [
                    'title' => 'Peminjaman Online 24/7',
                    'icon' => 'fas fa-mobile-alt',
                    'desc' => 'Baca langsung secara online atau unduh ke perangkat untuk dibaca tanpa koneksi internet.',
                    'badge' => 'Offline Reading Ready'
                ],
            ],
        ],
        'literasi' => [
            'name' => 'Pojok Literasi Digital',
            'tag' => 'LITERASI • RUANG KARYA TULIS & JURNALISTIK',
            'title' => 'Pojok Literasi SMAN 2 Situbondo',
            'icon' => 'fas fa-feather-alt',
            'badge_color' => 'amber',
            'description' => 'Ruang publikasi karya tulis kreatif, puisi, cerpen, esai, artikel ilmiah remaja, majalah dinding digital, dan liputan berita karya peserta didik serta ekstrakurikuler jurnalistik SMAN 2 Situbondo.',
            'progress' => '85%',
            'progress_label' => 'Tahap Kurasi Karya Tulis & Redaksi Digital',
            'cards' => [
                [
                    'title' => 'Karya Cerpen & Puisi Siswa',
                    'icon' => 'fas fa-pen-nib',
                    'desc' => 'Wadah ekspresi sastra dan bakat menulis fiksi orisinal peserta didik SMADA Situbondo.',
                    'badge' => 'Kreatif & Berbakat'
                ],
                [
                    'title' => 'Artikel Ilmiah & Opini Populer',
                    'icon' => 'fas fa-newspaper',
                    'desc' => 'Tulisan berbasis riset ilmiah, opini sosial berwawasan lingkungan, dan teknologi terkini.',
                    'badge' => 'Wawasan Kritis'
                ],
                [
                    'title' => 'Mading Digital & Majalah SMADA',
                    'icon' => 'fas fa-images',
                    'desc' => 'Edisi berkala buletin jurnalistik sekolah dengan liputan eksklusif kegiatan dan prestasi siswa.',
                    'badge' => 'Edisi Berkala'
                ],
            ],
        ],
    ];

    /**
     * Tampilkan Halaman Aplikasi Dalam Masa Pengembangan
     */
    public function index(Request $request, ?string $type = null): View
    {
        $slug = strtolower($type ?? $request->query('app', 'elearning'));

        // Fallback jika tipe tidak dikenal
        if (!array_key_exists($slug, $this->appConfigs)) {
            $slug = 'elearning';
        }

        $app = $this->appConfigs[$slug];
        $colorSetting = ColorSetting::first();

        return view('user.apps.development', compact('app', 'slug', 'colorSetting'));
    }

    public function elearning(Request $request): View
    {
        return $this->index($request, 'elearning');
    }

    public function video(Request $request): View
    {
        return $this->index($request, 'video-pembelajaran');
    }

    public function bukuDigital(Request $request): View
    {
        return $this->index($request, 'buku-digital');
    }

    public function literasi(Request $request): View
    {
        return $this->index($request, 'literasi');
    }
}
