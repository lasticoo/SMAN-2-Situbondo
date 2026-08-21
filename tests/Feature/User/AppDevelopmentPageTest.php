<?php

namespace Tests\Feature\User;

use App\Models\ColorSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppDevelopmentPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_apps_development_pages_are_accessible(): void
    {
        $apps = [
            '/elearning' => 'Elearning',
            '/video-pembelajaran' => 'Video Pembelajaran',
            '/buku-digital' => 'Buku Digital',
            '/literasi' => 'Literasi',
            '/aplikasi' => 'Aplikasi',
        ];

        foreach ($apps as $url => $titleSnippet) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee('Aplikasi Dalam Masa');
            $response->assertSee('Pengembangan');
            $response->assertSee($titleSnippet);
            $response->assertSee('Kembali ke Beranda');
            $response->assertSee('Hubungi Layanan Informasi');
        }
    }

    public function test_dynamic_theme_colors_are_applied_to_apps_development_page(): void
    {
        ColorSetting::create([
            'primary_color' => '#002B49',
            'secondary_color' => '#FFBF00',
        ]);

        $response = $this->get('/elearning');

        $response->assertStatus(200);
        $response->assertSee('--primary-main: #002B49', false);
        $response->assertSee('--secondary-gold: #FFBF00', false);
    }

    public function test_footer_contains_working_application_links(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('apps.elearning'));
        $response->assertSee(route('apps.video'));
        $response->assertSee(route('apps.buku'));
        $response->assertSee(route('apps.literasi'));
    }
}
