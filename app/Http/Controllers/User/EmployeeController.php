<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ColorSetting;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of active employees and teachers with filtering, search, and pimpinan-first sorting.
     */
    public function index(Request $request)
    {
        // 1. Fetch Dynamic Color Settings & School Profile (Realtime directly from DB)
        $colorSetting = ColorSetting::first();
        $profile = SchoolProfile::first();

        // 2. Base Query: Only active employees with optimized column selection
        $query = Employee::where('is_active', true)
            ->select(['id', 'name', 'nip', 'position', 'photo_url', 'extra_info', 'is_active']);

        // 3. Search Filter (by Name or NIP or Position)
        $search = $request->filled('search') 
            ? trim($request->query('search')) 
            : ($request->filled('q') ? trim($request->query('q')) : null);

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // 4. Position / Category Filter
        $positionFilter = $request->query('position');
        if ($positionFilter && $positionFilter !== 'all' && $positionFilter !== 'Semua Jabatan') {
            $posLower = strtolower(trim($positionFilter));

            if (in_array($posLower, ['pimpinan', 'kepala & wakil', 'kepala', 'wakil', 'wakasek'])) {
                $query->where(function ($q) {
                    $q->where('position', 'like', '%kepala%')
                      ->orWhere('position', 'like', '%wakil%')
                      ->orWhere('position', 'like', '%wakasek%');
                });
            } elseif (in_array($posLower, ['guru', 'guru mapel', 'pendidik', 'pengajar'])) {
                $query->where(function ($q) {
                    $q->where('position', 'like', '%guru%')
                      ->orWhere('position', 'like', '%pendidik%')
                      ->orWhere('position', 'like', '%pengajar%');
                });
            } elseif (in_array($posLower, ['tu', 'staff', 'tata usaha', 'staff tata usaha', 'tenaga kependidikan', 'kependidikan'])) {
                $query->where(function ($q) {
                    $q->where('position', 'like', '%tu%')
                      ->orWhere('position', 'like', '%tata usaha%')
                      ->orWhere('position', 'like', '%staf%')
                      ->orWhere('position', 'like', '%staff%')
                      ->orWhere('position', 'like', '%admin%')
                      ->orWhere('position', 'like', '%kependidikan%');
                });
            } else {
                $query->where('position', 'like', "%{$positionFilter}%");
            }
        }

        // 5. Pimpinan-First Ordering (Kepala Sekolah -> Wakasek -> Kepala TU -> Guru -> Staff)
        $query->orderByRaw("
            CASE 
                WHEN LOWER(position) LIKE '%kepala sekolah%' THEN 1
                WHEN LOWER(position) LIKE '%wakil kepala sekolah%' OR LOWER(position) LIKE '%wakasek%' THEN 2
                WHEN LOWER(position) LIKE '%kepala%' THEN 3
                WHEN LOWER(position) LIKE '%guru%' OR LOWER(position) LIKE '%pendidik%' THEN 4
                ELSE 5 
            END ASC
        ")->orderBy('name', 'asc');

        // 6. Paginate (12 cards per page to match the responsive 4-column grid)
        $employees = $query->paginate(12)->withQueryString();

        // 7. Get Distinct Available Positions for Dropdown Filter
        $availablePositions = Employee::where('is_active', true)
            ->select('position')
            ->distinct()
            ->orderBy('position', 'asc')
            ->pluck('position')
            ->filter()
            ->values();

        return view('user.employee.index', compact(
            'employees',
            'colorSetting',
            'profile',
            'search',
            'positionFilter',
            'availablePositions'
        ));
    }
}

