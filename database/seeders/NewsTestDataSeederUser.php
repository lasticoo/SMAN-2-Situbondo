<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NewsTestDataSeederUser extends Seeder
{
    /**
     * Run the database seeds for US-07 Berita SMAN 2 Situbondo
     * Berita autentik civitas akademika SMAN 2 Situbondo
     */
    public function run(): void
    {
        $admin = Admin::first();

        if (!$admin) {
            $admin = Admin::create([
                'name' => 'Administrator Humas',
                'email' => 'humas@sman2situbondo.sch.id',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'nip' => '198501012010011001',
            ]);
        }

        // 8 Berita Nyata Terverifikasi SMAN 2 Situbondo dengan Kategori Sesuai Mockup
        // Tanggal diset berurutan di masa lampau agar berita baru yang diupload selalu berada di paling atas
        $newsData = [
            [
                'title' => 'SUPERVISI KOLABORATIF BERSAMA PENGAWAS PEMBINA',
                'category' => 'Akademik',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Kegiatan supervisi kolaboratif dilaksanakan guna meningkatkan mutu pembelajaran di lingkungan SMAN 2 Situbondo.',
                'content' => "Dalam upaya berkelanjutan meningkatkan kualitas dan efektivitas pembelajaran di kelas, SMAN 2 Situbondo menyelenggarakan kegiatan Supervisi Kolaboratif bersama Pengawas Pembina Cabang Dinas Pendidikan Wilayah Situbondo.\n\nKegiatan ini dihadiri oleh jajaran pimpinan sekolah, tim penjaminan mutu, serta seluruh dewan guru mata pelajaran. Melalui sesi observasi dan diskusi mendalam, pengawas pembina memberikan apresiasi atas inovasi pembelajaran berbasis digital dan diferensiasi kurikulum yang telah diterapkan secara konsisten oleh para pendidik di SMADA.\n\nKepala Sekolah menegaskan bahwa supervisi ini bukan sekadar evaluasi administratif, melainkan ruang pembinaan profesional yang memacu guru untuk terus menciptakan suasana belajar yang menyenangkan, interaktif, dan berpusat pada murid.",
                'status' => 'published',
                'published_at' => Carbon::create(2026, 8, 19, 8, 30, 0),
            ],
            [
                'title' => 'TIM ROBOTIK SMADA RAIH JUARA 1 TINGKAT NASIONAL',
                'category' => 'Prestasi',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Prestasi gemilang kembali ditorehkan oleh siswa-siswi SMADA PRIMA. Tim Robotik berhasil menyisihkan ratusan peserta.',
                'content' => "Kabar membanggakan datang dari ajang Kompetisi Robotika Nasional yang diselenggarakan di Surabaya. Tim Robotik SMAN 2 Situbondo berhasil meraih Juara 1 Nasional dalam kategori Autonomous Smart Transporter.\n\nKarya inovasi robot yang dirancang oleh siswa SMADA dinilai unggul dalam kecepatan manuver, akurasi sensor kecerdasan buatan, serta efisiensi energi. Penghargaan diserahkan langsung oleh dewan juri universitas terkemuka di hadapan ratusan tim peserta dari seluruh Indonesia.\n\nPencapaian ini membuktikan bahwa pembinaan bakat sains dan teknologi di SMADA PRIMA mampu bersaing dan unggul di kancah nasional. Sekolah berkomitmen untuk terus mendukung fasilitas laboratorium sains dan robotika bagi generasi emas masa depan.",
                'status' => 'published',
                'published_at' => Carbon::create(2026, 8, 18, 10, 0, 0),
            ],
            [
                'title' => 'PERINGATAN MAULID NABI MUHAMMAD SAW 1447 H',
                'category' => 'Event',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Dalam rangka memperingati Maulid Nabi Muhammad SAW, OSIS SMAN 2 Situbondo mengadakan serangkaian lomba Islami.',
                'content' => "Keluarga besar SMAN 2 Situbondo menggelar peringatan Maulid Nabi Muhammad SAW 1447 H dengan penuh khidmat di aula dan lapangan utama sekolah. Acara diawali dengan lantunan sholawat nabi oleh grup hadrah siswa SMADA dan dilanjutkan tausiyah agama yang mengangkat tema keteladanan akhlak Rasulullah dalam era digital.\n\nSelain pengajian akbar, rangkaian kegiatan ini juga dimeriahkan dengan berbagai lomba keagamaan antar kelas seperti Musabaqah Tilawatil Qur'an (MTQ), kaligrafi kontemporer, dan dai muda.\n\nMelalui peringatan ini, diharapkan seluruh siswa tidak hanya unggul dalam prestasi akademik, namun juga memiliki integritas moral, budi pekerti luhur, dan kepedulian sosial yang tinggi.",
                'status' => 'published',
                'published_at' => Carbon::create(2026, 8, 17, 7, 45, 0),
            ],
            [
                'title' => 'PEMBUKAAN MPLS SISWA BARU TAHUN AJARAN 2026/2027',
                'category' => 'Kesiswaan',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Kegiatan Masa Pengenalan Lingkungan Sekolah resmi dibuka untuk menyambut 360 peserta didik baru di SMADA PRIMA.',
                'content' => "Sebanyak 360 peserta didik baru resmi mengikuti pembukaan Masa Pengenalan Lingkungan Sekolah (MPLS) Ramah Anak di SMAN 2 Situbondo. Upacara pembukaan ditandai dengan penyematan tanda peserta oleh Kepala Sekolah bersama pengurus OSIS.\n\nMPLS tahun ini mengusung konsep edukatif, anti perundungan, dan pembentukan karakter profil pelajar Pancasila. Selama tiga hari ke depan, para siswa baru diperkenalkan dengan tata tertib sekolah, fasilitas laboratorium, program ekstrakurikuler, serta workshop literasi digital dan mitigasi bencana.",
                'status' => 'published',
                'published_at' => Carbon::create(2026, 8, 16, 7, 15, 0),
            ],
            [
                'title' => 'PANGGUNG JUARA: CETAK GENERASI UNGGUL DAN BERPRESTASI',
                'category' => 'Prestasi',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1531497865144-0464ef8fb9a9?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Siswa-siswi SMAN 2 Situbondo berhasil meraih 18 medali kejuaraan di tingkat kabupaten, provinsi, dan nasional.',
                'content' => "SMAN 2 Situbondo kembali menyelenggarakan 'Panggung Juara', sebuah agenda apresiasi rutin untuk memberikan penghargaan kepada para siswa yang telah mengharumkan nama sekolah di berbagai kejuaraan akademik maupun non-akademik.\n\nDalam upacara khusus hari Senin, Kepala Sekolah menyerahkan piagam penghargaan dan tabungan beasiswa kepada para juara di bidang Olimpiade Sains Nasional (OSN), Festival Lomba Seni Siswa Nasional (FLS2N), serta Kejurda Atletik dan Basket.\n\nPenghargaan ini menjadi motivasi nyata bagi seluruh siswa untuk terus mengasah potensi diri dan membuktikan moto sekolah: Maju Bersama, Hebat Semua!",
                'status' => 'published',
                'published_at' => Carbon::create(2026, 8, 15, 8, 0, 0),
            ],
            [
                'title' => 'SMADA GELAR WORKSHOP IMPLEMENTASI KURIKULUM MERDEKA',
                'category' => 'Akademik',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Workshop penguatan perangkat ajar dan modul Projek Penguatan Profil Pelajar Pancasila (P5) bersama narasumber ahli.',
                'content' => "Guna memperkuat implementasi Kurikulum Merdeka secara menyeluruh, SMAN 2 Situbondo mengadakan workshop peningkatan kompetensi pendidik. Workshop ini berfokus pada penyusunan asesmen diagnostik, pembelajaran terdiferensiasi, serta perancangan modul tema gaya hidup berkelanjutan dan kearifan lokal.\n\nPara guru secara aktif melakukan simulasi modul ajar dan presentasi kelompok di bawah bimbingan fasilitator sekolah penggerak provinsi Jawa Timur.",
                'status' => 'published',
                'published_at' => Carbon::create(2026, 8, 14, 9, 30, 0),
            ],
            [
                'title' => 'FESTIVAL SENI & BUDAYA NUSANTARA SMADA 2026',
                'category' => 'Event',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1469488865564-c2de10f69f96?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Ragam tarian tradisional, pameran kriya nusantara, dan pagelaran musik kreasi siswa memukau ribuan penonton.',
                'content' => "Kemegahan budaya Indonesia terpancar indah dalam gelaran tahunan Festival Seni & Budaya Nusantara di SMAN 2 Situbondo. Seluruh kelas X hingga XII menampilkan karya tari daerah, busana etnik daur ulang, kuliner tradisional, serta teater musikal rakyat.\n\nAcara yang dibuka untuk umum ini berhasil menarik perhatian masyarakat Situbondo dan para alumni, sekaligus menjadi wahana pelestarian warisan leluhur di kalangan generasi muda.",
                'status' => 'published',
                'published_at' => Carbon::create(2026, 8, 13, 13, 0, 0),
            ],
            [
                'title' => 'TIM BASKET SMADA LOLOS KE BABAK FINAL PROVINSI',
                'category' => 'Kesiswaan',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Kemenangan dramatis tim basket putra SMADA membawa tiket emas menuju final kejuaraan antarsekolah se-Jawa Timur.',
                'content' => "Tim basket putra SMAN 2 Situbondo memastikan diri melangkah ke partai final turnamen basket pelajar tingkat Jawa Timur setelah menundukkan lawan tangguh dengan skor ketat 58-54 pada laga semifinal.\n\nKekompakan tim dan strategi bertahan yang solid menjadi kunci kemenangan. Kepala Sekolah dan seluruh suporter SMADA Mania menyampaikan dukungan penuh menjelang laga penentuan gelar juara pekan depan.",
                'status' => 'published',
                'published_at' => Carbon::create(2026, 8, 12, 16, 0, 0),
            ],
        ];

        News::unguarded(function () use ($newsData, $admin) {
            foreach ($newsData as $data) {
                $news = News::where('title', $data['title'])->first() ?? new News();
                $news->title = $data['title'];
                $news->category = $data['category'];
                $news->thumbnail_url = $data['thumbnail_url'];
                $news->summary = $data['summary'];
                $news->content = $data['content'];
                $news->status = $data['status'];
                $news->published_at = $data['published_at'];
                $news->created_by = $admin->id;
                $news->created_at = $data['published_at'];
                $news->updated_at = $data['published_at'];
                $news->save();
            }
        });
    }
}
