<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\ColorSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AnnouncementPageTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Admin Pengumuman',
            'email' => 'admin.pengumuman@smada.sch.id',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_user_can_access_announcement_index_page_without_login(): void
    {
        $response = $this->get(route('announcement.index'));

        $response->assertStatus(200);
        $response->assertSee('Kategori');
        $response->assertSee('Semua Pengumuman');
        $response->assertSee('Cari Pengumuman');
    }

    public function test_user_can_access_announcement_via_alias_routes(): void
    {
        $responseAgenda = $this->get('/agenda');
        $responseAgenda->assertStatus(200);

        $responseInfo = $this->get('/informasi');
        $responseInfo->assertStatus(200);
    }

    public function test_announcement_index_only_displays_published_announcements(): void
    {
        $published = Announcement::create([
            'title' => 'Pengumuman Resmi Terbuka 2026',
            'category' => 'Akademik',
            'thumbnail_url' => '/build/assets/banner smada.png',
            'summary' => 'Ringkasan pengumuman publik yang tayang.',
            'content' => 'Isi lengkap pengumuman publik yang tayang untuk semua siswa.',
            'status' => 'published',
            'published_at' => Carbon::now()->subHour(),
            'created_by' => $this->admin->id,
        ]);

        $draft = Announcement::create([
            'title' => 'Draft Rahasia Internal Sekolah',
            'category' => 'Kesiswaan',
            'thumbnail_url' => '/build/assets/banner smada.png',
            'summary' => 'Ringkasan draft rahasia yang belum terbit.',
            'content' => 'Isi lengkap draft rahasia.',
            'status' => 'draft',
            'published_at' => null,
            'created_by' => $this->admin->id,
        ]);

        $response = $this->get(route('announcement.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengumuman Resmi Terbuka 2026');
        $response->assertDontSee('Draft Rahasia Internal Sekolah');
    }

    public function test_user_can_filter_announcements_by_category(): void
    {
        $akademik = Announcement::create([
            'title' => 'Pengumuman Ujian Tengah Semester',
            'category' => 'Akademik',
            'thumbnail_url' => '/build/assets/banner smada.png',
            'summary' => 'Jadwal UTS semester genap.',
            'content' => 'Isi lengkap jadwal UTS semester genap.',
            'status' => 'published',
            'published_at' => Carbon::now()->subDays(1),
            'created_by' => $this->admin->id,
        ]);

        $kesiswaan = Announcement::create([
            'title' => 'Seleksi Anggota Paskibra Sekolah',
            'category' => 'Kesiswaan',
            'thumbnail_url' => '/build/assets/banner smada.png',
            'summary' => 'Pendaftaran seleksi paskibraka.',
            'content' => 'Isi lengkap seleksi paskibraka sekolah.',
            'status' => 'published',
            'published_at' => Carbon::now()->subDays(2),
            'created_by' => $this->admin->id,
        ]);

        $responseAkademik = $this->get(route('announcement.index', ['category' => 'Akademik']));
        $responseAkademik->assertStatus(200);
        $responseAkademik->assertSee('Pengumuman Ujian Tengah Semester');
        $responseAkademik->assertDontSee('Seleksi Anggota Paskibra Sekolah');

        $responseKesiswaan = $this->get(route('announcement.index', ['category' => 'Kesiswaan']));
        $responseKesiswaan->assertStatus(200);
        $responseKesiswaan->assertSee('Seleksi Anggota Paskibra Sekolah');
        $responseKesiswaan->assertDontSee('Pengumuman Ujian Tengah Semester');
    }

    public function test_user_can_search_announcements_by_keyword(): void
    {
        Announcement::create([
            'title' => 'Sosialisasi Beasiswa Prestasi Unggulan',
            'category' => 'Akademik',
            'thumbnail_url' => '/build/assets/banner smada.png',
            'summary' => 'Informasi beasiswa untuk siswa berprestasi tingkat provinsi.',
            'content' => 'Persyaratan pengajuan beasiswa prestasi tahun 2026.',
            'status' => 'published',
            'published_at' => Carbon::now()->subDays(3),
            'created_by' => $this->admin->id,
        ]);

        Announcement::create([
            'title' => 'Kerja Bakti Peduli Lingkungan Sekolah',
            'category' => 'Informasi Umum',
            'thumbnail_url' => '/build/assets/banner smada.png',
            'summary' => 'Gotong royong membersihkan taman sekolah.',
            'content' => 'Jadwal kerja bakti bersama seluruh civitas.',
            'status' => 'published',
            'published_at' => Carbon::now()->subDays(4),
            'created_by' => $this->admin->id,
        ]);

        $response = $this->get(route('announcement.index', ['search' => 'Beasiswa']));

        $response->assertStatus(200);
        $response->assertSee('Sosialisasi Beasiswa Prestasi Unggulan');
        $response->assertDontSee('Kerja Bakti Peduli Lingkungan Sekolah');
    }

    public function test_user_can_view_announcement_detail_page(): void
    {
        $announcement = Announcement::create([
            'title' => 'Pembukaan Pendaftaran SPMB Smada 2026',
            'category' => 'Informasi Umum',
            'thumbnail_url' => '/build/assets/banner smada.png',
            'summary' => 'Jalur pendaftaran siswa baru tahun ajaran 2026/2027.',
            'content' => 'Informasi lengkap mengenai jadwal verifikasi berkas dan persyaratan teknis pendaftaran siswa baru.',
            'status' => 'published',
            'published_at' => Carbon::now()->subDays(1)->setTime(9, 30),
            'created_by' => $this->admin->id,
        ]);

        $response = $this->get(route('announcement.show', $announcement->id));

        $response->assertStatus(200);
        $response->assertSee('Pembukaan Pendaftaran SPMB Smada 2026');
        $response->assertSee('Informasi lengkap mengenai jadwal verifikasi berkas');
        $response->assertSee('09:30 WIB');
        $response->assertSee('Humas &amp; Publikasi SMAN 2 Situbondo', false);
        $response->assertSee('Hubungi via WhatsApp');
    }

    public function test_draft_announcement_detail_returns_404(): void
    {
        $draft = Announcement::create([
            'title' => 'Draft Pengumuman Tertutup',
            'category' => 'Akademik',
            'thumbnail_url' => '/build/assets/banner smada.png',
            'summary' => 'Summary draft.',
            'content' => 'Content draft.',
            'status' => 'draft',
            'published_at' => null,
            'created_by' => $this->admin->id,
        ]);

        $response = $this->get(route('announcement.show', $draft->id));

        $response->assertStatus(404);
    }

    public function test_announcement_detail_has_seo_tags_and_json_ld(): void
    {
        $announcement = Announcement::create([
            'title' => 'Pengumuman Penting Hasil Seleksi OSN',
            'category' => 'Akademik',
            'thumbnail_url' => '/build/assets/banner smada.png',
            'summary' => 'Selamat kepada siswa yang lolos seleksi OSN tingkat kabupaten.',
            'content' => 'Daftar nama pemenang olimpiade sains nasional tingkat kabupaten.',
            'status' => 'published',
            'published_at' => Carbon::now()->subHours(5),
            'created_by' => $this->admin->id,
        ]);

        $response = $this->get(route('announcement.show', $announcement->id));

        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta property="og:title"', false);
        $response->assertSee('<script type="application/ld+json">', false);
        $response->assertSee('NewsArticle', false);
    }

    public function test_announcement_page_injects_dynamic_theme_colors(): void
    {
        ColorSetting::create([
            'primary_color' => '#0A4D68',
            'secondary_color' => '#088395',
        ]);

        $response = $this->get(route('announcement.index'));

        $response->assertStatus(200);
        $response->assertSee('--primary-main: #0A4D68;', false);
        $response->assertSee('--secondary-gold: #088395;', false);
    }
}
