<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSpmbInfoRequest;
use App\Http\Requests\Admin\UpdateSpmbInfoRequest;
use App\Services\SpmbInfoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SpmbInfoController extends Controller
{
    protected SpmbInfoService $spmbInfoService;

    public function __construct(SpmbInfoService $spmbInfoService)
    {
        $this->spmbInfoService = $spmbInfoService;
    }

    /**
     * Tampilkan daftar seluruh Paket SPMB (spmb_info).
     */
    public function index(): View
    {
        $packages = $this->spmbInfoService->getAllSpmbInfo();

        return view('admin.spmb_info.index', compact('packages'));
    }

    /**
     * Simpan Paket SPMB baru.
     */
    public function store(StoreSpmbInfoRequest $request): RedirectResponse
    {
        $this->spmbInfoService->createSpmbInfo(
            $request->validated(),
            $request->file('banner_url')
        );

        return redirect()
            ->route('admin.spmb_info.index')
            ->with('success', 'Paket SPMB berhasil ditambahkan.');
    }

    /**
     * Perbarui data Paket SPMB.
     */
    public function update(UpdateSpmbInfoRequest $request, int $id): RedirectResponse
    {
        $this->spmbInfoService->updateSpmbInfo(
            $id,
            $request->validated(),
            $request->file('banner_url')
        );

        return redirect()
            ->route('admin.spmb_info.index')
            ->with('success', 'Paket SPMB berhasil diperbarui.');
    }

    /**
     * Hapus Paket SPMB berantai (beserta dokumen & file fisiknya).
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->spmbInfoService->deleteSpmbInfo($id);

        return redirect()
            ->route('admin.spmb_info.index')
            ->with('success', 'Paket SPMB beserta seluruh dokumennya berhasil dihapus.');
    }
}
