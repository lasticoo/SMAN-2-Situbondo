<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'category',
        'thumbnail_url',
        'summary',
        'content',
        'status',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'display_thumbnail_url',
        'day',
        'month_short',
        'formatted_date',
        'formatted_time',
        'category_accent',
    ];

    public function author()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Scope untuk berita yang sudah dipublikasikan
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope untuk filter kategori berita
     */
    public function scopeFilterCategory(Builder $query, ?string $category): Builder
    {
        if (empty($category) || in_array(strtolower($category), ['all', 'semua', 'semua berita'])) {
            return $query;
        }

        return $query->where('category', $category);
    }

    /**
     * Scope untuk pencarian judul, ringkasan, atau isi konten berita
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('summary', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    /**
     * Accessor untuk Thumbnail Image URL yang membaca gambar dari manapun gambar itu berada
     */
    public function getDisplayThumbnailUrlAttribute(): string
    {
        $defaultFallback = file_exists(public_path('images/static/gambar_profile_statis.jpg'))
            ? asset('images/static/gambar_profile_statis.jpg')
            : (file_exists(public_path('build/assets/banner smada.png')) ? asset('build/assets/banner smada.png') : asset('images/static/gambar_profile_statis.jpg'));

        if (empty($this->thumbnail_url)) {
            return $defaultFallback;
        }

        // 1. Normalisasi Windows backslashes, leading/trailing whitespace
        $clean = trim(str_replace('\\', '/', $this->thumbnail_url));
        if (empty($clean)) {
            return $defaultFallback;
        }

        // 2. Data URI atau URL Web Eksternal Penuh (http, https, protocol-relative)
        if (Str::startsWith($clean, ['http://', 'https://', '//', 'data:image/'])) {
            return $clean;
        }

        // 3. Absolute local filesystem path on server (misal: C:/laragon/www/smada/public/...)
        $publicBasePath = str_replace('\\', '/', public_path());
        if (Str::startsWith($clean, $publicBasePath)) {
            $rel = ltrim(substr($clean, strlen($publicBasePath)), '/');
            return asset($rel);
        }

        // 4. Dimulai dengan slash / (misal: /images/..., /storage/..., /build/..., /uploads/...)
        if (Str::startsWith($clean, '/')) {
            $clean = ltrim($clean, '/');
        }

        // 5. Membersihkan prefix berlebih seperti storage/public/, storage/app/public/, app/public/, public/
        $cleanStoragePath = preg_replace('#^(storage/)?(app/)?public/#i', '', $clean);
        $cleanStoragePath = preg_replace('#^storage/#i', '', $cleanStoragePath);

        // 6. Cek langsung di Storage disk public Laravel
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanStoragePath)) {
            return asset('storage/' . $cleanStoragePath);
        }

        // 7. Cek file langsung di dalam direktori public/ (misal: build/assets/banner smada.png atau images/...)
        if (file_exists(public_path($clean))) {
            return asset($clean);
        }

        // 8. Cek subfolder umum jika hanya nama file tanpa nama folder
        $subfolders = [
            'news/', 'berita/', 'announcement/', 'announcements/', 'pengumuman/', 'banners/', 'popups/',
            'employees/', 'employee/', 'guru/', 'pegawai/',
            'school_profile/', 'images/static/', 'images/', 'uploads/', 'build/assets/'
        ];

        foreach ($subfolders as $folder) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($folder . $cleanStoragePath)) {
                return asset('storage/' . $folder . $cleanStoragePath);
            }
            if (file_exists(public_path($folder . $clean))) {
                return asset($folder . $clean);
            }
            if (file_exists(public_path('storage/' . $folder . $cleanStoragePath))) {
                return asset('storage/' . $folder . $cleanStoragePath);
            }
        }

        // 9. Fallback: URL Storage publik Laravel via asset()
        return asset('storage/' . $cleanStoragePath);
    }

    /**
     * Accessor untuk Hari publikasi (misal: "16", "28", "08")
     */
    public function getDayAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at ?? Carbon::now();
        return $date->format('d');
    }

    /**
     * Accessor untuk Singkatan Bulan publikasi dalam Bahasa Indonesia (misal: "JUL", "AGT", "SEP")
     */
    public function getMonthShortAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at ?? Carbon::now();
        $months = [
            1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR', 5 => 'MEI', 6 => 'JUN',
            7 => 'JUL', 8 => 'AGT', 9 => 'SEP', 10 => 'OKT', 11 => 'NOV', 12 => 'DES'
        ];

        return $months[$date->month] ?? $date->format('M');
    }

    /**
     * Accessor untuk Format Tanggal Lengkap (misal: "16 Juli 2025")
     */
    public function getFormattedDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at ?? Carbon::now();
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $date->format('d') . ' ' . ($months[$date->month] ?? $date->format('F')) . ' ' . $date->format('Y');
    }

    /**
     * Accessor untuk Jam Publikasi (misal: "08:00 WIB")
     */
    public function getFormattedTimeAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at ?? Carbon::now();
        return $date->format('H:i') . ' WIB';
    }

    /**
     * Accessor untuk Style Aksen Warna Kategori
     */
    public function getCategoryAccentAttribute(): array
    {
        $cat = strtolower(trim($this->category ?? 'informasi umum'));

        if (str_contains($cat, 'akademik') || str_contains($cat, 'kurikulum') || str_contains($cat, 'ujian')) {
            return [
                'name' => $this->category ?: 'Akademik',
                'badge_bg' => 'bg-blue-50 text-blue-700 border border-blue-200/80',
                'date_badge_text' => 'text-blue-700',
                'dot_color' => 'bg-blue-500',
                'icon' => 'fa-graduation-cap',
            ];
        }

        if (str_contains($cat, 'kesiswaan') || str_contains($cat, 'osis') || str_contains($cat, 'ekstra') || str_contains($cat, 'lomba') || str_contains($cat, 'prestasi')) {
            return [
                'name' => $this->category ?: 'Kesiswaan',
                'badge_bg' => 'bg-amber-50 text-amber-800 border border-amber-200/80',
                'date_badge_text' => 'text-amber-800',
                'dot_color' => 'bg-amber-500',
                'icon' => 'fa-users',
            ];
        }

        // Default: Informasi Umum / Umum
        return [
            'name' => $this->category ?: 'Informasi Umum',
            'badge_bg' => 'bg-emerald-50 text-emerald-800 border border-emerald-200/80',
            'date_badge_text' => 'text-emerald-800',
            'dot_color' => 'bg-emerald-500',
            'icon' => 'fa-info-circle',
        ];
    }

    /**
     * Auto-clear caches on update or delete (Invalidate-on-Write)
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('landing_news_top5');
        });

        static::deleted(function () {
            Cache::forget('landing_news_top5');
        });
    }
}
