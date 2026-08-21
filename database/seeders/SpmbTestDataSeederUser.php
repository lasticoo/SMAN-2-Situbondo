<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SpmbTestDataSeederUser extends Seeder
{
    /**
     * Seeder Khusus Data Simulasi SPMB / PPDB (9 Rangkaian Jadwal & 3 Banner Gambar HD)
     */
    public function run(): void
    {
        // 1. Pastikan folder dokumen dan gambar tersedia
        $docDir = public_path('documents');
        if (!File::exists($docDir)) {
            File::makeDirectory($docDir, 0755, true);
        }

        $imgDir = public_path('images/spmb');
        if (!File::exists($imgDir)) {
            File::makeDirectory($imgDir, 0755, true);
        }

        // 2. Buat berkas dokumen nyata dan terstruktur
        $documents = [
            'brosur_spmb_2024.pdf' => "%PDF-1.4\n%âãÏÓ\n1 0 obj\n<< /Title (Brosur Resmi SPMB SMAN 2 Situbondo) /Author (Panitia SPMB SMADA) /Subject (Penerimaan Peserta Didik Baru) >>\nendobj\n2 0 obj\n<< /Type /Catalog /Pages 3 0 R >>\nendobj\n3 0 obj\n<< /Type /Pages /Kids [4 0 R] /Count 1 >>\nendobj\n4 0 obj\n<< /Type /Page /Parent 3 0 R /MediaBox [0 0 595 842] /Contents 5 0 R >>\nendobj\n5 0 obj\n<< /Length 120 >>\nstream\nBT\n/F1 18 Tf\n50 750 Td\n(BROSUR PENERIMAAN MURID BARU SMAN 2 SITUBONDO) Tj\n0 -30 Td\n(Unggul, Berprestasi, dan Berkarakter Global) Tj\nET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f \n0000000015 00000 n \n0000000138 00000 n \n0000000188 00000 n \n0000000248 00000 n \n0000000329 00000 n \ntrailer\n<< /Size 6 /Root 2 0 R >>\nstartxref\n502\n%%EOF",
            
            'petunjuk_teknis_ppdb_jatim.pdf' => "%PDF-1.4\n%âãÏÓ\n1 0 obj\n<< /Title (Petunjuk Teknis PPDB Provinsi Jawa Timur) /Author (Dinas Pendidikan Jatim) >>\nendobj\n2 0 obj\n<< /Type /Catalog /Pages 3 0 R >>\nendobj\n3 0 obj\n<< /Type /Pages /Kids [4 0 R] /Count 1 >>\nendobj\n4 0 obj\n<< /Type /Page /Parent 3 0 R /MediaBox [0 0 595 842] /Contents 5 0 R >>\nendobj\n5 0 obj\n<< /Length 110 >>\nstream\nBT\n/F1 16 Tf\n50 750 Td\n(PETUNJUK TEKNIS SPMB SMA NEGERI PROVINSI JAWA TIMUR) Tj\nET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f \n0000000015 00000 n \n0000000120 00000 n \n0000000170 00000 n \n0000000230 00000 n \n0000000311 00000 n \ntrailer\n<< /Size 6 /Root 2 0 R >>\nstartxref\n474\n%%EOF",

            'surat_pernyataan_keabsahan_dokumen.pdf' => "%PDF-1.4\n%âãÏÓ\n1 0 obj\n<< /Title (Surat Pernyataan Keabsahan Dokumen) /Author (SMAN 2 Situbondo) >>\nendobj\n2 0 obj\n<< /Type /Catalog /Pages 3 0 R >>\nendobj\n3 0 obj\n<< /Type /Pages /Kids [4 0 R] /Count 1 >>\nendobj\n4 0 obj\n<< /Type /Page /Parent 3 0 R /MediaBox [0 0 595 842] /Contents 5 0 R >>\nendobj\n5 0 obj\n<< /Length 105 >>\nstream\nBT\n/F1 14 Tf\n50 750 Td\n(SURAT PERNYATAAN KEABSAHAN DOKUMEN PESERTA DIDIK BARU) Tj\nET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f \n0000000015 00000 n \n0000000115 00000 n \n0000000165 00000 n \n0000000225 00000 n \n0000000306 00000 n \ntrailer\n<< /Size 6 /Root 2 0 R >>\nstartxref\n464\n%%EOF",

            'tata_tertib_dan_alur_daftar_ulang.pdf' => "%PDF-1.4\n%âãÏÓ\n1 0 obj\n<< /Title (Tata Tertib dan Alur Daftar Ulang) /Author (SMAN 2 Situbondo) >>\nendobj\n2 0 obj\n<< /Type /Catalog /Pages 3 0 R >>\nendobj\n3 0 obj\n<< /Type /Pages /Kids [4 0 R] /Count 1 >>\nendobj\n4 0 obj\n<< /Type /Page /Parent 3 0 R /MediaBox [0 0 595 842] /Contents 5 0 R >>\nendobj\n5 0 obj\n<< /Length 100 >>\nstream\nBT\n/F1 14 Tf\n50 750 Td\n(TATA TERTIB DAN ALUR PENDAFTARAN ULANG TAHUN 2026) Tj\nET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f \n0000000015 00000 n \n0000000110 00000 n \n0000000160 00000 n \n0000000220 00000 n \n0000000301 00000 n \ntrailer\n<< /Size 6 /Root 2 0 R >>\nstartxref\n454\n%%EOF",

            'formulir_pendaftaran_manual.docx' => "PK\x03\x04\x14\x00\x06\x00\x08\x00\x00\x00!\x00Formulir Pendaftaran Siswa Baru SMAN 2 Situbondo\x00PK\x05\x06\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
            'rekap_pembagian_zona_dan_kuota_spmb.xlsx' => "PK\x03\x04\x14\x00\x06\x00\x08\x00\x00\x00!\x00Rekapitulasi Pembagian Zona dan Kuota Pendaftaran SMADA\x00PK\x05\x06\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
        ];

        foreach ($documents as $filename => $content) {
            File::put($docDir . '/' . $filename, $content);
        }

        // 3. Bersihkan data sebelumnya
        DB::table('spmb_documents')->delete();
        DB::table('spmb_info')->delete();

        // 4. Siapkan Path Banner HD
        $bannerPath1 = 'images/spmb/banner_spmb_smada_hd.jpg';
        $bannerPath2 = 'images/static/gambar_profile_statis.jpg';

        if (!File::exists(public_path($bannerPath1))) {
            $bannerPath1 = 'images/static/gambar_profile_statis.jpg';
        }

        // 5. Masukkan Tepat 9 Data Entri SPMB (3 Memiliki Banner Gambar, 6 Tanpa Banner)
        $schedules = [
            [
                'banner_url' => $bannerPath1, // Gambar 1
                'title' => 'Sosialisasi & Publikasi Juknis PPDB',
                'schedule_desc' => 'Penyampaian informasi mekanisme seleksi, ketentuan zonasi, kuota masing-masing jalur, dan tata cara pendaftaran kepada seluruh SMP/MTs dan masyarakat luas.',
                'req_desc' => 'Mempelajari petunjuk teknis resmi dan mempersiapkan kelengkapan biodata rapor semester 1-5.',
                'start' => '2026-05-15',
                'end' => '2026-05-31',
            ],
            [
                'banner_url' => $bannerPath2, // Gambar 2
                'title' => 'Pengambilan PIN & Verifikasi Berkas Rapor',
                'schedule_desc' => 'Calon peserta didik baru melakukan login pada sistem PPDB Jatim untuk mengunduh Personal Identification Number (PIN) serta memverifikasi kesesuaian nilai rapor.',
                'req_desc' => 'Mempersiapkan NISN, NPSN sekolah asal, foto Kartu Keluarga asli, dan SKL/Ijazah SMP.',
                'start' => '2026-06-01',
                'end' => '2026-06-12',
            ],
            [
                'banner_url' => null,
                'title' => 'Tahap 1: Pendaftaran Jalur Afirmasi & Disabilitas',
                'schedule_desc' => 'Pendaftaran khusus bagi calon murid dari keluarga ekonomi tidak mampu (KIP/PKH) serta penyandang disabilitas ringan yang memenuhi kriteria inklusi sekolah.',
                'req_desc' => 'Kartu KIP/KKS/PKH terdaftar pada DTKS Kemensos dan surat keterangan disabilitas dari dokter.',
                'start' => '2026-06-15',
                'end' => '2026-06-17',
            ],
            [
                'banner_url' => null,
                'title' => 'Tahap 2: Pendaftaran Jalur Perpindahan Tugas Orang Tua',
                'schedule_desc' => 'Pendaftaran bagi calon peserta didik yang mengikuti perpindahan domisili tugas orang tua/wali dari instansi pemerintah/BUMN/TNI/Polri atau anak guru/tenaga kependidikan.',
                'req_desc' => 'Surat Keputusan (SK) mutasi kerja orang tua maksimal 1 tahun dan surat penugasan resmi.',
                'start' => '2026-06-20',
                'end' => '2026-06-22',
            ],
            [
                'banner_url' => $bannerPath1, // Gambar 3
                'title' => 'Tahap 3: Pendaftaran Jalur Prestasi Hasil Lomba',
                'schedule_desc' => 'Pendaftaran jalur prestasi kejuaraan akademik maupun non-akademik (olahraga, seni, keagamaan, pramuka) berjenjang minimal tingkat kabupaten/kota.',
                'req_desc' => 'Sertifikat/Piagam kejuaraan asli minimal juara 1-3 yang diterbitkan instansi resmi.',
                'start' => '2026-06-23',
                'end' => '2026-06-25',
            ],
            [
                'banner_url' => null,
                'title' => 'Tahap 4: Pendaftaran Jalur Zonasi Wilayah',
                'schedule_desc' => 'Pendaftaran jalur domisili radius terdekat dari tempat tinggal resmi siswa ke SMAN 2 Situbondo berdasarkan titik koordinat Kartu Keluarga yang terverifikasi.',
                'req_desc' => 'Kartu Keluarga yang diterbitkan paling lambat 1 (satu) tahun sebelum tanggal pendaftaran.',
                'start' => '2026-06-29',
                'end' => '2026-07-02',
            ],
            [
                'banner_url' => null,
                'title' => 'Tahap 5: Pendaftaran Jalur Prestasi Nilai Akademik',
                'schedule_desc' => 'Seleksi berdasarkan pemeringkatan gabungan rata-rata nilai rapor semester 1 sampai 5 dan nilai akreditasi sekolah asal.',
                'req_desc' => 'Surat Keterangan Nilai Rapor (SKNR) yang telah ditandatangani kepala sekolah asal.',
                'start' => '2026-07-03',
                'end' => '2026-07-06',
            ],
            [
                'banner_url' => null,
                'title' => 'Pengumuman Kelulusan Akhir Seluruh Jalur',
                'schedule_desc' => 'Hasil seleksi akhir SPMB SMAN 2 Situbondo diumumkan secara serentak secara daring di website resmi sekolah dan sistem PPDB Jatim.',
                'req_desc' => 'Calon siswa mencetak Bukti Penerimaan Resmi untuk keperluan administrasi daftar ulang.',
                'start' => '2026-07-08',
                'end' => '2026-07-08',
            ],
            [
                'banner_url' => null,
                'title' => 'Daftar Ulang Fisik & Masa Pengenalan Lingkungan Sekolah',
                'schedule_desc' => 'Peserta yang dinyatakan lulus wajib hadir di sekolah didampingi orang tua untuk penyerahan berkas fisik, pengukuran seragam, serta mengikuti rangkaian kegiatan MPLS.',
                'req_desc' => 'Membawa map berkas berisi ijazah asli, fotokopi KK, akta lahir, dan pasfoto 3x4.',
                'start' => '2026-07-09',
                'end' => '2026-07-15',
            ],
        ];

        $firstInfoId = null;
        $secondInfoId = null;
        $thirdInfoId = null;

        foreach ($schedules as $idx => $s) {
            $insertedId = DB::table('spmb_info')->insertGetId([
                'banner_url' => $s['banner_url'],
                'schedule_info' => $s['title'] . "\n" . $s['schedule_desc'],
                'requirements_info' => $s['req_desc'],
                'period_start' => $s['start'],
                'period_end' => $s['end'],
                'created_at' => Carbon::now()->subDays(18 - ($idx * 2)),
                'updated_at' => Carbon::now()->subDays(18 - ($idx * 2)),
            ]);

            if ($idx === 0) {
                $firstInfoId = $insertedId;
            } elseif ($idx === 1) {
                $secondInfoId = $insertedId;
            } elseif ($idx === 2) {
                $thirdInfoId = $insertedId;
            }
        }

        // 6. Masukkan 6 Dokumen Resmi Unduhan
        DB::table('spmb_documents')->insert([
            [
                'spmb_info_id' => $firstInfoId,
                'title' => 'Brosur Informasi Resmi SPMB 2024/2026',
                'file_url' => 'documents/brosur_spmb_2024.pdf',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'spmb_info_id' => $firstInfoId,
                'title' => 'Petunjuk Teknis PPDB Provinsi Jawa Timur',
                'file_url' => 'documents/petunjuk_teknis_ppdb_jatim.pdf',
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => Carbon::now()->subDays(14),
            ],
            [
                'spmb_info_id' => $secondInfoId,
                'title' => 'Formulir Pendaftaran Siswa Baru Manual',
                'file_url' => 'documents/formulir_pendaftaran_manual.docx',
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'spmb_info_id' => $secondInfoId,
                'title' => 'Surat Pernyataan Keabsahan Dokumen & Nilai Rapor',
                'file_url' => 'documents/surat_pernyataan_keabsahan_dokumen.pdf',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'spmb_info_id' => $thirdInfoId,
                'title' => 'Tata Tertib & Alur Pendaftaran Ulang Siswa Baru',
                'file_url' => 'documents/tata_tertib_dan_alur_daftar_ulang.pdf',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'spmb_info_id' => $thirdInfoId,
                'title' => 'Rekapitulasi Pembagian Zona & Kuota Pendaftaran',
                'file_url' => 'documents/rekap_pembagian_zona_dan_kuota_spmb.xlsx',
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6),
            ],
        ]);
    }
}
