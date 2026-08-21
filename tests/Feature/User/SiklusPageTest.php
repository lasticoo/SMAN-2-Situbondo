<?php

namespace Tests\Feature\User;

use App\Models\ColorSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiklusPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_siklus_page_is_accessible_by_public(): void
    {
        $response = $this->get('/siklus');

        $response->assertStatus(200);
        $response->assertSee('Fitur Sedang Dalam');
        $response->assertSee('Pengembangan');
        $response->assertSee('SIKLUS');
        $response->assertSee('Kembali ke Beranda');
        $response->assertSee('Hubungi Layanan Informasi');
        $response->assertSee('Cek Status Kelulusan NISN');
        $response->assertSee('Unduh SKL &amp; Transkrip Digital', false);
    }

    public function test_siklus_alias_routes_are_accessible(): void
    {
        $responseKelulusan = $this->get('/kelulusan');
        $responseKelulusan->assertStatus(200);

        $responseSkl = $this->get('/skl');
        $responseSkl->assertStatus(200);

        $responseCek = $this->get('/cek-kelulusan');
        $responseCek->assertStatus(200);
    }

    public function test_dynamic_theme_colors_are_applied_to_siklus_page(): void
    {
        ColorSetting::create([
            'primary_color' => '#0A2540',
            'secondary_color' => '#FF8A00',
        ]);

        $response = $this->get('/siklus');

        $response->assertStatus(200);
        $response->assertSee('--primary-main: #0A2540', false);
        $response->assertSee('--secondary-gold: #FF8A00', false);
    }

    public function test_all_siklus_navigation_links_point_to_siklus_index(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('siklus.index'));
    }
}
