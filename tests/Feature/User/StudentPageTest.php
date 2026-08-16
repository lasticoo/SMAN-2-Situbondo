<?php

namespace Tests\Feature\User;

use App\Models\ColorSetting;
use App\Models\SchoolProfile;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ColorSetting::create([
            'primary_color' => '#001c4d',
            'secondary_color' => '#f59e0b',
        ]);

        SchoolProfile::create([
            'about_us' => 'Tentang SMAN 2 Situbondo',
            'vision' => 'Visi Sekolah Unggul',
            'mission' => 'Misi Sekolah Berkarakter',
            'goals' => 'Tujuan Sekolah Berprestasi',
            'history' => 'Sejarah Panjang Sekolah',
        ]);
    }

    public function test_user_can_access_student_page_without_login(): void
    {
        $response = $this->get('/siswa');
        $response->assertStatus(200);
        $response->assertSee('Data Siswa', false);
    }

    public function test_user_can_access_via_data_siswa_alias(): void
    {
        $response = $this->get('/data-siswa');
        $response->assertStatus(200);
        $response->assertSee('Data Siswa', false);
    }

    public function test_only_public_students_are_displayed(): void
    {
        $publicStudent = Student::create([
            'nisn' => '0041234567',
            'name' => 'Ahmad Fauzi Rahman',
            'class' => 'X MIPA 1',
            'is_public' => true,
        ]);

        $privateStudent = Student::create([
            'nisn' => '0059876543',
            'name' => 'Siswa Rahasia Privat',
            'class' => 'X IPS 1',
            'is_public' => false,
        ]);

        $response = $this->get('/siswa');
        $response->assertStatus(200);
        $response->assertSee('Ahmad Fauzi Rahman', false);
        $response->assertDontSee('Siswa Rahasia Privat', false);
    }

    public function test_search_filters_by_name_and_nisn(): void
    {
        Student::create([
            'nisn' => '0041112233',
            'name' => 'Bunga Citra Lestari',
            'class' => 'XI MIPA 2',
            'is_public' => true,
        ]);

        Student::create([
            'nisn' => '0054445566',
            'name' => 'Cakra Khan',
            'class' => 'XII IPS 3',
            'is_public' => true,
        ]);

        // Search by Name
        $responseName = $this->get('/siswa?search=Bunga');
        $responseName->assertStatus(200);
        $responseName->assertSee('Bunga Citra Lestari', false);
        $responseName->assertDontSee('Cakra Khan', false);

        // Search by NISN
        $responseNisn = $this->get('/siswa?search=0054445566');
        $responseNisn->assertStatus(200);
        $responseNisn->assertSee('Cakra Khan', false);
        $responseNisn->assertDontSee('Bunga Citra Lestari', false);
    }

    public function test_filter_by_grade_level(): void
    {
        Student::create([
            'nisn' => '0010000001',
            'name' => 'Siswa Kelas Sepuluh',
            'class' => 'X MIPA 1',
            'is_public' => true,
        ]);

        Student::create([
            'nisn' => '0010000002',
            'name' => 'Siswa Kelas Sebelas',
            'class' => 'XI IPS 2',
            'is_public' => true,
        ]);

        Student::create([
            'nisn' => '0010000003',
            'name' => 'Siswa Kelas Duabelas',
            'class' => 'XII MIPA 3',
            'is_public' => true,
        ]);

        // Filter Grade 10
        $response10 = $this->get('/siswa?class=10');
        $response10->assertStatus(200);
        $response10->assertSee('Siswa Kelas Sepuluh', false);
        $response10->assertDontSee('Siswa Kelas Sebelas', false);
        $response10->assertDontSee('Siswa Kelas Duabelas', false);

        // Filter Grade 12
        $response12 = $this->get('/siswa?class=12');
        $response12->assertStatus(200);
        $response12->assertSee('Siswa Kelas Duabelas', false);
        $response12->assertDontSee('Siswa Kelas Sepuluh', false);
    }

    public function test_filter_by_specific_class(): void
    {
        Student::create([
            'nisn' => '0020000001',
            'name' => 'Siswa Kelas XII-6',
            'class' => 'XII-6',
            'is_public' => true,
        ]);

        Student::create([
            'nisn' => '0020000002',
            'name' => 'Siswa Kelas X-1',
            'class' => 'X-1',
            'is_public' => true,
        ]);

        // Filter Specific Class XII-6
        $responseClass = $this->get('/siswa?class=XII-6');
        $responseClass->assertStatus(200);
        $responseClass->assertSee('Siswa Kelas XII-6', false);
        $responseClass->assertDontSee('Siswa Kelas X-1', false);
    }

    public function test_senior_first_ordering_xii_before_x(): void
    {
        $studentX = Student::create([
            'nisn' => '0030000001',
            'name' => 'Aditya Junior',
            'class' => 'X MIPA 1',
            'is_public' => true,
        ]);

        $studentXII = Student::create([
            'nisn' => '0030000002',
            'name' => 'Zahra Senior',
            'class' => 'XII MIPA 1',
            'is_public' => true,
        ]);

        $response = $this->get('/siswa');
        $response->assertStatus(200);

        // Verify Zahra (XII) comes before Aditya (X) despite Alphabetical Z vs A
        $content = $response->getContent();
        $posXII = strpos($content, 'Zahra Senior');
        $posX = strpos($content, 'Aditya Junior');

        $this->assertNotFalse($posXII);
        $this->assertNotFalse($posX);
        $this->assertTrue($posXII < $posX, 'Siswa Kelas XII harus muncul sebelum Kelas X');
    }

    public function test_dynamic_colors_injected_properly(): void
    {
        $response = $this->get('/siswa');
        $response->assertStatus(200);
        $response->assertSee('--primary-main: #001c4d;', false);
        $response->assertSee('--secondary-gold: #f59e0b;', false);
    }
}
