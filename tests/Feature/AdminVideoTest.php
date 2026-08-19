<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Video;
use App\Services\ImageOptimizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminVideoTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@smada.sch.id',
            'password' => bcrypt('password123'),
        ]);
    }

    /** @test */
    public function guest_cannot_access_video_index(): void
    {
        $response = $this->get(route('admin.videos.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function authenticated_admin_can_access_video_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.videos.index'));

        $response->assertOk();
        $response->assertViewIs('admin.videos.index');
        $response->assertViewHas('videos');
    }

    /** @test */
    public function admin_can_create_video_with_youtube_url_and_auto_extracted_youtube_id(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.videos.store'), [
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'title' => 'Video Profil SMAN 2 Situbondo',
                'sort_order' => 1,
            ]);

        $response->assertRedirect(route('admin.videos.index'));
        $response->assertSessionHas('success', 'Video YouTube berhasil ditambahkan.');

        $video = Video::where('title', 'Video Profil SMAN 2 Situbondo')->first();
        $this->assertNotNull($video);
        $this->assertEquals('dQw4w9WgXcQ', $video->youtube_id);
        $this->assertEquals(1, $video->sort_order);
        $this->assertEquals($this->admin->id, $video->created_by);
        $this->assertNull($video->thumbnail_url);
        $this->assertEquals('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg', $video->display_thumbnail_url);
    }

    /** @test */
    public function admin_can_create_video_with_custom_thumbnail_compressed_to_webp_and_logged_to_media_files(): void
    {
        Storage::fake('public');

        $thumbnail = UploadedFile::fake()->image('custom_thumb.jpg', 640, 360);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.videos.store'), [
                'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
                'title' => 'Video Ekstrakurikuler',
                'thumbnail' => $thumbnail,
            ]);

        $response->assertRedirect(route('admin.videos.index'));

        $video = Video::where('title', 'Video Ekstrakurikuler')->first();
        $this->assertNotNull($video);
        $this->assertNotNull($video->thumbnail_url);
        $this->assertStringStartsWith('videos/', $video->thumbnail_url);
        $this->assertStringEndsWith('.webp', $video->thumbnail_url);

        Storage::disk('public')->assertExists($video->thumbnail_url);

        $this->assertDatabaseHas('media_files', [
            'module' => 'videos',
            'reference_id' => $video->id,
            'file_url' => $video->thumbnail_url,
        ]);
    }

    /** @test */
    public function admin_can_create_video_with_auto_assigned_sort_order(): void
    {
        Video::create([
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'title' => 'Video 1',
            'sort_order' => 4,
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.videos.store'), [
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'title' => 'Video 2 Auto Order',
            ]);

        $response->assertRedirect(route('admin.videos.index'));

        $newVideo = Video::where('title', 'Video 2 Auto Order')->first();
        $this->assertNotNull($newVideo);
        $this->assertEquals(5, $newVideo->sort_order);
    }

    /** @test */
    public function validation_fails_for_invalid_youtube_url_domain(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.videos.store'), [
                'youtube_url' => 'https://vimeo.com/123456789',
                'title' => 'Link Vimeo',
            ]);

        $response->assertSessionHasErrors(['youtube_url']);
    }

    /** @test */
    public function admin_can_update_video_data_and_replace_thumbnail(): void
    {
        Storage::fake('public');

        $initialThumb = UploadedFile::fake()->image('thumb1.jpg', 400, 300);

        $video = Video::create([
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'title' => 'Judul Lama',
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        $oldPath = ImageOptimizerService::compressAndLog(
            $initialThumb,
            'videos',
            $video->id,
            'videos'
        );
        $video->update(['thumbnail_url' => $oldPath]);

        $newThumb = UploadedFile::fake()->image('thumb2.png', 500, 400);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.videos.update', $video), [
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'title' => 'Judul Baru Diperbarui',
                'sort_order' => 2,
                'thumbnail' => $newThumb,
            ]);

        $response->assertRedirect(route('admin.videos.index'));

        $video->refresh();
        $this->assertEquals('Judul Baru Diperbarui', $video->title);
        $this->assertEquals(2, $video->sort_order);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($video->thumbnail_url);
    }

    /** @test */
    public function admin_can_delete_video_and_clean_up_files(): void
    {
        Storage::fake('public');

        $thumb = UploadedFile::fake()->image('del.jpg', 300, 300);

        $video = Video::create([
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'title' => 'Video Dihapus',
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        $thumbPath = ImageOptimizerService::compressAndLog(
            $thumb,
            'videos',
            $video->id,
            'videos'
        );
        $video->update(['thumbnail_url' => $thumbPath]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.videos.destroy', $video));

        $response->assertRedirect(route('admin.videos.index'));

        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
        $this->assertDatabaseMissing('media_files', [
            'module' => 'videos',
            'reference_id' => $video->id,
        ]);
        Storage::disk('public')->assertMissing($thumbPath);
    }

    /** @test */
    public function video_scope_ordered_returns_records_sorted_by_sort_order_asc(): void
    {
        $v2 = Video::create([
            'youtube_url' => 'https://www.youtube.com/watch?v=v2',
            'youtube_id' => 'v2',
            'title' => 'Video Order 10',
            'sort_order' => 10,
            'created_by' => $this->admin->id,
        ]);

        $v1 = Video::create([
            'youtube_url' => 'https://www.youtube.com/watch?v=v3',
            'youtube_id' => 'v3',
            'title' => 'Video Order 2',
            'sort_order' => 2,
            'created_by' => $this->admin->id,
        ]);

        $ordered = Video::ordered()->get();

        $this->assertCount(2, $ordered);
        $this->assertEquals($v1->id, $ordered->first()->id);
        $this->assertEquals($v2->id, $ordered->last()->id);
    }
}
