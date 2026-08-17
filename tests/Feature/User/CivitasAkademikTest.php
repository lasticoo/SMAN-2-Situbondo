<?php

namespace Tests\Feature\User;

use App\Models\ColorSetting;
use App\Models\Employee;
use App\Models\SchoolProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CivitasAkademikTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ColorSetting::create([
            'primary_color' => '#1a365d',
            'secondary_color' => '#e67e22',
        ]);

        SchoolProfile::create([
            'about_us' => 'Tentang SMAN 2 Situbondo',
            'vision' => 'Visi Sekolah Unggul',
            'mission' => 'Misi Sekolah Berkarakter',
            'goals' => 'Tujuan Sekolah Berprestasi',
            'history' => 'Sejarah Panjang Sekolah',
        ]);
    }

    public function test_user_can_access_civitas_akademik_page_without_login(): void
    {
        $response = $this->get('/civitas-akademik');
        $response->assertStatus(200);
        $response->assertSee('Data Pegawai', false);
    }

    public function test_user_can_access_via_pegawai_alias(): void
    {
        $response = $this->get('/pegawai');
        $response->assertStatus(200);
        $response->assertSee('Data Pegawai', false);
    }

    public function test_only_active_employees_are_displayed(): void
    {
        $activeEmployee = Employee::create([
            'name' => 'Budi Santoso, S.Si',
            'nip' => '19850112 201001 1 004',
            'position' => 'Guru Biologi',
            'is_active' => true,
        ]);

        $inactiveEmployee = Employee::create([
            'name' => 'Karyawan Non Aktif',
            'nip' => '19700101 199001 1 001',
            'position' => 'Mantan Guru',
            'is_active' => false,
        ]);

        $response = $this->get('/civitas-akademik');
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso, S.Si');
        $response->assertSee('Guru Biologi');
        $response->assertSee('NIP. 19850112 201001 1 004');
        $response->assertDontSee('Karyawan Non Aktif');
    }

    public function test_user_can_search_by_name_and_nip(): void
    {
        Employee::create([
            'name' => 'Siti Aminah, S.Pd',
            'nip' => '19900325 201504 2 001',
            'position' => 'Guru Matematika',
            'is_active' => true,
        ]);

        Employee::create([
            'name' => 'Ahmad Riza',
            'nip' => '19881105 201212 1 002',
            'position' => 'Staff Tata Usaha',
            'is_active' => true,
        ]);

        // Search by name
        $responseName = $this->get('/civitas-akademik?search=Aminah');
        $responseName->assertStatus(200);
        $responseName->assertSee('Siti Aminah, S.Pd');
        $responseName->assertDontSee('Ahmad Riza');

        // Search by NIP
        $responseNip = $this->get('/civitas-akademik?search=201212');
        $responseNip->assertStatus(200);
        $responseNip->assertSee('Ahmad Riza');
        $responseNip->assertDontSee('Siti Aminah, S.Pd');
    }

    public function test_user_can_filter_by_position(): void
    {
        Employee::create([
            'name' => 'Nikmatil Hasanah, S.Pd, M.Pd',
            'nip' => '19840516 200604 2 012',
            'position' => 'Kepala Sekolah',
            'is_active' => true,
        ]);

        Employee::create([
            'name' => 'Budi Santoso, S.Si',
            'nip' => '19850112 201001 1 004',
            'position' => 'Guru Biologi',
            'is_active' => true,
        ]);

        Employee::create([
            'name' => 'Ahmad Riza',
            'nip' => '19881105 201212 1 002',
            'position' => 'Staff Tata Usaha',
            'is_active' => true,
        ]);

        // Filter pimpinan
        $responsePimpinan = $this->get('/civitas-akademik?position=pimpinan');
        $responsePimpinan->assertStatus(200);
        $responsePimpinan->assertSee('Nikmatil Hasanah, S.Pd, M.Pd');
        $responsePimpinan->assertDontSee('Ahmad Riza');

        // Filter staff
        $responseStaff = $this->get('/civitas-akademik?position=staff');
        $responseStaff->assertStatus(200);
        $responseStaff->assertSee('Ahmad Riza');
        $responseStaff->assertDontSee('Nikmatil Hasanah, S.Pd, M.Pd');
    }

    public function test_pimpinan_is_sorted_at_the_top(): void
    {
        $guru = Employee::create([
            'name' => 'Agus Guru',
            'position' => 'Guru Fisika',
            'is_active' => true,
        ]);

        $kepsek = Employee::create([
            'name' => 'Zulfa Kepala',
            'position' => 'Kepala Sekolah',
            'is_active' => true,
        ]);

        $response = $this->get('/civitas-akademik');
        $response->assertStatus(200);
        
        $content = $response->getContent();
        $posKepsek = strpos($content, 'Zulfa Kepala');
        $posGuru = strpos($content, 'Agus Guru');

        $this->assertNotFalse($posKepsek);
        $this->assertNotFalse($posGuru);
        $this->assertTrue($posKepsek < $posGuru, 'Kepala Sekolah must appear before Guru in the HTML output.');
    }
}
