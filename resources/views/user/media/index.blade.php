@extends('layouts.app')

@php
    // Universal 10-Tier Image Resolver Engine for Gallery
    $resolvePhotoUrl = function (?string $path): string {
        static $resolvedCache = [];
        $default = asset('images/static/gambar_profile_statis.jpg');
        if (empty($path)) return $default;
        
        $clean = trim(str_replace('\\', '/', $path));
        if (empty($clean)) return $default;
        
        if (isset($resolvedCache[$clean])) {
            return $resolvedCache[$clean];
        }
        
        // 1. Data URI atau Full Web URL
        if (\Illuminate\Support\Str::startsWith($clean, ['http://', 'https://', '//', 'data:image/'])) {
            return $resolvedCache[$clean] = $clean;
        }
        
        // 2. Absolute filesystem path on server
        // a. C:\laragon\www\smada\storage\app\public\... (storage_path('app/public'))
        $storageAppPublicPath = str_replace('\\', '/', storage_path('app/public'));
        if (\Illuminate\Support\Str::startsWith($clean, $storageAppPublicPath)) {
            $rel = ltrim(substr($clean, strlen($storageAppPublicPath)), '/');
            return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url($rel);
        }

        // b. C:\laragon\www\smada\storage\... (storage_path())
        $storageBasePath = str_replace('\\', '/', storage_path());
        if (\Illuminate\Support\Str::startsWith($clean, $storageBasePath)) {
            $rel = ltrim(substr($clean, strlen($storageBasePath)), '/');
            if (\Illuminate\Support\Str::startsWith($rel, 'app/public/')) {
                return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url(substr($rel, 11));
            }
            return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url($rel);
        }

        // c. C:\laragon\www\smada\public\... dan C:\laragon\www\smada\public\storage\... (public_path())
        $publicBasePath = str_replace('\\', '/', public_path());
        if (\Illuminate\Support\Str::startsWith($clean, $publicBasePath)) {
            $rel = ltrim(substr($clean, strlen($publicBasePath)), '/');
            return $resolvedCache[$clean] = asset($rel);
        }

        // d. C:\laragon\www\smada\... (base_path())
        $baseProjectPath = str_replace('\\', '/', base_path());
        if (\Illuminate\Support\Str::startsWith($clean, $baseProjectPath)) {
            $rel = ltrim(substr($clean, strlen($baseProjectPath)), '/');
            if (\Illuminate\Support\Str::startsWith($rel, 'public/')) {
                return $resolvedCache[$clean] = asset(substr($rel, 7));
            }
            if (\Illuminate\Support\Str::startsWith($rel, 'storage/app/public/')) {
                return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url(substr($rel, 19));
            }
            if (\Illuminate\Support\Str::startsWith($rel, 'storage/')) {
                return $resolvedCache[$clean] = asset($rel);
            }
        }
        
        // 3. Leading slash
        if (\Illuminate\Support\Str::startsWith($clean, '/')) {
            $rel = ltrim($clean, '/');
            if (file_exists(public_path($rel))) {
                return $resolvedCache[$clean] = asset($rel);
            }
            if (\Illuminate\Support\Str::startsWith($rel, 'storage/') && \Illuminate\Support\Facades\Storage::disk('public')->exists(substr($rel, 8))) {
                return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url(substr($rel, 8));
            }
            return $resolvedCache[$clean] = asset($rel);
        }
        
        // 4. Stored as public/... or app/public/...
        if (\Illuminate\Support\Str::startsWith($clean, 'public/')) {
            $clean = substr($clean, 7);
        }
        if (\Illuminate\Support\Str::startsWith($clean, 'app/public/')) {
            $clean = substr($clean, 11);
        }
        
        // 5. storage/ prefix
        if (\Illuminate\Support\Str::startsWith($clean, 'storage/')) {
            if (file_exists(public_path($clean))) {
                return $resolvedCache[$clean] = asset($clean);
            }
            $storageRel = substr($clean, 8);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($storageRel)) {
                return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url($storageRel);
            }
            return $resolvedCache[$clean] = asset($clean);
        }
        
        // 6. Direct public/ directory
        if (file_exists(public_path($clean))) {
            return $resolvedCache[$clean] = asset($clean);
        }
        
        // 7. Laravel Storage 'public' disk
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($clean)) {
            return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url($clean);
        }
        
        // 8. Common subfolders
        $subfolders = ['galleries/', 'gallery/', 'kegiatan/', 'media/', 'banners/', 'popups/', 'images/static/', 'images/', 'uploads/', 'build/assets/'];
        foreach ($subfolders as $folder) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($folder . $clean)) {
                return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url($folder . $clean);
            }
            if (file_exists(public_path($folder . $clean))) {
                return $resolvedCache[$clean] = asset($folder . $clean);
            }
            if (file_exists(public_path('storage/' . $folder . $clean))) {
                return $resolvedCache[$clean] = asset('storage/' . $folder . $clean);
            }
        }
        
        return $resolvedCache[$clean] = $default;
    };

    $ogImage = (isset($galleries[0]) && !empty($galleries[0]->photo_url)) 
        ? $resolvePhotoUrl($galleries[0]->photo_url) 
        : asset('images/static/gambar_profile_statis.jpg');
@endphp

@section('title', 'Galeri Kegiatan - SMA Negeri 2 Situbondo')

@push('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="Dokumentasi aktivitas, prestasi, dan momen penting civitas akademik SMAN 2 Situbondo. Jelajahi perjalanan prima kami melalui galeri visual.">
    <meta name="author" content="SMA Negeri 2 Situbondo">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot-image" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ route('gallery.index') }}">
    @if(isset($galleries[0]) && !empty($galleries[0]->photo_url))
        <link rel="preload" as="image" href="{{ $ogImage }}" fetchpriority="high">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SMA Negeri 2 Situbondo">
    <meta property="og:title" content="Galeri Kegiatan - SMA Negeri 2 Situbondo">
    <meta property="og:description" content="Dokumentasi aktivitas, prestasi, dan momen penting civitas akademik SMAN 2 Situbondo. Jelajahi perjalanan prima kami melalui galeri visual.">
    <meta property="og:url" content="{{ route('gallery.index') }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Galeri Kegiatan - SMA Negeri 2 Situbondo">
    <meta name="twitter:description" content="Dokumentasi aktivitas, prestasi, dan momen penting civitas akademik SMAN 2 Situbondo.">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Schema.org JSON-LD Structured Data (ImageGallery & BreadcrumbList) for Google Search & Google Images -->
    @php
        $galleryImagesSchema = [];
        foreach ($galleries as $item) {
            $imgCleanUrl = $resolvePhotoUrl($item->photo_url);
            $imgTitle = $item->activity_name ?: 'Dokumentasi Kegiatan SMA Negeri 2 Situbondo';
            $galleryImagesSchema[] = [
                '@type' => 'ImageObject',
                'name' => $imgTitle,
                'caption' => $imgTitle,
                'description' => 'Dokumentasi foto kegiatan ' . $imgTitle . ' di SMA Negeri 2 Situbondo, Jawa Timur.',
                'contentUrl' => $imgCleanUrl,
                'thumbnailUrl' => $imgCleanUrl,
                'url' => route('gallery.index'),
                'datePublished' => $item->activity_date ? \Illuminate\Support\Carbon::parse($item->activity_date)->toIso8601String() : $item->created_at?->toIso8601String(),
                'uploadDate' => ($item->created_at ?? \Illuminate\Support\Carbon::now())->toIso8601String(),
                'creator' => [
                    '@type' => 'Organization',
                    'name' => 'Humas SMA Negeri 2 Situbondo',
                    'url' => url('/'),
                ],
                'copyrightHolder' => [
                    '@type' => 'Organization',
                    'name' => 'SMA Negeri 2 Situbondo',
                ],
                'creditText' => 'Dokumentasi Resmi Humas SMA Negeri 2 Situbondo',
                'acquireLicensePage' => url('/profil'),
            ];
        }

        $galleryJsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'ImageGallery',
                    '@id' => route('gallery.index') . '#gallery',
                    'name' => 'Galeri Kegiatan SMA Negeri 2 Situbondo',
                    'description' => 'Dokumentasi aktivitas, prestasi, dan momen penting civitas akademik SMAN 2 Situbondo.',
                    'url' => route('gallery.index'),
                    'inLanguage' => 'id-ID',
                    'image' => $galleryImagesSchema,
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => 'SMA Negeri 2 Situbondo',
                        'url' => url('/'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => asset('images/static/gambar_profile_statis.jpg'),
                        ],
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => route('gallery.index') . '#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Beranda',
                            'item' => url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'Media',
                            'item' => route('gallery.index'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => 'Galeri Kegiatan',
                            'item' => route('gallery.index'),
                        ],
                    ],
                ]
            ]
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($galleryJsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@push('styles')
<style>
    :root {
        --primary-main: {{ $colorSetting?->primary_color ?? '#1B3C73' }};
        --secondary-gold: {{ $colorSetting?->secondary_color ?? '#F19E38' }};
        --primary-deep: {{ $colorSetting?->primary_color ? 'color-mix(in srgb, ' . $colorSetting->primary_color . ' 85%, black)' : '#0e2347' }};
        --primary-light: {{ $colorSetting?->primary_color ? 'color-mix(in srgb, ' . $colorSetting->primary_color . ' 10%, white)' : 'rgba(27, 60, 115, 0.08)' }};
        --secondary-hover: {{ $colorSetting?->secondary_color ? 'color-mix(in srgb, ' . $colorSetting->secondary_color . ' 85%, black)' : '#d9821f' }};
    }

    .bg-theme-primary { background-color: var(--primary-main) !important; }
    .text-theme-primary { color: var(--primary-main) !important; }
    .border-theme-primary { border-color: var(--primary-main) !important; }
    
    .bg-theme-secondary { background-color: var(--secondary-gold) !important; }
    .text-theme-secondary { color: var(--secondary-gold) !important; }
    .border-theme-secondary { border-color: var(--secondary-gold) !important; }
    
    .bg-theme-primary-deep { background-color: var(--primary-deep) !important; }
    .bg-theme-primary-light { background-color: var(--primary-light) !important; }
    .bg-theme-gradient {
        background: linear-gradient(135deg, var(--primary-main) 0%, var(--primary-deep) 100%) !important;
    }

    .hover-text-primary:hover { color: var(--primary-main) !important; }
    .hover-bg-primary:hover { background-color: var(--primary-main) !important; color: #ffffff !important; }

    /* Hardware Acceleration & Micro-Animations */
    .spring-hover, .gallery-card, .img-zoom-box img {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        perspective: 1000px;
    }

    /* Ultra-Smooth Spring Physics Hover Scaling & Rendering Isolation */
    .gallery-card {
        contain: layout paint;
        content-visibility: auto;
        contain-intrinsic-size: 260px;
    }
    @media (prefers-reduced-motion: no-preference) {
        .gallery-card {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease !important;
        }
        .gallery-card:hover {
            transform: translateY(-4px) scale(1.008) translate3d(0, 0, 0) !important;
            box-shadow: 0 20px 35px -10px rgba(0, 28, 77, 0.16), 0 10px 20px -5px rgba(245, 158, 11, 0.12) !important;
        }
    }

    /* CSS Containment & Image Zoom Reveal */
    .img-zoom-box {
        overflow: hidden;
        contain: paint;
    }
    .img-zoom-box img {
        transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1) !important;
        will-change: transform;
    }
    .gallery-card:hover .img-zoom-box img {
        transform: scale(1.07) translate3d(0, 0, 0) !important;
    }

    /* Skeleton Loading Placeholder */
    @keyframes skeleton-shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .skeleton-loader {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: skeleton-shimmer 1.8s infinite;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col justify-between overflow-x-hidden w-full max-w-full" 
     x-data="{ 
         lightboxOpen: false, 
         activeImage: '', 
         activeTitle: '', 
         activeDate: '',
         openLightbox(img, title, date) {
             this.activeImage = img;
             this.activeTitle = title;
             this.activeDate = date;
             this.lightboxOpen = true;
             document.body.classList.add('overflow-hidden');
         },
         closeLightbox() {
             this.lightboxOpen = false;
             document.body.classList.remove('overflow-hidden');
         },
         async downloadImage() {
             if (!this.activeImage) return;
             try {
                 const fileName = (this.activeTitle ? this.activeTitle.replace(/[^a-zA-Z0-9_-]/g, '_').toLowerCase() : 'foto_galeri_smada') + '.jpg';
                 const response = await fetch(this.activeImage);
                 const blob = await response.blob();
                 const blobUrl = window.URL.createObjectURL(blob);
                 const link = document.createElement('a');
                 link.href = blobUrl;
                 link.download = fileName;
                 document.body.appendChild(link);
                 link.click();
                 document.body.removeChild(link);
                 window.URL.revokeObjectURL(blobUrl);
             } catch (e) {
                 const link = document.createElement('a');
                 link.href = this.activeImage;
                 link.download = 'foto_galeri_smada.jpg';
                 link.target = '_blank';
                 document.body.appendChild(link);
                 link.click();
                 document.body.removeChild(link);
             }
         }
     }"
     @keydown.escape.window="closeLightbox()">
    
    <div class="w-full">
        <!-- ------------------------------------------------------------- -->
        <!-- 1. SHARED HEADER & NAVBAR COMPONENT                           -->
        <!-- ------------------------------------------------------------- -->
        @include('user.partials.navbar')

        <!-- ------------------------------------------------------------- -->
        <!-- 2. HERO BANNER SECTION (MATCHING DESIGN MOCKUP)               -->
        <!-- ------------------------------------------------------------- -->
        <section class="bg-theme-gradient text-white py-12 sm:py-16 md:py-20 relative overflow-hidden w-full">
            <!-- Ambient Subtle Lighting & Shapes -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-theme-secondary/20 blur-3xl pointer-events-none"></div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center max-w-4xl" data-aos="fade-up" data-aos-duration="600">
                
                <!-- Ambient Hero Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-theme-secondary text-slate-950 text-xs font-black uppercase tracking-wider mb-4 shadow-sm" data-aos="fade-down" data-aos-duration="600">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-950 animate-pulse"></span>
                    <span>Dokumentasi &amp; Galeri Visual</span>
                </div>

                <!-- Main Title "Galeri Kegiatan" in Clean Bold White -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white font-headline tracking-tight mb-2 sm:mb-3">
                    Galeri Kegiatan
                </h1>
                <div class="w-20 h-1.5 bg-theme-secondary mx-auto rounded-full mb-4 shadow-xs"></div>
                
                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm md:text-base text-slate-200 leading-relaxed max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="150">
                    Dokumentasi aktivitas, prestasi, dan momen penting civitas akademik SMAN 2 Situbondo. Jelajahi perjalanan prima kami melalui lensa visual.
                </p>

            </div>
        </section>

        <!-- ------------------------------------------------------------- -->
        <!-- 3. MAIN GALLERY CONTENT & CATEGORY FILTER BAR                 -->
        <!-- ------------------------------------------------------------- -->
        <main class="bg-white py-10 sm:py-14 w-full">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

                <!-- SINGLE UNIFIED COMPACT FILTER BOX (CLEAN, TIDY & RESPONSIVE) -->
                <div class="relative z-40 max-w-md mx-auto mb-10 sm:mb-12" x-data="{ filterOpen: false, searchAct: '' }">
                    <div class="relative w-full">
                        
                        <!-- Filter Trigger Button Box -->
                        <button type="button" 
                                @click="filterOpen = !filterOpen"
                                class="w-full flex items-center justify-between gap-3 px-5 py-3 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:shadow-md hover:border-theme-secondary transition-all duration-200 text-left cursor-pointer group">
                            
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-theme-secondary text-slate-950 flex items-center justify-center shrink-0 text-sm font-black shadow-xs group-hover:scale-105 transition-all duration-200">
                                    <i class="fas fa-sliders-h"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Kategori Aktivitas</span>
                                    <span class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-theme-primary transition-colors truncate block">
                                        {{ !empty($selectedCategory) && strcasecmp($selectedCategory, 'Semua') !== 0 ? $selectedCategory : 'Semua Kegiatan & Aktivitas' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                @if(!empty($selectedCategory) && strcasecmp($selectedCategory, 'Semua') !== 0)
                                    <a href="{{ route('gallery.index') }}" 
                                       @click.stop
                                       title="Reset ke Semua Kegiatan"
                                       class="p-1 text-slate-400 hover:text-rose-500 transition text-xs">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                @endif
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-theme-secondary group-hover:bg-theme-secondary/15 transition">
                                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="filterOpen ? 'rotate-180' : ''"></i>
                                </div>
                            </div>
                        </button>

                        <!-- POPUP BOX DROPDOWN (RESPONSIF, RAPI, SEARCHABLE & FLOATS ABOVE ALL IMAGES) -->
                        <div x-show="filterOpen"
                             x-cloak
                             @click.outside="filterOpen = false"
                             x-transition:enter="transition ease-out duration-200 transform-gpu"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150 transform-gpu"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                             class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200/95 p-4 z-50 ring-1 ring-slate-900/10">
                            
                            <!-- Search Box inside dropdown -->
                            <div class="relative mb-3">
                                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" 
                                       x-model="searchAct" 
                                       placeholder="Cari nama kegiatan / kategori..." 
                                       class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:outline-hidden focus:ring-2 focus:ring-theme-primary/30 focus:border-theme-primary text-slate-700 transition">
                            </div>

                            <!-- Category / Activity Options (Scrollable & Responsive) -->
                            <div class="max-h-60 overflow-y-auto space-y-1.5 pr-1.5 custom-scrollbar">
                                
                                <!-- Opsi 'Semua Kegiatan' -->
                                <a href="{{ route('gallery.index') }}"
                                   x-show="!searchAct || 'semua kegiatan aktivitas'.includes(searchAct.toLowerCase())"
                                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ empty($selectedCategory) || strcasecmp($selectedCategory, 'Semua') === 0 ? 'bg-theme-primary text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                                    <div class="flex items-center gap-2.5 truncate">
                                        <i class="fas fa-th-large text-[11px] {{ empty($selectedCategory) || strcasecmp($selectedCategory, 'Semua') === 0 ? 'text-amber-300' : 'text-slate-400' }}"></i>
                                        <span>Semua Kegiatan</span>
                                    </div>
                                    @if(empty($selectedCategory) || strcasecmp($selectedCategory, 'Semua') === 0)
                                        <i class="fas fa-check text-xs text-amber-300 shrink-0"></i>
                                    @endif
                                </a>

                                <!-- Opsi Aktivitas Dinamis Lainnya -->
                                @foreach ($rawActivities as $act)
                                    @php
                                        $isItemActive = (!empty($selectedCategory) && strcasecmp($selectedCategory, $act) === 0);
                                    @endphp
                                    <a href="{{ route('gallery.index', ['activity' => $act]) }}"
                                       x-show="!searchAct || '{{ strtolower(addslashes($act)) }}'.includes(searchAct.toLowerCase())"
                                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ $isItemActive ? 'bg-theme-primary text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                                        <div class="flex items-center gap-2.5 truncate pr-2">
                                            <i class="fas fa-tag text-[11px] {{ $isItemActive ? 'text-amber-300' : 'text-slate-400' }}"></i>
                                            <span class="truncate">{{ $act }}</span>
                                        </div>
                                        @if($isItemActive)
                                            <i class="fas fa-check text-xs text-amber-300 shrink-0"></i>
                                        @endif
                                    </a>
                                @endforeach

                            </div>
                        </div>

                    </div>
                </div>

                @if($galleries->isEmpty())
                    <!-- EMPTY STATE -->
                    <div class="bg-slate-50 rounded-3xl border border-slate-200/80 p-12 text-center shadow-xs max-w-xl mx-auto my-8">
                        <div class="w-16 h-16 bg-white text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-xs">
                            <i class="fas fa-images"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Foto Galeri</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mb-5">
                            @if(!empty($selectedCategory) && $selectedCategory !== 'Semua')
                                Belum ada foto kegiatan yang tersedia untuk "{{ $selectedCategory }}".
                            @else
                                Saat ini belum ada dokumentasi foto kegiatan yang dipublikasikan.
                            @endif
                        </p>
                        @if(!empty($selectedCategory) && $selectedCategory !== 'Semua')
                            <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-theme-primary text-white text-xs font-bold hover:opacity-95 transition shadow-sm">
                                <i class="fas fa-redo-alt text-[10px]"></i>
                                <span>Tampilkan Semua Galeri</span>
                            </a>
                        @endif
                    </div>
                @else
                    @php
                        $items = $galleries->items();
                        $itemCount = count($items);
                    @endphp

                    <!-- ASYMMETRICAL BENTO GRID CONTAINER (EXACT MATCHING MOCKUP) -->
                    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">
                        
                        <!-- ========================================================================= -->
                        <!-- LEFT COLUMN (SPAN 7 / ~60% WIDTH): TOP LARGE LANDSCAPE + 2 BOTTOM CARDS  -->
                        <!-- ========================================================================= -->
                        <div class="lg:col-span-7 flex flex-col gap-5 sm:gap-6">
                            
                            <!-- ITEM 0: TOP LARGE LANDSCAPE PHOTO -->
                            @if(isset($items[0]))
                                @php
                                    $img0 = $items[0];
                                    $imgUrl0 = $resolvePhotoUrl($img0->photo_url);
                                    $title0 = $img0->activity_name ?: 'Dokumentasi Kegiatan SMA Negeri 2 Situbondo';
                                    $date0 = $img0->activity_date ? \Illuminate\Support\Carbon::parse($img0->activity_date)->translatedFormat('d F Y') : '';
                                @endphp
                                <figure class="gallery-card group relative w-full h-64 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 bg-slate-100 cursor-pointer"
                                        data-aos="fade-up" data-aos-duration="600"
                                        itemscope itemtype="https://schema.org/ImageObject"
                                        @click="openLightbox('{{ $imgUrl0 }}', '{{ addslashes($title0) }}', '{{ $date0 }}')">
                                    
                                    <meta itemprop="contentUrl" content="{{ $imgUrl0 }}">
                                    <meta itemprop="name" content="{{ $title0 }}">
                                    <meta itemprop="description" content="Dokumentasi foto {{ $title0 }} di SMA Negeri 2 Situbondo">

                                    <div class="img-zoom-box w-full h-full skeleton-loader">
                                        <img src="{{ $imgUrl0 }}" 
                                             alt="Foto {{ $title0 }} - Galeri Dokumentasi Kegiatan SMA Negeri 2 Situbondo"
                                             title="{{ $title0 }} - SMAN 2 Situbondo"
                                             itemprop="thumbnail"
                                             fetchpriority="high"
                                             loading="eager"
                                             decoding="async"
                                             onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                             class="w-full h-full object-cover">
                                    </div>

                                    <!-- HOVER OVERLAY CAPTION -->
                                    <figcaption class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5 text-white">
                                        <span class="text-[11px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-theme-secondary text-slate-950 inline-block mb-1.5 shadow-2xs w-fit">
                                            {{ $date0 ?: 'Dokumentasi' }}
                                        </span>
                                        <h3 class="text-base sm:text-lg font-bold font-headline leading-tight line-clamp-2" itemprop="caption">
                                            {{ $title0 }}
                                        </h3>
                                        <div class="mt-2 inline-flex items-center gap-1.5 text-xs text-slate-200 font-semibold">
                                            <i class="fas fa-search-plus text-xs text-theme-secondary"></i>
                                            <span>Klik untuk memperbesar</span>
                                        </div>
                                    </figcaption>
                                </figure>
                            @endif

                            <!-- BOTTOM ROW: 2 LANDSCAPE CARDS SIDE-BY-SIDE -->
                            @if(isset($items[2]) || isset($items[3]))
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                                    
                                    <!-- ITEM 2: BOTTOM LEFT 1 -->
                                    @if(isset($items[2]))
                                        @php
                                            $img2 = $items[2];
                                            $imgUrl2 = $resolvePhotoUrl($img2->photo_url);
                                            $title2 = $img2->activity_name ?: 'Dokumentasi Kegiatan SMA Negeri 2 Situbondo';
                                            $date2 = $img2->activity_date ? \Illuminate\Support\Carbon::parse($img2->activity_date)->translatedFormat('d F Y') : '';
                                        @endphp
                                        <figure class="gallery-card group relative w-full h-52 sm:h-60 rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 bg-slate-100 cursor-pointer hover:border-theme-secondary/70 transition-all"
                                                data-aos="fade-up" data-aos-delay="100" data-aos-duration="600"
                                                itemscope itemtype="https://schema.org/ImageObject"
                                                @click="openLightbox('{{ $imgUrl2 }}', '{{ addslashes($title2) }}', '{{ $date2 }}')">
                                            <meta itemprop="contentUrl" content="{{ $imgUrl2 }}">
                                            <meta itemprop="name" content="{{ $title2 }}">
                                            <div class="img-zoom-box w-full h-full skeleton-loader">
                                                <img src="{{ $imgUrl2 }}" 
                                                     alt="Foto {{ $title2 }} - Galeri Kegiatan SMA Negeri 2 Situbondo"
                                                     title="{{ $title2 }} - SMAN 2 Situbondo"
                                                     itemprop="thumbnail"
                                                     loading="lazy"
                                                     decoding="async"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <figcaption class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
                                                <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-theme-secondary text-slate-950 inline-block mb-1 shadow-2xs w-fit">
                                                    {{ $date2 ?: 'Dokumentasi' }}
                                                </span>
                                                <h3 class="text-sm font-bold font-headline leading-tight line-clamp-2" itemprop="caption">
                                                    {{ $title2 }}
                                                </h3>
                                            </figcaption>
                                        </figure>
                                    @endif

                                    <!-- ITEM 3: BOTTOM LEFT 2 -->
                                    @if(isset($items[3]))
                                        @php
                                            $img3 = $items[3];
                                            $imgUrl3 = $resolvePhotoUrl($img3->photo_url);
                                            $title3 = $img3->activity_name ?: 'Dokumentasi Kegiatan SMA Negeri 2 Situbondo';
                                            $date3 = $img3->activity_date ? \Illuminate\Support\Carbon::parse($img3->activity_date)->translatedFormat('d F Y') : '';
                                        @endphp
                                        <figure class="gallery-card group relative w-full h-52 sm:h-60 rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 bg-slate-100 cursor-pointer hover:border-theme-secondary/70 transition-all"
                                                data-aos="fade-up" data-aos-delay="150" data-aos-duration="600"
                                                itemscope itemtype="https://schema.org/ImageObject"
                                                @click="openLightbox('{{ $imgUrl3 }}', '{{ addslashes($title3) }}', '{{ $date3 }}')">
                                            <meta itemprop="contentUrl" content="{{ $imgUrl3 }}">
                                            <meta itemprop="name" content="{{ $title3 }}">
                                            <div class="img-zoom-box w-full h-full skeleton-loader">
                                                <img src="{{ $imgUrl3 }}" 
                                                     alt="Foto {{ $title3 }} - Galeri Kegiatan SMA Negeri 2 Situbondo"
                                                     title="{{ $title3 }} - SMAN 2 Situbondo"
                                                     itemprop="thumbnail"
                                                     loading="lazy"
                                                     decoding="async"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <figcaption class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
                                                <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-theme-secondary text-slate-950 inline-block mb-1 shadow-2xs w-fit">
                                                    {{ $date3 ?: 'Dokumentasi' }}
                                                </span>
                                                <h3 class="text-sm font-bold font-headline leading-tight line-clamp-2" itemprop="caption">
                                                    {{ $title3 }}
                                                </h3>
                                            </figcaption>
                                        </figure>
                                    @endif

                                </div>
                            @endif

                        </div>

                        <!-- ========================================================================= -->
                        <!-- RIGHT COLUMN (SPAN 5 / ~40% WIDTH): TOP MEDIUM LANDSCAPE + TALL POSTER    -->
                        <!-- ========================================================================= -->
                        <div class="lg:col-span-5 flex flex-col gap-5 sm:gap-6">
                            
                            <!-- ITEM 1: TOP RIGHT MEDIUM LANDSCAPE -->
                            @if(isset($items[1]))
                                @php
                                    $img1 = $items[1];
                                    $imgUrl1 = $resolvePhotoUrl($img1->photo_url);
                                    $title1 = $img1->activity_name ?: 'Dokumentasi Kegiatan SMA Negeri 2 Situbondo';
                                    $date1 = $img1->activity_date ? \Illuminate\Support\Carbon::parse($img1->activity_date)->translatedFormat('d F Y') : '';
                                @endphp
                                <figure class="gallery-card group relative w-full h-52 sm:h-60 rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 bg-slate-100 cursor-pointer hover:border-theme-secondary/70 transition-all"
                                        data-aos="fade-up" data-aos-delay="50" data-aos-duration="600"
                                        itemscope itemtype="https://schema.org/ImageObject"
                                        @click="openLightbox('{{ $imgUrl1 }}', '{{ addslashes($title1) }}', '{{ $date1 }}')">
                                    <meta itemprop="contentUrl" content="{{ $imgUrl1 }}">
                                    <meta itemprop="name" content="{{ $title1 }}">
                                    <div class="img-zoom-box w-full h-full skeleton-loader">
                                        <img src="{{ $imgUrl1 }}" 
                                             alt="Foto {{ $title1 }} - Galeri Kegiatan SMA Negeri 2 Situbondo"
                                             title="{{ $title1 }} - SMAN 2 Situbondo"
                                             itemprop="thumbnail"
                                             loading="lazy"
                                             decoding="async"
                                             onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <figcaption class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
                                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-theme-secondary text-slate-950 inline-block mb-1 shadow-2xs w-fit">
                                            {{ $date1 ?: 'Dokumentasi' }}
                                        </span>
                                        <h3 class="text-sm font-bold font-headline leading-tight line-clamp-2" itemprop="caption">
                                            {{ $title1 }}
                                        </h3>
                                    </figcaption>
                                </figure>
                            @endif

                            <!-- ITEM 4: BOTTOM RIGHT TALL VERTICAL POSTER / INFOGRAPHIC -->
                            @if(isset($items[4]))
                                @php
                                    $img4 = $items[4];
                                    $imgUrl4 = $resolvePhotoUrl($img4->photo_url);
                                    $title4 = $img4->activity_name ?: 'Dokumentasi Kegiatan SMA Negeri 2 Situbondo';
                                    $date4 = $img4->activity_date ? \Illuminate\Support\Carbon::parse($img4->activity_date)->translatedFormat('d F Y') : '';
                                @endphp
                                <figure class="gallery-card group relative w-full h-80 sm:h-96 lg:h-[396px] rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 bg-slate-100 cursor-pointer hover:border-theme-secondary/70 transition-all"
                                        data-aos="fade-up" data-aos-delay="200" data-aos-duration="600"
                                        itemscope itemtype="https://schema.org/ImageObject"
                                        @click="openLightbox('{{ $imgUrl4 }}', '{{ addslashes($title4) }}', '{{ $date4 }}')">
                                    <meta itemprop="contentUrl" content="{{ $imgUrl4 }}">
                                    <meta itemprop="name" content="{{ $title4 }}">
                                    <div class="img-zoom-box w-full h-full skeleton-loader">
                                        <img src="{{ $imgUrl4 }}" 
                                             alt="Foto {{ $title4 }} - Galeri Kegiatan SMA Negeri 2 Situbondo"
                                             title="{{ $title4 }} - SMAN 2 Situbondo"
                                             itemprop="thumbnail"
                                             loading="lazy"
                                             decoding="async"
                                             onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <figcaption class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5 text-white">
                                        <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-theme-secondary text-slate-950 inline-block mb-1 shadow-2xs w-fit">
                                            {{ $date4 ?: 'Dokumentasi' }}
                                        </span>
                                        <h3 class="text-base font-bold font-headline leading-tight line-clamp-2" itemprop="caption">
                                            {{ $title4 }}
                                        </h3>
                                        <div class="mt-2 inline-flex items-center gap-1.5 text-xs text-slate-200 font-semibold">
                                            <i class="fas fa-search-plus text-xs text-theme-secondary"></i>
                                            <span>Klik untuk memperbesar</span>
                                        </div>
                                    </figcaption>
                                </figure>
                            @endif

                        </div>

                    </div>

                    <!-- FALLBACK FOR ADDITIONAL ITEMS (IF PAGE SIZE > 5) -->
                    @if($itemCount > 5)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 sm:gap-6 mt-6">
                            @for($i = 5; $i < $itemCount; $i++)
                                @php
                                    $extraImg = $items[$i];
                                    $extraUrl = $resolvePhotoUrl($extraImg->photo_url);
                                    $extraTitle = $extraImg->activity_name ?: 'Dokumentasi Kegiatan SMA Negeri 2 Situbondo';
                                    $extraDate = $extraImg->activity_date ? \Illuminate\Support\Carbon::parse($extraImg->activity_date)->translatedFormat('d F Y') : '';
                                @endphp
                                <figure class="gallery-card group relative w-full h-60 rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 bg-slate-100 cursor-pointer"
                                        data-aos="fade-up"
                                        itemscope itemtype="https://schema.org/ImageObject"
                                        @click="openLightbox('{{ $extraUrl }}', '{{ addslashes($extraTitle) }}', '{{ $extraDate }}')">
                                    <meta itemprop="contentUrl" content="{{ $extraUrl }}">
                                    <meta itemprop="name" content="{{ $extraTitle }}">
                                    <div class="img-zoom-box w-full h-full skeleton-loader">
                                        <img src="{{ $extraUrl }}" 
                                             alt="Foto {{ $extraTitle }} - Galeri Kegiatan SMA Negeri 2 Situbondo"
                                             title="{{ $extraTitle }} - SMAN 2 Situbondo"
                                             itemprop="thumbnail"
                                             loading="lazy"
                                             decoding="async"
                                             onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <figcaption class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
                                        <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider mb-0.5">
                                            {{ $extraDate ?: 'Dokumentasi' }}
                                        </span>
                                        <h3 class="text-sm font-bold font-headline leading-tight line-clamp-2" itemprop="caption">
                                            {{ $extraTitle }}
                                        </h3>
                                    </figcaption>
                                </figure>
                            @endfor
                        </div>
                    @endif

                    <!-- PAGINATION NAVIGATION -->
                    <div class="mt-8">
                        {{ $galleries->links('user.media.partials.pagination') }}
                    </div>

                @endif

            </div>
        </main>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 4. LIGHTBOX MODAL (PHOTO ZOOM & PREVIEW)                      -->
    <!-- ------------------------------------------------------------- -->
    <div x-show="lightboxOpen" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] bg-slate-950/90 backdrop-blur-md flex items-center justify-center p-4 sm:p-6"
         @click.self="closeLightbox()">
        
        <!-- Modal Top Action Buttons (Download & Close) -->
        <div class="absolute top-4 right-4 sm:top-6 sm:right-6 flex items-center gap-2.5 z-20">
            <!-- Download Button -->
            <button type="button" 
                    @click="downloadImage()"
                    title="Unduh Foto"
                    class="h-10 sm:h-11 px-3.5 sm:px-4 rounded-full bg-theme-secondary hover:scale-105 active:scale-95 text-slate-950 flex items-center gap-2 transition font-bold text-xs shadow-lg spring-hover cursor-pointer">
                <i class="fas fa-download text-xs"></i>
                <span class="hidden sm:inline">Unduh Foto</span>
            </button>

            <!-- Close Button -->
            <button type="button" 
                    @click="closeLightbox()"
                    title="Tutup"
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition border border-white/20 text-lg cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center"
             x-show="lightboxOpen"
             x-transition:enter="transition ease-out duration-300 transform-gpu"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform-gpu"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- High-Res Image -->
            <div class="rounded-2xl overflow-hidden shadow-2xl border border-white/10 max-h-[75vh] flex items-center justify-center bg-black/40">
                <img :src="activeImage" 
                     :alt="activeTitle" 
                     class="max-w-full max-h-[75vh] object-contain rounded-xl">
            </div>

            <!-- Modal Info Banner & Download Action -->
            <div class="mt-4 text-center text-white max-w-2xl px-4 flex flex-col items-center gap-3">
                <div>
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block mb-1" x-text="activeDate"></span>
                    <h3 class="text-base sm:text-lg font-bold font-headline" x-text="activeTitle"></h3>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap justify-center">
                    <button type="button" 
                            @click="downloadImage()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-theme-secondary text-slate-950 font-bold text-xs hover:opacity-95 active:scale-95 transition-all shadow-md cursor-pointer">
                        <i class="fas fa-download text-xs"></i>
                        <span>Download Gambar</span>
                    </button>
                    <a :href="activeImage" 
                       target="_blank" 
                       rel="noopener"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs transition border border-white/20">
                        <i class="fas fa-external-link-alt text-xs"></i>
                        <span>Buka Tab Baru</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. SHARED FOOTER COMPONENT                                    -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')
</div>
@endsection
