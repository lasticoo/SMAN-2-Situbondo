<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AnnouncementTestDataSeederUser extends Seeder
{
    /**
     * Seeder Khusus Data Simulasi Pengumuman (US-05)
     * Mengisi kategori: Akademik, Kesiswaan, Informasi Umum
     */
    public function run(): void
    {
        $admin = Admin::first() ?? Admin::create([
            'name' => 'Administrator SMADA',
            'email' => 'admin@smada.sch.id',
            'password' => bcrypt('admin123'),
        ]);

        $annData = [
            [
                'title' => 'Pembukaan Masa Pengenalan Lingkungan Sekolah (MPLS) 2025',
                'category' => 'Informasi Umum',
                'summary' => 'Kegiatan Masa Pengenalan Lingkungan Sekolah (MPLS) tahun ajaran 2025/2026 resmi dibuka. Diharapkan seluruh peserta didik baru kelas X dapat mengikuti seluruh rangkaian acara.',
                'content' => "Kegiatan Masa Pengenalan Lingkungan Sekolah (MPLS) tahun ajaran 2025/2026 resmi dibuka oleh Kepala SMA Negeri 2 Situbondo.\n\nDiharapkan seluruh peserta didik baru kelas X dapat mengikuti seluruh rangkaian acara dengan tertib, penuh semangat, dan menjunjung tinggi nilai kedisiplinan serta kekeluargaan di lingkungan sekolah.\n\nJadwal kegiatan dimulai pukul 07.00 WIB di Lapangan Utama SMAN 2 Situbondo dengan mengenakan seragam sekolah asal masing-masing.",
                'published_at' => Carbon::now()->subDays(2)->setTime(8, 0),
            ],
            [
                'title' => 'Peringatan Maulid Nabi Muhammad SAW 1447 H',
                'category' => 'Kesiswaan',
                'summary' => 'Dalam rangka memperingati hari kelahiran Nabi Muhammad SAW, OSIS SMAN 2 Situbondo menyelenggarakan pengajian akbar yang wajib dihadiri oleh seluruh warga sekolah.',
                'content' => "Dalam rangka memperingati hari kelahiran Nabi Muhammad SAW, OSIS SMAN 2 Situbondo menyelenggarakan pengajian akbar dan lantunan shalawat bersama.\n\nKegiatan ini wajib dihadiri oleh seluruh dewan guru, staf, dan peserta didik muslim yang bertempat di Aula Utama Graha Smada Prima pada pukul 07.30 WIB.\n\nMari jadikan momentum peringatan Maulid Nabi ini untuk meneladani akhlak mulia Rasulullah SAW dalam kehidupan sehari-hari.",
                'published_at' => Carbon::now()->subDays(5)->setTime(7, 30),
            ],
            [
                'title' => 'Peringatan HUT Polantas Ke-70 di Lingkungan Sekolah',
                'category' => 'Informasi Umum',
                'summary' => 'SMAN 2 Situbondo terpilih sebagai tuan rumah sosialisasi keselamatan berkendara dalam rangka HUT Polisi Lalu Lintas ke-70. Seluruh pengurus kelas diharap berkumpul di lapangan.',
                'content' => "SMAN 2 Situbondo terpilih sebagai tuan rumah sosialisasi keselamatan berkendara (Safety Riding) dalam rangka peringatan HUT Polisi Lalu Lintas ke-70 bersama Satlantas Polres Situbondo.\n\nSeluruh perwakilan pengurus kelas X, XI, dan XII diharapkan hadir tepat waktu di lapangan basket pukul 09.00 WIB untuk mendapatkan materi edukasi tertib lalu lintas dan simulasi berkendara yang aman.",
                'published_at' => Carbon::now()->subDays(10)->setTime(9, 0),
            ],
            [
                'title' => 'Jadwal Penilaian Akhir Semester (PAS) Ganjil',
                'category' => 'Akademik',
                'summary' => 'Pelaksanaan Penilaian Akhir Semester (PAS) Ganjil akan dimulai sesuai dengan kalender akademik. Siswa diimbau mempersiapkan perangkat digital dan kartu peserta ujian.',
                'content' => "Diberitahukan kepada seluruh siswa kelas X, XI, dan XII bahwa Penilaian Akhir Semester (PAS) Ganjil akan dilaksanakan secara Computer Based Test (CBT).\n\nSiswa diimbau untuk memeriksa jadwal ujian masing-masing mata pelajaran, memastikan koneksi internet stabil, dan membawa gawai/laptop yang telah terpasang aplikasi ujian sekolah.",
                'published_at' => Carbon::now()->subDays(15)->setTime(8, 0),
            ],
            [
                'title' => 'Pendaftaran Ekstrakurikuler Siswa Baru',
                'category' => 'Kesiswaan',
                'summary' => 'Seluruh siswa kelas X diwajibkan memilih minimal satu ekstrakurikuler wajib (Pramuka) dan satu ekstrakurikuler pilihan (Paskibra, PMR, Seni Musik, Olahraga).',
                'content' => "Pendaftaran kegiatan ekstrakurikuler bagi siswa baru kelas X telah resmi dibuka.\n\nSetiap siswa wajib mengikuti ekstrakurikuler Kepramukaan dan dipersilakan memilih salah satu dari ragam ekstrakurikuler pilihan minat bakat seperti Paskibra, PMR, KIR, Paduan Suara, Tari Tradisional, Futsal, Basket, dan Robotika.",
                'published_at' => Carbon::now()->subDays(20)->setTime(13, 0),
            ],
            [
                'title' => 'Pelaksanaan Asesmen Nasional Berbasis Komputer (ANBK)',
                'category' => 'Akademik',
                'summary' => 'Pelaksanaan gladi bersih dan simulasi utama ANBK bagi siswa terpilih kelas XI akan berlangsung di Lab Komputer ICT 1 dan 2.',
                'content' => "SMA Negeri 2 Situbondo akan melaksanakan Asesmen Nasional Berbasis Komputer (ANBK) untuk memotret mutu input, proses, dan hasil belajar di lingkungan satuan pendidikan.\n\nSiswa yang terpilih sebagai sampel diharapkan hadir sesuai sesi yang telah ditentukan di laboratorium komputer.",
                'published_at' => Carbon::now()->subDays(25)->setTime(7, 30),
            ],
        ];

        foreach ($annData as $item) {
            Announcement::updateOrCreate(
                ['title' => $item['title']],
                [
                    'category' => $item['category'],
                    'thumbnail_url' => '/build/assets/banner smada.png',
                    'summary' => $item['summary'],
                    'content' => $item['content'],
                    'status' => 'published',
                    'published_at' => $item['published_at'],
                    'created_by' => $admin->id,
                ]
            );
        }
    }
}
