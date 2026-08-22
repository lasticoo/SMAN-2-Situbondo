<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SpmbInfoService
{
    public const MODULE_NAME = 'spmb';

    public const STORAGE_FOLDER = 'spmb';

    protected SpmbDocumentService $documentService;

    public function __construct(SpmbDocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Ambil seluruh paket SPMB (spmb_info) beserta jumlah dokumen terkait.
     */
    public function getAllSpmbInfo(): Collection
    {
        return DB::table('spmb_info')
            ->select('spmb_info.*', DB::raw('(SELECT COUNT(*) FROM spmb_documents WHERE spmb_documents.spmb_info_id = spmb_info.id) as documents_count'))
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Ambil detail paket SPMB berdasarkan ID.
     */
    public function getSpmbInfoById(int $id): ?object
    {
        return DB::table('spmb_info')
            ->where('id', $id)
            ->first();
    }

    /**
     * Simpan paket SPMB baru: buat record awal, kompres banner ke WebP, simpan ke storage & media_files.
     */
    public function createSpmbInfo(array $data, UploadedFile $bannerFile): int
    {
        return DB::transaction(function () use ($data, $bannerFile) {
            $id = DB::table('spmb_info')->insertGetId([
                'banner_url' => null,
                'schedule_info' => $data['schedule_info'],
                'requirements_info' => $data['requirements_info'],
                'period_start' => $data['period_start'],
                'period_end' => $data['period_end'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $bannerPath = ImageOptimizerService::compressAndLog(
                $bannerFile,
                self::MODULE_NAME,
                $id,
                self::STORAGE_FOLDER
            );

            DB::table('spmb_info')
                ->where('id', $id)
                ->update(['banner_url' => $bannerPath]);

            return $id;
        });
    }

    /**
     * Perbarui paket SPMB. Jika banner diperbarui, bersihkan banner lama & log media_files, lalu kompres banner baru.
     */
    public function updateSpmbInfo(int $id, array $data, ?UploadedFile $bannerFile = null): bool
    {
        $spmbInfo = $this->getSpmbInfoById($id);
        if (! $spmbInfo) {
            return false;
        }

        return DB::transaction(function () use ($id, $spmbInfo, $data, $bannerFile) {
            $updateData = [
                'schedule_info' => $data['schedule_info'],
                'requirements_info' => $data['requirements_info'],
                'period_start' => $data['period_start'],
                'period_end' => $data['period_end'],
                'updated_at' => now(),
            ];

            if ($bannerFile) {
                // Bersihkan gambar lama & log media_files
                ImageOptimizerService::deleteAndClean($spmbInfo->banner_url, self::MODULE_NAME, $id);

                // Upload & kompres gambar baru
                $updateData['banner_url'] = ImageOptimizerService::compressAndLog(
                    $bannerFile,
                    self::MODULE_NAME,
                    $id,
                    self::STORAGE_FOLDER
                );
            }

            return DB::table('spmb_info')
                ->where('id', $id)
                ->update($updateData) >= 0;
        });
    }

    /**
     * Hapus paket SPMB berantai: hapus seluruh dokumen & file terkait, hapus banner & media_files, lalu hapus paket SPMB.
     */
    public function deleteSpmbInfo(int $id): bool
    {
        $spmbInfo = $this->getSpmbInfoById($id);
        if (! $spmbInfo) {
            return false;
        }

        return DB::transaction(function () use ($id, $spmbInfo) {
            // 1. Hapus seluruh dokumen & file fisiknya
            $this->documentService->deleteAllBySpmbInfoId($id);

            // 2. Hapus file banner & catatan media_files
            ImageOptimizerService::deleteAndClean($spmbInfo->banner_url, self::MODULE_NAME, $id);

            // 3. Hapus row spmb_info
            return DB::table('spmb_info')
                ->where('id', $id)
                ->delete() > 0;
        });
    }
}
