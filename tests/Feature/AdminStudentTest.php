<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ImportBatch;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AdminStudentTest extends TestCase
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
    public function guest_cannot_access_student_index(): void
    {
        $response = $this->get(route('admin.students.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function authenticated_admin_can_access_student_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.students.index'));

        $response->assertOk();
        $response->assertViewIs('admin.students.index');
        $response->assertViewHas('students');
        $response->assertViewHas('importBatches');
    }

    /** @test */
    public function admin_can_create_student_manually(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.store'), [
                'nisn' => '0051234567',
                'name' => 'Ahmad Ridwan',
                'class' => 'XII IPA 1',
                'extra_info' => 'Ketua Kelas',
                'is_public' => 1,
            ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success', 'Data siswa berhasil ditambahkan.');

        $this->assertDatabaseHas('students', [
            'nisn' => '0051234567',
            'name' => 'Ahmad Ridwan',
            'class' => 'XII IPA 1',
            'is_public' => true,
        ]);
    }

    /** @test */
    public function admin_can_update_student_data(): void
    {
        $student = Student::create([
            'nisn' => '0059876543',
            'name' => 'Siti Aisyah',
            'class' => 'XI IPS 2',
            'is_public' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.students.update', $student), [
                'nisn' => '0059876543',
                'name' => 'Siti Aisyah, S.Ked',
                'class' => 'XI IPS 2',
                'extra_info' => 'Pengurus OSIS',
                'is_public' => 1,
            ]);

        $response->assertRedirect(route('admin.students.index'));

        $student->refresh();
        $this->assertEquals('Siti Aisyah, S.Ked', $student->name);
        $this->assertEquals('Pengurus OSIS', $student->extra_info);
    }

    /** @test */
    public function admin_can_toggle_student_public_status(): void
    {
        $student = Student::create([
            'nisn' => '0042345678',
            'name' => 'Nabila Putri',
            'class' => 'X-1',
            'is_public' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.students.togglePublic', $student));

        $response->assertRedirect(route('admin.students.index'));

        $this->assertFalse($student->fresh()->is_public);
    }

    /** @test */
    public function admin_can_delete_student(): void
    {
        $student = Student::create([
            'nisn' => '0038765432',
            'name' => 'Dimas Wahyudi',
            'class' => 'XII Bahasa',
            'is_public' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.students.destroy', $student));

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    /** @test */
    public function validation_fails_for_duplicate_nisn(): void
    {
        Student::create([
            'nisn' => '0051234567',
            'name' => 'Siswa 1',
            'class' => 'X-1',
            'is_public' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.store'), [
                'nisn' => '0051234567', // Duplicate NISN
                'name' => 'Siswa 2',
                'class' => 'X-2',
                'is_public' => 1,
            ]);

        $response->assertSessionHasErrors(['nisn']);
    }

    /** @test */
    public function admin_can_download_excel_template(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.students.downloadTemplate'));

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=template_import_siswa.xlsx');
    }

    /** @test */
    public function admin_can_import_students_from_excel_file_and_log_batch(): void
    {
        // Generate test Excel file in temp directory
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NISN');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Kelas');
        $sheet->setCellValue('D1', 'Info');
        $sheet->setCellValue('E1', 'Status');

        $sheet->setCellValue('A2', '0011111111');
        $sheet->setCellValue('B2', 'Budi Harapan');
        $sheet->setCellValue('C2', 'X-1');
        $sheet->setCellValue('D2', 'Juara 1 Math');
        $sheet->setCellValue('E2', '1');

        $sheet->setCellValue('A3', '0022222222');
        $sheet->setCellValue('B3', 'Citra Dewi');
        $sheet->setCellValue('C3', 'XI IPA 2');
        $sheet->setCellValue('D3', 'Anggota Pramuka');
        $sheet->setCellValue('E3', '1');

        $tempFile = sys_get_temp_dir().'/test_students_import.xlsx';
        (new Xlsx($spreadsheet))->save($tempFile);

        $uploadedFile = new UploadedFile($tempFile, 'data_siswa_2026.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.import'), [
                'excel_file' => $uploadedFile,
            ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', ['nisn' => '0011111111', 'name' => 'Budi Harapan']);
        $this->assertDatabaseHas('students', ['nisn' => '0022222222', 'name' => 'Citra Dewi']);

        $this->assertDatabaseHas('import_batches', [
            'uploaded_by' => $this->admin->id,
            'file_name' => 'data_siswa_2026.xlsx',
            'total_rows' => 2,
            'success_rows' => 2,
            'failed_rows' => 0,
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function import_handles_partial_duplicate_rows_gracefully_and_logs_failed_rows(): void
    {
        // Seed existing student
        Student::create([
            'nisn' => '0011111111',
            'name' => 'Existing Student',
            'class' => 'X-1',
            'is_public' => true,
        ]);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NISN');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Kelas');

        // Row 2: Duplicate NISN (should fail & be logged)
        $sheet->setCellValue('A2', '0011111111');
        $sheet->setCellValue('B2', 'Duplicate Student');
        $sheet->setCellValue('C2', 'X-1');

        // Row 3: Valid new student (should succeed)
        $sheet->setCellValue('A3', '0033333333');
        $sheet->setCellValue('B3', 'Eka Putra');
        $sheet->setCellValue('C3', 'XII IPS 1');

        $tempFile = sys_get_temp_dir().'/test_partial_import.xlsx';
        (new Xlsx($spreadsheet))->save($tempFile);

        $uploadedFile = new UploadedFile($tempFile, 'data_partial.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.import'), [
                'excel_file' => $uploadedFile,
            ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('warning');

        $this->assertDatabaseHas('students', ['nisn' => '0033333333', 'name' => 'Eka Putra']);

        $batch = ImportBatch::latest()->first();
        $this->assertEquals(2, $batch->total_rows);
        $this->assertEquals(1, $batch->success_rows);
        $this->assertEquals(1, $batch->failed_rows);
        $this->assertStringContainsString("NISN '0011111111' sudah terdaftar", $batch->error_log);
    }
}
