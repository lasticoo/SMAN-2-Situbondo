<?php

namespace Tests\Feature\User;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SpmbPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan warna default jika tabel ada
        if (\Illuminate\Support\Facades\Schema::hasTable('color')) {
            DB::table('color')->insert([
                'primary_color' => '#05479E',
                'secondary_color' => '#F19E38',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Test 1: User dapat mengakses halaman /spmb dengan status HTTP 200
     */
    public function test_user_can_access_spmb_page(): void
    {
        $response = $this->get(route('spmb.index'));

        $response->assertStatus(200);
        $response->assertSee('Sistem Penerimaan Murid Baru');
        $response->assertSee('Jadwal &amp; Tahapan', false);
        $response->assertSee('Informasi Penting');
        $response->assertSee('Dokumen Unduhan');
    }

    /**
     * Test 2: User dapat mengakses alias rute /ppdb
     */
    public function test_user_can_access_ppdb_alias_route(): void
    {
        $response = $this->get('/ppdb');
        $response->assertStatus(200);
        $response->assertSee('Sistem Penerimaan Murid Baru');
    }

    /**
     * Test 3: Rangkaian jadwal dan tahapan dirender dinamis dari database
     */
    public function test_spmb_page_renders_timeline_schedules(): void
    {
        $infoId = DB::table('spmb_info')->insertGetId([
            'banner_url' => 'images/static/gambar_profile_statis.jpg',
            'schedule_info' => "Pendaftaran Online SMADA\nPelaksanaan pendaftaran melalui portal resmi PPDB Jawa Timur.",
            'requirements_info' => 'Mempersiapkan rapor dan kartu keluarga.',
            'period_start' => '2026-06-15',
            'period_end' => '2026-06-30',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('spmb.index'));

        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Online SMADA');
        $response->assertSee('Pelaksanaan pendaftaran melalui portal resmi PPDB Jawa Timur.');
        $response->assertSee('15 - 30 Juni 2026');
    }

    /**
     * Test 4: Kartu Informasi Penting dan Tombol Tautan Portal PPDB Jatim
     */
    public function test_spmb_page_renders_important_info_and_portal_link(): void
    {
        $response = $this->get(route('spmb.index'));

        $response->assertStatus(200);
        $response->assertSee('Informasi Penting');
        $response->assertSee('Jalur Pendaftaran');
        $response->assertSee('Portal PPDB');
        $response->assertSee('https://spmbjatim.net/', false);
    }

    /**
     * Test 5: Dokumen unduhan dirender dinamis dari tabel spmb_documents
     */
    public function test_spmb_page_renders_downloadable_documents(): void
    {
        $infoId = DB::table('spmb_info')->insertGetId([
            'schedule_info' => 'Tahap 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('spmb_documents')->insert([
            'spmb_info_id' => $infoId,
            'title' => 'Petunjuk Teknis PPDB 2026',
            'file_url' => 'documents/petunjuk_teknis_ppdb.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('spmb.index'));

        $response->assertStatus(200);
        $response->assertSee('Petunjuk Teknis PPDB 2026');
        $response->assertSee('Unduh Dokumen');
        $response->assertSee('PDF');
    }

    /**
     * Test 6: User dapat mengunduh dokumen secara langsung melalui rute /spmb/download/{id}
     */
    public function test_user_can_download_spmb_document(): void
    {
        // Buat file uji sementara
        $docDir = public_path('documents');
        if (!File::exists($docDir)) {
            File::makeDirectory($docDir, 0755, true);
        }
        $testFilePath = $docDir . '/test_panduan.pdf';
        File::put($testFilePath, '%PDF-1.4 test document content');

        $infoId = DB::table('spmb_info')->insertGetId([
            'schedule_info' => 'Tahap Uji',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $docId = DB::table('spmb_documents')->insertGetId([
            'spmb_info_id' => $infoId,
            'title' => 'Panduan Pendaftaran Test',
            'file_url' => 'documents/test_panduan.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('spmb.download', $docId));

        $response->assertStatus(200);
        $this->assertStringContainsString('panduan-pendaftaran-test.pdf', $response->headers->get('Content-Disposition'));

        // Bersihkan file uji
        if (File::exists($testFilePath)) {
            File::delete($testFilePath);
        }
    }

    /**
     * Test 7: Halaman menangani kondisi database kosong (empty state) secara natural tanpa error
     */
    public function test_spmb_page_handles_empty_state_gracefully(): void
    {
        DB::table('spmb_documents')->delete();
        DB::table('spmb_info')->delete();

        $response = $this->get(route('spmb.index'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal SPMB belum dipublikasikan');
    }

    /**
     * Test 8: Pengunduhan dokumen yang tidak terdaftar mengembalikan 404 Not Found
     */
    public function test_download_returns_404_for_non_existent_document(): void
    {
        $response = $this->get(route('spmb.download', 99999));
        $response->assertStatus(404);
    }

    /**
     * Test 9: User dapat melihat halaman detail jadwal/tahapan SPMB dengan CTA WhatsApp pesan otomatis
     */
    public function test_user_can_view_spmb_schedule_detail_page_with_whatsapp_templated_cta(): void
    {
        $infoId = DB::table('spmb_info')->insertGetId([
            'banner_url' => 'images/spmb/banner_spmb_smada_hd.jpg',
            'schedule_info' => "Tahap 3: Pendaftaran Jalur Prestasi\nPelaksanaan seleksi jalur kejuaraan akademik dan non-akademik.",
            'requirements_info' => 'Mempersiapkan sertifikat piagam kejuaraan asli.',
            'period_start' => '2026-06-23',
            'period_end' => '2026-06-25',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('spmb.show', $infoId));

        $response->assertStatus(200);
        $response->assertSee('Tahap 3: Pendaftaran Jalur Prestasi');
        $response->assertSee('Pelaksanaan seleksi jalur kejuaraan akademik dan non-akademik.');
        $response->assertSee('Mempersiapkan sertifikat piagam kejuaraan asli.');
        $response->assertSee('Tanya Panitia via WhatsApp');
        $response->assertSee('https://wa.me/', false);
    }

    /**
     * Test 10: Detail SPMB mengembalikan status 404 jika ID tidak ditemukan
     */
    public function test_spmb_detail_returns_404_when_not_found(): void
    {
        $response = $this->get(route('spmb.show', 99999));
        $response->assertStatus(404);
    }
}
