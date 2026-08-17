<?php

namespace Tests\Feature\User;

use App\Models\SchoolProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_profile_page_without_login(): void
    {
        $response = $this->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('Profil SMAN 2 Situbondo');
    }

    public function test_profile_page_displays_school_profile_database_fields(): void
    {
        SchoolProfile::create([
            'about_us' => 'Sekilas tentang SMAN 2 Situbondo sekolah unggulan.',
            'vision' => 'Visi Sekolah Cerdas Berkarakter',
            'mission' => 'Misi Sekolah Inovatif Berkelanjutan',
            'goals' => 'Tujuan Sekolah Berdaya Saing Global',
            'history' => 'Sejarah Sekolah Berdiri Sejak 1980',
            'structure_image_url' => '/images/static/semangat_prima_bg.jpg',
        ]);

        $response = $this->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('Sekilas tentang SMAN 2 Situbondo sekolah unggulan');
        $response->assertSee('Visi, Misi &amp; Tujuan', false);
        $response->assertSee('Sejarah Singkat');
        $response->assertSee('Struktur Organisasi');
        $response->assertSee('Semangat PRIMA');
    }
}
