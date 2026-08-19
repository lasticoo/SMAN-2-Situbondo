@extends('layouts.app')

@section('title', 'Pengumuman & Agenda Resmi - SMA Negeri 2 Situbondo')

@push('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="Informasi dan pengumuman resmi terbaru mengenai kegiatan akademik, kesiswaan, dan agenda penting SMA Negeri 2 Situbondo.">
    <meta name="author" content="SMA Negeri 2 Situbondo">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <link rel="canonical" href="{{ route('announcement.index') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SMA Negeri 2 Situbondo">
    <meta property="og:title" content="Pengumuman & Agenda Resmi - SMA Negeri 2 Situbondo">
    <meta property="og:description" content="Informasi dan pengumuman resmi terbaru mengenai kegiatan akademik, kesiswaan, dan agenda penting SMA Negeri 2 Situbondo.">
    <meta property="og:url" content="{{ route('announcement.index') }}">
    <meta property="og:image" content="{{ asset('images/static/gambar_profile_statis.jpg') }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Pengumuman & Agenda Resmi - SMA Negeri 2 Situbondo">
    <meta name="twitter:description" content="Informasi dan pengumuman resmi terbaru mengenai kegiatan akademik, kesiswaan, dan agenda penting SMA Negeri 2 Situbondo.">
    <meta name="twitter:image" content="{{ asset('images/static/gambar_profile_statis.jpg') }}">

    <!-- Schema.org JSON-LD Structured Data for CollectionPage & Breadcrumbs -->
    @php
        $indexJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'Pengumuman & Agenda Resmi SMA Negeri 2 Situbondo',
            'description' => 'Direktori pengumuman resmi dan agenda kegiatan SMA Negeri 2 Situbondo.',
            'url' => route('announcement.index'),
            'inLanguage' => 'id-ID',
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'SMA Negeri 2 Situbondo',
                'url' => url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/static/gambar_profile_statis.jpg'),
                ],
            ],
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
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
                        'name' => 'Pengumuman',
                        'item' => route('announcement.index'),
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($indexJsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
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
    .hover-border-primary:hover { border-color: var(--primary-main) !important; }

    .hover-text-secondary:hover { color: var(--secondary-gold) !important; }
    .hover-bg-secondary:hover { background-color: var(--secondary-gold) !important; }

    /* Hardware Acceleration (GPU Layer Compositing) for 60FPS Micro-Animations */
    .spring-hover, .animate-float, .glow-pulse, .img-zoom-box img {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        perspective: 1000px;
    }

    /* Ultra-Smooth Spring Physics Hover Scaling */
    @media (prefers-reduced-motion: no-preference) {
        .spring-hover {
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.35s ease !important;
            will-change: transform, box-shadow;
        }
        .spring-hover:hover {
            transform: translateY(-5px) scale(1.008) translate3d(0, 0, 0) !important;
            box-shadow: 0 20px 35px -10px rgba(0, 28, 77, 0.12), 0 10px 20px -5px rgba(245, 158, 11, 0.14) !important;
        }
    }

    /* CSS Containment & Image Zoom Reveal Container */
    .img-zoom-box {
        overflow: hidden;
        contain: paint;
    }
    .img-zoom-box img {
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) !important;
        will-change: transform;
    }
    .img-zoom-box:hover img {
        transform: scale(1.08) translate3d(0, 0, 0) !important;
    }

    /* Optimized Floating Micro-Animation (GPU Transform Only) */
    @keyframes subtle-float {
        0%, 100% { transform: translateY(0) translate3d(0, 0, 0); }
        50% { transform: translateY(-6px) translate3d(0, 0, 0); }
    }
    .animate-float {
        animation: subtle-float 4.5s ease-in-out infinite;
        will-change: transform;
    }

    /* Pulse Glow Ring */
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
        50% { box-shadow: 0 0 20px 6px rgba(245, 158, 11, 0.55); }
    }
    .glow-pulse {
        animation: pulse-glow 3s infinite;
    }

    /* Content Visibility for Offscreen Rendering Performance */
    .announcement-card {
        content-visibility: auto;
        contain-intrinsic-size: 1px 220px;
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
        <!-- 2. HERO BANNER SECTION (MATCHING DESIGN REFERENCE MOCKUP)      -->
        <!-- ------------------------------------------------------------- -->
        <section class="bg-theme-gradient text-white py-12 sm:py-16 md:py-20 relative overflow-hidden w-full">
            <!-- Ambient Subtle Lighting & Shapes -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-theme-secondary/20 blur-3xl pointer-events-none"></div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center max-w-4xl" data-aos="fade-up" data-aos-duration="600">
                
                <!-- Pill Badge "Pusat Informasi" -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-theme-secondary text-xs font-extrabold uppercase tracking-wider mb-4 shadow-xs" data-aos="zoom-in" data-aos-delay="100">
                    <i class="fas fa-bullhorn text-[11px] animate-pulse"></i>
                    <span>Pusat Informasi</span>
                </div>
                
                <!-- Main Title "Pengumuman Resmi" -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white font-headline tracking-tight mb-4">
                    Pengumuman Resmi
                </h1>
                
                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm md:text-base text-slate-200 leading-relaxed max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="200">
                    Dapatkan informasi terbaru dan terpercaya mengenai kegiatan akademik, kesiswaan, dan agenda penting SMA Negeri 2 Situbondo.
                </p>

            </div>
        </section>

        <!-- ------------------------------------------------------------- -->
        <!-- 3. MAIN CONTENT CONTAINER (WITH DASHED BORDER)                -->
        <!-- ------------------------------------------------------------- -->
        <main class="bg-slate-50/70 py-10 sm:py-14 relative overflow-hidden w-full">
            <!-- Ambient Background Glows -->
            <div class="absolute top-10 right-10 w-96 h-96 rounded-full bg-theme-primary-light blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-10 left-10 w-80 h-80 rounded-full bg-amber-500/5 blur-3xl pointer-events-none"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                
                <!-- OUTER FRAME WITH DASHED BORDER (MATCHING VISUAL MOCKUP) -->
                <div class="border-2 border-dashed border-theme-primary/35 rounded-3xl p-4 sm:p-7 md:p-9 bg-white/70 backdrop-blur-md shadow-sm relative w-full" data-aos="fade-up" data-aos-duration="600">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                        
                        <!-- ========================================================================= -->
                        <!-- LEFT SIDEBAR: KATEGORI & FORMULIR PENCARIAN -->
                        <!-- ========================================================================= -->
                        <aside class="lg:col-span-4 xl:col-span-3 w-full min-w-0" data-aos="fade-right" data-aos-duration="500">
                            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-5 sticky top-24">
                                
                                <!-- KATEGORI HEADER -->
                                <div class="flex items-center gap-2 mb-4 pb-1">
                                    <div class="w-6 h-6 rounded-md bg-amber-50 flex items-center justify-center text-theme-secondary text-sm">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <h2 class="text-base font-extrabold text-slate-900 font-headline tracking-wide">Kategori</h2>
                                </div>

                                <!-- LIST KATEGORI -->
                                <div class="space-y-1.5 mb-5" role="tablist" aria-label="Kategori Pengumuman">
                                    @php
                                        $categoriesList = [
                                            'Semua Pengumuman' => ['icon' => 'fa-link', 'key' => null],
                                            'Akademik' => ['icon' => 'fa-graduation-cap', 'key' => 'Akademik'],
                                            'Kesiswaan' => ['icon' => 'fa-users', 'key' => 'Kesiswaan'],
                                            'Informasi Umum' => ['icon' => 'far fa-clock', 'key' => 'Informasi Umum'],
                                        ];

                                        // Include other dynamic categories if exist
                                        foreach ($categoryCounts as $cName => $cCount) {
                                            if (!isset($categoriesList[$cName])) {
                                                $categoriesList[$cName] = ['icon' => 'fa-tag', 'key' => $cName];
                                            }
                                        }
                                    @endphp

                                    @foreach ($categoriesList as $title => $meta)
                                        @php
                                            $isSelected = ($meta['key'] === null && empty($selectedCategory)) || 
                                                          (strcasecmp($selectedCategory ?? '', $meta['key'] ?? '') === 0);
                                            $count = $categoryCounts[$title] ?? 0;
                                            $linkUrl = $meta['key'] 
                                                ? route('announcement.index', array_merge(request()->except('page'), ['category' => $meta['key']]))
                                                : route('announcement.index', array_merge(request()->except(['page', 'category', 'kategori'])));
                                        @endphp

                                        <a href="{{ $linkUrl }}"
                                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition-all duration-200 group {{ $isSelected ? 'bg-amber-50/90 text-amber-800 border border-amber-200/80 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-theme-primary hover:bg-slate-50 border border-transparent' }}">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <i class="{{ str_contains($meta['icon'], 'far') ? $meta['icon'] : 'fas ' . $meta['icon'] }} text-xs opacity-75 group-hover:scale-110 transition-transform shrink-0 {{ $isSelected ? 'text-theme-secondary' : '' }}"></i>
                                                <span class="truncate">{{ $title }}</span>
                                            </div>
                                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-black {{ $isSelected ? 'bg-amber-100 text-amber-900' : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200 group-hover:text-slate-700' }} transition-colors shrink-0 ml-2">
                                                {{ $count }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>

                                <!-- DIVIDER -->
                                <hr class="border-slate-100 mb-5">

                                <!-- FORMULIR PENCARIAN (SUBMIT ON ENTER / DEBOUNCED) -->
                                <div>
                                    <label for="search-input" class="block text-xs font-extrabold text-slate-800 mb-2">
                                        Cari Pengumuman
                                    </label>
                                    
                                    <form action="{{ route('announcement.index') }}" method="GET" id="search-form" class="relative">
                                        @if(!empty($selectedCategory))
                                            <input type="hidden" name="category" value="{{ $selectedCategory }}">
                                        @endif
                                        
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                                <i class="fas fa-search text-xs"></i>
                                            </span>
                                            <input type="text" 
                                                   id="search-input" 
                                                   name="search" 
                                                   value="{{ $searchKeyword }}"
                                                   placeholder="Masukkan kata kunci..." 
                                                   autocomplete="off"
                                                   class="w-full pl-8 pr-7 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-theme-primary/20 focus:border-theme-primary transition-all shadow-2xs">
                                            
                                            @if(!empty($searchKeyword))
                                                <a href="{{ route('announcement.index', request()->except(['search', 'q', 'page'])) }}" 
                                                   title="Hapus pencarian"
                                                   class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600 transition">
                                                    <i class="fas fa-times-circle text-xs"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </aside>

                        <!-- ========================================================================= -->
                        <!-- RIGHT CONTENT: DAFTAR KARTU PENGUMUMAN & PAGINATION -->
                        <!-- ========================================================================= -->
                        <div class="lg:col-span-8 xl:col-span-9 w-full min-w-0" data-aos="fade-left" data-aos-duration="500">
                            
                            @if($announcements->isEmpty())
                                <!-- EMPTY STATE -->
                                <div class="bg-white rounded-2xl border border-slate-200/90 p-10 text-center shadow-sm">
                                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Pengumuman Ditemukan</h3>
                                    <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                                        @if(!empty($searchKeyword) || !empty($selectedCategory))
                                            Tidak ada pengumuman yang sesuai dengan kriteria filter atau pencarian "{{ $searchKeyword }}".
                                        @else
                                            Saat ini belum ada data pengumuman resmi yang dipublikasikan.
                                        @endif
                                    </p>
                                    @if(!empty($searchKeyword) || !empty($selectedCategory))
                                        <a href="{{ route('announcement.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-theme-primary text-white text-xs font-bold hover:opacity-95 transition shadow-sm">
                                            <i class="fas fa-redo-alt text-[10px]"></i>
                                            <span>Reset Filter &amp; Pencarian</span>
                                        </a>
                                    @endif
                                </div>
                            @else
                                <!-- LIST KARTU PENGUMUMAN (STACK VERTIKAL) -->
                                <div class="space-y-5 sm:space-y-6">
                                    @foreach ($announcements as $index => $announcement)
                                        @php
                                            $accent = $announcement->category_accent;
                                        @endphp
                                        <article class="announcement-card bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs hover:shadow-xl hover:border-theme-primary/30 transition-all duration-300 transform-gpu spring-hover flex flex-col md:flex-row md:items-stretch gap-5 lg:gap-6 relative overflow-hidden group min-w-0" data-aos="fade-up" data-aos-delay="{{ $index * 70 }}">
                                            
                                            <!-- GAMBAR DI KIRI + FLOATING DATE BADGE (FRAME TERKUNCI & PROPORSIONAL) -->
                                            <div class="img-zoom-box relative w-full md:w-64 lg:w-72 h-48 sm:h-52 md:h-44 lg:h-48 shrink-0 rounded-xl overflow-hidden bg-slate-100">
                                                <img src="{{ $announcement->display_thumbnail_url }}" 
                                                     alt="{{ $announcement->title }}" 
                                                     loading="lazy"
                                                     decoding="async"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                                     class="w-full h-full object-cover object-center">
                                                
                                                <!-- FLOATING DATE BADGE (TOP-LEFT) -->
                                                <div class="absolute top-2.5 left-2.5 bg-white/95 backdrop-blur-md shadow-md rounded-xl px-2.5 py-1.5 text-center min-w-[50px] border border-white/80 z-10 flex flex-col items-center justify-center pointer-events-none">
                                                    <span class="block text-lg sm:text-xl font-black text-slate-900 leading-none">
                                                        {{ $announcement->day }}
                                                    </span>
                                                    <span class="block text-[10px] font-extrabold tracking-wider uppercase {{ $accent['date_badge_text'] }}">
                                                        {{ $announcement->month_short }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- DETAIL TEKS DI KANAN -->
                                            <div class="flex flex-col justify-between flex-grow py-0.5 min-w-0">
                                                <div>
                                                    <!-- KATEGORI PILL & JAM -->
                                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] sm:text-[11px] font-extrabold tracking-wide uppercase {{ $accent['badge_bg'] }}">
                                                            {{ $accent['name'] }}
                                                        </span>
                                                        <span class="text-slate-300 text-xs">•</span>
                                                        <span class="text-[11px] text-slate-500 font-semibold inline-flex items-center gap-1">
                                                            <i class="far fa-clock text-[10px]"></i>
                                                            {{ $announcement->formatted_time }}
                                                        </span>
                                                    </div>

                                                    <!-- JUDUL PENGUMUMAN -->
                                                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-slate-900 group-hover:text-theme-primary transition-colors leading-snug line-clamp-2 mb-2 font-headline break-words">
                                                        <a href="{{ route('announcement.show', $announcement->id) }}">
                                                            {{ $announcement->title }}
                                                        </a>
                                                    </h3>

                                                    <!-- RINGKASAN TEKS -->
                                                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-3 mb-4 font-normal break-words">
                                                        {{ $announcement->summary ?: Str::limit(strip_tags($announcement->content), 150) }}
                                                    </p>
                                                </div>

                                                <!-- ACTION LINK: BACA SELENGKAPNYA -->
                                                <div>
                                                    <a href="{{ route('announcement.show', $announcement->id) }}" 
                                                       class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-theme-secondary hover:text-amber-600 transition-all group-hover:gap-2.5">
                                                        <span>Baca Selengkapnya</span>
                                                        <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                                                    </a>
                                                </div>
                                            </div>

                                        </article>
                                    @endforeach
                                </div>

                                <!-- PAGINATION -->
                                <div class="mt-8">
                                    {{ $announcements->links('user.announcement.partials.pagination') }}
                                </div>
                            @endif

                        </div>

                    </div>

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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');
        
        if (searchInput && searchForm) {
            let debounceTimer;
            
            // Auto submit on mobile/tablet after typing stops for 800ms
            if (window.innerWidth < 1024) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        if (searchInput.value.trim().length >= 3 || searchInput.value.trim().length === 0) {
                            searchForm.submit();
                        }
                    }, 850);
                });
            }
        }
    });
</script>
@endpush
