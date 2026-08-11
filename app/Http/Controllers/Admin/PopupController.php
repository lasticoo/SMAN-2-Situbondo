<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PopupRequest;
use App\Models\Popup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PopupController extends Controller
{
    // Developer Admin (AD - 02): Manajemen Pop-Up Event
    // Task: View, Controller, Routes, Testing, PR ke branch develop

    /**
     * Redirect ke halaman utama Landing Page Management yang juga menampilkan pop-up.
     * Halaman utama dikelola oleh BannerController::index().
     */
    public function index(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('admin.banners.index');
    }


    /**
     * Tampilkan form tambah pop-up baru.
     */
    public function create(): View
    {
        $nextSortOrder = Popup::max('sort_order') + 1;

        return view('admin.popup.create', compact('nextSortOrder'));
    }

    /**
     * Simpan pop-up baru ke database.
     * Gambar di-upload, dikompres, dan dikonversi ke WebP sebelum disimpan.
     */
    public function store(PopupRequest $request): RedirectResponse
    {
        $imagePath = $this->processAndStoreImage($request);

        Popup::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image_url'   => $imagePath,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $request->sort_order,
        ]);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Pop-up event berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit pop-up.
     */
    public function edit(Popup $popup): View
    {
        return view('admin.popup.edit', compact('popup'));
    }

    /**
     * Perbarui data pop-up di database.
     * Jika ada gambar baru, gambar lama dihapus dari disk (hard delete).
     */
    public function update(PopupRequest $request, Popup $popup): RedirectResponse
    {
        $data = [
            'title'       => $request->title,
            'description' => $request->description,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $request->sort_order,
        ];

        if ($request->hasFile('image')) {
            // Hapus file gambar lama dari disk (hard delete, hindari orphan file)
            if ($popup->image_url) {
                Storage::disk('public')->delete($popup->image_url);
            }
            // Proses dan simpan gambar baru
            $data['image_url'] = $this->processAndStoreImage($request);
        }

        $popup->update($data);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Pop-up event berhasil diperbarui.');
    }

    /**
     * Hapus pop-up beserta file gambarnya dari disk (hard delete).
     */
    public function destroy(Popup $popup): RedirectResponse
    {
        // Hard delete file gambar dari storage
        if ($popup->image_url) {
            Storage::disk('public')->delete($popup->image_url);
        }

        $popup->delete();

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Pop-up event berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif pop-up (AJAX-friendly PATCH).
     */
    public function toggleActive(Popup $popup): RedirectResponse
    {
        $popup->update(['is_active' => ! $popup->is_active]);

        $status = $popup->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.banners.index')
            ->with('success', "Pop-up event berhasil {$status}.");
    }

    /**
     * Proses upload gambar: konversi ke WebP dan simpan ke storage/public/popups/.
     * File asli (jpg/png/dll) tidak disimpan permanen.
     * Hanya path file .webp yang dikembalikan untuk disimpan di kolom image_url.
     */
    private function processAndStoreImage(PopupRequest $request): string
    {
        $file = $request->file('image');

        // Inisialisasi Intervention Image dengan driver GD
        $manager = new ImageManager(new Driver());
        $image   = $manager->read($file->getRealPath());

        // Kompres dan konversi ke WebP (kualitas 85)
        $webpContent = $image->toWebp(85)->toString();

        // Generate nama file unik dengan ekstensi .webp
        $filename = 'popups/' . uniqid('popup_', true) . '.webp';

        // Simpan ke disk public (storage/app/public/popups/)
        Storage::disk('public')->put($filename, $webpContent);

        return $filename;
    }
}
