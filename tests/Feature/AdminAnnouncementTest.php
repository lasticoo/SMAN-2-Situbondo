<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\MediaFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAnnouncementTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = Admin::create([
            'name' => 'Admin Pengumuman',
            'email' => 'admin@smada.sch.id',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_guest_cannot_access_announcement_index(): void
    {
        $response = $this->get(route('admin.announcements.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_access_announcement_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.announcements.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.announcements.index');
    }

    public function test_admin_can_create_announcement_with_thumbnail_compressed_to_webp_and_logged_to_media_files(): void
    {
        $file = UploadedFile::fake()->image('banner.png', 800, 600);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.announcements.store'), [
            'title' => 'Pengumuman Ujian Semester',
            'category' => 'Akademik',
            'summary' => 'Ringkasan mengenai jadwal ujian semester ganjil.',
            'content' => 'Isi lengkap jadwal dan tata tertib ujian semester ganjil.',
            'status' => 'published',
            'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'thumbnail' => $file,
        ]);

        $response->assertRedirect(route('admin.announcements.index'));
        $response->assertSessionHas('success');

        $announcement = Announcement::first();
        $this->assertNotNull($announcement);
        $this->assertEquals('Pengumuman Ujian Semester', $announcement->title);
        $this->assertEquals('Akademik', $announcement->category);
        $this->assertEquals('published', $announcement->status);
        $this->assertEquals($this->admin->id, $announcement->created_by);
        $this->assertStringEndsWith('.webp', $announcement->thumbnail_url);
        $this->assertStringStartsWith('announcement/', $announcement->thumbnail_url);

        Storage::disk('public')->assertExists($announcement->thumbnail_url);

        $mediaFile = MediaFile::where('module', 'announcements')
            ->where('reference_id', $announcement->id)
            ->first();

        $this->assertNotNull($mediaFile);
        $this->assertEquals($announcement->thumbnail_url, $mediaFile->file_url);
    }

    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.announcements.store'), []);

        $response->assertSessionHasErrors(['title', 'category', 'thumbnail', 'summary', 'content', 'status', 'published_at']);
    }

    public function test_admin_can_update_announcement_without_replacing_thumbnail(): void
    {
        $announcement = Announcement::create([
            'title' => 'Pengumuman Lama',
            'category' => 'Umum',
            'summary' => 'Ringkasan lama',
            'content' => 'Konten lama',
            'status' => 'draft',
            'published_at' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
            'created_by' => $this->admin->id,
            'thumbnail_url' => 'announcements/old_banner.webp',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.announcements.update', $announcement), [
            'title' => 'Pengumuman Baru Diperbarui',
            'category' => 'Kegiatan',
            'summary' => 'Ringkasan diperbarui',
            'content' => 'Konten diperbarui',
            'status' => 'published',
            'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('admin.announcements.index'));
        $response->assertSessionHas('success');

        $announcement->refresh();
        $this->assertEquals('Pengumuman Baru Diperbarui', $announcement->title);
        $this->assertEquals('Kegiatan', $announcement->category);
        $this->assertEquals('published', $announcement->status);
        $this->assertEquals('announcements/old_banner.webp', $announcement->thumbnail_url);
    }

    public function test_admin_can_update_announcement_and_replace_thumbnail(): void
    {
        $oldFile = UploadedFile::fake()->image('old_thumb.png', 400, 300);

        $this->actingAs($this->admin, 'admin')->post(route('admin.announcements.store'), [
            'title' => 'Pengumuman Awal',
            'category' => 'Umum',
            'summary' => 'Ringkasan awal',
            'content' => 'Konten awal',
            'status' => 'published',
            'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'thumbnail' => $oldFile,
        ]);

        $announcement = Announcement::first();
        $oldThumbnailPath = $announcement->thumbnail_url;
        Storage::disk('public')->assertExists($oldThumbnailPath);

        $newFile = UploadedFile::fake()->image('new_thumb.jpg', 800, 600);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.announcements.update', $announcement), [
            'title' => 'Pengumuman Gambar Diganti',
            'category' => 'Kesiswaan',
            'summary' => 'Ringkasan gambar diganti',
            'content' => 'Konten gambar diganti',
            'status' => 'published',
            'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'thumbnail' => $newFile,
        ]);

        $response->assertRedirect(route('admin.announcements.index'));
        $announcement->refresh();

        Storage::disk('public')->assertMissing($oldThumbnailPath);
        Storage::disk('public')->assertExists($announcement->thumbnail_url);
        $this->assertNotEquals($oldThumbnailPath, $announcement->thumbnail_url);
    }

    public function test_admin_can_delete_announcement_and_clean_up_files(): void
    {
        $file = UploadedFile::fake()->image('to_delete.png', 500, 500);

        $this->actingAs($this->admin, 'admin')->post(route('admin.announcements.store'), [
            'title' => 'Pengumuman Akan Dihapus',
            'category' => 'Informasi',
            'summary' => 'Ringkasan pengumuman dihapus',
            'content' => 'Konten pengumuman dihapus',
            'status' => 'published',
            'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'thumbnail' => $file,
        ]);

        $announcement = Announcement::first();
        $thumbnailPath = $announcement->thumbnail_url;
        Storage::disk('public')->assertExists($thumbnailPath);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.announcements.destroy', $announcement));

        $response->assertRedirect(route('admin.announcements.index'));
        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
        Storage::disk('public')->assertMissing($thumbnailPath);
        $this->assertDatabaseMissing('media_files', [
            'module' => 'announcements',
            'reference_id' => $announcement->id,
        ]);
    }

    public function test_published_scope_only_returns_published_items(): void
    {
        // 1. Published item -> Should appear
        Announcement::create([
            'title' => 'Published Dulu',
            'category' => 'Umum',
            'summary' => 'Ringkasan 1',
            'content' => 'Konten 1',
            'status' => 'published',
            'published_at' => Carbon::now()->subHours(2),
            'created_by' => $this->admin->id,
            'thumbnail_url' => 'announcements/test1.webp',
        ]);

        // 2. Draft item -> Should NOT appear
        Announcement::create([
            'title' => 'Draft Dulu',
            'category' => 'Umum',
            'summary' => 'Ringkasan 2',
            'content' => 'Konten 2',
            'status' => 'draft',
            'published_at' => Carbon::now()->subHours(2),
            'created_by' => $this->admin->id,
            'thumbnail_url' => 'announcements/test2.webp',
        ]);

        $publishedAnnouncements = Announcement::published()->get();

        $this->assertCount(1, $publishedAnnouncements);
        $this->assertEquals('Published Dulu', $publishedAnnouncements->first()->title);
    }
}
