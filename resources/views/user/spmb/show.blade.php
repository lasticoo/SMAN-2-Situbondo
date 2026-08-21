@extends('layouts.app')

@php
    $pageTitle = $scheduleDetail->title . ' - Jadwal SPMB SMAN 2 Situbondo';
    $pageDescription = 'Rincian informasi tahapan ' . $scheduleDetail->title . ' pada penerimaan murid baru SMAN 2 Situbondo tahun ' . $spmbYear . '. Periode: ' . $scheduleDetail->date_range;

    // Schema.org JSON-LD Structured Data
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebPage',
                '@id' => route('spmb.show', $scheduleDetail->id) . '#webpage',
                'url' => route('spmb.show', $scheduleDetail->id),
                'name' => $pageTitle,
                'description' => $pageDescription,
                'inLanguage' => 'id-ID',
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => route('spmb.show', $scheduleDetail->id) . '#breadcrumb',
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
                        'name' => 'SPMB',
                        'item' => route('spmb.index'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $scheduleDetail->title,
                        'item' => route('spmb.show', $scheduleDetail->id),
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
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <link rel="canonical" href="{{ route('spmb.show', $scheduleDetail->id) }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="SMA Negeri 2 Situbondo">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ route('spmb.show', $scheduleDetail->id) }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@push('styles')
<style>
    /* Dynamic Theme Styles, GPU Acceleration & Performance Optimizations */
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

    /* GPU Render Acceleration */
    .spring-hover {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
    }
    .spring-hover:hover {
        transform: translateY(-2px);
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
        <!-- 2. BREADCRUMBS NAVIGATION BAR                                 -->
        <!-- ------------------------------------------------------------- -->
        <nav class="bg-white border-b border-slate-200/80 py-3.5" aria-label="Breadcrumb">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <ol class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
                    <li>
                        <a href="{{ url('/') }}" class="hover-text-secondary transition-colors flex items-center gap-1.5">
                            <i class="fas fa-home text-xs"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li class="text-slate-300">/</li>
                    <li>
                        <a href="{{ route('spmb.index') }}" class="hover-text-secondary transition-colors font-bold">
                            SPMB {{ $spmbYear }}
                        </a>
                    </li>
                    <li class="text-slate-300">/</li>
                    <li class="text-theme-secondary font-black truncate max-w-xs sm:max-w-md">
                        {{ $scheduleDetail->title }}
                    </li>
                </ol>
            </div>
        </nav>

        <!-- ------------------------------------------------------------- -->
        <!-- 3. DETAIL HERO HEADER (NO IMAGE & NO CATEGORY FILTER)         -->
        <!-- ------------------------------------------------------------- -->
        <header class="text-white py-12 sm:py-16 relative overflow-hidden shadow-md" style="background: linear-gradient(135deg, var(--primary-main, #05479E) 0%, #032b69 60%, #0f172a 100%);">
            <!-- Subtle Lighting Accents -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-1/3 w-64 h-64 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                <div class="max-w-4xl space-y-4" data-aos="fade-up" data-aos-duration="600">
                    
                    <!-- Eyebrow Badge & Date Pill -->
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-theme-secondary text-slate-950 shadow-sm" data-aos="zoom-in" data-aos-delay="100">
                            <i class="fas fa-calendar-check text-[11px]"></i>
                            <span>Tahapan SPMB</span>
                        </span>

                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-md border border-white/30">
                            <i class="far fa-calendar-alt text-amber-300 text-xs"></i>
                            <span>{{ $scheduleDetail->date_range }}</span>
                        </span>
                    </div>

                    <!-- Stage Title with Crystal-Clear High Contrast Typography -->
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black font-headline tracking-tight text-white leading-tight drop-shadow-xs break-words">
                        {{ $scheduleDetail->title }}
                    </h1>

                    <p class="text-xs sm:text-sm text-slate-100/90 leading-relaxed font-normal max-w-2xl">
                        Petunjuk teknis dan panduan lengkap pelaksanaan tahapan seleksi penerimaan murid baru SMAN 2 Situbondo.
                    </p>

                </div>
            </div>
        </header>

        <!-- ------------------------------------------------------------- -->
        <!-- 4. MAIN ARTICLE CONTENT (2-COLUMN LAYOUT)                     -->
        <!-- ------------------------------------------------------------- -->
        <main class="py-10 sm:py-14 w-full">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    
                    <!-- ========================================================= -->
                    <!-- LEFT COLUMN: DETAIL INFORMASI (8 COLS)                    -->
                    <!-- ========================================================= -->
                    <article class="lg:col-span-8 space-y-8" data-aos="fade-up" data-aos-duration="600">
                        
                        <!-- Main Content Box -->
                        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 md:p-10 shadow-xs space-y-6">
                            
                            <!-- Section: Deskripsi Pelaksanaan -->
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="w-2 h-6 bg-theme-secondary rounded-full inline-block shadow-xs"></span>
                                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-headline">
                                        Deskripsi &amp; Pelaksanaan
                                    </h2>
                                </div>
                                <div class="prose prose-slate max-w-none text-xs sm:text-sm text-slate-700 leading-relaxed space-y-3 font-normal break-words">
                                    <p class="whitespace-pre-line">{{ $scheduleDetail->full_description }}</p>
                                </div>
                            </div>

                            <!-- Section: Jadwal & Waktu Penting -->
                            <div class="p-4 sm:p-5 rounded-2xl bg-amber-50/70 border border-amber-200/80">
                                <div class="flex items-center gap-2.5 mb-2">
                                    <div class="w-7 h-7 rounded-lg bg-theme-secondary text-slate-950 flex items-center justify-center text-xs font-bold shadow-2xs">
                                        <i class="far fa-clock"></i>
                                    </div>
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Periode Waktu Pelaksanaan</h3>
                                </div>
                                <p class="text-xs sm:text-sm font-black text-theme-primary pl-9">
                                    {{ $scheduleDetail->date_range }}
                                </p>
                            </div>

                            @if(!empty($scheduleDetail->requirements_info))
                                <!-- Section: Persyaratan & Berkas Diperlukan -->
                                <div class="pt-4 border-t border-slate-100">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="w-2 h-6 bg-theme-secondary rounded-full inline-block shadow-xs"></span>
                                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-headline">
                                            Ketentuan &amp; Dokumen Persyaratan
                                        </h2>
                                    </div>
                                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed font-normal whitespace-pre-line break-words">
                                        {{ $scheduleDetail->requirements_info }}
                                    </div>
                                </div>
                            @endif

                        </div>

                        <!-- Back Button to SPMB Overview -->
                        <div class="flex items-center justify-between pt-2">
                            <a href="{{ route('spmb.index') }}#jadwal-tahapan" 
                               class="spring-hover inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 hover:border-theme-secondary text-slate-700 hover-text-secondary text-xs sm:text-sm font-black shadow-2xs hover:shadow-xs transition-all min-h-[42px]">
                                <i class="fas fa-arrow-left text-xs"></i>
                                <span>Kembali ke Jadwal SPMB</span>
                            </a>
                        </div>

                    </article>

                    <!-- ========================================================= -->
                    <!-- RIGHT COLUMN: SIDEBAR WIDGETS (4 COLS)                    -->
                    <!-- ========================================================= -->
                    <aside class="lg:col-span-4 space-y-6" data-aos="fade-left" data-aos-duration="600">
                        
                        <!-- Widget 1: Konsultasi Panitia Resmi via WhatsApp (High-Priority Placement on Top) -->
                        <div class="bg-gradient-to-br from-emerald-50 via-teal-50/70 to-emerald-100/60 rounded-3xl border-2 border-emerald-300 p-5 sm:p-6 shadow-xs space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-600 text-white shadow-2xs">
                                <i class="fab fa-whatsapp text-sm"></i>
                                <span>Konsultasi Panitia Resmi</span>
                            </div>

                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 font-headline leading-snug">
                                Punya Pertanyaan Mengenai Tahapan Ini?
                            </h3>

                            <p class="text-xs text-slate-600 leading-relaxed">
                                Klik tombol di bawah ini untuk langsung berkonsultasi via WhatsApp dengan pesan otomatis sesuai tahapan ini.
                            </p>

                            <a href="{{ $scheduleDetail->wa_url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="spring-hover w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs sm:text-sm shadow-md hover:shadow-lg flex items-center justify-center gap-2.5 active:scale-95 transition-all min-h-[44px]">
                                <i class="fab fa-whatsapp text-base"></i>
                                <span>Tanya Panitia via WhatsApp</span>
                            </a>
                        </div>

                        <!-- Widget 2: Akses Portal PPDB Jawa Timur -->
                        <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-theme-secondary text-slate-950 shadow-2xs">
                                <i class="fas fa-shield-alt text-xs"></i>
                                <span>Akses Portal</span>
                            </div>

                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 font-headline">
                                Portal PPDB Jawa Timur
                            </h3>

                            <p class="text-xs text-slate-600 leading-relaxed">
                                Pendaftaran online resmi dan seleksi jalur mandiri/zonasi dilakukan melalui portal Dinas Pendidikan Provinsi Jawa Timur.
                            </p>

                            <a href="https://spmbjatim.net/" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="spring-hover w-full py-3 px-4 rounded-xl bg-theme-primary hover:bg-theme-primary-deep text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs border-2 border-theme-secondary hover:opacity-95 active:scale-98 transition-all group min-h-[42px]">
                                <i class="fas fa-external-link-alt text-xs text-theme-secondary group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                <span>Buka Portal PPDB Jatim</span>
                            </a>
                        </div>

                        <!-- Widget 3: Tahapan SPMB Lainnya -->
                        @if($otherSchedules->isNotEmpty())
                            <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                                    <span class="w-2 h-5 bg-theme-secondary rounded-full shadow-xs"></span>
                                    <h3 class="text-sm sm:text-base font-extrabold text-slate-900 font-headline">
                                        Tahapan Lainnya
                                    </h3>
                                </div>

                                <div class="space-y-3">
                                    @foreach($otherSchedules as $other)
                                        <a href="{{ route('spmb.show', $other->id) }}" 
                                           class="block p-3.5 rounded-xl bg-slate-50 hover:bg-white border border-slate-200/70 hover:border-theme-secondary/80 transition-all group shadow-2xs">
                                            <div class="text-[10px] font-black text-theme-secondary uppercase tracking-wider mb-1">
                                                {{ $other->date_formatted }}
                                            </div>
                                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-theme-secondary transition-colors line-clamp-1 break-words">
                                                {{ $other->title }}
                                            </h4>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Widget 4: Unduhan Dokumen Terkait -->
                        @if($documents->isNotEmpty())
                            <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                                    <span class="w-2 h-5 bg-theme-secondary rounded-full shadow-xs"></span>
                                    <h3 class="text-sm sm:text-base font-extrabold text-slate-900 font-headline">
                                        Dokumen Terkait
                                    </h3>
                                </div>

                                <div class="space-y-2.5">
                                    @foreach($documents as $doc)
                                        <a href="{{ route('spmb.download', $doc->id) }}" 
                                           class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-theme-secondary text-slate-700 hover:text-slate-950 border border-slate-200/70 hover:border-theme-secondary transition-all group shadow-2xs min-h-[42px]">
                                            <div class="min-w-0 flex-1 pr-2">
                                                <h4 class="text-xs font-bold group-hover:text-slate-950 transition-colors truncate">
                                                    {{ $doc->title }}
                                                </h4>
                                                <p class="text-[10px] text-slate-400 group-hover:text-slate-800">
                                                    {{ $doc->extension }} • {{ $doc->size_formatted }}
                                                </p>
                                            </div>
                                            <i class="fas fa-download text-xs text-theme-secondary group-hover:text-slate-950 transition-colors shrink-0"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </aside>

                </div>

            </div>
        </main>

    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. SHARED FOOTER COMPONENT (100% UNIFIED)                     -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')

</div>
@endsection
