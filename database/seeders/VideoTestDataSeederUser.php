<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VideoTestDataSeederUser extends Seeder
{
    /**
     * Run the database seeds for US-06 Media Video SMAN 2 Situbondo
     * Sumber data asli 100% dari Channel YouTube Resmi @SMADAPRIMA (SMA Negeri 2 Situbondo)
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

        // 12 Video Nyata Resmi dari Kanal YouTube @SMADAPRIMA (SMAN 2 Situbondo)
        $videoData = [
            [
                'title' => '[Podcast] EPS19. SMADA PODCAST - SEBELUM KAMI MENGENAL MERDEKA',
                'youtube_url' => 'https://www.youtube.com/watch?v=LuKBQw8ubXE',
                'youtube_id' => 'LuKBQw8ubXE',
                'thumbnail_url' => 'https://img.youtube.com/vi/LuKBQw8ubXE/hqdefault.jpg',
                'sort_order' => 1,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => '[Kesiswaan] EPS18. SMADA PODCAST - OSIS SMADA PRIMA CARI PEMIMPIN BARU',
                'youtube_url' => 'https://www.youtube.com/watch?v=vwGXBJ4UFNw',
                'youtube_id' => 'vwGXBJ4UFNw',
                'thumbnail_url' => 'https://img.youtube.com/vi/vwGXBJ4UFNw/hqdefault.jpg',
                'sort_order' => 2,
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => '[Event] EPS17. SMADA PODCAST - KUPAS TUNTAS PENERIMAAN AKPOL BERSAMA KAPOLRES SITUBONDO',
                'youtube_url' => 'https://www.youtube.com/watch?v=aWTMwQNogQY',
                'youtube_id' => 'aWTMwQNogQY',
                'thumbnail_url' => 'https://img.youtube.com/vi/aWTMwQNogQY/hqdefault.jpg',
                'sort_order' => 3,
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'title' => '[Profil] Kawasan Tanpa Rokok | SMAN 2 Situbondo',
                'youtube_url' => 'https://www.youtube.com/watch?v=EEP3i1vHgpo',
                'youtube_id' => 'EEP3i1vHgpo',
                'thumbnail_url' => 'https://img.youtube.com/vi/EEP3i1vHgpo/hqdefault.jpg',
                'sort_order' => 4,
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'title' => '[Kesiswaan] EPS12. SMADA PODCAST - PEMIMPIN CERDAS DAN AMANAH SEJAK SMA',
                'youtube_url' => 'https://www.youtube.com/watch?v=68_m-UveeWc',
                'youtube_id' => '68_m-UveeWc',
                'thumbnail_url' => 'https://img.youtube.com/vi/68_m-UveeWc/hqdefault.jpg',
                'sort_order' => 5,
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'title' => '[Event] Parade Prima Nusantara | SMAN 2 Situbondo - EJIES 2026',
                'youtube_url' => 'https://www.youtube.com/watch?v=fOKjstPja3E',
                'youtube_id' => 'fOKjstPja3E',
                'thumbnail_url' => 'https://img.youtube.com/vi/fOKjstPja3E/hqdefault.jpg',
                'sort_order' => 6,
                'created_at' => Carbon::now()->subDays(25),
            ],
            [
                'title' => '[Inovasi] VGpreneur (Inkubator Bisnis SMADA) | SMAN 2 Situbondo - EJIES 2026',
                'youtube_url' => 'https://www.youtube.com/watch?v=8bNjht9EEwk',
                'youtube_id' => '8bNjht9EEwk',
                'thumbnail_url' => 'https://img.youtube.com/vi/8bNjht9EEwk/hqdefault.jpg',
                'sort_order' => 7,
                'created_at' => Carbon::now()->subDays(30),
            ],
            [
                'title' => '[Kesiswaan] Romansa 5.0 | SMAN 2 Situbondo - EJIES 2026',
                'youtube_url' => 'https://www.youtube.com/watch?v=O8bss3Ka_-g',
                'youtube_id' => 'O8bss3Ka_-g',
                'thumbnail_url' => 'https://img.youtube.com/vi/O8bss3Ka_-g/hqdefault.jpg',
                'sort_order' => 8,
                'created_at' => Carbon::now()->subDays(35),
            ],
            [
                'title' => '[Kesiswaan] Etalase Pintar, Satu Wadah Sejuta Karya | SMAN 2 Situbondo - EJIES 2026',
                'youtube_url' => 'https://www.youtube.com/watch?v=TFavwyeMLYc',
                'youtube_id' => 'TFavwyeMLYc',
                'thumbnail_url' => 'https://img.youtube.com/vi/TFavwyeMLYc/hqdefault.jpg',
                'sort_order' => 9,
                'created_at' => Carbon::now()->subDays(40),
            ],
            [
                'title' => '[Akademik] SAPEDA - Sanggam Pena Budaya | SMAN 2 Situbondo - EJIES 2026',
                'youtube_url' => 'https://www.youtube.com/watch?v=f76PILEojxQ',
                'youtube_id' => 'f76PILEojxQ',
                'thumbnail_url' => 'https://img.youtube.com/vi/f76PILEojxQ/hqdefault.jpg',
                'sort_order' => 10,
                'created_at' => Carbon::now()->subDays(45),
            ],
            [
                'title' => '[Podcast] SMADA PODCAST | SMAN 2 Situbondo - EJIES 2026',
                'youtube_url' => 'https://www.youtube.com/watch?v=Cg-9iIWQjuc',
                'youtube_id' => 'Cg-9iIWQjuc',
                'thumbnail_url' => 'https://img.youtube.com/vi/Cg-9iIWQjuc/hqdefault.jpg',
                'sort_order' => 11,
                'created_at' => Carbon::now()->subDays(50),
            ],
            [
                'title' => '[Kesiswaan] KK26 Legenda di Balik Layar Kreatifitas Siswa | SMAN 2 Situbondo - EJIES 2026',
                'youtube_url' => 'https://www.youtube.com/watch?v=StpfuEIQkF8',
                'youtube_id' => 'StpfuEIQkF8',
                'thumbnail_url' => 'https://img.youtube.com/vi/StpfuEIQkF8/hqdefault.jpg',
                'sort_order' => 12,
                'created_at' => Carbon::now()->subDays(60),
            ],
        ];

        Video::unguarded(function () use ($videoData, $admin) {
            foreach ($videoData as $data) {
                $video = Video::where('title', $data['title'])->first() ?? new Video();
                $video->title = $data['title'];
                $video->youtube_url = $data['youtube_url'];
                $video->youtube_id = $data['youtube_id'];
                $video->thumbnail_url = $data['thumbnail_url'];
                $video->sort_order = $data['sort_order'];
                $video->created_by = $admin->id;
                $video->created_at = $data['created_at'];
                $video->updated_at = $data['created_at'];
                $video->save();
            }
        });
    }
}
