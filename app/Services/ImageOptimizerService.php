<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageOptimizerService
{
    /**
     * Kompres file gambar ke format WebP, simpan di storage disk public (misal: storage/app/public/employees/),
     * dan catat rincian kompresi ke tabel media_files.
     *
     * @param  UploadedFile  $file  File gambar dari request
     * @param  string  $module  Nama modul (misal: 'employees')
     * @param  int  $referenceId  ID entitas terkait (misal: ID employee)
     * @param  string  $folder  Sub-folder di storage/app/public
     * @return string Path relatif file webp yang tersimpan (misal: 'employees/employee_xxx.webp')
     */
    public static function compressAndLog(
        UploadedFile $file,
        string $module,
        int $referenceId,
        string $folder = 'employees'
    ): string {
        $originalSizeKb = (int) ceil($file->getSize() / 1024);

        $manager = new ImageManager(new Driver);
        $image = $manager->read($file->getRealPath());

        $webpContent = $image->toWebp(85)->toString();
        $filename = $folder.'/'.uniqid($module.'_', true).'.webp';

        Storage::disk('public')->put($filename, $webpContent);
        $optimizedSizeKb = (int) ceil(strlen($webpContent) / 1024);

        DB::table('media_files')->insert([
            'module' => $module,
            'reference_id' => $referenceId,
            'file_url' => $filename,
            'original_size_kb' => $originalSizeKb,
            'optimized_size_kb' => $optimizedSizeKb,
            'created_at' => now(),
        ]);

        return $filename;
    }

    /**
     * Hapus file gambar lama dari disk public dan bersihkan record terkait dari tabel media_files.
     */
    public static function deleteAndClean(?string $filePath, string $module, int $referenceId): void
    {
        if ($filePath) {
            Storage::disk('public')->delete($filePath);
            DB::table('media_files')
                ->where('module', $module)
                ->where('reference_id', $referenceId)
                ->delete();
        }
    }
}
