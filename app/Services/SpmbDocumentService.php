<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SpmbDocumentService
{
    public const STORAGE_FOLDER = 'spmb-documents';

    /**
     * Ambil semua dokumen yang menempel pada satu paket SPMB (spmb_info_id).
     */
    public function getDocumentsBySpmbInfoId(int $spmbInfoId): Collection
    {
        return DB::table('spmb_documents')
            ->where('spmb_info_id', $spmbInfoId)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Ambil detail dokumen berdasarkan ID.
     */
    public function getDocumentById(int $id): ?object
    {
        return DB::table('spmb_documents')
            ->where('id', $id)
            ->first();
    }

    /**
     * Simpan dokumen baru ke storage disk public dan catat ke tabel spmb_documents.
     */
    public function createDocument(int $spmbInfoId, array $data, UploadedFile $file): int
    {
        $filePath = $file->store(self::STORAGE_FOLDER, 'public');

        return DB::table('spmb_documents')->insertGetId([
            'spmb_info_id' => $spmbInfoId,
            'title' => $data['title'],
            'file_url' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Perbarui data dokumen. Jika ada file baru, hapus file lama dan simpan file baru.
     */
    public function updateDocument(int $id, array $data, ?UploadedFile $file = null): bool
    {
        $doc = $this->getDocumentById($id);
        if (! $doc) {
            return false;
        }

        $updateData = [
            'title' => $data['title'],
            'updated_at' => now(),
        ];

        if ($file) {
            if ($doc->file_url && Storage::disk('public')->exists($doc->file_url)) {
                Storage::disk('public')->delete($doc->file_url);
            }
            $updateData['file_url'] = $file->store(self::STORAGE_FOLDER, 'public');
        }

        return DB::table('spmb_documents')
            ->where('id', $id)
            ->update($updateData) > 0;
    }

    /**
     * Hapus dokumen tunggal beserta file fisiknya dari storage disk public.
     */
    public function deleteDocument(int $id): bool
    {
        $doc = $this->getDocumentById($id);
        if (! $doc) {
            return false;
        }

        if ($doc->file_url && Storage::disk('public')->exists($doc->file_url)) {
            Storage::disk('public')->delete($doc->file_url);
        }

        return DB::table('spmb_documents')
            ->where('id', $id)
            ->delete() > 0;
    }

    /**
     * Hapus seluruh dokumen yang terikat ke satu spmb_info_id beserta file fisiknya.
     */
    public function deleteAllBySpmbInfoId(int $spmbInfoId): void
    {
        $documents = DB::table('spmb_documents')
            ->where('spmb_info_id', $spmbInfoId)
            ->get();

        foreach ($documents as $doc) {
            if ($doc->file_url && Storage::disk('public')->exists($doc->file_url)) {
                Storage::disk('public')->delete($doc->file_url);
            }
        }

        DB::table('spmb_documents')
            ->where('spmb_info_id', $spmbInfoId)
            ->delete();
    }
}
