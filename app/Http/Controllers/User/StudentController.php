<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ColorSetting;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display an optimized public listing of students with high-performance query aggregation,
     * class-level filtering, search, and senior-first ordering.
     */
    public function index(Request $request)
    {
        // 1. Fetch Dynamic Color Settings & School Profile (Realtime from DB)
        $colorSetting = ColorSetting::first();
        $profile = SchoolProfile::first();

        // 2. High-Performance Demographic Statistics (Combined in 1 Single Aggregated SQL Query)
        $statsRow = Student::where('is_public', true)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN class LIKE 'X-%' OR class LIKE 'X %' OR class = 'X' OR class LIKE '10%' THEN 1 ELSE 0 END) as kelas_10,
                SUM(CASE WHEN class LIKE 'XI-%' OR class LIKE 'XI %' OR class = 'XI' OR class LIKE '11%' THEN 1 ELSE 0 END) as kelas_11,
                SUM(CASE WHEN class LIKE 'XII-%' OR class LIKE 'XII %' OR class = 'XII' OR class LIKE '12%' THEN 1 ELSE 0 END) as kelas_12
            ")
            ->first();

        $stats = [
            'total' => (int) ($statsRow?->total ?? 0),
            'kelas_10' => (int) ($statsRow?->kelas_10 ?? 0),
            'kelas_11' => (int) ($statsRow?->kelas_11 ?? 0),
            'kelas_12' => (int) ($statsRow?->kelas_12 ?? 0),
        ];

        // 3. Single Optimized Query for Class Counts and Available Classes Dropdown
        $classCounts = Student::where('is_public', true)
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->select('class', DB::raw('count(*) as count'))
            ->groupBy('class')
            ->orderByRaw("
                CASE 
                    WHEN UPPER(class) LIKE 'XII%' OR class LIKE '12%' THEN 1
                    WHEN UPPER(class) LIKE 'XI%' OR class LIKE '11%' THEN 2
                    WHEN UPPER(class) LIKE 'X%' OR class LIKE '10%' THEN 3
                    ELSE 4 
                END ASC
            ")
            ->orderBy('class', 'asc')
            ->pluck('count', 'class');

        $availableClasses = $classCounts->keys()->values();

        // 4. Base Query: Only active public student records with strict column pruning
        $query = Student::where('is_public', true)
            ->select(['id', 'name', 'class']);

        // 5. Search Filter (by Name or NISN)
        $search = $request->filled('search') 
            ? trim($request->query('search')) 
            : ($request->filled('q') ? trim($request->query('q')) : null);

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // 6. Class / Tingkat Filter
        $classFilter = $request->query('class') ?? $request->query('kelas') ?? $request->query('grade');
        if ($classFilter && $classFilter !== 'all' && $classFilter !== 'Semua Kelas') {
            $cLower = strtolower(trim($classFilter));

            if (in_array($cLower, ['12', 'xii', 'kelas 12', 'kelas xii'])) {
                $query->where(function ($q) {
                    $q->where('class', 'like', 'XII-%')
                      ->orWhere('class', 'like', 'XII %')
                      ->orWhere('class', 'XII')
                      ->orWhere('class', 'like', '12%');
                });
            } elseif (in_array($cLower, ['11', 'xi', 'kelas 11', 'kelas xi'])) {
                $query->where(function ($q) {
                    $q->where('class', 'like', 'XI-%')
                      ->orWhere('class', 'like', 'XI %')
                      ->orWhere('class', 'XI')
                      ->orWhere('class', 'like', '11%');
                });
            } elseif (in_array($cLower, ['10', 'x', 'kelas 10', 'kelas x'])) {
                $query->where(function ($q) {
                    $q->where('class', 'like', 'X-%')
                      ->orWhere('class', 'like', 'X %')
                      ->orWhere('class', 'X')
                      ->orWhere('class', 'like', '10%');
                });
            } else {
                // Exact match with specific class from table
                $query->where('class', $classFilter);
            }
        }

        // 7. Senior-First Ordering (Kelas XII -> Kelas XI -> Kelas X -> Alphabetical by Class & Name)
        $query->orderByRaw("
            CASE 
                WHEN UPPER(class) LIKE 'XII%' OR class LIKE '12%' THEN 1
                WHEN UPPER(class) LIKE 'XI%' OR class LIKE '11%' THEN 2
                WHEN UPPER(class) LIKE 'X%' OR class LIKE '10%' THEN 3
                ELSE 4 
            END ASC
        ")->orderBy('class', 'asc')->orderBy('name', 'asc');

        // 8. Pagination (15 rows per page for smooth rendering and balanced DOM footprint)
        $students = $query->paginate(15)->withQueryString();

        return view('user.student.index', compact(
            'students',
            'stats',
            'classCounts',
            'colorSetting',
            'profile',
            'search',
            'classFilter',
            'availableClasses'
        ));
    }
}
