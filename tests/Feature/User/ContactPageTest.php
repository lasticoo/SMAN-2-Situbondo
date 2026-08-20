<?php

namespace Tests\Feature\User;

use App\Mail\NewContactMessageMail;
use App\Models\ColorSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_is_accessible_by_public(): void
    {
        $response = $this->get('/kontak');

        $response->assertStatus(200);
        $response->assertSee('Hubungi Kami');
        $response->assertSee('Informasi Kontak');
        $response->assertSee('Kirim Pesan');
        $response->assertSee('Nama Lengkap');
        $response->assertSee('Alamat Email');
        $response->assertSee('Subjek Pesan');
        $response->assertSee('Pesan');
        $response->assertSee('Pilih Subjek');
    }

    public function test_contact_alias_routes_are_accessible(): void
    {
        $response1 = $this->get('/contact');
        $response1->assertStatus(200);

        $response2 = $this->get('/hubungi-kami');
        $response2->assertStatus(200);
    }

    public function test_contact_form_validation_fails_on_empty_required_fields(): void
    {
        $response = $this->post('/kontak', [
            'name' => '',
            'email' => '',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->assertEquals(0, DB::table('contact_messages')->count());
    }

    public function test_contact_form_validation_fails_on_invalid_email_format(): void
    {
        $response = $this->post('/kontak', [
            'name' => 'John Doe',
            'email' => 'bukan-email-valid',
            'subject' => 'Pertanyaan',
            'message' => 'Ini isi pesan pertanyaan yang valid.',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(0, DB::table('contact_messages')->count());
    }

    public function test_contact_message_is_saved_to_database_with_unread_status(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@example.com',
            'phone' => '081234567890',
            'subject' => 'Pertanyaan',
            'message' => 'Mohon informasi mengenai prosedur mutasi siswa baru.',
        ];

        $response = $this->post('/kontak', $payload);

        $response->assertRedirect('/kontak');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@example.com',
            'phone' => '081234567890',
            'subject' => 'Pertanyaan',
            'status' => 'unread',
        ]);
    }

    public function test_admin_receives_email_notification_on_contact_submission(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'phone' => '085234567890',
            'subject' => 'Apresiasi',
            'message' => 'Selamat atas prestasi juara robotika nasional SMAN 2 Situbondo!',
        ];

        $response = $this->post('/kontak', $payload);
        $response->assertRedirect('/kontak');

        Mail::assertSent(NewContactMessageMail::class, function ($mail) {
            return $mail->contactData['name'] === 'Budi Santoso';
        });
    }

    public function test_dynamic_theme_colors_are_applied_to_contact_page(): void
    {
        ColorSetting::create([
            'primary_color' => '#0A2540',
            'secondary_color' => '#FF8A00',
        ]);

        $response = $this->get('/kontak');

        $response->assertStatus(200);
        $response->assertSee('--primary-main: #0A2540', false);
        $response->assertSee('--secondary-main: #FF8A00', false);
    }

    public function test_user_cannot_view_other_users_messages_on_public_contact_page(): void
    {
        DB::table('contact_messages')->insert([
            'name' => 'User Rahasia',
            'email' => 'rahasia@example.com',
            'phone' => '08199999999',
            'subject' => 'Pengaduan Rahasia',
            'message' => 'Pesan privat yang tidak boleh terlihat publik.',
            'status' => 'unread',
            'created_at' => now(),
        ]);

        $response = $this->get('/kontak');

        $response->assertStatus(200);
        $response->assertDontSee('User Rahasia');
        $response->assertDontSee('Pesan privat yang tidak boleh terlihat publik.');
    }
}
