<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\SchoolProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSchoolProfileTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@smada.sch.id',
            'password' => bcrypt('password123'),
        ]);
    }

    /** @test */
    public function guest_cannot_access_school_profile_edit_page(): void
    {
        $response = $this->get(route('admin.school_profile.edit'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function authenticated_admin_can_access_school_profile_edit_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.school_profile.edit'));

        $response->assertOk();
        $response->assertViewIs('admin.school_profile.edit');
        $response->assertViewHas('schoolProfile');
    }

    /** @test */
    public function admin_can_update_school_profile_text_fields(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.school_profile.update'), [
                'vision' => 'Visi Baru SMAN 2 Situbondo',
                'mission' => 'Misi Baru SMAN 2 Situbondo',
                'goals' => 'Tujuan Baru SMAN 2 Situbondo',
                'history' => 'Sejarah Baru SMAN 2 Situbondo',
                'about_us' => 'Tentang SMAN 2 Situbondo Baru',
            ]);

        $response->assertRedirect(route('admin.school_profile.edit'));
        $response->assertSessionHas('success', 'Profil sekolah berhasil diperbarui.');

        $this->assertDatabaseHas('school_profile', [
            'vision' => 'Visi Baru SMAN 2 Situbondo',
            'mission' => 'Misi Baru SMAN 2 Situbondo',
            'goals' => 'Tujuan Baru SMAN 2 Situbondo',
            'history' => 'Sejarah Baru SMAN 2 Situbondo',
            'about_us' => 'Tentang SMAN 2 Situbondo Baru',
        ]);
    }

    /** @test */
    public function admin_can_upload_structure_image_saved_to_school_profile_folder(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('structure_test.png', 800, 600);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.school_profile.update'), [
                'vision' => 'Visi Baru SMAN 2 Situbondo',
                'mission' => 'Misi Baru SMAN 2 Situbondo',
                'goals' => 'Tujuan Baru SMAN 2 Situbondo',
                'history' => 'Sejarah Baru SMAN 2 Situbondo',
                'about_us' => 'Tentang SMAN 2 Situbondo Baru',
                'structure_image' => $file,
            ]);

        $response->assertRedirect(route('admin.school_profile.edit'));
        $response->assertSessionHas('success');

        $profile = SchoolProfile::first();
        $this->assertNotNull($profile->structure_image_url);
        $this->assertStringStartsWith('school_profile/', $profile->structure_image_url);

        Storage::disk('public')->assertExists($profile->structure_image_url);
    }

    /** @test */
    public function validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.school_profile.update'), [
                'vision' => '',
                'mission' => '',
                'goals' => '',
                'history' => '',
                'about_us' => '',
            ]);

        $response->assertSessionHasErrors([
            'vision',
            'mission',
            'goals',
            'history',
            'about_us',
        ]);
    }

    /** @test */
    public function validation_fails_for_invalid_structure_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.pdf', 3000, 'application/pdf');

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.school_profile.update'), [
                'vision' => 'Visi Valid',
                'mission' => 'Misi Valid',
                'goals' => 'Tujuan Valid',
                'history' => 'Sejarah Valid',
                'about_us' => 'About Valid',
                'structure_image' => $file,
            ]);

        $response->assertSessionHasErrors(['structure_image']);
    }
}
