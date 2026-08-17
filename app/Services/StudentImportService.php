<?php

namespace App\Services;

use App\Models\ImportBatch;
use App\Models\Student;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentImportService
{
    /**
     * Baca file Excel/CSV, validasi tiap baris, masukkan data siswa valid ke DB,
     * dan catat hasil serta ringkasan error ke tabel import_batches.
     */
    public function importSpreadsheet(UploadedFile $file, int $adminId): ImportBatch
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, false);

        $totalRows = 0;
        $successRows = 0;
        $failedRows = 0;
        $errorLogs = [];
        $seenNisns = [];

        // Skip baris header (index 0)
        $dataRows = array_slice($rows, 1);

        foreach ($dataRows as $index => $row) {
            $rowNum = $index + 2; // Baris riil di Excel (baris 1 header)

            $nisn = trim((string) ($row[0] ?? ''));
            $name = trim((string) ($row[1] ?? ''));
            $class = trim((string) ($row[2] ?? ''));
            $extraInfo = trim((string) ($row[3] ?? ''));
            $isPublicRaw = trim((string) ($row[4] ?? '1'));

            // Abaikan baris yang benar-benar kosong
            if (empty($nisn) && empty($name) && empty($class) && empty($extraInfo)) {
                continue;
            }

            $totalRows++;
            $rowErrors = [];

            // Validasi NISN
            if (empty($nisn)) {
                $rowErrors[] = 'NISN kosong';
            } elseif (strlen($nisn) > 20) {
                $rowErrors[] = 'NISN lebih dari 20 karakter';
            } elseif (isset($seenNisns[$nisn])) {
                $rowErrors[] = "NISN '{$nisn}' duplikat dalam file ini";
            } elseif (Student::where('nisn', $nisn)->exists()) {
                $rowErrors[] = "NISN '{$nisn}' sudah terdaftar di database";
            }

            // Validasi Nama
            if (empty($name)) {
                $rowErrors[] = 'Nama siswa kosong';
            } elseif (strlen($name) > 150) {
                $rowErrors[] = 'Nama siswa lebih dari 150 karakter';
            }

            // Validasi Kelas
            if (empty($class)) {
                $rowErrors[] = 'Kelas siswa kosong';
            } elseif (strlen($class) > 20) {
                $rowErrors[] = 'Kelas siswa lebih dari 20 karakter';
            }

            // Jika ada error pada baris ini
            if (! empty($rowErrors)) {
                $failedRows++;
                $errorLogs[] = "Baris #{$rowNum} GAGAL: ".implode(', ', $rowErrors)." (NISN: '{$nisn}', Nama: '{$name}')";

                continue;
            }

            // Parse status publikasi
            $isPublic = ! in_array(strtolower($isPublicRaw), ['0', 'false', 'nonaktif', 'private', 'tidak']);

            // Simpan siswa valid
            Student::create([
                'nisn' => $nisn,
                'name' => $name,
                'class' => $class,
                'extra_info' => $extraInfo ?: null,
                'is_public' => $isPublic,
            ]);

            $seenNisns[$nisn] = true;
            $successRows++;
        }

        $status = ($totalRows > 0 && $successRows === 0)
            ? ImportBatch::STATUS_FAILED
            : ImportBatch::STATUS_COMPLETED;

        return ImportBatch::create([
            'uploaded_by' => $adminId,
            'file_name' => $file->getClientOriginalName(),
            'total_rows' => $totalRows,
            'success_rows' => $successRows,
            'failed_rows' => $failedRows,
            'status' => $status,
            'error_log' => ! empty($errorLogs) ? implode("\n", $errorLogs) : null,
        ]);
    }

    /**
     * Generate dan download template file Excel (.xlsx) untuk import data siswa.
     */
    public function generateTemplateDownload(): BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import Siswa');

        // Header Columns
        $headers = [
            'A1' => 'NISN (Wajib, Unik)',
            'B1' => 'Nama Lengkap (Wajib)',
            'C1' => 'Kelas (Wajib)',
            'D1' => 'Informasi Tambahan (Opsional)',
            'E1' => 'Status Publikasi (1 = Ya, 0 = Tidak)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Sample Data Rows
        $sampleData = [
            ['0051234567', 'Ahmad Ridwan', 'XII IPA 1', 'Wali Kelas Pak Budi', '1'],
            ['0059876543', 'Siti Aisyah', 'XI IPS 2', 'Ketua OSIS', '1'],
            ['0042345678', 'Nabila Putri', 'X-1', 'Siswa Berprestasi', '1'],
            ['0038765432', 'Dimas Wahyudi', 'XII Bahasa', 'Siswa Mutasi', '0'],
        ];

        $rowNum = 2;
        foreach ($sampleData as $row) {
            $sheet->setCellValue("A{$rowNum}", $row[0]);
            $sheet->setCellValue("B{$rowNum}", $row[1]);
            $sheet->setCellValue("C{$rowNum}", $row[2]);
            $sheet->setCellValue("D{$rowNum}", $row[3]);
            $sheet->setCellValue("E{$rowNum}", $row[4]);
            $rowNum++;
        }

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $tempPath = sys_get_temp_dir().'/template_import_siswa_'.time().'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        return response()->download($tempPath, 'template_import_siswa.xlsx')->deleteFileAfterSend(true);
    }
}
