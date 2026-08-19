<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\ColorSetting;
use App\Models\Gallery;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryPageTest extends TestCase
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

    protected function createGalleryRecord(array $attributes): Gallery
    {
        $gallery = new Gallery();
        $gallery->activity_name = $attributes['activity_name'] ?? 'Kegiatan Sekolah';
        $gallery->activity_date = $attributes['activity_date'] ?? '2025-08-01';
        $gallery->photo_url = $attributes['photo_url'] ?? 'https://example.com/photo.jpg';
        $gallery->sort_order = $attributes['sort_order'] ?? 0;
        $gallery->created_by = $attributes['created_by'] ?? $this->admin->id;
        $gallery->created_at = $attributes['created_at'] ?? Carbon::now();
        $gallery->updated_at = Carbon::now();
        $gallery->save();

        return $gallery;
    }

    public function test_public_user_can_access_media_gallery_page_without_login(): void
    {
        $this->createGalleryRecord([
            'activity_name' => 'Sosialisasi Olimpiade Sains Nasional',
            'activity_date' => '2025-08-10',
            'photo_url' => 'https://example.com/gallery1.jpg',
            'sort_order' => 1,
        ]);

        $response = $this->get('/media');

        $response->assertStatus(200);
        $response->assertSee('Galeri Kegiatan');
        $response->assertSee('Sosialisasi Olimpiade Sains Nasional');
    }

    public function test_gallery_can_be_filtered_by_dynamic_activity_name(): void
    {
        $this->createGalleryRecord([
            'activity_name' => 'Ujian Tengah Semester Berbasis Komputer',
            'activity_date' => '2025-08-12',
            'photo_url' => 'https://example.com/akademik.jpg',
            'sort_order' => 1,
        ]);

        $this->createGalleryRecord([
            'activity_name' => 'Kemah Bersama Pramuka Gugus Depan',
            'activity_date' => '2025-08-15',
            'photo_url' => 'https://example.com/pramuka.jpg',
            'sort_order' => 2,
        ]);

        // Filter Activity 1
        $responseAct1 = $this->get('/media?activity=Ujian+Tengah+Semester+Berbasis+Komputer');
        $responseAct1->assertStatus(200);
        $this->assertEquals(1, $responseAct1->viewData('galleries')->count());
        $this->assertEquals('Ujian Tengah Semester Berbasis Komputer', $responseAct1->viewData('galleries')->first()->activity_name);

        // Filter Activity 2
        $responseAct2 = $this->get('/media?activity=Kemah+Bersama+Pramuka+Gugus+Depan');
        $responseAct2->assertStatus(200);
        $this->assertEquals(1, $responseAct2->viewData('galleries')->count());
        $this->assertEquals('Kemah Bersama Pramuka Gugus Depan', $responseAct2->viewData('galleries')->first()->activity_name);
    }

    public function test_galleries_are_ordered_with_newest_upload_first(): void
    {
        $this->createGalleryRecord([
            'activity_name' => 'Kegiatan Upload Lama',
            'activity_date' => '2025-08-01',
            'photo_url' => 'https://example.com/old.jpg',
            'created_at' => Carbon::now()->subDays(10),
        ]);

        $this->createGalleryRecord([
            'activity_name' => 'Kegiatan Upload Terbaru',
            'activity_date' => '2025-08-10',
            'photo_url' => 'https://example.com/newest.jpg',
            'created_at' => Carbon::now(),
        ]);

        $response = $this->get('/media');
        $response->assertStatus(200);

        // Assert 1: Newest gallery item is at position 0 in collection
        $galleries = $response->viewData('galleries');
        $this->assertEquals('Kegiatan Upload Terbaru', $galleries->first()->activity_name);

        // Assert 2: Newest activity name is at position 0 in filter list
        $rawActivities = $response->viewData('rawActivities');
        $this->assertEquals('Kegiatan Upload Terbaru', $rawActivities[0]);

        // Assert 3: In HTML output, newest upload appears before old upload
        $content = $response->getContent();
        $posNewest = strpos($content, 'Kegiatan Upload Terbaru');
        $posOld = strpos($content, 'Kegiatan Upload Lama');

        $this->assertTrue($posNewest !== false && $posOld !== false);
        $this->assertTrue($posNewest < $posOld);
    }

    public function test_google_images_seo_schema_json_ld_is_rendered(): void
    {
        $this->createGalleryRecord([
            'activity_name' => 'Peringatan Hari Pahlawan Nasional',
            'activity_date' => '2025-11-10',
            'photo_url' => 'https://example.com/pahlawan.jpg',
            'sort_order' => 1,
        ]);

        $response = $this->get('/media');
        $response->assertStatus(200);

        $response->assertSee('ImageGallery', false);
        $response->assertSee('ImageObject', false);
        $response->assertSee('Peringatan Hari Pahlawan Nasional', false);
    }

    public function test_dynamic_color_setting_is_applied_to_page(): void
    {
        ColorSetting::create([
            'primary_color' => '#123456',
            'secondary_color' => '#654321',
        ]);

        $response = $this->get('/media');
        $response->assertStatus(200);
        $response->assertSee('#123456');
        $response->assertSee('#654321');
    }

    public function test_empty_state_is_displayed_when_no_galleries_exist(): void
    {
        $response = $this->get('/media');
        $response->assertStatus(200);
        $response->assertSee('Belum Ada Foto Galeri');
    }

    public function test_resolves_all_absolute_filesystem_paths(): void
    {
        // 1. C:\laragon\www\smada\storage\app\public\...
        $this->createGalleryRecord([
            'activity_name' => 'Dokumentasi Storage App Public',
            'activity_date' => '2025-08-01',
            'photo_url' => 'C:\\laragon\\www\\smada\\storage\\app\\public\\galleries\\foto1.jpg',
            'sort_order' => 1,
        ]);

        // 2. C:\laragon\www\smada\public\...
        $this->createGalleryRecord([
            'activity_name' => 'Dokumentasi Public Static',
            'activity_date' => '2025-08-02',
            'photo_url' => 'C:\\laragon\\www\\smada\\public\\images\\static\\gambar_profile_statis.jpg',
            'sort_order' => 2,
        ]);

        // 3. C:\laragon\www\smada\public\storage\...
        $this->createGalleryRecord([
            'activity_name' => 'Dokumentasi Public Storage',
            'activity_date' => '2025-08-03',
            'photo_url' => 'C:\\laragon\\www\\smada\\public\\storage\\galleries\\foto3.jpg',
            'sort_order' => 3,
        ]);

        $response = $this->get('/media');
        $response->assertStatus(200);
        $response->assertSee('Dokumentasi Storage App Public');
        $response->assertSee('Dokumentasi Public Static');
        $response->assertSee('Dokumentasi Public Storage');
    }
}
