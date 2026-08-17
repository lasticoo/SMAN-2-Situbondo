<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Banner;
use App\Models\Popup;
use App\Models\News;
use App\Models\Announcement;
use App\Models\Student;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LandingPageTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();
        if (!$admin) {
            $admin = Admin::create([
                'name' => 'Admin Test',
                'email' => 'admin@smada.sch.id',
                'password' => bcrypt('password123'),
            ]);
        }

        // 1. Seed Banners
        if (Banner::count() === 0) {
            Banner::create([
                'title' => 'SMA Negeri 2 Situbondo',
                'description' => 'Smada Prima — Selamat datang di website resmi SMA Negeri 2 Situbondo. Sekolah unggulan yang berkomitmen mencetak generasi berprestasi, berkarakter, dan siap bersaing di era global.',
                'image_url' => '/build/assets/banner smada.png',
                'is_active' => true,
                'sort_order' => 1,
            ]);

            Banner::create([
                'title' => 'Penerimaan Peserta Didik Baru (SPMB) 2026',
                'description' => 'Bergabunglah menjadi bagian dari keluarga besar SMA Negeri 2 Situbondo. Pendaftaran Jalur Prestasi dan Zonasi telah dibuka.',
                'image_url' => '/build/assets/banner smada.png',
                'is_active' => true,
                'sort_order' => 2,
            ]);

            Banner::create([
                'title' => 'Fasilitas & Pembelajaran Berbasis Digital',
                'description' => 'Mendukung pembelajaran modern berbasis ICT, E-Learning terpadu, dan sarana prasarana terbaik di Situbondo.',
                'image_url' => '/build/assets/banner smada.png',
                'is_active' => true,
                'sort_order' => 3,
            ]);
        }

        // 2. Seed Popups
        if (Popup::count() === 0) {
            Popup::create([
                'title' => 'Sosialisasi SPMB & Pembelajaran Digital SMAN 2 Situbondo',
                'description' => 'Informasi penting mengenai pelaksanaan SPMB 2026 dan kegiatan MPLS siswa baru.',
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ6kXTlqW4082njJ1YE7lErBRJ9CE9MzICAWcPPazjRXQ&s=10',
                'start_date' => Carbon::now()->subDays(5)->toDateString(),
                'end_date' => Carbon::now()->addDays(30)->toDateString(),
                'is_active' => true,
            ]);
        }

        // 3. Seed News (5 Published News)
        if (News::count() === 0) {
            $newsData = [
                [
                    'title' => 'SUPERVISI KOLABORATIF BERSAMA PENGAWAS PEMBINA',
                    'summary' => 'Pelaksanaan supervisi kolaboratif bersama pengawas pembina dalam rangka peningkatan mutu pembelajaran di SMAN 2 Situbondo.',
                    'published_at' => Carbon::now()->subDays(1),
                ],
                [
                    'title' => 'PERINGATAN HUT SMAN 2 SITUBONDO KE-49',
                    'summary' => 'Serangkaian acara peringatan Hari Ulang Tahun SMAN 2 Situbondo berlangsung meriah dengan berbagai pentas seni & jalan sehat.',
                    'published_at' => Carbon::now()->subDays(3),
                ],
                [
                    'title' => 'PERINGATAN MAULID NABI MUHAMMAD SAW',
                    'summary' => 'Civitas akademika SMAN 2 Situbondo menggelar ceramah agama dan lantunan shalawat bersama dalam memperingati Maulid Nabi.',
                    'published_at' => Carbon::now()->subDays(7),
                ],
                [
                    'title' => 'PEMBUKAAN MPLS SISWA BARU TAHUN AJARAN 2025/2026',
                    'summary' => 'Kegiatan Masa Pengenalan Lingkungan Sekolah resmi dibuka oleh Kepala SMAN 2 Situbondo Ibu Nikmatil Hasanah.',
                    'published_at' => Carbon::now()->subDays(12),
                ],
                [
                    'title' => 'PANGGUNG JUARA: CETAK GENERASI UNGGUL DAN BERPRESTASI',
                    'summary' => 'Siswa-siswi SMAN 2 Situbondo berhasil meraih puluhan medali kejuaraan di tingkat kabupaten dan provinsi.',
                    'published_at' => Carbon::now()->subDays(20),
                ],
            ];

            foreach ($newsData as $item) {
                News::create([
                    'title' => $item['title'],
                    'thumbnail_url' => '/build/assets/banner smada.png',
                    'summary' => $item['summary'],
                    'content' => $item['summary'] . ' Isi lengkap berita dapat dibaca di halaman berita.',
                    'status' => 'published',
                    'published_at' => $item['published_at'],
                    'created_by' => $admin->id,
                ]);
            }
        }

        // 4. Seed Announcements (5 Published Announcements)
        if (Announcement::count() === 0) {
            $annData = [
                [
                    'title' => 'MPLS 2025 SMAN 2 SITUBONDO',
                    'summary' => 'Jadwal dan ketentuan pelaksanaan Masa Pengenalan Lingkungan Sekolah bagi seluruh calon siswa baru.',
                    'published_at' => Carbon::now()->subDays(2),
                ],
                [
                    'title' => 'PENGUMUMAN KELULUSAN TAHUN AJARAN 2024/2025',
                    'summary' => 'Pengumuman kelulusan siswa kelas XII SMAN 2 Situbondo dapat diakses secara online melalui sistem kelulusan.',
                    'published_at' => Carbon::now()->subDays(5),
                ],
                [
                    'title' => 'PEMBERITAHUAN PELAKSANAAN PEMBELAJARAN TATAP MUKA',
                    'summary' => 'Informasi edaran tata tertib serta jadwal kegiatan belajar mengajar tatap muka di lingkungan sekolah.',
                    'published_at' => Carbon::now()->subDays(10),
                ],
                [
                    'title' => 'JADWAL UJIAN AKHIR SEMESTER (UAS)',
                    'summary' => 'Pelaksanaan UAS semester ganjil/genap akan dimulai sesuai dengan kalender akademik sekolah.',
                    'published_at' => Carbon::now()->subDays(15),
                ],
                [
                    'title' => 'PENDAFTARAN EKSTRAKURIKULER SISWA BARU',
                    'summary' => 'Seluruh siswa kelas X diwajibkan memilih minimal satu ekstrakurikuler wajib dan satu ekstrakurikuler pilihan.',
                    'published_at' => Carbon::now()->subDays(25),
                ],
            ];

            foreach ($annData as $item) {
                Announcement::create([
                    'title' => $item['title'],
                    'thumbnail_url' => '/build/assets/banner smada.png',
                    'summary' => $item['summary'],
                    'content' => $item['summary'] . ' Informasi selengkapnya dapat ditanyakan ke panitia.',
                    'status' => 'published',
                    'published_at' => $item['published_at'],
                    'created_by' => $admin->id,
                ]);
            }
        }

        // 5. Seed Students (Demographics for SMADA Fact)
        if (Student::count() === 0) {
            // Seed sample students across Kelas X, XI, XII to total 1073
            for ($i = 1; $i <= 360; $i++) {
                Student::create([
                    'nisn' => '006' . str_pad($i, 7, '0', STR_PAD_LEFT),
                    'name' => 'Siswa Kelas X - ' . $i,
                    'class' => 'X-' . (($i % 10) + 1),
                    'is_public' => true,
                ]);
            }

            for ($i = 1; $i <= 356; $i++) {
                Student::create([
                    'nisn' => '005' . str_pad($i, 7, '0', STR_PAD_LEFT),
                    'name' => 'Siswa Kelas XI - ' . $i,
                    'class' => 'XI-' . (($i % 10) + 1),
                    'is_public' => true,
                ]);
            }

            for ($i = 1; $i <= 357; $i++) {
                Student::create([
                    'nisn' => '004' . str_pad($i, 7, '0', STR_PAD_LEFT),
                    'name' => 'Siswa Kelas XII - ' . $i,
                    'class' => 'XII-' . (($i % 10) + 1),
                    'is_public' => true,
                ]);
            }
        }

        // 6. Seed Employees (Realistic Civitas Akademik matching SMAN 2 Situbondo)
        if (Employee::count() === 0) {
            $employeeData = [
                // Pimpinan
                [
                    'name' => 'Nikmatil Hasanah, S.Pd, M.Pd',
                    'nip' => '19840516 200604 2 012',
                    'position' => 'Kepala Sekolah',
                    'photo_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHM8T5wDv85gv3khaO0MdjxxsvdRtQUSfKR1ZgEcmERA&s=10',
                    'extra_info' => 'Kepala SMA Negeri 2 Situbondo yang berdedikasi memajukan mutu pendidikan berbasis digital dan karakter prima.',
                ],
                [
                    'name' => 'Alifa Wulandari, S.Pd',
                    'nip' => '19860821 200903 2 009',
                    'position' => 'Wakasek Humas',
                    'photo_url' => null,
                    'extra_info' => 'Wakil Kepala Sekolah Bidang Hubungan Masyarakat.',
                ],
                [
                    'name' => 'Rofiqa Yuni Astuti, S.TP',
                    'nip' => '19740801 200801 2 018',
                    'position' => 'Wakasek Kurikulum',
                    'photo_url' => null,
                    'extra_info' => 'Wakil Kepala Sekolah Bidang Pengembangan Kurikulum dan Pembelajaran.',
                ],
                [
                    'name' => 'Jiefri Gunawan, S.Pd, Gr',
                    'nip' => '19811214 202221 1 013',
                    'position' => 'Wakasek Kesiswaan',
                    'photo_url' => null,
                    'extra_info' => 'Wakil Kepala Sekolah Bidang Kesiswaan dan Pembinaan Karakter.',
                ],
                [
                    'name' => 'Mas Rudy Hartono, S.Kom',
                    'nip' => '19800519 202221 1 008',
                    'position' => 'Wakasek Sarpras',
                    'photo_url' => null,
                    'extra_info' => 'Wakil Kepala Sekolah Bidang Sarana, Prasarana dan Teknologi Informasi.',
                ],
                // Dewan Guru
                [
                    'name' => 'Budi Santoso, S.Si',
                    'nip' => '19850112 201001 1 004',
                    'position' => 'Guru Biologi',
                    'photo_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
                    'extra_info' => 'Guru Pengampu Mata Pelajaran Biologi Kelas XI & XII.',
                ],
                [
                    'name' => 'Siti Aminah, S.Pd',
                    'nip' => '19900325 201504 2 001',
                    'position' => 'Guru Matematika',
                    'photo_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=500&auto=format&fit=crop&q=80',
                    'extra_info' => 'Guru Pengampu Mata Pelajaran Matematika Peminatan.',
                ],
                // Tenaga Kependidikan / Staff
                [
                    'name' => 'Ahmad Riza',
                    'nip' => '19881105 201212 1 002',
                    'position' => 'Staff Tata Usaha',
                    'photo_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=500&auto=format&fit=crop&q=80',
                    'extra_info' => 'Staff Administrasi dan Tata Usaha Sekolah.',
                ],
                [
                    'name' => 'Dra. Endang Sulistyowati',
                    'nip' => '19680315 199403 2 005',
                    'position' => 'Guru Bahasa Indonesia',
                    'photo_url' => null,
                    'extra_info' => 'Guru Bahasa Indonesia Kelas X & XI.',
                ],
                [
                    'name' => 'Mohammad Fajar, S.Pd',
                    'nip' => '19870912 201101 1 007',
                    'position' => 'Guru Bahasa Inggris',
                    'photo_url' => null,
                    'extra_info' => 'Guru Bahasa Inggris dan Pembina English Club.',
                ],
                [
                    'name' => 'Ratna Dewi, S.Pd',
                    'nip' => '19920108 201903 2 015',
                    'position' => 'Guru Fisika',
                    'photo_url' => null,
                    'extra_info' => 'Guru Fisika dan Pembina Olimpiade Sains.',
                ],
                [
                    'name' => 'Hendra Kusuma, S.Pd',
                    'nip' => '19830422 200902 1 003',
                    'position' => 'Guru Kimia',
                    'photo_url' => null,
                    'extra_info' => 'Guru Kimia Kelas XI & XII.',
                ],
            ];

            foreach ($employeeData as $data) {
                Employee::create(array_merge($data, ['is_active' => true]));
            }

            // Seed additional 27 staff to maintain demographic fact total
            for ($i = 2; $i <= 28; $i++) {
                Employee::create([
                    'nip' => '1985' . str_pad($i + 30, 14, '0', STR_PAD_LEFT),
                    'name' => 'Staf Tata Usaha ' . $i,
                    'position' => 'Staff Tata Usaha',
                    'is_active' => true,
                ]);
            }
        }

       
    }
}

