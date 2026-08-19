<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Gallery;
use App\Services\ImageOptimizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGalleryTest extends TestCase
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
    public function guest_cannot_access_gallery_index(): void
    {
        $response = $this->get(route('admin.galleries.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function authenticated_admin_can_access_gallery_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.galleries.index'));

        $response->assertOk();
        $response->assertViewIs('admin.gallery.index');
        $response->assertViewHas('galleries');
    }

    /** @test */
    public function admin_can_create_gallery_with_photo_compressed_to_webp_and_logged_to_media_files(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('activity.jpg', 800, 600);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.galleries.store'), [
                'activity_name' => 'Upacara Bendera HUT RI',
                'activity_date' => '2026-08-17',
                'sort_order' => 1,
                'photo' => $photo,
            ]);

        $response->assertRedirect(route('admin.galleries.index'));
        $response->assertSessionHas('success', 'Galeri foto berhasil ditambahkan.');

        $gallery = Gallery::where('activity_name', 'Upacara Bendera HUT RI')->first();
        $this->assertNotNull($gallery);
        $this->assertEquals('2026-08-17', $gallery->activity_date->format('Y-m-d'));
        $this->assertEquals(1, $gallery->sort_order);
        $this->assertEquals($this->admin->id, $gallery->created_by);
        $this->assertNotNull($gallery->photo_url);
        $this->assertStringStartsWith('galleries/', $gallery->photo_url);
        $this->assertStringEndsWith('.webp', $gallery->photo_url);

        Storage::disk('public')->assertExists($gallery->photo_url);

        $this->assertDatabaseHas('media_files', [
            'module' => 'galleries',
            'reference_id' => $gallery->id,
            'file_url' => $gallery->photo_url,
        ]);
    }

    /** @test */
    public function admin_can_create_gallery_with_auto_assigned_sort_order(): void
    {
        Storage::fake('public');

        Gallery::create([
            'activity_name' => 'Kegiatan 1',
            'activity_date' => '2026-01-01',
            'sort_order' => 5,
            'created_by' => $this->admin->id,
            'photo_url' => 'galleries/test1.webp',
        ]);

        $photo = UploadedFile::fake()->image('activity2.png', 600, 400);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.galleries.store'), [
                'activity_name' => 'Kegiatan 2 Auto Order',
                'activity_date' => '2026-02-01',
                'photo' => $photo,
            ]);

        $response->assertRedirect(route('admin.galleries.index'));

        $newGallery = Gallery::where('activity_name', 'Kegiatan 2 Auto Order')->first();
        $this->assertNotNull($newGallery);
        $this->assertEquals(6, $newGallery->sort_order);
    }

    /** @test */
    public function admin_can_update_gallery_data_and_replace_photo(): void
    {
        Storage::fake('public');

        $initialPhoto = UploadedFile::fake()->image('old.jpg', 500, 500);

        $gallery = Gallery::create([
            'activity_name' => 'Pramuka Lama',
            'activity_date' => '2026-03-01',
            'sort_order' => 2,
            'created_by' => $this->admin->id,
            'photo_url' => '',
        ]);

        $oldPath = ImageOptimizerService::compressAndLog(
            $initialPhoto,
            'galleries',
            $gallery->id,
            'galleries'
        );
        $gallery->update(['photo_url' => $oldPath]);

        $newPhoto = UploadedFile::fake()->image('new.png', 700, 700);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.galleries.update', $gallery), [
                'activity_name' => 'Pramuka Kemah Bhakti',
                'activity_date' => '2026-03-05',
                'sort_order' => 3,
                'photo' => $newPhoto,
            ]);

        $response->assertRedirect(route('admin.galleries.index'));

        $gallery->refresh();
        $this->assertEquals('Pramuka Kemah Bhakti', $gallery->activity_name);
        $this->assertEquals('2026-03-05', $gallery->activity_date->format('Y-m-d'));
        $this->assertEquals(3, $gallery->sort_order);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($gallery->photo_url);

        $this->assertDatabaseHas('media_files', [
            'module' => 'galleries',
            'reference_id' => $gallery->id,
            'file_url' => $gallery->photo_url,
        ]);
    }

    /** @test */
    public function admin_can_update_gallery_data_without_replacing_photo(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('keep.jpg', 500, 500);

        $gallery = Gallery::create([
            'activity_name' => 'Lomba Sains',
            'activity_date' => '2026-04-10',
            'sort_order' => 1,
            'created_by' => $this->admin->id,
            'photo_url' => '',
        ]);

        $photoPath = ImageOptimizerService::compressAndLog(
            $photo,
            'galleries',
            $gallery->id,
            'galleries'
        );
        $gallery->update(['photo_url' => $photoPath]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.galleries.update', $gallery), [
                'activity_name' => 'Lomba Sains Nasional',
                'activity_date' => '2026-04-12',
                'sort_order' => 4,
            ]);

        $response->assertRedirect(route('admin.galleries.index'));

        $gallery->refresh();
        $this->assertEquals('Lomba Sains Nasional', $gallery->activity_name);
        $this->assertEquals('2026-04-12', $gallery->activity_date->format('Y-m-d'));
        $this->assertEquals(4, $gallery->sort_order);
        $this->assertEquals($photoPath, $gallery->photo_url);
    }

    /** @test */
    public function admin_can_delete_gallery_and_clean_up_files(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('delete_me.jpg', 400, 400);

        $gallery = Gallery::create([
            'activity_name' => 'Foto Dihapus',
            'activity_date' => '2026-05-01',
            'sort_order' => 1,
            'created_by' => $this->admin->id,
            'photo_url' => '',
        ]);

        $photoPath = ImageOptimizerService::compressAndLog(
            $photo,
            'galleries',
            $gallery->id,
            'galleries'
        );
        $gallery->update(['photo_url' => $photoPath]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.galleries.destroy', $gallery));

        $response->assertRedirect(route('admin.galleries.index'));

        $this->assertDatabaseMissing('galleries', ['id' => $gallery->id]);
        $this->assertDatabaseMissing('media_files', [
            'module' => 'galleries',
            'reference_id' => $gallery->id,
        ]);
        Storage::disk('public')->assertMissing($photoPath);
    }

    /** @test */
    public function gallery_scope_ordered_returns_items_sorted_by_sort_order_asc(): void
    {
        $g2 = Gallery::create([
            'activity_name' => 'Kegiatan B',
            'activity_date' => '2026-01-02',
            'sort_order' => 10,
            'created_by' => $this->admin->id,
            'photo_url' => 'galleries/b.webp',
        ]);

        $g1 = Gallery::create([
            'activity_name' => 'Kegiatan A',
            'activity_date' => '2026-01-01',
            'sort_order' => 2,
            'created_by' => $this->admin->id,
            'photo_url' => 'galleries/a.webp',
        ]);

        $orderedGalleries = Gallery::ordered()->get();

        $this->assertEquals($g1->id, $orderedGalleries->first()->id);
        $this->assertEquals($g2->id, $orderedGalleries->last()->id);
    }

    /** @test */
    public function validation_fails_for_missing_required_fields_or_invalid_file_type(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.galleries.store'), [
                'activity_name' => '',
                'activity_date' => 'invalid-date',
                'photo' => UploadedFile::fake()->create('document.pdf', 500, 'application/pdf'),
            ]);

        $response->assertSessionHasErrors(['activity_name', 'activity_date', 'photo']);
    }
}
