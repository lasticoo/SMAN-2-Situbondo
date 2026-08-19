<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateColorRequest;
use App\Models\ColorSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ColorSettingController extends Controller
{
    /**
     * Tampilkan halaman kustomisasi warna tema (singleton).
     */
    public function edit(): View
    {
        $colorSetting = ColorSetting::current();

        return view('admin.color_setting.edit', compact('colorSetting'));
    }

    /**
     * Simpan/perbarui konfigurasi warna tema ke database.
     */
    public function update(UpdateColorRequest $request): RedirectResponse
    {
        $colorSetting = ColorSetting::first() ?? new ColorSetting;

        $colorSetting->fill([
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        $colorSetting->save();

        return redirect()
            ->route('admin.color_settings.edit')
            ->with('success', 'Konfigurasi warna berhasil disimpan.');
    }
}
