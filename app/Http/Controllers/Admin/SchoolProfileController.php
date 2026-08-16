<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSchoolProfileRequest;
use App\Models\SchoolProfile;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SchoolProfileController extends Controller
{
    /**
     * Tampilkan halaman Profil Sekolah (Lihat & Edit dalam 1 halaman).
     */
    public function edit(): View
    {
        $schoolProfile = SchoolProfile::firstOrCreate([], [
            'vision' => 'Menjadi lembaga pendidikan terdepan yang menghasilkan lulusan berkarakter, inovatif, dan berdaya saing global berlandaskan nilai-nilai luhur bangsa.',
            'mission' => "1. Menyelenggarakan pendidikan berkualitas berorientasi pada kecerdasan spiritual, intelektual, dan emosional.\n2. Mengembangkan potensi peserta didik secara optimal melalui kegiatan intrakurikuler dan ekstrakurikuler.\n3. Menanamkan nilai budi pekerti, kedisiplinan, dan kepedulian lingkungan.",
            'goals' => "1. Mewujudkan lulusan yang memiliki kompetensi akademik dan non-akademik unggul.\n2. Meningkatkan persentase kelulusan siswa ke Perguruan Tinggi Negeri (PTN) favorit.\n3. Membentuk karakter siswa yang beriman, bertakwa, serta berwawasan lingkungan.",
            'history' => 'SMA Negeri 2 Situbondo didirikan pada tahun 1980 dengan tujuan mulia untuk mencerdaskan kehidupan bangsa di wilayah Kabupaten Situbondo.',
            'about_us' => 'SMA Negeri 2 Situbondo (Smada) merupakan salah satu Sekolah Menengah Atas Negeri unggulan di Kabupaten Situbondo.',
            'structure_image_url' => null,
        ]);

        return view('admin.school_profile.edit', compact('schoolProfile'));
    }

    /**
     * Perbarui data Profil Sekolah di database.
     */
    public function update(UpdateSchoolProfileRequest $request): RedirectResponse
    {
        $schoolProfile = SchoolProfile::first();

        if (! $schoolProfile) {
            $schoolProfile = new SchoolProfile;
        }

        $data = [
            'vision' => $request->vision,
            'mission' => $request->mission,
            'goals' => $request->goals,
            'history' => $request->history,
            'about_us' => $request->about_us,
        ];

        if ($request->hasFile('structure_image')) {
            // Hapus gambar lama dari storage & media_files jika ada
            if ($schoolProfile->structure_image_url) {
                ImageOptimizerService::deleteAndClean($schoolProfile->structure_image_url, 'school_profile', $schoolProfile->id ?? 1);
            }

            // Simpan gambar struktur baru sebagai WebP & log ke media_files via ImageOptimizerService
            $data['structure_image_url'] = ImageOptimizerService::compressAndLog(
                $request->file('structure_image'),
                'school_profile',
                $schoolProfile->id ?? 1,
                'school_profile'
            );
        }

        $schoolProfile->fill($data);
        $schoolProfile->save();

        // Invalidate cache landing page
        Cache::forget('landing_school_profile');

        return redirect()
            ->route('admin.school_profile.edit')
            ->with('success', 'Profil sekolah berhasil diperbarui.');
    }
}
