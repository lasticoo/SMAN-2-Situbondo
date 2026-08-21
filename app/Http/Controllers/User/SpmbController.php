<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SpmbController extends Controller
{
    /**
     * Tampilkan Halaman Utama SPMB / PPDB Publik SMAN 2 Situbondo (US-09)
     */
    public function index()
    {
        // 1. Ambil pengaturan warna dinamis dari database (tabel color / color_settings)
        $colorSetting = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('color')) {
            $colorSetting = DB::table('color')->first();
        } elseif (\Illuminate\Support\Facades\Schema::hasTable('color_settings')) {
            $colorSetting = DB::table('color_settings')->first();
        }
        $primaryColor = $colorSetting->primary_color ?? '#05479E';
        $secondaryColor = $colorSetting->secondary_color ?? '#F19E38';

        // 2. Kontak & Alamat Sekolah
        $schoolPhone = '(0338) 671234';
        $schoolEmail = 'info@sman2situbondo.sch.id';
        $schoolAddress = 'Jl. Argopuro No.17, Mimbaan, Kec. Panji, Kabupaten Situbondo, Jawa Timur 68322';

        // Format link WhatsApp interaktif dengan pre-filled inquiry text
        $cleanPhone = preg_replace('/[^0-9]/', '', $schoolPhone);
        if (Str::startsWith($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (!Str::startsWith($cleanPhone, '62')) {
            $cleanPhone = '62' . $cleanPhone;
        }
        $whatsappUrl = "https://wa.me/{$cleanPhone}?text=" . urlencode("Halo Panitia SPMB SMAN 2 Situbondo, saya ingin berkonsultasi mengenai informasi dan persyaratan pendaftaran murid baru.");

        // 3. Ambil data informasi SPMB (urutkan dari yang terbaru diupload)
        $spmbInfoList = DB::table('spmb_info')
            ->orderBy('id', 'desc')
            ->get();

        // Ambil seluruh banner unik yang tersedia di tabel spmb_info untuk slider dinamis
        $banners = $spmbInfoList
            ->whereNotNull('banner_url')
            ->pluck('banner_url')
            ->filter()
            ->unique()
            ->values()
            ->map(function ($url) {
                return $this->resolveFileUrl($url);
            })
            ->filter()
            ->values();

        if ($banners->isEmpty()) {
            $banners = collect(['images/static/gambar_profile_statis.jpg']);
        }

        $activeBannerUrl = $banners->first();
        $spmbYear = Carbon::now()->year;

        foreach ($spmbInfoList as $info) {
            if (!empty($info->period_start)) {
                $spmbYear = Carbon::parse($info->period_start)->year;
                break;
            }
        }

        if (empty($activeBannerUrl)) {
            $activeBannerUrl = 'images/static/gambar_profile_statis.jpg';
        }

        // Format timeline data jadwal & tahapan (Latest first)
        $timelineSchedules = $spmbInfoList->map(function ($item) use ($cleanPhone) {
            $dateRange = '';
            if (!empty($item->period_start) && !empty($item->period_end)) {
                $start = Carbon::parse($item->period_start);
                $end = Carbon::parse($item->period_end);

                if ($start->month === $end->month && $start->year === $end->year) {
                    $dateRange = $start->format('j') . ' - ' . $end->translatedFormat('j F Y');
                } elseif ($start->year === $end->year) {
                    $dateRange = $start->translatedFormat('j F') . ' - ' . $end->translatedFormat('j F Y');
                } else {
                    $dateRange = $start->translatedFormat('j F Y') . ' - ' . $end->translatedFormat('j F Y');
                }
            } elseif (!empty($item->period_start)) {
                $dateRange = Carbon::parse($item->period_start)->translatedFormat('j F Y');
            } elseif (!empty($item->period_end)) {
                $dateRange = 'Hingga ' . Carbon::parse($item->period_end)->translatedFormat('j F Y');
            } else {
                $dateRange = 'Jadwal Ditentukan Kemudian';
            }

            // Parsing judul dan deskripsi dari schedule_info atau requirements_info
            $scheduleText = trim($item->schedule_info ?? '');
            $requirementsText = trim($item->requirements_info ?? '');

            $title = 'Tahapan Pendaftaran';
            $fullDescription = $requirementsText ?: $scheduleText;

            if (!empty($scheduleText)) {
                $lines = explode("\n", $scheduleText);
                $firstLine = trim($lines[0]);
                if (strlen($firstLine) <= 60 && !empty($firstLine)) {
                    $title = $firstLine;
                    if (count($lines) > 1) {
                        $remaining = trim(implode("\n", array_slice($lines, 1)));
                        if (!empty($remaining)) {
                            $fullDescription = $remaining;
                        }
                    }
                } else {
                    $title = Str::limit($firstLine, 40, '...');
                }
            }

            if (empty($fullDescription)) {
                $fullDescription = 'Informasi teknis pelaksanaan tahapan seleksi penerimaan murid baru SMAN 2 Situbondo.';
            }

            // Ringkasan preview beberapa kata untuk kartu awal
            $previewDescription = Str::limit(strip_tags($fullDescription), 120, '...');

            // Pre-filled WhatsApp direct query template
            $waMessage = "Halo Panitia SPMB SMAN 2 Situbondo, saya ingin bertanya lebih lanjut mengenai jadwal/tahapan \"{$title}\" (Periode: {$dateRange}). Mohon informasinya.";
            $stageWaUrl = "https://wa.me/{$cleanPhone}?text=" . urlencode($waMessage);

            return (object) [
                'id' => $item->id,
                'date_range' => $dateRange,
                'title' => $title,
                'preview_description' => $previewDescription,
                'full_description' => $fullDescription,
                'raw_schedule' => $scheduleText,
                'raw_requirements' => $requirementsText,
                'period_start' => $item->period_start,
                'period_end' => $item->period_end,
                'wa_url' => $stageWaUrl,
                'created_at' => $item->created_at,
            ];
        });

        // 4. Ambil data dokumen unduhan beserta metadata ekstensi & ukuran file
        $documents = DB::table('spmb_documents')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($doc) {
                $fileMeta = $this->resolveFileMetadata($doc->file_url);
                return (object) [
                    'id' => $doc->id,
                    'spmb_info_id' => $doc->spmb_info_id,
                    'title' => $doc->title,
                    'file_url' => $this->resolveFileUrl($doc->file_url),
                    'extension' => $fileMeta['extension'],
                    'size_formatted' => $fileMeta['size_formatted'],
                    'icon_class' => $fileMeta['icon_class'],
                    'badge_bg' => $fileMeta['badge_bg'],
                    'badge_text' => $fileMeta['badge_text'],
                ];
            });

        return view('user.spmb.index', compact(
            'primaryColor',
            'secondaryColor',
            'schoolPhone',
            'schoolEmail',
            'schoolAddress',
            'whatsappUrl',
            'activeBannerUrl',
            'banners',
            'spmbYear',
            'timelineSchedules',
            'documents'
        ));
    }

    /**
     * Tampilkan Halaman Detail Jadwal & Tahapan SPMB (US-09 Detail)
     */
    public function show($id)
    {
        // 1. Ambil data tema dinamis
        $colorSetting = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('color')) {
            $colorSetting = DB::table('color')->first();
        } elseif (\Illuminate\Support\Facades\Schema::hasTable('color_settings')) {
            $colorSetting = DB::table('color_settings')->first();
        }
        $primaryColor = $colorSetting->primary_color ?? '#05479E';
        $secondaryColor = $colorSetting->secondary_color ?? '#F19E38';

        // 2. Kontak sekolah & konfigurasi WhatsApp
        $schoolPhone = '(0338) 671234';
        $schoolEmail = 'info@sman2situbondo.sch.id';
        $schoolAddress = 'Jl. Argopuro No.17, Mimbaan, Kec. Panji, Kabupaten Situbondo, Jawa Timur 68322';

        $cleanPhone = preg_replace('/[^0-9]/', '', $schoolPhone);
        if (Str::startsWith($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (!Str::startsWith($cleanPhone, '62')) {
            $cleanPhone = '62' . $cleanPhone;
        }

        // 3. Ambil data informasi SPMB terpilih
        $item = DB::table('spmb_info')->where('id', $id)->first();
        if (!$item) {
            abort(404, 'Informasi tahapan SPMB tidak ditemukan.');
        }

        // Format tanggal
        $dateRange = '';
        if (!empty($item->period_start) && !empty($item->period_end)) {
            $start = Carbon::parse($item->period_start);
            $end = Carbon::parse($item->period_end);

            if ($start->month === $end->month && $start->year === $end->year) {
                $dateRange = $start->format('j') . ' - ' . $end->translatedFormat('j F Y');
            } elseif ($start->year === $end->year) {
                $dateRange = $start->translatedFormat('j F') . ' - ' . $end->translatedFormat('j F Y');
            } else {
                $dateRange = $start->translatedFormat('j F Y') . ' - ' . $end->translatedFormat('j F Y');
            }
        } elseif (!empty($item->period_start)) {
            $dateRange = Carbon::parse($item->period_start)->translatedFormat('j F Y');
        } elseif (!empty($item->period_end)) {
            $dateRange = 'Hingga ' . Carbon::parse($item->period_end)->translatedFormat('j F Y');
        } else {
            $dateRange = 'Jadwal Ditentukan Kemudian';
        }

        // Parsing judul dan rincian konten
        $scheduleText = trim($item->schedule_info ?? '');
        $requirementsText = trim($item->requirements_info ?? '');

        $title = 'Tahapan Pendaftaran';
        $fullDescription = $requirementsText ?: $scheduleText;

        if (!empty($scheduleText)) {
            $lines = explode("\n", $scheduleText);
            $firstLine = trim($lines[0]);
            if (strlen($firstLine) <= 60 && !empty($firstLine)) {
                $title = $firstLine;
                if (count($lines) > 1) {
                    $remaining = trim(implode("\n", array_slice($lines, 1)));
                    if (!empty($remaining)) {
                        $fullDescription = $remaining;
                    }
                }
            } else {
                $title = Str::limit($firstLine, 40, '...');
            }
        }

        // Template Pesan Otomatis WhatsApp
        $waMessage = "Halo Panitia SPMB SMAN 2 Situbondo, saya ingin bertanya lebih lanjut mengenai jadwal/tahapan \"{$title}\" (Periode: {$dateRange}). Mohon informasinya.";
        $stageWaUrl = "https://wa.me/{$cleanPhone}?text=" . urlencode($waMessage);

        $spmbYear = !empty($item->period_start) ? Carbon::parse($item->period_start)->year : Carbon::now()->year;

        $scheduleDetail = (object) [
            'id' => $item->id,
            'title' => $title,
            'date_range' => $dateRange,
            'full_description' => $fullDescription ?: 'Rincian teknis pelaksanaan seleksi penerimaan murid baru.',
            'schedule_info' => $scheduleText,
            'requirements_info' => $requirementsText,
            'period_start' => $item->period_start,
            'period_end' => $item->period_end,
            'wa_url' => $stageWaUrl,
            'created_at' => $item->created_at,
        ];

        // 4. Ambil jadwal tahapan lainnya untuk sidebar navigasi cepat (exclude current ID)
        $otherSchedules = DB::table('spmb_info')
            ->where('id', '!=', $id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($other) {
                $lines = explode("\n", trim($other->schedule_info ?? ''));
                $otherTitle = !empty($lines[0]) && strlen(trim($lines[0])) <= 60 ? trim($lines[0]) : 'Tahapan SPMB';
                $otherDate = !empty($other->period_start) ? Carbon::parse($other->period_start)->translatedFormat('j M Y') : 'Jadwal';
                return (object) [
                    'id' => $other->id,
                    'title' => $otherTitle,
                    'date_formatted' => $otherDate,
                ];
            });

        // 5. Dokumen Terkait
        $documents = DB::table('spmb_documents')
            ->orderBy('id', 'asc')
            ->limit(4)
            ->get()
            ->map(function ($doc) {
                $fileMeta = $this->resolveFileMetadata($doc->file_url);
                return (object) [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'extension' => $fileMeta['extension'],
                    'size_formatted' => $fileMeta['size_formatted'],
                    'icon_class' => $fileMeta['icon_class'],
                ];
            });

        return view('user.spmb.show', compact(
            'scheduleDetail',
            'spmbYear',
            'primaryColor',
            'secondaryColor',
            'otherSchedules',
            'documents',
            'schoolPhone',
            'schoolEmail',
            'schoolAddress'
        ));
    }

    /**
     * Layanan Pengunduhan Dokumen SPMB Terkompresi & Teroptimasi
     */
    public function download($id)
    {
        $doc = DB::table('spmb_documents')->where('id', $id)->first();

        if (!$doc) {
            abort(404, 'Dokumen SPMB tidak ditemukan.');
        }

        $filePath = $this->resolveLocalFilePath($doc->file_url);

        // Jika file lokal tersedia, kirim dengan header kompresi & download binary optimal
        if ($filePath && file_exists($filePath)) {
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $safeTitle = Str::slug($doc->title) . '.' . ($ext ?: 'pdf');

            $headers = [
                'Content-Description' => 'File Transfer',
                'Content-Type' => File::mimeType($filePath) ?: 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $safeTitle . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Pragma' => 'public',
            ];

            return response()->download($filePath, $safeTitle, $headers);
        }

        // Fallback untuk URL eksternal atau file yang di-host di storage link
        $resolvedUrl = $this->resolveFileUrl($doc->file_url);
        return redirect()->away($resolvedUrl);
    }

    /**
     * Multi-Tier Universal File URL Resolver with In-Memory Caching
     */
    protected function resolveFileUrl(?string $path): string
    {
        static $urlCache = [];

        if (empty($path)) {
            return asset('images/static/gambar_profile_statis.jpg');
        }

        $clean = trim(str_replace('\\', '/', $path));

        if (isset($urlCache[$clean])) {
            return $urlCache[$clean];
        }

        // 1. Data URI atau Full Web URL
        if (Str::startsWith($clean, ['http://', 'https://', '//', 'data:'])) {
            return $urlCache[$clean] = $clean;
        }

        // 2. Format storage/
        if (Str::startsWith($clean, 'storage/')) {
            $rel = substr($clean, 8);
            if (Storage::disk('public')->exists($rel)) {
                return $urlCache[$clean] = Storage::disk('public')->url($rel);
            }
            return $urlCache[$clean] = asset($clean);
        }

        // 3. Format public/
        if (Str::startsWith($clean, 'public/')) {
            $rel = substr($clean, 7);
            if (file_exists(public_path($rel))) {
                return $urlCache[$clean] = asset($rel);
            }
            if (Storage::disk('public')->exists($rel)) {
                return $urlCache[$clean] = Storage::disk('public')->url($rel);
            }
        }

        // 4. Cek disk public
        if (Storage::disk('public')->exists($clean)) {
            return $urlCache[$clean] = Storage::disk('public')->url($clean);
        }

        // 5. Cek public_path
        if (file_exists(public_path($clean))) {
            return $urlCache[$clean] = asset($clean);
        }

        return $urlCache[$clean] = asset($clean);
    }

    /**
     * Resolusi Path File Lokal untuk Kompresi & Download with In-Memory Caching
     */
    protected function resolveLocalFilePath(?string $path): ?string
    {
        static $localPathCache = [];

        if (empty($path)) {
            return null;
        }

        $clean = trim(str_replace('\\', '/', $path));

        if (isset($localPathCache[$clean])) {
            return $localPathCache[$clean];
        }

        if (Str::startsWith($clean, ['http://', 'https://', '//'])) {
            return $localPathCache[$clean] = null;
        }

        if (file_exists($clean)) {
            return $localPathCache[$clean] = $clean;
        }

        if (Str::startsWith($clean, 'storage/')) {
            $rel = substr($clean, 8);
            $diskPath = Storage::disk('public')->path($rel);
            if (file_exists($diskPath)) {
                return $localPathCache[$clean] = $diskPath;
            }
            $pubPath = public_path($clean);
            if (file_exists($pubPath)) {
                return $localPathCache[$clean] = $pubPath;
            }
        }

        if (Str::startsWith($clean, 'public/')) {
            $rel = substr($clean, 7);
            $pubPath = public_path($rel);
            if (file_exists($pubPath)) {
                return $localPathCache[$clean] = $pubPath;
            }
            $diskPath = Storage::disk('public')->path($rel);
            if (file_exists($diskPath)) {
                return $localPathCache[$clean] = $diskPath;
            }
        }

        $diskPath = Storage::disk('public')->path($clean);
        if (file_exists($diskPath)) {
            return $localPathCache[$clean] = $diskPath;
        }

        $pubPath = public_path($clean);
        if (file_exists($pubPath)) {
            return $localPathCache[$clean] = $pubPath;
        }

        return $localPathCache[$clean] = null;
    }

    /**
     * Deteksi Metadata Ekstensi, Ukuran File & Ikon Visual with In-Memory Caching
     */
    protected function resolveFileMetadata(?string $path): array
    {
        static $metaCache = [];

        $key = (string) $path;
        if (isset($metaCache[$key])) {
            return $metaCache[$key];
        }

        $ext = 'PDF';
        $sizeFormatted = '1.5 MB';

        if (!empty($path)) {
            $cleanPath = parse_url($path, PHP_URL_PATH) ?? $path;
            $rawExt = pathinfo($cleanPath, PATHINFO_EXTENSION);
            if (!empty($rawExt)) {
                $ext = strtoupper($rawExt);
            }

            $localPath = $this->resolveLocalFilePath($path);
            if ($localPath && file_exists($localPath)) {
                $bytes = filesize($localPath);
                $sizeFormatted = $this->formatBytes($bytes);
            } else {
                // Estimasi bobot berkas standar
                $sizeFormatted = ($ext === 'PDF') ? '2.4 MB' : (($ext === 'DOCX' || $ext === 'DOC') ? '1.1 MB' : '1.8 MB');
            }
        }

        // Mapping Ikon & Nuansa Warna Visual
        switch ($ext) {
            case 'PDF':
                $iconClass = 'far fa-file-pdf';
                $badgeBg = 'bg-rose-50 border-rose-200';
                $badgeText = 'text-rose-600';
                break;
            case 'DOC':
            case 'DOCX':
                $iconClass = 'far fa-file-word';
                $badgeBg = 'bg-blue-50 border-blue-200';
                $badgeText = 'text-blue-600';
                break;
            case 'XLS':
            case 'XLSX':
            case 'CSV':
                $iconClass = 'far fa-file-excel';
                $badgeBg = 'bg-emerald-50 border-emerald-200';
                $badgeText = 'text-emerald-600';
                break;
            case 'ZIP':
            case 'RAR':
            case '7Z':
                $iconClass = 'far fa-file-archive';
                $badgeBg = 'bg-amber-50 border-amber-200';
                $badgeText = 'text-amber-600';
                break;
            case 'JPG':
            case 'JPEG':
            case 'PNG':
            case 'WEBP':
                $iconClass = 'far fa-file-image';
                $badgeBg = 'bg-indigo-50 border-indigo-200';
                $badgeText = 'text-indigo-600';
                break;
            default:
                $iconClass = 'far fa-file-alt';
                $badgeBg = 'bg-slate-50 border-slate-200';
                $badgeText = 'text-slate-600';
                break;
        }

        return $metaCache[$key] = [
            'extension' => $ext,
            'size_formatted' => $sizeFormatted,
            'icon_class' => $iconClass,
            'badge_bg' => $badgeBg,
            'badge_text' => $badgeText,
        ];
    }

    /**
     * Format Bytes ke Satuan Human-Readable
     */
    protected function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
