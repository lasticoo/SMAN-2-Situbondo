<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Employee;
use App\Models\News;
use App\Models\Popup;
use App\Models\SchoolProfile;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LandingPageTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();
        if (! $admin) {
            $admin = Admin::create([
                'name' => 'Admin Test',
                'email' => 'admin@smada.sch.id',
                'password' => bcrypt('password123'),
            ]);
        }

        if (SchoolProfile::count() === 0) {
            SchoolProfile::create([
                'vision' => 'Menjadi lembaga pendidikan terdepan yang menghasilkan lulusan berkarakter, inovatif, dan berdaya saing global berlandaskan nilai-nilai luhur bangsa.',
                'mission' => "1. Menyelenggarakan pendidikan berkualitas berorientasi pada kecerdasan spiritual, intelektual, dan emosional.\n2. Mengembangkan potensi peserta didik secara optimal melalui kegiatan intrakurikuler dan ekstrakurikuler.\n3. Menanamkan nilai budi pekerti, kedisiplinan, dan kepedulian lingkungan.",
                'goals' => "1. Mewujudkan lulusan yang memiliki kompetensi akademik dan non-akademik unggul.\n2. Meningkatkan persentase kelulusan siswa ke Perguruan Tinggi Negeri (PTN) favorit.\n3. Membentuk karakter siswa yang beriman, bertakwa, serta berwawasan lingkungan.",
                'history' => 'SMA Negeri 2 Situbondo didirikan pada tahun 1980 dengan tujuan mulia untuk mencerdaskan kehidupan bangsa di wilayah Kabupaten Situbondo. Seiring berjalannya waktu, SMAN 2 Situbondo tumbuh menjadi salah satu sekolah unggulan yang melahirkan banyak alumni berprestasi di berbagai bidang.',
                'about_us' => 'SMA Negeri 2 Situbondo (Smada) merupakan salah satu Sekolah Menengah Atas Negeri unggulan di Kabupaten Situbondo yang berkomitmen untuk memberikan layanan pendidikan terbaik, berbasis teknologi informasi (ICT), dan membentuk karakter generasi muda Indonesia.',
                'structure_image_url' => null,
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
                    'content' => $item['summary'].' Isi lengkap berita dapat dibaca di halaman berita.',
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
                    'content' => $item['summary'].' Informasi selengkapnya dapat ditanyakan ke panitia.',
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
                    'nisn' => '006'.str_pad($i, 7, '0', STR_PAD_LEFT),
                    'name' => 'Siswa Kelas X - '.$i,
                    'class' => 'X-'.(($i % 10) + 1),
                    'is_public' => true,
                ]);
            }

            for ($i = 1; $i <= 356; $i++) {
                Student::create([
                    'nisn' => '005'.str_pad($i, 7, '0', STR_PAD_LEFT),
                    'name' => 'Siswa Kelas XI - '.$i,
                    'class' => 'XI-'.(($i % 10) + 1),
                    'is_public' => true,
                ]);
            }

            for ($i = 1; $i <= 357; $i++) {
                Student::create([
                    'nisn' => '004'.str_pad($i, 7, '0', STR_PAD_LEFT),
                    'name' => 'Siswa Kelas XII - '.$i,
                    'class' => 'XII-'.(($i % 10) + 1),
                    'is_public' => true,
                ]);
            }
        }

        // 6. Seed Employees (Guru vs Staff for SMADA Fact)
        if (Employee::count() === 0) {
            // Seed 12 Teachers
            $guruPositions = [
                'Kepala Sekolah', 'Wakil Kepala Sekolah Kurikulum', 'Wakil Kepala Sekolah Kesiswaan',
                'Guru Matematika', 'Guru Bahasa Indonesia', 'Guru Bahasa Inggris', 'Guru Fisika',
                'Guru Kimia', 'Guru Biologi', 'Guru Sejarah', 'Guru Olahraga', 'Guru Seni Budaya',
            ];

            foreach ($guruPositions as $idx => $pos) {
                Employee::create([
                    'nip' => '1980'.str_pad($idx + 1, 14, '0', STR_PAD_LEFT),
                    'name' => 'Tenaga Pendidik '.($idx + 1),
                    'position' => $pos,
                    'is_active' => true,
                ]);
            }

            // Seed 28 Staff Members
            for ($i = 1; $i <= 28; $i++) {
                Employee::create([
                    'nip' => '1985'.str_pad($i + 20, 14, '0', STR_PAD_LEFT),
                    'name' => 'Tenaga Kependidikan '.$i,
                    'position' => 'Staf Tata Usaha / Administrasi',
                    'is_active' => true,
                ]);
            }
        }
    }
}
