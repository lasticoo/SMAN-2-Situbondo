<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportStudentRequest;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\ImportBatch;
use App\Models\Student;
use App\Services\StudentImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentController extends Controller
{
    /**
     * Tampilkan daftar siswa (dengan fitur pencarian, filter kelas & filter status publikasi).
     */
    public function index(Request $request): View
    {
        $query = Student::query();

        // Scope search (NISN, Nama, Kelas)
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Scope class filter
        if ($request->filled('class_filter')) {
            $query->classFilter($request->class_filter);
        }

        // Status filter (publik / non-publik)
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            if ($request->status_filter === 'public' || $request->status_filter === '1') {
                $query->where('is_public', true);
            } elseif ($request->status_filter === 'private' || $request->status_filter === '0') {
                $query->where('is_public', false);
            }
        }

        $students = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Fetch riwayat import batches
        $importBatches = ImportBatch::with('uploader')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        return view('admin.students.index', compact('students', 'importBatches'));
    }

    /**
     * Simpan data siswa baru (manual CRUD via Popup).
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        Student::create([
            'nisn' => $request->nisn,
            'name' => $request->name,
            'class' => $request->class,
            'extra_info' => $request->extra_info,
            'is_public' => $request->boolean('is_public', true),
        ]);

        Cache::forget('landing_student_stats');

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Perbarui data siswa (manual CRUD via Popup).
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update([
            'nisn' => $request->nisn,
            'name' => $request->name,
            'class' => $request->class,
            'extra_info' => $request->extra_info,
            'is_public' => $request->boolean('is_public', true),
        ]);

        Cache::forget('landing_student_stats');

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Hapus data siswa.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        Cache::forget('landing_student_stats');

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Toggle status publikasi data siswa (is_public).
     */
    public function togglePublic(Student $student): RedirectResponse
    {
        $student->update(['is_public' => ! $student->is_public]);

        Cache::forget('landing_student_stats');

        $statusText = $student->is_public ? 'dipublikasikan' : 'disembunyikan (private)';

        return redirect()
            ->route('admin.students.index')
            ->with('success', "Status publikasi siswa {$student->name} berhasil diubah menjadi {$statusText}.");
    }

    /**
     * Proses import data siswa dari file Excel / CSV.
     */
    public function import(ImportStudentRequest $request, StudentImportService $importService): RedirectResponse
    {
        $adminId = Auth::guard('admin')->id();
        $batch = $importService->importSpreadsheet($request->file('excel_file'), $adminId);

        Cache::forget('landing_student_stats');

        if ($batch->failed_rows > 0) {
            $msg = "Proses import selesai. {$batch->success_rows} data siswa berhasil diimport, {$batch->failed_rows} baris gagal. Silakan periksa log detail di menu Impor Data.";

            return redirect()->route('admin.students.index')->with('warning', $msg);
        }

        return redirect()
            ->route('admin.students.index')
            ->with('success', "Proses import berhasil! {$batch->success_rows} data siswa dimasukkan ke sistem.");
    }

    /**
     * Download template Excel untuk import data siswa.
     */
    public function downloadTemplate(StudentImportService $importService): BinaryFileResponse
    {
        return $importService->generateTemplateDownload();
    }
}
