<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BannerController extends Controller
{
    // Developer Admin (AD - 01): Manajemen Banner Web User
    // Task: View, Controller, Routes, Testing, PR ke branch develop

    /**
     * Tampilkan halaman utama Manajemen Landing Page:
     * menampilkan daftar banner (kiri) dan daftar popup (kanan) sesuai desain.
     */
    public function index(): View
    {
        $banners = Banner::orderBy('sort_order')->orderBy('id')->get();
        $popups  = \App\Models\Popup::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.banner.index', compact('banners', 'popups'));
    }

    /**
     * Tampilkan form tambah banner baru.
     */
    public function create(): View
    {
        $nextSortOrder = Banner::max('sort_order') + 1;

        return view('admin.banner.create', compact('nextSortOrder'));
    }

    /**
     * Simpan banner baru ke database.
     * Gambar di-upload, dikompres, dan dikonversi ke WebP sebelum disimpan.
     */
    public function store(BannerRequest $request): RedirectResponse
    {
        $imagePath = $this->processAndStoreImage($request);

        Banner::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image_url'   => $imagePath,
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $request->sort_order,
        ]);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit banner.
     */
    public function edit(Banner $banner): View
    {
        return view('admin.banner.edit', compact('banner'));
    }

    /**
     * Perbarui data banner di database.
     * Jika ada gambar baru, gambar lama dihapus dari disk (hard delete).
     */
    public function update(BannerRequest $request, Banner $banner): RedirectResponse
    {
        $data = [
            'title'       => $request->title,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $request->sort_order,
        ];

        if ($request->hasFile('image')) {
            // Hapus file gambar lama dari disk (hard delete, hindari orphan file)
            if ($banner->image_url) {
                Storage::disk('public')->delete($banner->image_url);
            }
            // Proses dan simpan gambar baru
            $data['image_url'] = $this->processAndStoreImage($request);
        }

        $banner->update($data);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner berhasil diperbarui.');
    }

    /**
     * Hapus banner beserta file gambarnya dari disk (hard delete).
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        // Hard delete file gambar dari storage
        if ($banner->image_url) {
            Storage::disk('public')->delete($banner->image_url);
        }

        $banner->delete();

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif banner (AJAX-friendly PATCH).
     */
    public function toggleActive(Banner $banner): RedirectResponse
    {
        $banner->update(['is_active' => ! $banner->is_active]);

        $status = $banner->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.banners.index')
            ->with('success', "Banner berhasil {$status}.");
    }

    /**
     * Proses upload gambar: konversi ke WebP dan simpan ke storage/public/banners/.
     * File asli (jpg/png/dll) tidak disimpan permanen.
     * Hanya path file .webp yang dikembalikan untuk disimpan di kolom image_url.
     */
    private function processAndStoreImage(BannerRequest $request): string
    {
        $file = $request->file('image');

        // Inisialisasi Intervention Image dengan driver GD
        $manager = new ImageManager(new Driver());
        $image   = $manager->read($file->getRealPath());

        // Kompres dan konversi ke WebP (kualitas 85)
        $webpContent = $image->toWebp(85)->toString();

        // Generate nama file unik dengan ekstensi .webp
        $filename = 'banners/' . uniqid('banner_', true) . '.webp';

        // Simpan ke disk public (storage/app/public/banners/)
        Storage::disk('public')->put($filename, $webpContent);

        return $filename;
    }
}
