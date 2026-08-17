<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use Illuminate\Database\Seeder;

class SchoolProfileSeeder extends Seeder
{
    /**
     * Seed initial data for school_profile table.
     */
    public function run(): void
    {
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
    }
}
