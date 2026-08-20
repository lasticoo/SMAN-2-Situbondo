@extends('layouts.app')

@php
    // Universal 10-Tier Thumbnail Resolver Engine for News
    $resolveThumbnailUrl = function (?string $thumbPath): string {
        static $resolvedCache = [];
        $cacheKey = $thumbPath ?? '';
        if (isset($resolvedCache[$cacheKey])) {
            return $resolvedCache[$cacheKey];
        }

        $clean = trim(str_replace('\\', '/', $thumbPath ?? ''));
        
        // 1. Data URI atau Full Web URL eksternal
        if (!empty($clean) && \Illuminate\Support\Str::startsWith($clean, ['http://', 'https://', '//', 'data:image/'])) {
            return $resolvedCache[$cacheKey] = $clean;
        }

        // 2. Absolute filesystem path on server
        if (!empty($clean)) {
            // a. C:\laragon\www\smada\storage\app\public\...
            $storageAppPublicPath = str_replace('\\', '/', storage_path('app/public'));
            if (\Illuminate\Support\Str::startsWith($clean, $storageAppPublicPath)) {
                $rel = ltrim(substr($clean, strlen($storageAppPublicPath)), '/');
                return $resolvedCache[$cacheKey] = \Illuminate\Support\Facades\Storage::disk('public')->url($rel);
            }

            // b. C:\laragon\www\smada\storage\...
            $storageBasePath = str_replace('\\', '/', storage_path());
            if (\Illuminate\Support\Str::startsWith($clean, $storageBasePath)) {
                $rel = ltrim(substr($clean, strlen($storageBasePath)), '/');
                if (\Illuminate\Support\Str::startsWith($rel, 'app/public/')) {
                    return $resolvedCache[$cacheKey] = \Illuminate\Support\Facades\Storage::disk('public')->url(substr($rel, 11));
                }
                return $resolvedCache[$cacheKey] = \Illuminate\Support\Facades\Storage::disk('public')->url($rel);
            }

            // c. C:\laragon\www\smada\public\...
            $publicBasePath = str_replace('\\', '/', public_path());
            if (\Illuminate\Support\Str::startsWith($clean, $publicBasePath)) {
                $rel = ltrim(substr($clean, strlen($publicBasePath)), '/');
                return $resolvedCache[$cacheKey] = asset($rel);
            }

            // d. C:\laragon\www\smada\...
            $baseProjectPath = str_replace('\\', '/', base_path());
            if (\Illuminate\Support\Str::startsWith($clean, $baseProjectPath)) {
                $rel = ltrim(substr($clean, strlen($baseProjectPath)), '/');
                if (\Illuminate\Support\Str::startsWith($rel, 'public/')) {
                    return $resolvedCache[$cacheKey] = asset(substr($rel, 7));
                }
                if (\Illuminate\Support\Str::startsWith($rel, 'storage/app/public/')) {
                    return $resolvedCache[$cacheKey] = \Illuminate\Support\Facades\Storage::disk('public')->url(substr($rel, 19));
                }
                if (\Illuminate\Support\Str::startsWith($rel, 'storage/')) {
                    return $resolvedCache[$cacheKey] = asset($rel);
                }
            }

            // 3. Leading slash
            if (\Illuminate\Support\Str::startsWith($clean, '/')) {
                $rel = ltrim($clean, '/');
                if (file_exists(public_path($rel))) {
                    return $resolvedCache[$cacheKey] = asset($rel);
                }
                if (\Illuminate\Support\Str::startsWith($rel, 'storage/') && \Illuminate\Support\Facades\Storage::disk('public')->exists(substr($rel, 8))) {
                    return $resolvedCache[$cacheKey] = \Illuminate\Support\Facades\Storage::disk('public')->url(substr($rel, 8));
                }
                return $resolvedCache[$cacheKey] = asset($rel);
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
                    return $resolvedCache[$cacheKey] = asset($clean);
                }
                $storageRel = substr($clean, 8);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($storageRel)) {
                    return $resolvedCache[$cacheKey] = \Illuminate\Support\Facades\Storage::disk('public')->url($storageRel);
                }
                return $resolvedCache[$cacheKey] = asset($clean);
            }

            // 6. Direct public directory
            if (file_exists(public_path($clean))) {
                return $resolvedCache[$cacheKey] = asset($clean);
            }

            // 7. Laravel Storage 'public' disk
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($clean)) {
                return $resolvedCache[$cacheKey] = \Illuminate\Support\Facades\Storage::disk('public')->url($clean);
            }

            // 8. Subfolders
            $subfolders = ['news/', 'berita/', 'announcement/', 'announcements/', 'pengumuman/', 'images/static/', 'images/', 'build/assets/'];
            foreach ($subfolders as $folder) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($folder . $clean)) {
                    return $resolvedCache[$cacheKey] = \Illuminate\Support\Facades\Storage::disk('public')->url($folder . $clean);
                }
                if (file_exists(public_path($folder . $clean))) {
                    return $resolvedCache[$cacheKey] = asset($folder . $clean);
                }
            }
        }

        // 9. Static School Placeholder (Fallback Terakhir)
        return $resolvedCache[$cacheKey] = asset('images/static/gambar_profile_statis.jpg');
    };

    $ogImage = (isset($newsList[0])) 
        ? $resolveThumbnailUrl($newsList[0]->thumbnail_url) 
        : asset('images/static/gambar_profile_statis.jpg');

    // Dynamic SEO Title & Description
    if (!empty($selectedCategory)) {
        $seoTitle = 'Berita Kategori ' . $selectedCategory . ' - SMA Negeri 2 Situbondo';
        $seoDescription = 'Kumpulan berita dan informasi resmi seputar kegiatan ' . $selectedCategory . ' di SMAN 2 Situbondo.';
    } elseif (!empty($searchKeyword)) {
        $seoTitle = 'Pencarian: "' . $searchKeyword . '" - Berita SMADA SMAN 2 Situbondo';
        $seoDescription = 'Hasil pencarian berita dan liputan kegiatan civitas akademika SMAN 2 Situbondo untuk kata kunci "' . $searchKeyword . '".';
    } else {
        $seoTitle = 'Berita SMADA - SMA Negeri 2 Situbondo';
        $seoDescription = 'Ikuti perkembangan terbaru, prestasi membanggakan, dan kegiatan inspiratif dari civitas akademika SMAN 2 Situbondo.';
    }

    // JSON-LD Schema Items for News Article List
    $schemaItems = [];
    foreach ($newsList as $idx => $item) {
        $pDate = $item->published_at ?? $item->created_at ?? \Carbon\Carbon::now();
        $uDate = $item->updated_at ?? $item->created_at ?? \Carbon\Carbon::now();
        $schemaItems[] = [
            '@type' => 'ListItem',
            'position' => $idx + 1,
            'item' => [
                '@type' => 'NewsArticle',
                'headline' => $item->title,
                'description' => $item->summary ?: \Illuminate\Support\Str::limit(strip_tags($item->content), 160),
                'url' => route('news.show', $item->id),
                'image' => $resolveThumbnailUrl($item->thumbnail_url),
                'datePublished' => $pDate instanceof \Carbon\Carbon ? $pDate->toIso8601String() : \Carbon\Carbon::parse($pDate)->toIso8601String(),
                'dateModified' => $uDate instanceof \Carbon\Carbon ? $uDate->toIso8601String() : \Carbon\Carbon::parse($uDate)->toIso8601String(),
                'articleSection' => $item->category ?: 'Informasi',
                'inLanguage' => 'id-ID',
                'author' => [
                    '@type' => 'Person',
                    'name' => $item->author->name ?? 'Humas SMADA',
                ],
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
        ];
    }

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'CollectionPage',
                '@id' => route('news.index') . '#collection',
                'name' => $seoTitle,
                'description' => $seoDescription,
                'url' => route('news.index'),
                'inLanguage' => 'id-ID',
                'publisher' => [
                    '@type' => 'EducationalOrganization',
                    'name' => 'SMA Negeri 2 Situbondo',
                    'url' => url('/'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('images/static/gambar_profile_statis.jpg'),
                    ],
                ],
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'itemListElement' => $schemaItems,
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => route('news.index') . '#breadcrumb',
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
                        'name' => 'Berita',
                        'item' => route('news.index'),
                    ],
                ],
            ],
        ],
    ];
@endphp

@section('title', $seoTitle)

@push('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="author" content="SMA Negeri 2 Situbondo">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot-news" content="index, follow">
    <link rel="canonical" href="{{ route('news.index') }}">
    @if(isset($newsList[0]))
        <link rel="preload" as="image" href="{{ $ogImage }}" fetchpriority="high">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SMA Negeri 2 Situbondo">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ route('news.index') }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Schema.org JSON-LD for Google Search & Google News Collection -->
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@push('styles')
<style>
    /* =========================================================================
       DYNAMIC THEME CSS VARIABLES (SYNCHRONIZED WITH PENGUMUMAN PATTERN)
       ========================================================================= */
    :root {
        --primary-main: {{ $colorSetting?->primary_color ?? '#1B3C73' }};
        --secondary-gold: {{ $colorSetting?->secondary_color ?? '#F19E38' }};
        --secondary-main: {{ $colorSetting?->secondary_color ?? '#F19E38' }};
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
    .hover-border-primary:hover { border-color: var(--primary-main) !important; }

    .hover-text-secondary:hover { color: var(--secondary-gold) !important; }
    .hover-bg-secondary:hover { background-color: var(--secondary-gold) !important; }

    /* Hardware Acceleration & Micro-Animations */
    .spring-hover, .news-card, .img-zoom-box img {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
    }

    .news-card {
        contain: layout paint;
        content-visibility: auto;
        contain-intrinsic-size: 380px;
    }

    @media (prefers-reduced-motion: no-preference) {
        .news-card {
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.35s ease !important;
        }
        .news-card:hover {
            transform: translateY(-5px) scale(1.01) translate3d(0, 0, 0) !important;
            box-shadow: 0 20px 35px -10px rgba(0, 43, 102, 0.12), 0 10px 20px -5px rgba(241, 158, 56, 0.10) !important;
        }
    }

    /* Image Zoom Reveal */
    .img-zoom-box {
        overflow: hidden;
        contain: paint;
    }
    .img-zoom-box img {
        transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1) !important;
        will-change: transform;
    }
    .news-card:hover .img-zoom-box img {
        transform: scale(1.08) translate3d(0, 0, 0) !important;
    }

    /* No Scrollbar Utility for Touch Drag/Scroll Bars */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col justify-between overflow-x-hidden w-full max-w-full">
    
    <div class="w-full">
        <!-- ------------------------------------------------------------- -->
        <!-- 1. SHARED HEADER & NAVBAR COMPONENT                           -->
        <!-- ------------------------------------------------------------- -->
        @include('user.partials.navbar')

        <!-- ------------------------------------------------------------- -->
        <!-- 2. HERO BANNER SECTION (MATCHING EXACT DESIGN MOCKUP)         -->
        <!-- ------------------------------------------------------------- -->
        <header class="bg-theme-gradient text-white py-14 sm:py-16 md:py-20 relative overflow-hidden w-full">
            <!-- Ambient Subtle Lighting & Shapes -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-theme-secondary/20 blur-3xl pointer-events-none"></div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center max-w-4xl" data-aos="fade-up" data-aos-duration="600">
                
                <!-- Ambient Hero Badge "Pusat Informasi" -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold mb-4 shadow-xs" data-aos="fade-down" data-aos-duration="600">
                    <span class="w-2 h-2 rounded-full bg-theme-secondary animate-pulse"></span>
                    <span class="text-white/95 font-medium tracking-wide">Pusat Informasi</span>
                </div>

                <!-- Main Title "Berita SMADA" in Clean Bold White (Single H1 on Page) -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white font-headline tracking-tight mb-3 sm:mb-4">
                    Berita SMADA
                </h1>
                
                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm md:text-base text-slate-200 leading-relaxed max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="150">
                    Ikuti perkembangan terbaru, prestasi membanggakan, dan kegiatan inspiratif dari civitas akademika SMAN 2 Situbondo.
                </p>

            </div>
        </header>

        <!-- ------------------------------------------------------------- -->
        <!-- 3. MAIN ASYMMETRICAL 2-COLUMN NEWS CONTENT & SIDEBAR SECTION  -->
        <!-- ------------------------------------------------------------- -->
        <main class="bg-white py-8 sm:py-10 md:py-14 w-full">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    
                    <!-- ========================================================= -->
                    <!-- LEFT COLUMN: MAIN NEWS CONTENT (8 COLS)                   -->
                    <!-- ========================================================= -->
                    <div class="lg:col-span-8 flex flex-col space-y-5 sm:space-y-6">
                        
                        <!-- Mobile & Tablet Quick Category Filter Strip (lg:hidden) -->
                        <div class="lg:hidden mb-1" data-aos="fade-down" data-aos-duration="400">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <i class="fas fa-th-large text-theme-secondary text-[11px]"></i>
                                    <span>Pilih Kategori:</span>
                                </span>
                                @if(!empty($selectedCategory))
                                    <a href="{{ route('news.index', request()->except('category', 'kategori', 'page')) }}" class="text-[11px] font-bold text-theme-secondary hover:underline">
                                        Reset Kategori
                                    </a>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-2 pt-0.5 -mx-4 px-4 sm:-mx-6 sm:px-6">
                                @foreach ($categoryCounts as $categoryName => $count)
                                    @php
                                        $isAll = in_array(strtolower($categoryName), ['all', 'semua', 'semua berita']);
                                        $isActive = ($isAll && empty($selectedCategory)) || (strcasecmp($selectedCategory, $categoryName) === 0);
                                        
                                        $catLower = strtolower($categoryName);
                                        if ($isAll) {
                                            $catIcon = 'fa-infinity';
                                        } elseif (str_contains($catLower, 'akademik') || str_contains($catLower, 'kurikulum')) {
                                            $catIcon = 'fa-graduation-cap';
                                        } elseif (str_contains($catLower, 'prestasi') || str_contains($catLower, 'juara')) {
                                            $catIcon = 'fa-trophy';
                                        } elseif (str_contains($catLower, 'event') || str_contains($catLower, 'kegiatan')) {
                                            $catIcon = 'fa-calendar-alt';
                                        } elseif (str_contains($catLower, 'kesiswaan') || str_contains($catLower, 'osis')) {
                                            $catIcon = 'fa-users';
                                        } else {
                                            $catIcon = 'fa-newspaper';
                                        }
                                    @endphp

                                    <a href="{{ $isAll ? route('news.index', request()->except('category', 'kategori', 'page')) : route('news.index', array_merge(request()->except('page'), ['category' => $categoryName])) }}" 
                                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all duration-200 shrink-0 {{ $isActive ? 'bg-theme-primary text-white shadow-xs ring-2 ring-theme-primary/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200/80' }}">
                                        <i class="fas {{ $catIcon }} text-[11px] {{ $isActive ? 'text-theme-secondary' : 'text-slate-400' }}"></i>
                                        <span>{{ $categoryName }}</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-black {{ $isActive ? 'bg-white text-theme-primary' : 'bg-white/90 text-slate-700 border border-slate-200/60' }}">
                                            {{ $count }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Top Control Bar (Search Input + Sort Dropdown) -->
                        <div class="bg-slate-50/70 rounded-2xl border border-slate-200/90 p-3 sm:p-4 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-2xs relative z-30"
                             data-aos="fade-up" data-aos-duration="400">
                            
                            <!-- Search Input Box -->
                            <form action="{{ route('news.index') }}" method="GET" class="relative w-full sm:max-w-md flex items-center">
                                @if(!empty($selectedCategory))
                                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                                @endif
                                @if(!empty($sortOrder) && $sortOrder !== 'terbaru')
                                    <input type="hidden" name="sort" value="{{ $sortOrder }}">
                                @endif
                                
                                <div class="absolute left-3.5 text-slate-400 pointer-events-none flex items-center">
                                    <i class="fas fa-search text-xs"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ $searchKeyword }}" 
                                       placeholder="Cari judul berita..." 
                                       aria-label="Cari judul berita"
                                       class="w-full pl-9 pr-4 py-2 bg-white rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400 border border-slate-200 focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs">
                            </form>

                            <!-- Sort Dropdown Menu -->
                            <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                <span class="text-xs font-bold text-slate-600 hidden sm:inline">Urutkan:</span>
                                <div class="relative" x-data="{ sortOpen: false }" @click.away="sortOpen = false">
                                    <button type="button" 
                                            @click="sortOpen = !sortOpen"
                                            class="inline-flex items-center gap-2 px-3.5 py-2 bg-white rounded-xl text-xs font-bold text-slate-800 border border-slate-200 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                                        <span class="text-slate-500 sm:hidden">Urutkan:</span>
                                        <span class="text-slate-900">{{ $sortOrder === 'terlama' ? 'Terlama' : 'Terbaru' }}</span>
                                        <i class="fas fa-chevron-down text-[9px] text-slate-500 transition-transform duration-200" :class="sortOpen ? 'rotate-180' : ''"></i>
                                    </button>
                                    
                                    <div x-show="sortOpen" 
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-150 transform-gpu"
                                         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave="transition ease-in duration-100 transform-gpu"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                         style="z-index: 100;"
                                         class="absolute right-0 top-full mt-1.5 w-40 bg-white rounded-xl shadow-xl border border-slate-200 p-1.5">
                                        <a href="{{ route('news.index', array_merge(request()->query(), ['sort' => 'terbaru'])) }}" 
                                           class="flex items-center justify-between px-3 py-2 text-xs font-bold rounded-lg transition {{ $sortOrder !== 'terlama' ? 'bg-theme-primary text-white shadow-2xs' : 'text-slate-800 hover:bg-slate-100' }}">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-arrow-down-wide-short text-[11px] {{ $sortOrder !== 'terlama' ? 'text-white' : 'text-slate-400' }}"></i>
                                                <span>Terbaru</span>
                                            </div>
                                            @if($sortOrder !== 'terlama')
                                                <i class="fas fa-check text-[10px]"></i>
                                            @endif
                                        </a>
                                        <a href="{{ route('news.index', array_merge(request()->query(), ['sort' => 'terlama'])) }}" 
                                           class="flex items-center justify-between px-3 py-2 text-xs font-bold rounded-lg transition {{ $sortOrder === 'terlama' ? 'bg-theme-primary text-white shadow-2xs' : 'text-slate-800 hover:bg-slate-100' }}">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-arrow-up-wide-short text-[11px] {{ $sortOrder === 'terlama' ? 'text-white' : 'text-slate-400' }}"></i>
                                                <span>Terlama</span>
                                            </div>
                                            @if($sortOrder === 'terlama')
                                                <i class="fas fa-check text-[10px]"></i>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Active Filter Badge Pill Alert (if searched or filtered) -->
                        @if(!empty($selectedCategory) || !empty($searchKeyword))
                            <div class="flex items-center gap-2 flex-wrap text-xs text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-200/90 shadow-2xs">
                                <span class="font-bold text-slate-600">Menampilkan filter:</span>
                                @if(!empty($selectedCategory))
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-theme-primary-light text-theme-primary border border-theme-primary/30 font-extrabold shadow-2xs">
                                        <span>Kategori: {{ $selectedCategory }}</span>
                                        <a href="{{ route('news.index', array_merge(request()->except('category', 'kategori', 'page'))) }}" class="text-slate-400 hover:text-red-600 transition" title="Hapus filter kategori"><i class="fas fa-times"></i></a>
                                    </span>
                                @endif
                                @if(!empty($searchKeyword))
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-100/90 text-amber-950 border border-amber-300 font-extrabold shadow-2xs">
                                        <span>Cari: "{{ $searchKeyword }}"</span>
                                        <a href="{{ route('news.index', array_merge(request()->except('search', 'q', 'page'))) }}" class="text-amber-700 hover:text-red-600 transition" title="Hapus pencarian"><i class="fas fa-times"></i></a>
                                    </span>
                                @endif
                                <a href="{{ route('news.index') }}" class="text-xs font-extrabold text-slate-700 hover:text-red-600 hover:underline ml-auto transition">Reset Semua</a>
                            </div>
                        @endif

                        <!-- News Articles Content Grid -->
                        @if($newsList->isEmpty())
                            <!-- Empty State -->
                            <div class="bg-slate-50 rounded-3xl border border-slate-200/80 p-12 text-center shadow-xs my-6">
                                <div class="w-16 h-16 bg-white text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-xs">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800 mb-1">Tidak Ada Berita Ditemukan</h3>
                                <p class="text-xs text-slate-500 max-w-md mx-auto mb-5">
                                    @if(!empty($searchKeyword) || !empty($selectedCategory))
                                        Tidak ada artikel berita yang cocok dengan kriteria pencarian atau filter yang Anda pilih.
                                    @else
                                        Saat ini belum ada berita yang dipublikasikan.
                                    @endif
                                </p>
                                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-theme-primary text-white text-xs font-bold hover:opacity-95 transition shadow-sm">
                                    <i class="fas fa-redo-alt text-[10px]"></i>
                                    <span>Tampilkan Semua Berita</span>
                                </a>
                            </div>
                        @else
                            <!-- 2-Column Multi-Card Grid (Exact Match Mockup) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-7 items-stretch">
                                @foreach ($newsList as $index => $item)
                                    @php
                                        $thumbUrl = $resolveThumbnailUrl($item->thumbnail_url);
                                        $pubDate = $item->published_at ?? $item->created_at ?? \Carbon\Carbon::now();
                                        $dayNum = $pubDate->format('d');
                                        
                                        $monthsMap = [
                                            1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR', 5 => 'MEI', 6 => 'JUN',
                                            7 => 'JUL', 8 => 'AGT', 9 => 'SEP', 10 => 'OKT', 11 => 'NOV', 12 => 'DES'
                                        ];
                                        $monthShort = $monthsMap[$pubDate->month] ?? $pubDate->format('M');
                                        
                                        $cat = $item->category ?: 'Informasi';
                                        $catLower = strtolower($cat);
                                        
                                        // Badge Pastel Color Configuration (High Contrast Text)
                                        if (str_contains($catLower, 'prestasi') || str_contains($catLower, 'juara')) {
                                            $badgeClass = 'bg-amber-50 text-amber-900 border-amber-300';
                                        } elseif (str_contains($catLower, 'akademik') || str_contains($catLower, 'kurikulum') || str_contains($catLower, 'ujian')) {
                                            $badgeClass = 'bg-blue-50 text-blue-900 border-blue-300';
                                        } elseif (str_contains($catLower, 'event') || str_contains($catLower, 'kegiatan') || str_contains($catLower, 'lomba')) {
                                            $badgeClass = 'bg-orange-50 text-orange-950 border-orange-300';
                                        } elseif (str_contains($catLower, 'kesiswaan') || str_contains($catLower, 'osis') || str_contains($catLower, 'ekstra')) {
                                            $badgeClass = 'bg-indigo-50 text-indigo-900 border-indigo-300';
                                        } else {
                                            $badgeClass = 'bg-emerald-50 text-emerald-950 border-emerald-300';
                                        }
                                    @endphp

                                    <!-- News Article Card Component (Semantic Article + Itemscope) -->
                                    <article class="news-card group relative flex flex-col justify-between bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-theme-primary/40 overflow-hidden"
                                             data-aos="fade-up"
                                             data-aos-delay="{{ ($index % 2) * 100 }}"
                                             data-aos-duration="600"
                                             itemscope itemtype="https://schema.org/NewsArticle">
                                        
                                        <div>
                                            <!-- Top Thumbnail Area with Floating Date Badge -->
                                            <div class="aspect-[16/10] w-full overflow-hidden relative bg-slate-100 img-zoom-box">
                                                <a href="{{ route('news.show', $item->id) }}" class="block w-full h-full" tabindex="-1" aria-hidden="true">
                                                    <img src="{{ $thumbUrl }}" 
                                                         alt="Foto Berita: {{ $item->title }} - SMAN 2 Situbondo" 
                                                         title="{{ $item->title }}"
                                                         {{ $index === 0 ? 'fetchpriority=high loading=eager' : 'loading=lazy' }}
                                                         decoding="async"
                                                         itemprop="image"
                                                         onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                                         class="w-full h-full object-cover">
                                                </a>

                                                <!-- Floating Date Badge (Top-Left of Image) -->
                                                <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-md rounded-xl shadow-md px-2.5 py-1.5 text-center border border-white/60 pointer-events-none flex flex-col items-center justify-center min-w-[44px]">
                                                    <span class="text-base sm:text-lg font-black text-slate-900 leading-none">{{ $dayNum }}</span>
                                                    <span class="text-[9px] font-black tracking-wider text-theme-secondary uppercase mt-0.5">{{ $monthShort }}</span>
                                                </div>
                                            </div>

                                            <!-- Card Body Info -->
                                            <div class="p-5 sm:p-6">
                                                <!-- Category Badge -->
                                                <div class="mb-2.5">
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] sm:text-[11px] font-black uppercase tracking-wider border {{ $badgeClass }}" itemprop="articleSection">
                                                        {{ $cat }}
                                                    </span>
                                                </div>

                                                <!-- News Title (Semantic H2 with Proper Hierarchy) -->
                                                <h2 class="text-base sm:text-lg font-extrabold font-headline text-slate-900 line-clamp-2 leading-snug group-hover:text-theme-primary transition-colors mb-2.5" itemprop="headline">
                                                    <a href="{{ route('news.show', $item->id) }}" class="hover:underline">
                                                        {{ $item->title }}
                                                    </a>
                                                </h2>

                                                <!-- News Summary (Clamped Excerpt) -->
                                                <p class="text-xs sm:text-sm text-slate-500 line-clamp-3 leading-relaxed font-normal" itemprop="description">
                                                    {{ $item->summary ?: \Illuminate\Support\Str::limit(strip_tags($item->content), 120) }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Card Footer: "Baca Selengkapnya →" Action Link -->
                                        <div class="px-5 pb-5 sm:px-6 sm:pb-6 pt-0 mt-auto">
                                            <a href="{{ route('news.show', $item->id) }}" 
                                               class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-theme-secondary hover:text-theme-primary group-hover:translate-x-1 transition-all duration-200">
                                                <span>Baca Selengkapnya</span>
                                                <i class="fas fa-arrow-right text-[11px]"></i>
                                            </a>
                                        </div>

                                    </article>
                                @endforeach
                            </div>

                            <!-- Centered Pagination Navigation Component -->
                            <nav aria-label="Navigasi Halaman Berita" class="mt-10 pt-4 flex justify-center">
                                {{ $newsList->links('user.news.partials.pagination') }}
                            </nav>
                        @endif

                    </div>

                    <!-- ========================================================= -->
                    <!-- RIGHT COLUMN: SIDEBAR NAVIGATION & CATEGORIES (4 COLS)    -->
                    <!-- ========================================================= -->
                    <aside class="hidden lg:flex lg:col-span-4 flex-col space-y-6 lg:sticky lg:top-24">
                        
                        <!-- Sidebar Category Card Container (Exact Match Mockup) -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs" data-aos="fade-left" data-aos-duration="600">
                            
                            <!-- Header: Icon + "Kategori" Title (Semantic H2) -->
                            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100 mb-4">
                                <div class="w-8 h-8 rounded-lg bg-theme-secondary/15 text-theme-secondary flex items-center justify-center text-sm shadow-2xs">
                                    <i class="fas fa-th-large"></i>
                                </div>
                                <h2 class="text-base font-extrabold font-headline text-slate-900">
                                    Kategori
                                </h2>
                            </div>

                            <!-- Category Links List with Dynamic Counts & High-Contrast Theme -->
                            <nav aria-label="Kategori Berita" class="space-y-1.5">
                                @foreach ($categoryCounts as $categoryName => $count)
                                    @php
                                        $isAll = in_array(strtolower($categoryName), ['all', 'semua', 'semua berita']);
                                        $isActive = ($isAll && empty($selectedCategory)) || (strcasecmp($selectedCategory, $categoryName) === 0);
                                        
                                        // Category Icon Assignment
                                        $catLower = strtolower($categoryName);
                                        if ($isAll) {
                                            $catIcon = 'fa-infinity';
                                        } elseif (str_contains($catLower, 'akademik') || str_contains($catLower, 'kurikulum')) {
                                            $catIcon = 'fa-graduation-cap';
                                        } elseif (str_contains($catLower, 'prestasi') || str_contains($catLower, 'juara')) {
                                            $catIcon = 'fa-trophy';
                                        } elseif (str_contains($catLower, 'event') || str_contains($catLower, 'kegiatan')) {
                                            $catIcon = 'fa-calendar-alt';
                                        } elseif (str_contains($catLower, 'kesiswaan') || str_contains($catLower, 'osis')) {
                                            $catIcon = 'fa-users';
                                        } else {
                                            $catIcon = 'fa-newspaper';
                                        }
                                    @endphp

                                    <a href="{{ $isAll ? route('news.index', request()->except('category', 'kategori', 'page')) : route('news.index', array_merge(request()->except('page'), ['category' => $categoryName])) }}" 
                                       class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 {{ $isActive ? 'bg-theme-primary-light text-theme-primary border border-theme-primary/30 shadow-2xs font-extrabold' : 'text-slate-700 hover:bg-slate-50 hover:text-theme-primary font-bold border border-transparent' }}">
                                        
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <i class="fas {{ $catIcon }} text-xs {{ $isActive ? 'text-theme-secondary' : 'text-slate-400 group-hover:text-theme-primary' }} shrink-0"></i>
                                            <span class="truncate {{ $isActive ? 'text-theme-primary font-extrabold' : 'text-slate-700 group-hover:text-theme-primary' }}">{{ $categoryName }}</span>
                                        </div>

                                        <span class="text-[11px] px-2.5 py-0.5 rounded-full font-black transition-colors shrink-0 ml-2 {{ $isActive ? 'bg-white text-theme-primary border border-theme-primary/20 shadow-2xs' : 'bg-slate-100 text-slate-600 group-hover:bg-slate-200/80 group-hover:text-slate-800' }}">
                                            {{ $count }}
                                        </span>
                                    </a>
                                @endforeach
                            </nav>

                            <!-- Horizontal Divider -->
                            <div class="my-5 border-t border-slate-100"></div>

                            <!-- Additional Sidebar Search Form "Cari Berita" -->
                            <div>
                                <label for="sidebar-news-search" class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">
                                    Cari Berita
                                </label>
                                <form action="{{ route('news.index') }}" method="GET" class="relative flex items-center">
                                    @if(!empty($selectedCategory))
                                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                                    @endif
                                    <div class="absolute left-3 text-slate-400 pointer-events-none flex items-center">
                                        <i class="fas fa-search text-xs"></i>
                                    </div>
                                    <input type="text" 
                                           id="sidebar-news-search"
                                           name="search" 
                                           value="{{ $searchKeyword }}" 
                                           placeholder="Masukkan kata kunci..." 
                                           class="w-full pl-8 pr-3 py-2 bg-slate-50/70 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 border border-slate-200 focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs">
                                </form>
                            </div>

                        </div>

                    </aside>

                </div>

            </div>
        </main>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 4. SHARED FOOTER COMPONENT                                    -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')

</div>
@endsection
