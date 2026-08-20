<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\ColorSetting;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPageTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Humas Admin SMADA',
            'email' => 'humas@smada.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    protected function createNews(array $attributes): News
    {
        $news = new News();
        $news->title = $attributes['title'] ?? 'Berita Unggulan SMADA';
        $news->category = $attributes['category'] ?? 'Akademik';
        $news->thumbnail_url = $attributes['thumbnail_url'] ?? 'https://example.com/thumb.jpg';
        $news->summary = $attributes['summary'] ?? 'Ringkasan berita sekolah SMADA.';
        $news->content = $attributes['content'] ?? 'Isi lengkap berita sekolah SMADA.';
        $news->status = $attributes['status'] ?? 'published';
        $news->published_at = $attributes['published_at'] ?? Carbon::now();
        $news->created_by = $attributes['created_by'] ?? $this->admin->id;
        $news->created_at = $attributes['created_at'] ?? Carbon::now();
        $news->updated_at = Carbon::now();
        $news->save();

        return $news;
    }

    public function test_public_user_can_access_news_page_without_login(): void
    {
        $this->createNews([
            'title' => 'SUPERVISI KOLABORATIF BERSAMA PENGAWAS PEMBINA',
            'category' => 'Akademik',
        ]);

        $response = $this->get('/berita');

        $response->assertStatus(200);
        $response->assertSee('Berita SMADA');
        $response->assertSee('Pusat Informasi');
        $response->assertSee('SUPERVISI KOLABORATIF BERSAMA PENGAWAS PEMBINA');
        $response->assertSee('Akademik');
    }

    public function test_only_published_news_are_displayed(): void
    {
        $this->createNews([
            'title' => 'Berita Publikasi Resmi',
            'status' => 'published',
        ]);

        $this->createNews([
            'title' => 'Berita Rahasia Masih Draft',
            'status' => 'draft',
        ]);

        $response = $this->get('/berita');

        $response->assertStatus(200);
        $response->assertSee('Berita Publikasi Resmi');
        $response->assertDontSee('Berita Rahasia Masih Draft');
    }

    public function test_news_can_be_filtered_by_category(): void
    {
        $this->createNews([
            'title' => 'Juara 1 Lomba Robotika Nasional',
            'category' => 'Prestasi',
            'published_at' => Carbon::now()->subDays(1),
        ]);

        $this->createNews([
            'title' => 'Workshop Kurikulum Merdeka Guru',
            'category' => 'Akademik',
            'published_at' => Carbon::now()->subDays(2),
        ]);

        // Filter Prestasi
        $responsePrestasi = $this->get('/berita?category=Prestasi');
        $responsePrestasi->assertStatus(200);
        $this->assertEquals(1, $responsePrestasi->viewData('newsList')->count());
        $this->assertStringContainsString('Juara 1 Lomba Robotika Nasional', $responsePrestasi->viewData('newsList')->first()->title);

        // Filter Akademik
        $responseAkademik = $this->get('/berita?category=Akademik');
        $responseAkademik->assertStatus(200);
        $this->assertEquals(1, $responseAkademik->viewData('newsList')->count());
        $this->assertStringContainsString('Workshop Kurikulum Merdeka Guru', $responseAkademik->viewData('newsList')->first()->title);
    }

    public function test_news_can_be_searched_by_keyword(): void
    {
        $this->createNews([
            'title' => 'Peringatan Maulid Nabi Muhammad SAW',
            'summary' => 'Kegiatan ceramah dan sholawat bersama.',
        ]);

        $this->createNews([
            'title' => 'Turnamen Bola Basket Antar Sekolah',
            'summary' => 'Kompetisi sengit tim basket pelajar.',
        ]);

        $response = $this->get('/berita?search=Maulid');
        $response->assertStatus(200);
        $this->assertEquals(1, $response->viewData('newsList')->count());
        $this->assertStringContainsString('Maulid Nabi', $response->viewData('newsList')->first()->title);
    }

    public function test_news_can_be_sorted_by_newest_and_oldest(): void
    {
        $this->createNews([
            'title' => 'Berita Waktu Lalu',
            'published_at' => Carbon::now()->subDays(15),
        ]);

        $this->createNews([
            'title' => 'Berita Waktu Sekarang',
            'published_at' => Carbon::now(),
        ]);

        // Default / Terbaru
        $responseTerbaru = $this->get('/berita?sort=terbaru');
        $responseTerbaru->assertStatus(200);
        $this->assertStringContainsString('Berita Waktu Sekarang', $responseTerbaru->viewData('newsList')->first()->title);

        // Terlama
        $responseTerlama = $this->get('/berita?sort=terlama');
        $responseTerlama->assertStatus(200);
        $this->assertStringContainsString('Berita Waktu Lalu', $responseTerlama->viewData('newsList')->first()->title);
    }

    public function test_user_can_view_news_detail_page(): void
    {
        $news = $this->createNews([
            'title' => 'Inovasi Pembelajaran Digital SMADA',
            'category' => 'Akademik',
            'summary' => 'Ringkasan inovasi pembelajaran.',
            'content' => 'Paragraf isi lengkap inovasi teknologi pendidikan SMAN 2 Situbondo.',
            'status' => 'published',
        ]);

        $response = $this->get("/berita/{$news->id}");

        $response->assertStatus(200);
        $response->assertSee('Inovasi Pembelajaran Digital SMADA');
        $response->assertSee('Paragraf isi lengkap inovasi teknologi pendidikan');
        $response->assertSee('Lihat Semua Berita');
    }

    public function test_draft_news_detail_returns_404(): void
    {
        $news = $this->createNews([
            'title' => 'Berita Masih Draft',
            'status' => 'draft',
        ]);

        $response = $this->get("/berita/{$news->id}");
        $response->assertStatus(404);
    }

    public function test_related_news_are_displayed_in_detail_page(): void
    {
        $mainNews = $this->createNews([
            'title' => 'Berita Utama Terpilih',
            'category' => 'Prestasi',
        ]);

        $relatedNews = $this->createNews([
            'title' => 'Berita Terkait Prestasi Lainnya',
            'category' => 'Prestasi',
        ]);

        $response = $this->get("/berita/{$mainNews->id}");
        $response->assertStatus(200);
        $response->assertSee('Berita Lainnya');
        $response->assertSee('Berita Terkait Prestasi Lainnya');
    }

    public function test_category_counts_are_dynamically_calculated(): void
    {
        $this->createNews(['title' => 'News 1', 'category' => 'Akademik']);
        $this->createNews(['title' => 'News 2', 'category' => 'Akademik']);
        $this->createNews(['title' => 'News 3', 'category' => 'Prestasi']);

        $response = $this->get('/berita');
        $response->assertStatus(200);

        $counts = $response->viewData('categoryCounts');
        $this->assertEquals(3, $counts['Semua Berita']);
        $this->assertEquals(2, $counts['Akademik']);
        $this->assertEquals(1, $counts['Prestasi']);
    }

    public function test_news_json_ld_schema_is_rendered(): void
    {
        $this->createNews([
            'title' => 'Berita SEO Schema Google',
            'category' => 'Informasi',
        ]);

        $response = $this->get('/berita');
        $response->assertStatus(200);
        $response->assertSee('https://schema.org');
        $response->assertSee('CollectionPage');
        $response->assertSee('NewsArticle');
        $response->assertSee('BreadcrumbList');
        $response->assertSee('googlebot-news');
    }

    public function test_news_detail_seo_and_schema_are_rendered(): void
    {
        $news = $this->createNews([
            'title' => 'Prestasi Internasional Siswa SMADA',
            'category' => 'Prestasi',
            'summary' => 'Ringkasan prestasi internasional membanggakan.',
            'content' => 'Isi lengkap berita prestasi internasional sekolah.',
        ]);

        $response = $this->get("/berita/{$news->id}");
        $response->assertStatus(200);
        $response->assertSee('googlebot-news');
        $response->assertSee('article:published_time');
        $response->assertSee('article:section');
        $response->assertSee('Prestasi Internasional Siswa SMADA');
        $response->assertSee('Ringkasan prestasi internasional membanggakan.');
        $response->assertSee('NewsArticle');
        $response->assertSee('BreadcrumbList');
    }

    public function test_dynamic_theme_colors_are_applied(): void
    {
        ColorSetting::create([
            'primary_color' => '#0A2540',
            'secondary_color' => '#FF8A00',
            'hero_gradient_end' => '#051329',
        ]);

        $this->createNews(['title' => 'Berita Uji Warna']);

        $response = $this->get('/berita');
        $response->assertStatus(200);
        $response->assertSee('--primary-main: #0A2540', false);
        $response->assertSee('--secondary-main: #FF8A00', false);
    }
}
