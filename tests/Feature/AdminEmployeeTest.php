<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\MediaFile;
use App\Services\ImageOptimizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminEmployeeTest extends TestCase
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
    public function guest_cannot_access_employee_index(): void
    {
        $response = $this->get(route('admin.employees.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function authenticated_admin_can_access_employee_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.employees.index'));

        $response->assertOk();
        $response->assertViewIs('admin.employees.index');
        $response->assertViewHas('employees');
    }

    /** @test */
    public function admin_can_create_employee_with_photo_compressed_to_webp_and_logged_to_media_files(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('teacher.jpg', 600, 600);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.employees.store'), [
                'name' => 'Siti Aminah, S.Pd',
                'nip' => '198507232010012004',
                'position' => 'Guru Matematika',
                'extra_info' => 'siti.aminah@smada.sch.id',
                'is_active' => 1,
                'photo' => $photo,
            ]);

        $response->assertRedirect(route('admin.employees.index'));
        $response->assertSessionHas('success', 'Data pegawai berhasil ditambahkan.');

        $employee = Employee::where('nip', '198507232010012004')->first();
        $this->assertNotNull($employee);
        $this->assertNotNull($employee->photo_url);
        $this->assertStringStartsWith('employees/', $employee->photo_url);
        $this->assertStringEndsWith('.webp', $employee->photo_url);

        Storage::disk('public')->assertExists($employee->photo_url);

        // Verify media_files logging
        $this->assertDatabaseHas('media_files', [
            'module' => 'employees',
            'reference_id' => $employee->id,
            'file_url' => $employee->photo_url,
        ]);

        $mediaFile = MediaFile::where('module', 'employees')
            ->where('reference_id', $employee->id)
            ->first();

        $this->assertNotNull($mediaFile->original_size_kb);
        $this->assertNotNull($mediaFile->optimized_size_kb);
    }

    /** @test */
    public function admin_can_update_employee_data_and_replace_photo(): void
    {
        Storage::fake('public');

        $employee = Employee::create([
            'name' => 'Ahmad Ridwan',
            'nip' => '199002152015031002',
            'position' => 'Staf IT',
            'is_active' => true,
        ]);

        $newPhoto = UploadedFile::fake()->image('new_avatar.png', 400, 400);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.employees.update', $employee), [
                'name' => 'Ahmad Ridwan, S.Kom',
                'nip' => '199002152015031002',
                'position' => 'Staf IT & Admin Utama',
                'extra_info' => 'ahmad@smada.sch.id',
                'is_active' => 1,
                'photo' => $newPhoto,
            ]);

        $response->assertRedirect(route('admin.employees.index'));

        $employee->refresh();
        $this->assertEquals('Ahmad Ridwan, S.Kom', $employee->name);
        $this->assertEquals('Staf IT & Admin Utama', $employee->position);
        $this->assertNotNull($employee->photo_url);

        Storage::disk('public')->assertExists($employee->photo_url);
        $this->assertDatabaseHas('media_files', [
            'module' => 'employees',
            'reference_id' => $employee->id,
        ]);
    }

    /** @test */
    public function admin_can_toggle_employee_active_status(): void
    {
        $employee = Employee::create([
            'name' => 'Budi Santoso',
            'nip' => '197511052000121001',
            'position' => 'Kepala Sekolah',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.employees.toggleActive', $employee));

        $response->assertRedirect(route('admin.employees.index'));

        $this->assertFalse($employee->fresh()->is_active);
    }

    /** @test */
    public function admin_can_delete_employee_and_clean_up_files(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('photo.jpg', 300, 300);

        $employee = Employee::create([
            'name' => 'Dwi Santoso',
            'nip' => '198001012005011001',
            'position' => 'Guru Fisika',
            'is_active' => true,
        ]);

        $photoPath = ImageOptimizerService::compressAndLog(
            $photo,
            'employees',
            $employee->id,
            'employees'
        );
        $employee->update(['photo_url' => $photoPath]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.employees.destroy', $employee));

        $response->assertRedirect(route('admin.employees.index'));

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
        $this->assertDatabaseMissing('media_files', [
            'module' => 'employees',
            'reference_id' => $employee->id,
        ]);
        Storage::disk('public')->assertMissing($photoPath);
    }

    /** @test */
    public function validation_fails_for_duplicate_nip(): void
    {
        Employee::create([
            'name' => 'Pegawai 1',
            'nip' => '198501012010011001',
            'position' => 'Guru',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.employees.store'), [
                'name' => 'Pegawai 2',
                'nip' => '198501012010011001', // Duplicate NIP
                'position' => 'Staf',
                'is_active' => 1,
            ]);

        $response->assertSessionHasErrors(['nip']);
    }
}
