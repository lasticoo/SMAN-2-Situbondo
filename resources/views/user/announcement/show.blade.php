@extends('layouts.app')

@section('title', $announcement->title . ' - Pengumuman SMAN 2 Situbondo')

@push('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ Str::limit(strip_tags($announcement->summary ?: $announcement->content), 160) }}">
    <meta name="author" content="SMA Negeri 2 Situbondo">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <link rel="canonical" href="{{ route('announcement.show', $announcement->id) }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="SMA Negeri 2 Situbondo">
    <meta property="og:title" content="{{ $announcement->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($announcement->summary ?: $announcement->content), 160) }}">
    <meta property="og:url" content="{{ route('announcement.show', $announcement->id) }}">
    <meta property="og:image" content="{{ $announcement->display_thumbnail_url }}">
    <meta property="og:locale" content="id_ID">
    <meta property="article:published_time" content="{{ ($announcement->published_at ?? $announcement->created_at)?->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $announcement->updated_at?->toIso8601String() }}">
    <meta property="article:section" content="{{ $announcement->category ?: 'Informasi Umum' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $announcement->title }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($announcement->summary ?: $announcement->content), 160) }}">
    <meta name="twitter:image" content="{{ $announcement->display_thumbnail_url }}">

    <!-- Schema.org JSON-LD Structured Data for Google Search (NewsArticle & BreadcrumbList) -->
    @php
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'NewsArticle',
                    '@id' => route('announcement.show', $announcement->id) . '#article',
                    'isPartOf' => [
                        '@type' => 'WebPage',
                        '@id' => route('announcement.show', $announcement->id),
                        'url' => route('announcement.show', $announcement->id),
                        'name' => $announcement->title,
                    ],
                    'headline' => $announcement->title,
                    'description' => Str::limit(strip_tags($announcement->summary ?: $announcement->content), 200),
                    'articleSection' => $announcement->category ?: 'Informasi Umum',
                    'inLanguage' => 'id-ID',
                    'image' => [
                        $announcement->display_thumbnail_url
                    ],
                    'datePublished' => ($announcement->published_at ?? $announcement->created_at)?->toIso8601String(),
                    'dateModified' => $announcement->updated_at?->toIso8601String(),
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => route('announcement.show', $announcement->id),
                    ],
                    'author' => [
                        '@type' => 'Organization',
                        'name' => 'Humas & Publikasi SMA Negeri 2 Situbondo',
                        'url' => url('/'),
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
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => route('announcement.show', $announcement->id) . '#breadcrumb',
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
                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => $announcement->title,
                            'item' => route('announcement.show', $announcement->id),
                        ],
                    ],
                ]
            ]
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
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
    .hover-text-secondary:hover { color: var(--secondary-gold) !important; }

    /* Spring Hover Physics */
    .spring-hover {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        transform: translate3d(0, 0, 0);
        will-change: transform;
    }
    .spring-hover:hover {
        transform: translateY(-3px) translate3d(0, 0, 0);
    }

    /* Editorial News Typography & Prose Formatting */
    .article-prose {
        font-size: 0.95rem;
        line-height: 1.85;
        color: #334155;
    }
    @media (min-width: 640px) {
        .article-prose {
            font-size: 1.025rem;
            line-height: 1.9;
        }
    }
    .article-prose p {
        margin-bottom: 1.25rem;
        color: #334155;
    }
    .article-prose h2, .article-prose h3, .article-prose h4 {
        font-family: 'Hanken Grotesk', 'Outfit', sans-serif;
        font-weight: 800;
        color: #0f172a;
        margin-top: 1.75rem;
        margin-bottom: 0.75rem;
        line-height: 1.35;
        letter-spacing: -0.015em;
    }
    .article-prose h2 { font-size: 1.35rem; }
    .article-prose h3 { font-size: 1.18rem; }
    .article-prose h4 { font-size: 1.05rem; }
    .article-prose ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .article-prose ol {
        list-style-type: decimal;
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .article-prose li {
        margin-bottom: 0.5rem;
        line-height: 1.75;
    }
    .article-prose strong, .article-prose b {
        font-weight: 700;
        color: #0f172a;
    }
    .article-prose blockquote {
        border-left: 4px solid var(--primary-main);
        background: #f8fafc;
        padding: 1rem 1.25rem;
        border-radius: 0 0.75rem 0.75rem 0;
        margin: 1.5rem 0;
        font-style: italic;
        color: #475569;
    }
    .article-prose a {
        color: var(--primary-main);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: color 0.15s ease;
    }
    .article-prose a:hover {
        color: var(--secondary-gold);
    }
    .article-prose table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .article-prose th, .article-prose td {
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        text-align: left;
    }
    .article-prose th {
        background-color: #f1f5f9;
        font-weight: 700;
        color: #0f172a;
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
        <section class="bg-theme-gradient text-white py-10 sm:py-14 md:py-16 relative overflow-hidden w-full">
            <!-- Ambient Subtle Lighting -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-theme-secondary/20 blur-3xl pointer-events-none"></div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center max-w-4xl" data-aos="fade-up" data-aos-duration="600">
                <!-- Pill Badge "Pusat Informasi" -->
                <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-theme-secondary text-xs font-extrabold uppercase tracking-wider mb-3 shadow-xs" data-aos="zoom-in">
                    <i class="fas fa-bullhorn text-[11px] animate-pulse"></i>
                    <span>Pusat Informasi</span>
                </div>
                
                <!-- Main Title "Detail Pengumuman" -->
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white font-headline tracking-tight mb-2 sm:mb-3">
                    Detail Pengumuman Resmi
                </h1>
                
                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm text-slate-200 leading-relaxed max-w-xl mx-auto font-normal">
                    Informasi resmi terverifikasi dari Humas dan Publikasi SMA Negeri 2 Situbondo.
                </p>
            </div>
        </section>

        <!-- ------------------------------------------------------------- -->
        <!-- 3. MAIN DETAIL CONTENT                                        -->
        <!-- ------------------------------------------------------------- -->
        <main class="bg-slate-50/70 py-8 sm:py-12 relative overflow-hidden w-full">
            <!-- Ambient Lighting -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-theme-primary-light blur-3xl pointer-events-none"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                
                <!-- BREADCRUMBS -->
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium flex-wrap" data-aos="fade-down" data-aos-duration="400">
                    <a href="{{ route('home') }}" class="hover:text-theme-primary transition flex items-center gap-1">
                        <i class="fas fa-home text-[11px]"></i>
                        <span>Beranda</span>
                    </a>
                    <span>/</span>
                    <a href="{{ route('announcement.index') }}" class="hover:text-theme-primary transition">
                        <span>Pengumuman</span>
                    </a>
                    <span>/</span>
                    <span class="text-slate-800 font-bold truncate max-w-xs sm:max-w-md">
                        {{ $announcement->title }}
                    </span>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- ========================================================================= -->
                    <!-- MAIN ARTICLE AREA (LEFT) -->
                    <!-- ========================================================================= -->
                    <div class="lg:col-span-8 w-full min-w-0" data-aos="fade-up" data-aos-duration="500">
                        <article class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-8 md:p-10 shadow-sm relative w-full min-w-0">
                            
                            @php
                                $accent = $announcement->category_accent;
                            @endphp

                            <!-- ARTICLE META HEADER -->
                            <div class="flex items-center gap-2.5 flex-wrap mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold tracking-wide uppercase {{ $accent['badge_bg'] }}">
                                    {{ $accent['name'] }}
                                </span>
                                <span class="text-slate-300 text-xs">•</span>
                                <span class="text-xs text-slate-500 font-semibold inline-flex items-center gap-1.5">
                                    <i class="far fa-calendar-alt text-xs"></i>
                                    {{ $announcement->formatted_date }}
                                </span>
                                <span class="text-slate-300 text-xs">•</span>
                                <span class="text-xs text-slate-500 font-semibold inline-flex items-center gap-1.5">
                                    <i class="far fa-clock text-xs"></i>
                                    {{ $announcement->formatted_time }}
                                </span>
                            </div>

                            <!-- ARTICLE TITLE -->
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight mb-6 font-headline break-words">
                                {{ $announcement->title }}
                            </h1>

                            <!-- FEATURED IMAGE (FULL VIEW, ZERO CROPPING, ELEGANT PRESENTATION) -->
                            <div class="rounded-2xl overflow-hidden mb-8 bg-slate-50 border border-slate-200/80 p-2 sm:p-3 flex items-center justify-center shadow-xs">
                                <img src="{{ $announcement->display_thumbnail_url }}" 
                                     alt="{{ $announcement->title }}" 
                                     decoding="async"
                                     onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                     class="w-full h-auto max-h-[750px] object-contain rounded-xl shadow-xs">
                            </div>

                            <!-- SUMMARY HIGHLIGHT (IF AVAILABLE) -->
                            @if(!empty($announcement->summary))
                                <div class="bg-slate-50 border-l-4 border-theme-primary p-4 sm:p-5 rounded-r-xl mb-8 text-xs sm:text-sm text-slate-700 italic font-medium leading-relaxed break-words">
                                    "{{ $announcement->summary }}"
                                </div>
                            @endif

                            <!-- ARTICLE CONTENT (EDITORIAL NEWS FORMAT) -->
                            <div class="article-prose break-words overflow-hidden">
                                @php
                                    $rawContent = $announcement->content ?? '';
                                    $hasHtml = strip_tags($rawContent) !== $rawContent;
                                @endphp

                                @if($hasHtml)
                                    {!! $rawContent !!}
                                @else
                                    @php
                                        $paragraphs = array_filter(array_map('trim', explode("\n\n", str_replace("\r", "", $rawContent))));
                                    @endphp
                                    @if(count($paragraphs) > 0)
                                        @foreach($paragraphs as $pIndex => $paragraph)
                                            <p>
                                                {!! nl2br(e($paragraph)) !!}
                                            </p>
                                        @endforeach
                                    @else
                                        <p>
                                            {!! nl2br(e($rawContent)) !!}
                                        </p>
                                    @endif
                                @endif
                            </div>

                            <!-- AUTHOR & SIGNATURE -->
                            <div class="mt-10 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-xs text-slate-500">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-theme-primary text-white flex items-center justify-center font-bold text-xs shrink-0">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block font-bold text-slate-800 truncate">Humas &amp; Publikasi SMAN 2 Situbondo</span>
                                        <span class="block text-[11px] text-slate-400 truncate">Pemberitahuan Resmi Sekolah</span>
                                    </div>
                                </div>

                                <!-- SHARE BUTTONS -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="font-bold text-slate-700">Bagikan:</span>
                                    @php
                                        $shareUrl = urlencode(route('announcement.show', $announcement->id));
                                        $shareText = urlencode($announcement->title . ' - Pengumuman Resmi SMAN 2 Situbondo');
                                    @endphp
                                    <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" 
                                       target="_blank" 
                                       rel="noopener" 
                                       title="Bagikan ke WhatsApp"
                                       class="w-8 h-8 rounded-full bg-green-500 hover:bg-green-600 text-white flex items-center justify-center transition shadow-xs">
                                        <i class="fab fa-whatsapp text-sm"></i>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" 
                                       target="_blank" 
                                       rel="noopener" 
                                       title="Bagikan ke Facebook"
                                       class="w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition shadow-xs">
                                        <i class="fab fa-facebook-f text-xs"></i>
                                    </a>
                                    <button type="button" 
                                            onclick="navigator.clipboard.writeText('{{ route('announcement.show', $announcement->id) }}'); alert('Tautan pengumuman berhasil disalin!');" 
                                            title="Salin Tautan"
                                            class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition border border-slate-200">
                                        <i class="fas fa-link text-xs"></i>
                                    </button>
                                </div>
                            </div>

                        </article>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- SIDEBAR: CTA & PENGUMUMAN LAINNYA (RIGHT) -->
                    <!-- ========================================================================= -->
                    <aside class="lg:col-span-4 w-full space-y-6 min-w-0" data-aos="fade-left" data-aos-duration="600">
                        
                        <!-- CALL TO ACTION (CTA) CARD -->
                        <div class="bg-theme-gradient text-white rounded-3xl p-6 sm:p-7 shadow-lg relative overflow-hidden w-full">
                            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                            
                            <div class="relative z-10">
                                <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center text-white text-lg mb-4">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold mb-2 font-headline break-words">Butuh Informasi Lanjutan?</h3>
                                <p class="text-xs text-slate-200 leading-relaxed mb-5">
                                    Hubungi layanan bantuan atau sekretariat SMA Negeri 2 Situbondo untuk konfirmasi jadwal dan konsultasi kegiatan.
                                </p>
                                
                                <div class="space-y-2.5">
                                    <a href="https://wa.me/628123456789" 
                                       target="_blank" 
                                       rel="noopener" 
                                       class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold text-xs transition shadow-sm">
                                        <i class="fab fa-whatsapp text-sm"></i>
                                        <span>Hubungi via WhatsApp</span>
                                    </a>
                                    <a href="{{ route('announcement.index') }}" 
                                       class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs transition border border-white/20">
                                        <i class="fas fa-th-list text-xs"></i>
                                        <span>Lihat Semua Pengumuman</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- PENGUMUMAN LAINNYA / TERKAIT -->
                        @if($otherAnnouncements->isNotEmpty())
                            <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-sm w-full">
                                <h3 class="text-sm font-extrabold text-slate-900 font-headline uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="fas fa-bullhorn text-theme-secondary text-xs"></i>
                                    <span>Pengumuman Lainnya</span>
                                </h3>

                                <div class="space-y-4">
                                    @foreach ($otherAnnouncements as $item)
                                        @php
                                            $otherAccent = $item->category_accent;
                                        @endphp
                                        <a href="{{ route('announcement.show', $item->id) }}" class="flex gap-3.5 group items-start min-w-0">
                                            <div class="w-16 h-16 shrink-0 rounded-xl overflow-hidden bg-slate-100 relative">
                                                <img src="{{ $item->display_thumbnail_url }}" 
                                                     alt="{{ $item->title }}" 
                                                     loading="lazy"
                                                     decoding="async"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            </div>
                                            <div class="flex-grow min-w-0">
                                                <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                                                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded {{ $otherAccent['badge_bg'] }} uppercase shrink-0">
                                                        {{ $otherAccent['name'] }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 font-medium shrink-0">
                                                        {{ $item->day }} {{ $item->month_short }}
                                                    </span>
                                                </div>
                                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-theme-primary transition-colors line-clamp-2 leading-snug break-words">
                                                    {{ $item->title }}
                                                </h4>
                                            </div>
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
    <!-- 4. SHARED FOOTER COMPONENT                                    -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')
</div>
@endsection
