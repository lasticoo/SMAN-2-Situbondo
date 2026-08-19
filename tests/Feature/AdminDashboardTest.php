<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\ContactMessage;
use App\Models\Employee;
use App\Models\Gallery;
use App\Models\News;
use App\Models\SpmbInfo;
use App\Models\Student;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_root_url_redirects_to_login_for_guest(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_root_url_redirects_to_dashboard_for_authenticated_admin(): void
    {
        $admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@smada.sch.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }

    public function test_authenticated_admin_can_access_dashboard_and_view_statistics(): void
    {
        $admin = Admin::create([
            'name' => 'Admin Utama',
            'email' => 'admin@smada.sch.id',
            'password' => bcrypt('password123'),
        ]);

        // Seed sample records
        News::create([
            'title' => 'Berita Utama Test',
            'summary' => 'Ringkasan berita',
            'content' => 'Isi berita test',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        Announcement::create([
            'title' => 'Pengumuman Penting Test',
            'summary' => 'Ringkasan pengumuman',
            'content' => 'Isi pengumuman test',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        Student::create([
            'nisn' => '1234567890',
            'name' => 'Siswa Test',
            'class' => 'X IPA 1',
        ]);

        Employee::create([
            'name' => 'Guru Test',
            'position' => 'Guru Matematika',
            'is_active' => true,
        ]);

        Gallery::create([
            'activity_name' => 'Kegiatan Test',
            'activity_date' => '2026-08-18',
            'photo_url' => 'galleries/test.jpg',
            'created_by' => $admin->id,
        ]);

        Video::create([
            'youtube_url' => 'https://youtube.com/watch?v=123',
            'title' => 'Video Test',
            'created_by' => $admin->id,
        ]);

        ContactMessage::create([
            'name' => 'Penanya Test',
            'email' => 'penanya@example.com',
            'subject' => 'Pertanyaan PPDB',
            'message' => 'Halo, mau tanya mengenai pendaftaran.',
            'status' => 'unread',
        ]);

        SpmbInfo::create([
            'schedule_info' => 'Jadwal SPMB 2026',
            'requirements_info' => 'Syarat pendaftaran',
            'period_start' => now()->toDateString(),
            'period_end' => now()->addDays(30)->toDateString(),
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('stats');
        $response->assertSee('Dashboard Administrator');
        $response->assertSee('Admin Utama');
        $response->assertSee('Penanya Test');
        $response->assertSee('Total Siswa');
    }
}
