<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Tampilkan daftar pegawai (dengan fitur pencarian dan filter posisi/jabatan).
     */
    public function index(Request $request): View
    {
        $employees = Employee::search($request->search)
            ->positionFilter($request->position_filter)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Simpan pegawai baru ke database.
     * Foto pegawai di-upload, dikonversi ke WebP, disimpan di storage/app/public/employees,
     * dan dicatat riwayat kompresinya ke tabel media_files.
     */
    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $employee = Employee::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'position' => $request->position,
            'extra_info' => $request->extra_info,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = ImageOptimizerService::compressAndLog(
                $request->file('photo'),
                'employees',
                $employee->id,
                'employees'
            );
            $employee->update(['photo_url' => $photoPath]);
        }

        Cache::forget('landing_employee_stats');

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Perbarui data pegawai.
     * Jika foto diperbarui, hapus foto lama dari storage & media_files, lalu simpan foto baru sebagai WebP.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = [
            'name' => $request->name,
            'nip' => $request->nip,
            'position' => $request->position,
            'extra_info' => $request->extra_info,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('photo')) {
            // Bersihkan file dan log media lama
            ImageOptimizerService::deleteAndClean($employee->photo_url, 'employees', $employee->id);

            // Simpan file webp baru dan catat ke media_files
            $data['photo_url'] = ImageOptimizerService::compressAndLog(
                $request->file('photo'),
                'employees',
                $employee->id,
                'employees'
            );
        }

        $employee->update($data);

        Cache::forget('landing_employee_stats');

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Hapus data pegawai beserta foto dan catatan media_files-nya.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        ImageOptimizerService::deleteAndClean($employee->photo_url, 'employees', $employee->id);
        $employee->delete();

        Cache::forget('landing_employee_stats');

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif pegawai.
     */
    public function toggleActive(Employee $employee): RedirectResponse
    {
        $employee->update(['is_active' => ! $employee->is_active]);

        Cache::forget('landing_employee_stats');

        $status = $employee->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.employees.index')
            ->with('success', "Status pegawai {$employee->name} berhasil {$status}.");
    }
}
