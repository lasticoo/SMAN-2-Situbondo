@extends('layouts.app')

@php
    $pageTitle = 'Sistem Penerimaan Murid Baru (SPMB) ' . $spmbYear . ' - SMAN 2 Situbondo';
    $pageDescription = 'Informasi resmi penerimaan peserta didik baru (SPMB / PPDB) SMAN 2 Situbondo tahun ajaran ' . $spmbYear . '/' . ($spmbYear + 1) . '. Jadwal tahapan pendaftaran, jalur seleksi, dan unduhan berkas formulir resmi.';

    // Schema.org JSON-LD Structured Data
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebPage',
                '@id' => route('spmb.index') . '#webpage',
                'url' => route('spmb.index'),
                'name' => $pageTitle,
                'description' => $pageDescription,
                'inLanguage' => 'id-ID',
                'publisher' => [
                    '@type' => 'EducationalOrganization',
                    'name' => 'SMA Negeri 2 Situbondo',
                    'url' => url('/'),
                    'logo' => asset('images/static/gambar_profile_statis.jpg'),
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => route('spmb.index') . '#breadcrumb',
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
                        'name' => 'SPMB ' . $spmbYear,
                        'item' => route('spmb.index'),
                    ],
                ],
            ],
        ],
    ];
@endphp

@section('title', $pageTitle)

@push('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="author" content="Panitia SPMB SMAN 2 Situbondo">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ route('spmb.index') }}">

    <!-- High-Speed Resource Hints & Above-the-Fold Preload -->
    <link rel="preload" as="image" href="{{ asset($activeBannerUrl) }}" fetchpriority="high">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SMA Negeri 2 Situbondo">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ route('spmb.index') }}">
    <meta property="og:image" content="{{ asset($activeBannerUrl) }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ asset($activeBannerUrl) }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@push('styles')
<style>
    /* =========================================================================
       DYNAMIC THEME CSS VARIABLES, MICRO-ANIMATIONS & HARMONIZED PALETTE
       ========================================================================= */
    :root {
        --primary-main: {{ $primaryColor ?? '#05479E' }};
        --secondary-gold: {{ $secondaryColor ?? '#F19E38' }};
        --secondary-main: {{ $secondaryColor ?? '#F19E38' }};
        --primary-deep: {{ $primaryColor ? 'color-mix(in srgb, ' . $primaryColor . ' 85%, black)' : '#032b69' }};
        --primary-light: {{ $primaryColor ? 'color-mix(in srgb, ' . $primaryColor . ' 12%, white)' : 'rgba(5, 71, 158, 0.08)' }};
        --secondary-hover: {{ $secondaryColor ? 'color-mix(in srgb, ' . $secondaryColor . ' 85%, black)' : '#d9821f' }};
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

    .hover-text-secondary:hover,
    .hover\:text-theme-secondary:hover { color: var(--secondary-gold) !important; }
    .hover-bg-secondary:hover,
    .hover\:bg-theme-secondary:hover { background-color: var(--secondary-gold) !important; color: #020617 !important; }
    .hover-border-secondary:hover,
    .hover\:border-theme-secondary:hover { border-color: var(--secondary-gold) !important; }

    /* Group Hover Variants */
    .group:hover .group-hover-bg-secondary,
    .group:hover .group-hover\:bg-theme-secondary {
        background-color: var(--secondary-gold) !important;
        color: #020617 !important;
    }
    .group:hover .group-hover-border-secondary,
    .group:hover .group-hover\:border-theme-secondary {
        border-color: var(--secondary-gold) !important;
    }
    .group:hover .group-hover-text-secondary,
    .group:hover .group-hover\:text-theme-secondary {
        color: var(--secondary-gold) !important;
    }
    .group:hover .group-hover-text-slate-950,
    .group:hover .group-hover\:text-slate-950 {
        color: #020617 !important;
    }

    /* Custom Smooth Scrollbars */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: var(--secondary-gold, #f19e38);
    }

    /* Hardware Accelerated Transitions & GPU Rendering Optimization */
    .spring-hover, .spmb-card, .doc-card {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
    }

    .spring-hover {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
    }
    .spring-hover:hover {
        transform: translateY(-2px);
    }

    .spmb-card {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.25s ease, background-color 0.25s ease;
    }
    .spmb-card:hover {
        transform: translateY(-3px) scale(1.005);
        box-shadow: 0 14px 28px -6px rgba(5, 71, 158, 0.14), 0 6px 12px -4px rgba(241, 158, 56, 0.12);
    }

    .doc-card {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.25s ease;
    }
    .doc-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px -6px rgba(5, 71, 158, 0.14), 0 8px 16px -4px rgba(241, 158, 56, 0.15);
    }

    /* Rendering Performance: content-visibility for below-the-fold sections */
    .lazy-render-section {
        contain-intrinsic-size: 500px;
        content-visibility: auto;
    }

    /* HD Hero Banner Rendering Engine (Zero Blur, Optimal Contrast) */
    .hero-banner-img {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: auto;
        transform: translateZ(0);
        backface-visibility: hidden;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col justify-between bg-[#FAFAFB] text-slate-800 antialiased selection:bg-amber-100 selection:text-amber-900 overflow-x-hidden">
    
    <div>
        <!-- ------------------------------------------------------------- -->
        <!-- 1. SHARED HEADER & NAVBAR COMPONENT                           -->
        <!-- ------------------------------------------------------------- -->
        @include('user.partials.navbar')

        <!-- ------------------------------------------------------------- -->
        <!-- 2. HERO BANNER SECTION (UNIFIED 2-COLUMN STAGE & SLIDER)      -->
        <!-- ------------------------------------------------------------- -->
        <section class="relative w-full overflow-hidden text-white py-10 sm:py-12 md:py-14 lg:py-16"
                 style="background: radial-gradient(circle at 80% 40%, var(--primary-main, #05479E) 0%, var(--primary-deep, #0e2347) 55%, #050d1a 100%);"
                 x-data="{ 
                     activeSlide: 0, 
                     totalSlides: {{ count($banners) }},
                     intervalId: null,
                     next() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
                     prev() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; },
                     startAutoSlide() {
                         if (this.totalSlides > 1 && !this.intervalId) {
                             this.intervalId = setInterval(() => { this.next(); }, 6000);
                         }
                     },
                     stopAutoSlide() {
                         if (this.intervalId) {
                             clearInterval(this.intervalId);
                             this.intervalId = null;
                         }
                     }
                 }"
                 x-init="startAutoSlide()"
                 @mouseenter="stopAutoSlide()"
                 @mouseleave="startAutoSlide()">
            
            <!-- Dynamic Subtle Grid Matrix Pattern (Harmonizes Across Entire Hero Canvas) -->
            <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.12)_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none opacity-40 z-1"></div>
            
            <!-- Ambient Glowing Lighting Accent Orbs -->
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-theme-secondary/15 rounded-full blur-3xl pointer-events-none z-1"></div>
            <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none z-1"></div>

            <!-- Unified Content Grid (Left: Clear Typography & CTAs | Right: Framed HD Banner Showcase) -->
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                    
                    <!-- ========================================================= -->
                    <!-- LEFT: HERO INFORMATION & CTA BUTTONS (5 or 6 COLS)        -->
                    <!-- ========================================================= -->
                    <div class="lg:col-span-6 space-y-4 sm:space-y-5 text-left" data-aos="fade-right" data-aos-duration="650">
                        
                        <!-- Eyebrow Tag with High-Contrast Secondary Pill -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-theme-secondary text-slate-950 text-xs font-black uppercase tracking-wider shadow-md" data-aos="zoom-in" data-aos-delay="100">
                            <span class="w-2 h-2 rounded-full bg-slate-950 animate-ping"></span>
                            <span class="w-2 h-2 rounded-full bg-slate-950 -ml-4"></span>
                            <span>PENERIMAAN PESERTA DIDIK BARU {{ $spmbYear }}</span>
                        </div>

                        <!-- Main Headline -->
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black font-headline tracking-tight text-white leading-tight drop-shadow-md">
                            Sistem Penerimaan Murid Baru <span class="text-theme-secondary drop-shadow-sm">(SPMB)</span>
                        </h1>

                        <!-- Subtitle -->
                        <p class="text-xs sm:text-sm md:text-base text-slate-100/95 leading-relaxed font-normal max-w-xl drop-shadow-xs">
                            Selamat datang di portal resmi informasi pendaftaran calon siswa-siswi baru SMA Negeri 2 Situbondo. Temukan linimasa jadwal pelaksanaan seleksi dan unduh dokumen panduan resmi di bawah ini.
                        </p>

                        <!-- CTA Buttons Group -->
                        <div class="pt-2 flex flex-wrap items-center gap-3" data-aos="fade-up" data-aos-delay="200">
                            <a href="#jadwal-tahapan" 
                               class="spring-hover px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-theme-secondary text-slate-950 font-black text-xs sm:text-sm shadow-lg hover:opacity-95 flex items-center gap-2 active:scale-95 transition-all min-h-[42px]">
                                <span>Lihat Informasi Jadwal</span>
                                <i class="fas fa-arrow-down text-xs"></i>
                            </a>

                            <a href="#dokumen-unduhan" 
                               class="spring-hover px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-theme-primary hover:bg-theme-primary-deep text-white font-black text-xs sm:text-sm border-2 border-theme-secondary flex items-center gap-2 shadow-md transition-all min-h-[42px]">
                                <i class="fas fa-file-download text-xs text-theme-secondary"></i>
                                <span>Unduh Berkas Panduan</span>
                            </a>
                        </div>

                    </div>

                    <!-- ========================================================= -->
                    <!-- RIGHT: FRAMED HD BANNER SHOWCASE SLIDER (6 COLS)          -->
                    <!-- ========================================================= -->
                    <div class="lg:col-span-6" data-aos="fade-left" data-aos-duration="650">
                        <div class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl ring-1 ring-white/20 border-2 border-white/15 bg-slate-950/80 aspect-[16/10] sm:aspect-[16/9] flex items-center justify-center group">
                            
                            <!-- Dynamic Background Slides Loop (HD Unobstructed Banner Display) -->
                            @foreach($banners as $index => $bannerUrl)
                                <div x-show="activeSlide === {{ $index }}" 
                                     x-transition:enter="transition-opacity ease-out duration-500" 
                                     x-transition:enter-start="opacity-0" 
                                     x-transition:enter-end="opacity-100" 
                                     x-transition:leave="transition-opacity ease-in duration-400" 
                                     x-transition:leave-start="opacity-100" 
                                     x-transition:leave-end="opacity-0" 
                                     class="absolute inset-0 z-0 flex items-center justify-center overflow-hidden">
                                    
                                    <!-- Ambient Soft Blurred Backdrop (Fills Frame Canvas Seamlessly) -->
                                    <img src="{{ asset($bannerUrl) }}" 
                                         alt="" 
                                         class="absolute inset-0 w-full h-full object-cover filter blur-2xl opacity-40 scale-110 pointer-events-none"
                                         aria-hidden="true">

                                    <!-- Foreground Sharp HD Banner Image (Natural Aspect Ratio, Zero Text Obscurity) -->
                                    <img src="{{ asset($bannerUrl) }}" 
                                         alt="Banner SPMB SMAN 2 Situbondo {{ $index + 1 }}" 
                                         class="w-full h-full object-contain object-center relative z-0 filter brightness-[0.98] contrast-[1.03] hero-banner-img"
                                         loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                         decoding="{{ $index === 0 ? 'sync' : 'async' }}"
                                         @if($index === 0) fetchpriority="high" @endif
                                         onerror="this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}'">
                                </div>
                            @endforeach

                            <!-- Slide Navigation Controls (Arrows) -->
                            @if(count($banners) > 1)
                                <!-- Left Arrow -->
                                <button @click="prev()" 
                                        aria-label="Slide sebelumnya"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-slate-950/70 hover:bg-theme-secondary text-white hover:text-slate-950 border border-white/20 hover:border-theme-secondary flex items-center justify-center transition-all shadow-lg active:scale-90 cursor-pointer">
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </button>

                                <!-- Right Arrow -->
                                <button @click="next()" 
                                        aria-label="Slide berikutnya"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-slate-950/70 hover:bg-theme-secondary text-white hover:text-slate-950 border border-white/20 hover:border-theme-secondary flex items-center justify-center transition-all shadow-lg active:scale-90 cursor-pointer">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>

                                <!-- Slide Indicators (Dots) -->
                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 bg-slate-950/50 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/15">
                                    @foreach($banners as $dotIndex => $bannerUrl)
                                        <button @click="activeSlide = {{ $dotIndex }}" 
                                                aria-label="Beralih ke slide {{ $dotIndex + 1 }}"
                                                class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                                :class="activeSlide === {{ $dotIndex }} ? 'w-6 bg-theme-secondary shadow-xs' : 'w-2 bg-white/50 hover:bg-white'">
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>

            <!-- Wave divider transition to content -->
            <div class="absolute bottom-0 inset-x-0 h-6 sm:h-10 bg-gradient-to-t from-[#FAFAFB] to-transparent pointer-events-none"></div>
        </section>

        <!-- ------------------------------------------------------------- -->
        <!-- 3. MAIN SECTION: JADWAL & TAHAPAN (LEFT) + INFO PENTING (RIGHT) -->
        <!-- ------------------------------------------------------------- -->
        <main id="jadwal-tahapan" class="py-10 sm:py-14 md:py-16 w-full relative lazy-render-section">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    
                    <!-- ========================================================= -->
                    <!-- LEFT COLUMN: JADWAL & TAHAPAN (8 COLS) - LATEST FIRST     -->
                    <!-- ========================================================= -->
                    <div class="lg:col-span-8 space-y-5" data-aos="fade-up" data-aos-duration="600">
                        
                        <!-- Section Header with Primary & Secondary Accents -->
                        <div class="flex items-center justify-between border-b-2 border-theme-primary/15 pb-4">
                            <div>
                                <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-theme-secondary mb-1">
                                    <span class="w-2.5 h-2.5 bg-theme-secondary rounded-full inline-block shadow-2xs"></span>
                                    <span>Rangkaian Pelaksanaan Resmi</span>
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-theme-primary font-headline tracking-tight">
                                    Jadwal &amp; Tahapan
                                </h2>
                            </div>
                            <span class="px-4 py-1.5 bg-theme-primary text-white text-xs font-black rounded-full border border-theme-secondary/40 shadow-xs">
                                {{ $timelineSchedules->count() }} Tahapan
                            </span>
                        </div>

                        <!-- Timeline Cards Container with Clean Vertical Scroll -->
                        <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-6 shadow-xs">
                            
                            @if($timelineSchedules->isNotEmpty())
                                <!-- Scrollable Timeline Frame (Snug & Tight, no huge gap) -->
                                <div class="max-h-[540px] overflow-y-auto space-y-4 pr-1 sm:pr-2 custom-scrollbar">
                                    
                                    @foreach($timelineSchedules as $index => $item)
                                        <div class="relative pl-7 sm:pl-9 group">
                                            
                                            <!-- Continuous Vertical Timeline Connector -->
                                            @if(!$loop->last)
                                                <div class="absolute left-[11px] sm:left-[13px] top-7 bottom-[-16px] w-0.5 bg-theme-primary/20 group-hover:bg-theme-secondary/80 transition-colors"></div>
                                            @endif

                                            <!-- Timeline Node Dot with Strong Primary & Secondary Ring -->
                                            <div class="absolute left-0 top-1.5 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-theme-primary text-white border-2 border-theme-secondary flex items-center justify-center text-[10px] sm:text-xs font-black group-hover:bg-theme-secondary group-hover:border-theme-primary group-hover:text-slate-950 transition-all shadow-xs">
                                                {{ $index + 1 }}
                                            </div>

                                            <!-- Card Box Content with Rich Primary + Secondary Accents -->
                                            <div class="spmb-card bg-slate-50/80 hover:bg-white border border-slate-200/90 hover:border-theme-primary rounded-2xl p-4 sm:p-5 shadow-2xs">
                                                
                                                <!-- Top Date Badge -->
                                                <div class="flex flex-wrap items-center justify-between gap-2 mb-2.5">
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-theme-primary text-white border-l-4 border-theme-secondary shadow-2xs">
                                                        <i class="far fa-calendar-alt text-amber-300 text-xs"></i>
                                                        <span>{{ $item->date_range }}</span>
                                                    </span>
                                                    @if($loop->first)
                                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-2xs">
                                                            Terbaru
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Stage Title -->
                                                <h3 class="text-base sm:text-lg font-extrabold text-slate-900 font-headline leading-snug mb-2 group-hover:text-theme-primary transition-colors break-words">
                                                    <a href="{{ route('spmb.show', $item->id) }}" class="hover:underline">
                                                        {{ $item->title }}
                                                    </a>
                                                </h3>

                                                <!-- Truncated Preview Snippet -->
                                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal mb-3.5 line-clamp-2 break-words">
                                                    {{ $item->preview_description }}
                                                </p>

                                                <!-- Action: "Lihat Detail Tahapan" Link with Primary & Secondary Accents -->
                                                <div class="flex items-center justify-between pt-2.5 border-t border-slate-200/60">
                                                    <span class="text-[11px] font-semibold text-slate-400 truncate pr-2">
                                                        Klik untuk rincian &amp; persyaratan lengkap
                                                    </span>
                                                    <a href="{{ route('spmb.show', $item->id) }}" 
                                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-theme-primary/10 hover:bg-theme-primary text-theme-primary hover:text-white text-xs font-black border border-theme-primary/20 hover:border-theme-secondary transition-all group-hover:translate-x-1 shadow-2xs shrink-0">
                                                        <span>Lihat Detail Tahapan</span>
                                                        <i class="fas fa-arrow-right text-[10px] text-theme-secondary group-hover:text-amber-300"></i>
                                                    </a>
                                                </div>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>
                            @else
                                <div class="text-center py-12 text-slate-400">
                                    <i class="far fa-calendar-times text-4xl mb-3 text-theme-primary/40"></i>
                                    <p class="text-sm font-semibold">Jadwal SPMB belum dipublikasikan oleh panitia.</p>
                                </div>
                            @endif

                        </div>

                    </div>

                    <!-- ========================================================= -->
                    <!-- RIGHT COLUMN: INFORMASI PENTING (4 COLS)                  -->
                    <!-- ========================================================= -->
                    <div class="lg:col-span-4" data-aos="fade-left" data-aos-duration="600">
                        <div class="sticky top-24 space-y-6">
                            
                            <!-- Card Informasi Penting (Static Black Text on Premium Card, Harmonious with Primary & Secondary) -->
                            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs hover:shadow-md transition-all space-y-5">
                                
                                <!-- Eyebrow Pill -->
                                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-theme-secondary text-slate-950 shadow-2xs">
                                    <i class="fas fa-shield-alt text-xs"></i>
                                    <span>Panduan Resmi</span>
                                </div>

                                <h3 class="text-xl font-extrabold font-headline text-slate-900 tracking-tight flex items-center gap-2">
                                    <span>Informasi Penting</span>
                                </h3>

                                <!-- Jalur Pendaftaran -->
                                <div class="flex items-start gap-3.5 pb-4 border-b border-slate-100">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-900 flex items-center justify-center text-sm flex-shrink-0 mt-0.5 shadow-2xs font-bold border border-slate-200">
                                        <i class="fas fa-info text-theme-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs sm:text-sm font-bold text-slate-900">Jalur Pendaftaran</h4>
                                        <p class="text-xs text-slate-600 mt-1 leading-relaxed break-words">
                                            Zonasi Wilayah, Afirmasi KIP/PKH, Perpindahan Tugas Orang Tua, dan Prestasi Nilai/Lomba.
                                        </p>
                                    </div>
                                </div>

                                <!-- Bantuan Pendaftaran -->
                                <div class="flex items-start gap-3.5 pb-2">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-900 flex items-center justify-center text-sm flex-shrink-0 mt-0.5 shadow-2xs font-bold border border-slate-200">
                                        <i class="fas fa-headset text-theme-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs sm:text-sm font-bold text-slate-900">Bantuan Pendaftaran</h4>
                                        <p class="text-xs text-slate-600 mt-1 leading-relaxed break-words">
                                            Layanan bantuan teknis dan konsultasi buka setiap hari kerja pukul 08.00 - 14.00 WIB.
                                        </p>
                                    </div>
                                </div>

                                <!-- Portal PPDB Jatim Action Button -->
                                <a href="https://spmbjatim.net/" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="spring-hover w-full py-3.5 px-4 rounded-xl bg-theme-primary hover:bg-theme-primary-deep text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-sm border-2 border-theme-secondary hover:opacity-95 active:scale-98 transition-all group min-h-[44px]">
                                    <i class="fas fa-external-link-alt text-xs text-theme-secondary group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                    <span>Portal PPDB Jawa Timur</span>
                                </a>

                                <!-- WhatsApp Direct Consultation -->
                                <a href="{{ $whatsappUrl }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="spring-hover w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs active:scale-98 transition-all min-h-[42px]">
                                    <i class="fab fa-whatsapp text-sm"></i>
                                    <span>Konsultasi Panitia SPMB</span>
                                </a>

                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </main>

        <!-- ------------------------------------------------------------- -->
        <!-- 4. DOKUMEN UNDUHAN (HORIZONTAL SCROLLING ATMOSPHERE TRACK)     -->
        <!-- ------------------------------------------------------------- -->
        <section id="dokumen-unduhan" class="py-14 sm:py-16 w-full bg-white border-t border-slate-200/80 lazy-render-section" x-data="{
            scrollLeft() {
                $refs.docTrack.scrollBy({ left: -320, behavior: 'smooth' });
            },
            scrollRight() {
                $refs.docTrack.scrollBy({ left: 320, behavior: 'smooth' });
            }
        }">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                
                <!-- Section Header with Primary Dominance & Secondary Accent -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8 sm:mb-10" data-aos="fade-up">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-theme-secondary mb-1.5">
                            <span class="w-2.5 h-2.5 bg-theme-secondary rounded-full inline-block shadow-2xs"></span>
                            <span>Pusat Berkas Resmi</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-theme-primary font-headline tracking-tight">
                            Dokumen Unduhan
                        </h2>
                        <div class="w-20 h-1.5 bg-theme-primary rounded-full mt-2 relative overflow-hidden">
                            <div class="w-6 h-full bg-theme-secondary rounded-full"></div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 mt-2 font-normal">
                            Geser untuk melihat dan mengunduh formulir serta petunjuk teknis pendaftaran resmi.
                        </p>
                    </div>

                    <!-- Horizontal Scroll Arrows -->
                    <div class="flex items-center gap-2 shrink-0">
                        <button @click="scrollLeft()" 
                                aria-label="Geser ke kiri"
                                class="w-10 h-10 rounded-full border border-slate-200 bg-slate-50 hover:bg-theme-primary hover:text-white text-slate-700 flex items-center justify-center transition-all shadow-2xs active:scale-95 cursor-pointer">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                        <button @click="scrollRight()" 
                                aria-label="Geser ke kanan"
                                class="w-10 h-10 rounded-full border border-slate-200 bg-slate-50 hover:bg-theme-primary hover:text-white text-slate-700 flex items-center justify-center transition-all shadow-2xs active:scale-95 cursor-pointer">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>

                @if($documents->isNotEmpty())
                    <!-- Horizontal Scroll Track with School Atmosphere -->
                    <div x-ref="docTrack" 
                         class="flex overflow-x-auto gap-5 pb-5 pt-1 custom-scrollbar snap-x snap-mandatory scroll-smooth">
                        
                        @foreach($documents as $docIndex => $doc)
                            <div class="doc-card min-w-[280px] sm:min-w-[320px] max-w-[340px] shrink-0 snap-start bg-[#FAFAFB] hover:bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 hover:border-theme-primary shadow-2xs flex flex-col justify-between group"
                                 data-aos="fade-up" 
                                 data-aos-delay="{{ ($docIndex % 3) * 100 }}">
                                
                                <!-- Card Header: File Icon Box + Metadata -->
                                <div>
                                    <div class="flex items-start gap-3.5 mb-4">
                                        <div class="w-12 h-12 rounded-xl bg-theme-primary/10 text-theme-primary flex items-center justify-center text-xl flex-shrink-0 border border-theme-primary/20 shadow-2xs group-hover:scale-105 group-hover:bg-theme-primary group-hover:text-white transition-all">
                                            <i class="{{ $doc->icon_class }}"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="inline-block text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-theme-secondary/20 text-slate-950 border border-theme-secondary/40 mb-1">
                                                {{ $doc->extension }}
                                            </span>
                                            <p class="text-[11px] font-semibold text-slate-400">
                                                Ukuran: {{ $doc->size_formatted }}
                                            </p>
                                        </div>
                                    </div>

                                    <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-theme-primary transition-colors line-clamp-2 leading-snug mb-3 break-words">
                                        {{ $doc->title }}
                                    </h3>
                                </div>

                                <!-- Action Button: Download with Strong Primary Background -->
                                <a href="{{ route('spmb.download', $doc->id) }}" 
                                   class="w-full mt-2 py-2.5 px-4 rounded-xl bg-theme-primary hover:bg-theme-primary-deep text-white border border-theme-secondary/50 font-bold text-xs flex items-center justify-center gap-2 transition-all duration-200 shadow-2xs active:scale-98 min-h-[40px]">
                                    <i class="fas fa-download text-xs text-theme-secondary"></i>
                                    <span>Unduh Dokumen</span>
                                </a>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="bg-slate-50 rounded-2xl p-8 text-center border border-dashed border-slate-300">
                        <i class="far fa-folder-open text-3xl text-slate-400 mb-2"></i>
                        <p class="text-xs text-slate-500 font-medium">Belum ada dokumen unduhan yang diunggah.</p>
                    </div>
                @endif

            </div>
        </section>

    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. SHARED FOOTER COMPONENT (100% UNIFIED WITH OTHER MENUS)   -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')

</div>
@endsection
