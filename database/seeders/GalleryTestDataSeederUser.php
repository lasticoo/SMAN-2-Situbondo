<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Gallery;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GalleryTestDataSeederUser extends Seeder
{
    /**
     * Run the database seeds for US-06 Media Galeri Kegiatan.
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
            ]);
        }

        // Data kegiatan sekolah dengan nama aktivitas bersih tanpa tanda kurung []
        $galleryData = [
            [
                'activity_name' => 'Sosialisasi dan Pembekalan Siswa Berprestasi',
                'activity_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'photo_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1200&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'activity_name' => 'Peringatan Maulid Nabi Muhammad SAW 1447 H',
                'activity_date' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'photo_url' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 2,
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'activity_name' => 'Sosialisasi Tertib Berlalu Lintas Satlantas',
                'activity_date' => Carbon::now()->subDays(9)->format('Y-m-d'),
                'photo_url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 3,
                'created_at' => Carbon::now()->subDays(9),
            ],
            [
                'activity_name' => 'Rapat Koordinasi Kurikulum Merdeka Guru',
                'activity_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
                'photo_url' => 'https://images.unsplash.com/photo-1531497865144-0464ef8fb9a9?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 4,
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'activity_name' => 'Daftar Prestasi Siswa Lolos PTN 2025',
                'activity_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'photo_url' => 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 5,
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'activity_name' => 'Latihan Gabungan Paskibraka dan Pramuka',
                'activity_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'photo_url' => 'https://images.unsplash.com/photo-1526976668912-1a811878dd37?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 6,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'activity_name' => 'Turnamen Futsal Smada Cup Antar Kelas',
                'activity_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'photo_url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 7,
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'activity_name' => 'Pagelaran Seni Budaya dan Teater Siswa',
                'activity_date' => Carbon::now()->format('Y-m-d'),
                'photo_url' => 'https://images.unsplash.com/photo-1469488865564-c2de10f69f96?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 8,
                'created_at' => Carbon::now(),
            ],
        ];

        Gallery::unguarded(function () use ($galleryData, $admin) {
            foreach ($galleryData as $data) {
                $gallery = Gallery::where('activity_name', $data['activity_name'])->first() ?? new Gallery();
                $gallery->activity_name = $data['activity_name'];
                $gallery->activity_date = $data['activity_date'];
                $gallery->photo_url = $data['photo_url'];
                $gallery->sort_order = $data['sort_order'];
                $gallery->created_by = $admin->id;
                $gallery->created_at = $data['created_at'] ?? Carbon::now();
                $gallery->updated_at = Carbon::now();
                $gallery->save();
            }
        });
    }
}
