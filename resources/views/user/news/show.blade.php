@extends('layouts.app')

@php
    // Universal 10-Tier Thumbnail Resolver Engine for News Detail
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

    $featuredImageUrl = $resolveThumbnailUrl($news->thumbnail_url);
    $pubDate = $news->published_at ?? $news->created_at ?? \Carbon\Carbon::now();
    $pCarbon = ($pubDate instanceof \Carbon\Carbon) ? $pubDate : \Carbon\Carbon::parse($pubDate);
    $formattedDate = $pCarbon->translatedFormat('d F Y');
    $formattedTime = $pCarbon->format('H:i') . ' WIB';
    
    $cat = $news->category ?: 'Informasi';
    $catLower = strtolower($cat);
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

    $newsSummary = $news->summary ?: \Illuminate\Support\Str::limit(strip_tags($news->content), 160);

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'NewsArticle',
                '@id' => route('news.show', $news->id) . '#article',
                'isPartOf' => [
                    '@type' => 'WebPage',
                    '@id' => route('news.show', $news->id),
                    'url' => route('news.show', $news->id),
                    'name' => $news->title,
                ],
                'headline' => $news->title,
                'description' => $newsSummary,
                'articleSection' => $cat,
                'inLanguage' => 'id-ID',
                'image' => [
                    $featuredImageUrl,
                ],
                'datePublished' => $pCarbon->toIso8601String(),
                'dateModified' => ($news->updated_at ?? $pCarbon)->toIso8601String(),
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => route('news.show', $news->id),
                ],
                'author' => [
                    '@type' => 'Person',
                    'name' => $news->author->name ?? 'Humas & Publikasi SMA Negeri 2 Situbondo',
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
                '@id' => route('news.show', $news->id) . '#breadcrumb',
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
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $news->title,
                        'item' => route('news.show', $news->id),
                    ],
                ],
            ],
        ],
    ];
@endphp

@section('title', $news->title . ' - Berita SMAN 2 Situbondo')

@push('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $newsSummary }}">
    <meta name="author" content="{{ $news->author->name ?? 'SMA Negeri 2 Situbondo' }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot-news" content="index, follow">
    <link rel="canonical" href="{{ route('news.show', $news->id) }}">
    <link rel="preload" as="image" href="{{ $featuredImageUrl }}" fetchpriority="high">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="SMA Negeri 2 Situbondo">
    <meta property="og:title" content="{{ $news->title }} - SMAN 2 Situbondo">
    <meta property="og:description" content="{{ $newsSummary }}">
    <meta property="og:url" content="{{ route('news.show', $news->id) }}">
    <meta property="og:image" content="{{ $featuredImageUrl }}">
    <meta property="og:locale" content="id_ID">
    <meta property="article:published_time" content="{{ $pCarbon->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ ($news->updated_at ?? $pCarbon)->toIso8601String() }}">
    <meta property="article:section" content="{{ $cat }}">
    <meta property="article:author" content="{{ $news->author->name ?? 'Humas SMADA' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $news->title }} - SMAN 2 Situbondo">
    <meta name="twitter:description" content="{{ $newsSummary }}">
    <meta name="twitter:image" content="{{ $featuredImageUrl }}">

    <!-- Schema.org JSON-LD Structured Data for Google News & Article Search -->
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@push('styles')
<style>
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
        <!-- 2. HERO BANNER SECTION (MATCHING ANNOUNCEMENT DETAIL DESIGN)  -->
        <!-- ------------------------------------------------------------- -->
        <header class="bg-theme-gradient text-white py-10 sm:py-14 md:py-16 relative overflow-hidden w-full">
            <!-- Ambient Subtle Lighting -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-theme-secondary/20 blur-3xl pointer-events-none"></div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center max-w-4xl" data-aos="fade-up" data-aos-duration="600">
                <!-- Pill Badge "Pusat Informasi" -->
                <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-theme-secondary text-xs font-extrabold uppercase tracking-wider mb-3 shadow-xs" data-aos="zoom-in">
                    <i class="fas fa-newspaper text-[11px] animate-pulse"></i>
                    <span>Pusat Informasi</span>
                </div>
                
                <!-- Section Eyebrow Title -->
                <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white font-headline tracking-tight mb-2 sm:mb-3">
                    Detail Berita Resmi
                </div>
                
                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm text-slate-200 leading-relaxed max-w-xl mx-auto font-normal">
                    Informasi dan kabar terverifikasi dari Humas dan Publikasi SMA Negeri 2 Situbondo.
                </p>
            </div>
        </header>

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
                    <a href="{{ route('news.index') }}" class="hover:text-theme-primary transition">
                        <span>Berita</span>
                    </a>
                    <span>/</span>
                    <span class="text-slate-800 font-bold truncate max-w-xs sm:max-w-md" aria-current="page">
                        {{ $news->title }}
                    </span>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- ========================================================================= -->
                    <!-- MAIN ARTICLE AREA (LEFT) -->
                    <!-- ========================================================================= -->
                    <div class="lg:col-span-8 w-full min-w-0" data-aos="fade-up" data-aos-duration="500">
                        <article class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-8 md:p-10 shadow-sm relative w-full min-w-0"
                                 itemscope itemtype="https://schema.org/NewsArticle">
                            
                            <!-- ARTICLE META HEADER -->
                            <div class="flex items-center gap-2.5 flex-wrap mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold tracking-wide uppercase border {{ $badgeClass }}" itemprop="articleSection">
                                    {{ $cat }}
                                </span>
                                <span class="text-slate-300 text-xs">•</span>
                                <span class="text-xs text-slate-500 font-semibold inline-flex items-center gap-1.5">
                                    <i class="far fa-calendar-alt text-xs"></i>
                                    <time datetime="{{ $pCarbon->toIso8601String() }}" itemprop="datePublished">{{ $formattedDate }}</time>
                                </span>
                                <span class="text-slate-300 text-xs">•</span>
                                <span class="text-xs text-slate-500 font-semibold inline-flex items-center gap-1.5">
                                    <i class="far fa-clock text-xs"></i>
                                    <span>{{ $formattedTime }}</span>
                                </span>
                            </div>

                            <!-- ARTICLE TITLE (SINGLE H1 FOR THE NEWS ARTICLE) -->
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight mb-6 font-headline break-words" itemprop="headline">
                                {{ $news->title }}
                            </h1>

                            <!-- FEATURED IMAGE (FULL VIEW, ZERO CROPPING, ELEGANT PRESENTATION) -->
                            <figure class="rounded-2xl overflow-hidden mb-8 bg-slate-50 border border-slate-200/80 p-2 sm:p-3 flex items-center justify-center shadow-xs">
                                <img src="{{ $featuredImageUrl }}" 
                                     alt="Foto Berita: {{ $news->title }} - SMAN 2 Situbondo" 
                                     title="{{ $news->title }}"
                                     fetchpriority="high"
                                     decoding="async"
                                     itemprop="image"
                                     onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                     class="w-full h-auto max-h-[750px] object-contain rounded-xl shadow-xs">
                            </figure>

                            <!-- SUMMARY HIGHLIGHT (IF AVAILABLE) -->
                            @if(!empty($news->summary))
                                <div class="bg-slate-50 border-l-4 border-theme-primary p-4 sm:p-5 rounded-r-xl mb-8 text-xs sm:text-sm text-slate-700 italic font-medium leading-relaxed break-words" itemprop="description">
                                    "{{ $news->summary }}"
                                </div>
                            @endif

                            <!-- ARTICLE CONTENT (EDITORIAL NEWS FORMAT) -->
                            <div class="article-prose break-words overflow-hidden" itemprop="articleBody">
                                @php
                                    $rawContent = $news->content ?? '';
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
                                <div class="flex items-center gap-2.5" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                    <div class="w-8 h-8 rounded-full bg-theme-primary text-white flex items-center justify-center font-bold text-xs shrink-0">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block font-bold text-slate-800 truncate" itemprop="name">{{ $news->author->name ?? 'Humas & Publikasi SMAN 2 Situbondo' }}</span>
                                        <span class="block text-[11px] text-slate-400 truncate">Pemberitaan Resmi Sekolah</span>
                                    </div>
                                </div>

                                <!-- SHARE BUTTONS -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="font-bold text-slate-700">Bagikan:</span>
                                    @php
                                        $shareUrl = urlencode(route('news.show', $news->id));
                                        $shareText = urlencode($news->title . ' - Berita Resmi SMAN 2 Situbondo');
                                    @endphp
                                    <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       title="Bagikan ke WhatsApp"
                                       class="w-8 h-8 rounded-full bg-green-500 hover:bg-green-600 text-white flex items-center justify-center transition shadow-xs">
                                        <i class="fab fa-whatsapp text-sm"></i>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       title="Bagikan ke Facebook"
                                       class="w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition shadow-xs">
                                        <i class="fab fa-facebook-f text-xs"></i>
                                    </a>
                                    <button type="button" 
                                            onclick="navigator.clipboard.writeText('{{ route('news.show', $news->id) }}'); alert('Tautan berita berhasil disalin!');" 
                                            title="Salin Tautan"
                                            class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition border border-slate-200 cursor-pointer">
                                        <i class="fas fa-link text-xs"></i>
                                    </button>
                                </div>
                            </div>

                        </article>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- SIDEBAR: CTA & BERITA LAINNYA (RIGHT) -->
                    <!-- ========================================================================= -->
                    <aside class="lg:col-span-4 w-full space-y-6 min-w-0" data-aos="fade-left" data-aos-duration="600">
                        
                        <!-- CALL TO ACTION (CTA) CARD (MATCHING ANNOUNCEMENT CTA) -->
                        <div class="bg-theme-gradient text-white rounded-3xl p-6 sm:p-7 shadow-lg relative overflow-hidden w-full">
                            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                            
                            <div class="relative z-10">
                                <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center text-white text-lg mb-4">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <h2 class="text-base sm:text-lg font-extrabold mb-2 font-headline break-words">Butuh Informasi Lanjutan?</h2>
                                <p class="text-xs text-slate-200 leading-relaxed mb-5">
                                    Hubungi layanan bantuan atau sekretariat SMA Negeri 2 Situbondo untuk konfirmasi informasi dan peliputan kegiatan.
                                </p>
                                
                                <div class="space-y-2.5">
                                    <a href="https://wa.me/628123456789" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold text-xs transition shadow-sm">
                                        <i class="fab fa-whatsapp text-sm"></i>
                                        <span>Hubungi via WhatsApp</span>
                                    </a>
                                    <a href="{{ route('news.index') }}" 
                                       class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs transition border border-white/20">
                                        <i class="fas fa-th-list text-xs"></i>
                                        <span>Lihat Semua Berita</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- BERITA LAINNYA / TERKAIT -->
                        @if($otherNews->isNotEmpty())
                            <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-sm w-full">
                                <h2 class="text-sm font-extrabold text-slate-900 font-headline uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="fas fa-newspaper text-theme-secondary text-xs"></i>
                                    <span>Berita Lainnya</span>
                                </h2>

                                <div class="space-y-4">
                                    @foreach ($otherNews as $item)
                                        @php
                                            $otherThumb = $resolveThumbnailUrl($item->thumbnail_url);
                                            $itemDate = $item->published_at ?? $item->created_at ?? \Carbon\Carbon::now();
                                            $itemCarbon = ($itemDate instanceof \Carbon\Carbon) ? $itemDate : \Carbon\Carbon::parse($itemDate);
                                            $day = $itemCarbon->format('d');
                                            $monthsShort = [
                                                1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR', 5 => 'MEI', 6 => 'JUN',
                                                7 => 'JUL', 8 => 'AGT', 9 => 'SEP', 10 => 'OKT', 11 => 'NOV', 12 => 'DES'
                                            ];
                                            $mShort = $monthsShort[$itemCarbon->month] ?? $itemCarbon->format('M');

                                            $itemCat = $item->category ?: 'Informasi';
                                            $itemCatLower = strtolower($itemCat);
                                            if (str_contains($itemCatLower, 'prestasi') || str_contains($itemCatLower, 'juara')) {
                                                $itemBadge = 'bg-amber-50 text-amber-900 border-amber-300';
                                            } elseif (str_contains($itemCatLower, 'akademik') || str_contains($itemCatLower, 'kurikulum') || str_contains($itemCatLower, 'ujian')) {
                                                $itemBadge = 'bg-blue-50 text-blue-900 border-blue-300';
                                            } elseif (str_contains($itemCatLower, 'event') || str_contains($itemCatLower, 'kegiatan') || str_contains($itemCatLower, 'lomba')) {
                                                $itemBadge = 'bg-orange-50 text-orange-950 border-orange-300';
                                            } elseif (str_contains($itemCatLower, 'kesiswaan') || str_contains($itemCatLower, 'osis') || str_contains($itemCatLower, 'ekstra')) {
                                                $itemBadge = 'bg-indigo-50 text-indigo-900 border-indigo-300';
                                            } else {
                                                $itemBadge = 'bg-emerald-50 text-emerald-950 border-emerald-300';
                                            }
                                        @endphp
                                        <a href="{{ route('news.show', $item->id) }}" class="flex gap-3.5 group items-start min-w-0">
                                            <div class="w-16 h-16 shrink-0 rounded-xl overflow-hidden bg-slate-100 relative">
                                                <img src="{{ $otherThumb }}" 
                                                     alt="Foto Berita: {{ $item->title }} - SMAN 2 Situbondo" 
                                                     title="{{ $item->title }}"
                                                     loading="lazy"
                                                     decoding="async"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}';"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            </div>
                                            <div class="flex-grow min-w-0">
                                                <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                                                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded border {{ $itemBadge }} uppercase shrink-0">
                                                        {{ $itemCat }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 font-medium shrink-0">
                                                        {{ $day }} {{ $mShort }}
                                                    </span>
                                                </div>
                                                <h3 class="text-xs font-bold text-slate-800 group-hover:text-theme-primary transition-colors line-clamp-2 leading-snug break-words">
                                                    {{ $item->title }}
                                                </h3>
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
