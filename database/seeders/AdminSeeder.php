<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\SchoolProfile;
use App\Models\ColorSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@smada.sch.id'],
            [
                'name' => 'Administrator SMAN 2 Situbondo',
                'password' => Hash::make('password123'),
            ]
        );

        ColorSetting::updateOrCreate(
            ['id' => 1],
            [
                'primary_color' => '#0B1F3A',
                'secondary_color' => '#FACC15',
                'updated_by' => $admin->id,
            ]
        );

        SchoolProfile::updateOrCreate(
            ['id' => 1],
            [
                'vision' => 'Terwujudnya Peserta Didik yang Berakhlak Mulia, Cerdas, Berprestasi, dan Berwawasan Lingkungan.',
                'mission' => "1. Meningkatkan keimanan dan ketaqwaan kepada Tuhan Yang Maha Esa.\n2. Melaksanakan pembelajaran yang efektif dan inovatif.\n3. Mengembangkan potensi bakat dan minat siswa di bidang akademik & non-akademik.",
                'goals' => 'Menciptakan lulusan yang berkualitas, mandiri, dan berdaya saing tinggi.',
                'history' => 'SMAN 2 Situbondo berdiri sejak tahun 1978 dan terus berkomitmen mencetak generasi bangsa unggul.',
                'about_us' => 'SMAN 2 Situbondo (SMADA) merupakan salah satu SMA Negeri unggulan di Kabupaten Situbondo.',
            ]
        );
    }
}
