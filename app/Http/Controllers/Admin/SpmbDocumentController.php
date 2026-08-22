<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSpmbDocumentRequest;
use App\Http\Requests\Admin\UpdateSpmbDocumentRequest;
use App\Services\SpmbDocumentService;
use App\Services\SpmbInfoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SpmbDocumentController extends Controller
{
    protected SpmbInfoService $spmbInfoService;

    protected SpmbDocumentService $spmbDocumentService;

    public function __construct(SpmbInfoService $spmbInfoService, SpmbDocumentService $spmbDocumentService)
    {
        $this->spmbInfoService = $spmbInfoService;
        $this->spmbDocumentService = $spmbDocumentService;
    }

    /**
     * Tampilkan daftar dokumen khusus untuk satu Paket SPMB (spmb_info_id).
     */
    public function index(int $spmbInfoId): View
    {
        $package = $this->spmbInfoService->getSpmbInfoById($spmbInfoId);
        if (! $package) {
            abort(404, 'Paket SPMB tidak ditemukan.');
        }

        $documents = $this->spmbDocumentService->getDocumentsBySpmbInfoId($spmbInfoId);

        return view('admin.spmb_documents.index', compact('package', 'documents'));
    }

    /**
     * Simpan dokumen baru ke dalam Paket SPMB.
     */
    public function store(StoreSpmbDocumentRequest $request, int $spmbInfoId): RedirectResponse
    {
        $package = $this->spmbInfoService->getSpmbInfoById($spmbInfoId);
        if (! $package) {
            abort(404, 'Paket SPMB tidak ditemukan.');
        }

        $this->spmbDocumentService->createDocument(
            $spmbInfoId,
            $request->validated(),
            $request->file('file_url')
        );

        return redirect()
            ->route('admin.spmb_info.documents.index', $spmbInfoId)
            ->with('success', 'Dokumen SPMB berhasil ditambahkan.');
    }

    /**
     * Perbarui data dokumen.
     */
    public function update(UpdateSpmbDocumentRequest $request, int $spmbInfoId, int $documentId): RedirectResponse
    {
        $this->spmbDocumentService->updateDocument(
            $documentId,
            $request->validated(),
            $request->file('file_url')
        );

        return redirect()
            ->route('admin.spmb_info.documents.index', $spmbInfoId)
            ->with('success', 'Dokumen SPMB berhasil diperbarui.');
    }

    /**
     * Hapus dokumen.
     */
    public function destroy(int $spmbInfoId, int $documentId): RedirectResponse
    {
        $this->spmbDocumentService->deleteDocument($documentId);

        return redirect()
            ->route('admin.spmb_info.documents.index', $spmbInfoId)
            ->with('success', 'Dokumen SPMB berhasil dihapus.');
    }
}
