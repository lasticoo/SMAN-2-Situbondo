<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\ColorSetting;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoPageTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Humas Admin',
            'email' => 'humas@smada.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    protected function createVideoRecord(array $attributes): Video
    {
        $video = new Video();
        $video->title = $attributes['title'] ?? 'Video Dokumentasi SMAN 2 Situbondo';
        $video->youtube_url = $attributes['youtube_url'] ?? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
        $video->youtube_id = array_key_exists('youtube_id', $attributes) ? $attributes['youtube_id'] : 'dQw4w9WgXcQ';
        $video->thumbnail_url = $attributes['thumbnail_url'] ?? null;
        $video->sort_order = $attributes['sort_order'] ?? 0;
        $video->created_by = $attributes['created_by'] ?? $this->admin->id;
        $video->created_at = $attributes['created_at'] ?? Carbon::now();
        $video->updated_at = Carbon::now();
        $video->save();

        return $video;
    }

    public function test_public_user_can_access_media_video_page_without_login(): void
    {
        $this->createVideoRecord([
            'title' => '[Akademik] Praktikum Kimia Lintas Minat Kelas XII',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
        ]);

        $response = $this->get('/video');

        $response->assertStatus(200);
        $response->assertSee('Galeri Video SMADA');
        $response->assertSee('Praktikum Kimia Lintas Minat Kelas XII');
    }

    public function test_videos_are_displayed_with_thumbnails_and_titles(): void
    {
        $this->createVideoRecord([
            'title' => '[Event] Peringatan Hari Guru Nasional 2024 di SMADA',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'thumbnail_url' => 'https://example.com/custom_thumb.jpg',
        ]);

        $response = $this->get('/video');

        $response->assertStatus(200);
        $response->assertSee('Peringatan Hari Guru Nasional 2024 di SMADA');
        $response->assertSee('https://example.com/custom_thumb.jpg');
        $response->assertSee('Tonton Video');
    }

    public function test_video_can_be_filtered_by_dynamic_category(): void
    {
        $this->createVideoRecord([
            'title' => '[Akademik] Olimpiade Sains Nasional 2024',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        $this->createVideoRecord([
            'title' => '[Kesiswaan] Turnamen Futsal Smada Cup Antar Kelas',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // Filter Akademik
        $responseAkademik = $this->get('/video?category=Akademik');
        $responseAkademik->assertStatus(200);
        $this->assertEquals(1, $responseAkademik->viewData('videos')->count());
        $this->assertStringContainsString('Olimpiade Sains Nasional 2024', $responseAkademik->viewData('videos')->first()->title);

        // Filter Kesiswaan
        $responseKesiswaan = $this->get('/video?category=Kesiswaan');
        $responseKesiswaan->assertStatus(200);
        $this->assertEquals(1, $responseKesiswaan->viewData('videos')->count());
        $this->assertStringContainsString('Turnamen Futsal Smada Cup Antar Kelas', $responseKesiswaan->viewData('videos')->first()->title);
    }

    public function test_videos_are_ordered_with_newest_upload_first(): void
    {
        $this->createVideoRecord([
            'title' => '[Event] Video Upload Lama',
            'created_at' => Carbon::now()->subDays(10),
        ]);

        $this->createVideoRecord([
            'title' => '[Event] Video Upload Terbaru',
            'created_at' => Carbon::now(),
        ]);

        $response = $this->get('/video');
        $response->assertStatus(200);

        // Assert 1: Newest video is at position 0
        $videos = $response->viewData('videos');
        $this->assertStringContainsString('Video Upload Terbaru', $videos->first()->title);

        // Assert 2: In HTML, newest upload appears before old upload
        $content = $response->getContent();
        $posNewest = strpos($content, 'Video Upload Terbaru');
        $posOld = strpos($content, 'Video Upload Lama');

        $this->assertTrue($posNewest !== false && $posOld !== false);
        $this->assertTrue($posNewest < $posOld);
    }

    public function test_youtube_id_is_extracted_from_youtube_url_when_null(): void
    {
        $this->createVideoRecord([
            'title' => 'Video Ekstraksi YouTube ID',
            'youtube_url' => 'https://youtu.be/abc123XYZ99',
            'youtube_id' => null,
        ]);

        $response = $this->get('/video');
        $response->assertStatus(200);
        $this->assertStringContainsString('Video Ekstraksi YouTube ID', $response->getContent());
        $videos = $response->viewData('videos');
        $this->assertEquals('abc123XYZ99', $videos->first()->youtube_id);
    }

    public function test_auto_youtube_thumbnail_is_used_when_thumbnail_url_is_null(): void
    {
        $this->createVideoRecord([
            'title' => 'Video Tanpa Input Thumbnail',
            'youtube_url' => 'https://www.youtube.com/watch?v=LuKBQw8ubXE',
            'youtube_id' => 'LuKBQw8ubXE',
            'thumbnail_url' => null, // Tidak ada input thumbnail
        ]);

        $response = $this->get('/video');
        $response->assertStatus(200);
        
        // Assert: Otomatis menggunakan URL thumbnail resmi dari YouTube CDN
        $response->assertSee('https://img.youtube.com/vi/LuKBQw8ubXE/hqdefault.jpg');
    }

    public function test_google_video_seo_schema_json_ld_is_rendered(): void
    {
        $this->createVideoRecord([
            'title' => '[Profil] Video Profil Sekolah SMAN 2 Situbondo',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
        ]);

        $response = $this->get('/video');
        $response->assertStatus(200);

        $response->assertSee('VideoObject', false);
        $response->assertSee('CollectionPage', false);
        $response->assertSee('Video Profil Sekolah SMAN 2 Situbondo', false);
    }

    public function test_dynamic_color_setting_is_applied_to_video_page(): void
    {
        ColorSetting::create([
            'primary_color' => '#0A2540',
            'secondary_color' => '#FFB703',
        ]);

        $response = $this->get('/video');
        $response->assertStatus(200);
        $response->assertSee('#0A2540');
        $response->assertSee('#FFB703');
    }

    public function test_empty_state_is_displayed_when_no_videos_exist(): void
    {
        $response = $this->get('/video');
        $response->assertStatus(200);
        $response->assertSee('Belum Ada Video');
    }

    public function test_resolves_all_absolute_filesystem_paths_for_video_thumbnails(): void
    {
        // 1. C:\laragon\www\smada\storage\app\public\...
        $this->createVideoRecord([
            'title' => 'Video Storage App Public',
            'thumbnail_url' => 'C:\\laragon\\www\\smada\\storage\\app\\public\\videos\\thumb1.jpg',
        ]);

        // 2. C:\laragon\www\smada\public\...
        $this->createVideoRecord([
            'title' => 'Video Public Path',
            'thumbnail_url' => 'C:\\laragon\\www\\smada\\public\\images\\static\\thumb2.jpg',
        ]);

        $response = $this->get('/video');
        $response->assertStatus(200);
        $response->assertSee('storage/videos/thumb1.jpg');
        $response->assertSee('images/static/thumb2.jpg');
    }
}
