<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSpmbTest extends TestCase
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
    public function guest_cannot_access_spmb_info_index(): void
    {
        $response = $this->get(route('admin.spmb_info.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function authenticated_admin_can_access_spmb_info_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.spmb_info.index'));

        $response->assertOk();
        $response->assertViewIs('admin.spmb_info.index');
        $response->assertViewHas('packages');
    }

    /** @test */
    public function admin_can_create_spmb_info_package_with_webp_banner_and_media_file_logging(): void
    {
        Storage::fake('public');

        $banner = UploadedFile::fake()->image('banner_spmb.jpg', 1200, 600);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.spmb_info.store'), [
                'banner_url' => $banner,
                'schedule_info' => 'Pendaftaran Jalur Reguler dibuka 1 - 15 Mei 2026.',
                'requirements_info' => 'Membawa SKL dan Kartu Keluarga.',
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-15',
            ]);

        $response->assertRedirect(route('admin.spmb_info.index'));
        $response->assertSessionHas('success', 'Paket SPMB berhasil ditambahkan.');

        $spmbInfo = DB::table('spmb_info')->where('period_start', '2026-05-01')->first();
        $this->assertNotNull($spmbInfo);
        $this->assertNotNull($spmbInfo->banner_url);
        $this->assertStringStartsWith('spmb/', $spmbInfo->banner_url);
        $this->assertStringEndsWith('.webp', $spmbInfo->banner_url);

        Storage::disk('public')->assertExists($spmbInfo->banner_url);

        // Verify media_files logging
        $this->assertDatabaseHas('media_files', [
            'module' => 'spmb',
            'reference_id' => $spmbInfo->id,
            'file_url' => $spmbInfo->banner_url,
        ]);
    }

    /** @test */
    public function validation_fails_if_period_end_is_before_period_start(): void
    {
        Storage::fake('public');

        $banner = UploadedFile::fake()->image('banner.jpg', 600, 300);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.spmb_info.store'), [
                'banner_url' => $banner,
                'schedule_info' => 'Jadwal Pendaftaran',
                'requirements_info' => 'Persyaratan Pendaftaran',
                'period_start' => '2026-05-15',
                'period_end' => '2026-05-01', // Invalid: before period_start
            ]);

        $response->assertSessionHasErrors(['period_end']);
    }

    /** @test */
    public function admin_can_update_spmb_info_package_and_replace_banner(): void
    {
        Storage::fake('public');

        $initialBanner = UploadedFile::fake()->image('old_banner.png', 800, 400);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.spmb_info.store'), [
                'banner_url' => $initialBanner,
                'schedule_info' => 'Jadwal Lama',
                'requirements_info' => 'Syarat Lama',
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-15',
            ]);

        $spmbInfo = DB::table('spmb_info')->first();
        $oldBannerPath = $spmbInfo->banner_url;

        $newBanner = UploadedFile::fake()->image('new_banner.jpg', 1000, 500);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.spmb_info.update', $spmbInfo->id), [
                'banner_url' => $newBanner,
                'schedule_info' => 'Jadwal Baru Diperbarui',
                'requirements_info' => 'Syarat Baru Diperbarui',
                'period_start' => '2026-06-01',
                'period_end' => '2026-06-20',
            ]);

        $response->assertRedirect(route('admin.spmb_info.index'));
        $response->assertSessionHas('success', 'Paket SPMB berhasil diperbarui.');

        $updatedSpmbInfo = DB::table('spmb_info')->where('id', $spmbInfo->id)->first();
        $this->assertEquals('Jadwal Baru Diperbarui', $updatedSpmbInfo->schedule_info);
        $this->assertNotEquals($oldBannerPath, $updatedSpmbInfo->banner_url);

        // Assert old file deleted and new file created
        Storage::disk('public')->assertMissing($oldBannerPath);
        Storage::disk('public')->assertExists($updatedSpmbInfo->banner_url);
    }

    /** @test */
    public function admin_can_access_spmb_documents_index_for_valid_package(): void
    {
        Storage::fake('public');
        $banner = UploadedFile::fake()->image('banner.jpg', 600, 300);

        $id = DB::table('spmb_info')->insertGetId([
            'banner_url' => 'spmb/test.webp',
            'schedule_info' => 'Jadwal Test',
            'requirements_info' => 'Syarat Test',
            'period_start' => '2026-05-01',
            'period_end' => '2026-05-15',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.spmb_info.documents.index', $id));

        $response->assertOk();
        $response->assertViewIs('admin.spmb_documents.index');
        $response->assertViewHas('package');
        $response->assertViewHas('documents');
    }

    /** @test */
    public function admin_can_create_spmb_document(): void
    {
        Storage::fake('public');

        $packageId = DB::table('spmb_info')->insertGetId([
            'schedule_info' => 'Jadwal Test',
            'requirements_info' => 'Syarat Test',
            'period_start' => '2026-05-01',
            'period_end' => '2026-05-15',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $documentFile = UploadedFile::fake()->create('brosur_spmb_2026.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.spmb_info.documents.store', $packageId), [
                'title' => 'Brosur Resmi SPMB 2026',
                'file_url' => $documentFile,
            ]);

        $response->assertRedirect(route('admin.spmb_info.documents.index', $packageId));
        $response->assertSessionHas('success', 'Dokumen SPMB berhasil ditambahkan.');

        $doc = DB::table('spmb_documents')->where('spmb_info_id', $packageId)->first();
        $this->assertNotNull($doc);
        $this->assertEquals('Brosur Resmi SPMB 2026', $doc->title);
        $this->assertStringStartsWith('spmb-documents/', $doc->file_url);

        Storage::disk('public')->assertExists($doc->file_url);
    }

    /** @test */
    public function validation_fails_when_uploading_invalid_document_type(): void
    {
        Storage::fake('public');

        $packageId = DB::table('spmb_info')->insertGetId([
            'schedule_info' => 'Jadwal Test',
            'requirements_info' => 'Syarat Test',
            'period_start' => '2026-05-01',
            'period_end' => '2026-05-15',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $invalidFile = UploadedFile::fake()->create('script.exe', 100, 'application/x-msdownload');

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.spmb_info.documents.store', $packageId), [
                'title' => 'File Terlarang',
                'file_url' => $invalidFile,
            ]);

        $response->assertSessionHasErrors(['file_url']);
    }

    /** @test */
    public function admin_can_update_spmb_document_title_and_file(): void
    {
        Storage::fake('public');

        $packageId = DB::table('spmb_info')->insertGetId([
            'schedule_info' => 'Jadwal Test',
            'requirements_info' => 'Syarat Test',
            'period_start' => '2026-05-01',
            'period_end' => '2026-05-15',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $oldDocFile = UploadedFile::fake()->create('old_doc.pdf', 300, 'application/pdf');
        $oldPath = $oldDocFile->store('spmb-documents', 'public');

        $docId = DB::table('spmb_documents')->insertGetId([
            'spmb_info_id' => $packageId,
            'title' => 'Formulir Lama',
            'file_url' => $oldPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $newDocFile = UploadedFile::fake()->create('new_doc.docx', 400, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.spmb_info.documents.update', [$packageId, $docId]), [
                'title' => 'Formulir Pendaftaran Revisi',
                'file_url' => $newDocFile,
            ]);

        $response->assertRedirect(route('admin.spmb_info.documents.index', $packageId));

        $updatedDoc = DB::table('spmb_documents')->where('id', $docId)->first();
        $this->assertEquals('Formulir Pendaftaran Revisi', $updatedDoc->title);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($updatedDoc->file_url);
    }

    /** @test */
    public function deleting_spmb_info_package_cascades_and_deletes_all_attached_documents_and_files(): void
    {
        Storage::fake('public');

        // Create banner image
        $banner = UploadedFile::fake()->image('banner_spmb.jpg', 600, 300);
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.spmb_info.store'), [
                'banner_url' => $banner,
                'schedule_info' => 'Jadwal yang akan dihapus',
                'requirements_info' => 'Syarat yang akan dihapus',
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-15',
            ]);

        $spmbInfo = DB::table('spmb_info')->first();
        $bannerPath = $spmbInfo->banner_url;

        // Create 2 attached documents
        $doc1File = UploadedFile::fake()->create('doc1.pdf', 200, 'application/pdf');
        $doc1Path = $doc1File->store('spmb-documents', 'public');
        $doc1Id = DB::table('spmb_documents')->insertGetId([
            'spmb_info_id' => $spmbInfo->id,
            'title' => 'Dokumen 1',
            'file_url' => $doc1Path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $doc2File = UploadedFile::fake()->create('doc2.docx', 200, 'application/docx');
        $doc2Path = $doc2File->store('spmb-documents', 'public');
        $doc2Id = DB::table('spmb_documents')->insertGetId([
            'spmb_info_id' => $spmbInfo->id,
            'title' => 'Dokumen 2',
            'file_url' => $doc2Path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Execute Delete Paket SPMB
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.spmb_info.destroy', $spmbInfo->id));

        $response->assertRedirect(route('admin.spmb_info.index'));
        $response->assertSessionHas('success', 'Paket SPMB beserta seluruh dokumennya berhasil dihapus.');

        // Assert database records deleted
        $this->assertDatabaseMissing('spmb_info', ['id' => $spmbInfo->id]);
        $this->assertDatabaseMissing('spmb_documents', ['spmb_info_id' => $spmbInfo->id]);
        $this->assertDatabaseMissing('media_files', ['module' => 'spmb', 'reference_id' => $spmbInfo->id]);

        // Assert storage files deleted
        Storage::disk('public')->assertMissing($bannerPath);
        Storage::disk('public')->assertMissing($doc1Path);
        Storage::disk('public')->assertMissing($doc2Path);
    }
}
