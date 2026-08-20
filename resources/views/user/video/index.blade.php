@extends('layouts.app')

@php
    // Universal 10-Tier Thumbnail Resolver Engine for Media Videos
    $resolveThumbnailUrl = function (?string $thumbPath, ?string $youtubeId = null): string {
        static $resolvedCache = [];
        $cacheKey = ($thumbPath ?? '') . '|' . ($youtubeId ?? '');
        if (isset($resolvedCache[$cacheKey])) {
            return $resolvedCache[$cacheKey];
        }

        $clean = trim(str_replace('\\', '/', $thumbPath ?? ''));
        
        // 1. Data URI atau Full Web URL
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
        }

        // 8. Auto High-Res YouTube Thumbnail
        if (!empty($youtubeId)) {
            return $resolvedCache[$cacheKey] = "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
        }

        // 9. Static School Placeholder
        return $resolvedCache[$cacheKey] = asset('images/static/gambar_profile_statis.jpg');
    };

    $ogImage = (isset($videos[0])) 
        ? $resolveThumbnailUrl($videos[0]->thumbnail_url, $videos[0]->youtube_id) 
        : asset('images/static/gambar_profile_statis.jpg');
@endphp

@section('title', 'Galeri Video SMADA - SMA Negeri 2 Situbondo')

@push('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="Dokumentasi video kegiatan, prestasi, profil, dan momen berharga civitas akademik SMAN 2 Situbondo dalam format video resmi.">
    <meta name="author" content="SMA Negeri 2 Situbondo">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot-image" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ route('video.index') }}">
    @if(isset($videos[0]))
        <link rel="preload" as="image" href="{{ $ogImage }}" fetchpriority="high">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SMA Negeri 2 Situbondo">
    <meta property="og:title" content="Galeri Video SMADA - SMA Negeri 2 Situbondo">
    <meta property="og:description" content="Dokumentasi kegiatan, prestasi, dan momen berharga civitas akademik SMAN 2 Situbondo dalam format video.">
    <meta property="og:url" content="{{ route('video.index') }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Galeri Video SMADA - SMA Negeri 2 Situbondo">
    <meta name="twitter:description" content="Dokumentasi kegiatan, prestasi, dan momen berharga civitas akademik SMAN 2 Situbondo dalam format video.">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Schema.org JSON-LD Structured Data for Google Search & Google Video -->
    @php
        $videoSchemaList = [];
        foreach ($videos as $item) {
            $vThumb = $resolveThumbnailUrl($item->thumbnail_url, $item->youtube_id);
            $cleanTitle = trim(preg_replace('/^\[.*?\]\s*/', '', $item->title ?? ''));
            $embedUrl = !empty($item->youtube_id) 
                ? "https://www.youtube.com/embed/{$item->youtube_id}" 
                : ($item->youtube_url ?? route('video.index'));

            $videoSchemaList[] = [
                '@type' => 'VideoObject',
                'name' => $cleanTitle ?: 'Dokumentasi Video SMAN 2 Situbondo',
                'description' => 'Dokumentasi video resmi ' . ($cleanTitle ?: 'SMAN 2 Situbondo') . ' di SMA Negeri 2 Situbondo.',
                'thumbnailUrl' => [$vThumb],
                'uploadDate' => ($item->created_at ?? \Illuminate\Support\Carbon::now())->toIso8601String(),
                'contentUrl' => $item->youtube_url ?? route('video.index'),
                'embedUrl' => $embedUrl,
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'SMA Negeri 2 Situbondo',
                    'url' => url('/'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('images/static/gambar_profile_statis.jpg'),
                    ],
                ],
            ];
        }

        $videoJsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'CollectionPage',
                    '@id' => route('video.index') . '#collection',
                    'name' => 'Galeri Video SMADA - SMA Negeri 2 Situbondo',
                    'description' => 'Dokumentasi kegiatan, prestasi, dan momen berharga civitas akademik SMAN 2 Situbondo dalam format video.',
                    'url' => route('video.index'),
                    'inLanguage' => 'id-ID',
                    'hasPart' => $videoSchemaList,
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => route('video.index') . '#breadcrumb',
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
                            'name' => 'Galeri Video',
                            'item' => route('video.index'),
                        ],
                    ],
                ],
            ],
        ];
    @endphp

    <script type="application/ld+json">
        {!! json_encode($videoJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
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
    .spring-hover, .video-card, .img-zoom-box img {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        perspective: 1000px;
    }

    /* Ultra-Smooth Spring Physics Hover Scaling & Rendering Isolation */
    .video-card {
        contain: layout paint;
        content-visibility: auto;
        contain-intrinsic-size: 320px;
    }
    @media (prefers-reduced-motion: no-preference) {
        .video-card {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease !important;
        }
        .video-card:hover {
            transform: translateY(-5px) scale(1.01) translate3d(0, 0, 0) !important;
            box-shadow: 0 20px 35px -10px rgba(0, 28, 77, 0.16), 0 10px 20px -5px rgba(245, 158, 11, 0.12) !important;
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
    .video-card:hover .img-zoom-box img {
        transform: scale(1.08) translate3d(0, 0, 0) !important;
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
         videoModalOpen: false,
         activeVideoTitle: '',
         activeVideoDate: '',
         activeYoutubeId: '',
         activeYoutubeUrl: '',
         openVideoModal(title, date, ytId, ytUrl) {
             this.activeVideoTitle = title;
             this.activeVideoDate = date;
             this.activeYoutubeId = ytId;
             this.activeYoutubeUrl = ytUrl;
             this.videoModalOpen = true;
             document.body.classList.add('overflow-hidden');
         },
         closeVideoModal() {
             this.videoModalOpen = false;
             this.activeYoutubeId = '';
             document.body.classList.remove('overflow-hidden');
         }
     }"
     @keydown.escape.window="closeVideoModal()">
    
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
                    <span>Dokumentasi &amp; Video Resmi</span>
                </div>

                <!-- Main Title "Galeri Video SMADA" in Clean Bold White -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white font-headline tracking-tight mb-2 sm:mb-3">
                    Galeri Video SMADA
                </h1>
                <div class="w-20 h-1.5 bg-theme-secondary mx-auto rounded-full mb-4 shadow-xs"></div>
                
                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm md:text-base text-slate-200 leading-relaxed max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="150">
                    Dokumentasi kegiatan, prestasi, dan momen berharga civitas akademik SMAN 2 Situbondo dalam format video.
                </p>

            </div>
        </section>

        <!-- ------------------------------------------------------------- -->
        <!-- 3. MAIN VIDEO GALLERY CONTENT (OPTIMIZED 3-COLUMN GRID)       -->
        <!-- ------------------------------------------------------------- -->
        <main class="bg-white py-10 sm:py-14 w-full">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

                @if($videos->isEmpty())
                    <!-- EMPTY STATE -->
                    <div class="bg-slate-50 rounded-3xl border border-slate-200/80 p-12 text-center shadow-xs max-w-xl mx-auto my-8">
                        <div class="w-16 h-16 bg-white text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-xs">
                            <i class="fas fa-video-slash"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Video</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mb-5">
                            Saat ini belum ada dokumentasi video yang dipublikasikan.
                        </p>
                    </div>
                @else

                    <!-- RESPONSIVE 3-COLUMN VIDEO CARD GRID (FAST & SMOOTH) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 items-stretch">
                        
                        @foreach ($videos as $index => $video)
                            @php
                                $thumbUrl = $resolveThumbnailUrl($video->thumbnail_url, $video->youtube_id);
                                $cleanTitle = trim(preg_replace('/^\[.*?\]\s*/', '', $video->title ?? ''));
                                $formattedDate = $video->created_at ? $video->created_at->translatedFormat('d M Y') : 'Terbaru';
                                $ytId = $video->youtube_id ?? '';
                                $ytUrl = $video->youtube_url ?? "https://www.youtube.com/watch?v={$ytId}";
                            @endphp

                            <!-- Video Card Component -->
                            <article class="video-card group relative flex flex-col justify-between bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-theme-primary/40 overflow-hidden cursor-pointer"
                                     data-aos="fade-up"
                                     data-aos-delay="{{ ($index % 3) * 100 }}"
                                     data-aos-duration="600"
                                     itemscope itemtype="https://schema.org/VideoObject"
                                     @click="openVideoModal('{{ addslashes($cleanTitle) }}', '{{ $formattedDate }}', '{{ $ytId }}', '{{ addslashes($ytUrl) }}')">
                                
                                <meta itemprop="name" content="{{ $cleanTitle }}">
                                <meta itemprop="uploadDate" content="{{ ($video->created_at ?? \Illuminate\Support\Carbon::now())->toIso8601String() }}">
                                <meta itemprop="thumbnailUrl" content="{{ $thumbUrl }}">

                                <!-- Top Thumbnail Area -->
                                <div class="aspect-video w-full overflow-hidden relative bg-slate-900 img-zoom-box">
                                    <img src="{{ $thumbUrl }}" 
                                         alt="Thumbnail {{ $cleanTitle }} - SMADA" 
                                         title="{{ $cleanTitle }}"
                                         @if($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" @endif
                                         decoding="async"
                                         onerror="if(!this.dataset.fallback){ this.dataset.fallback='1'; this.src='{{ !empty($video->youtube_id) ? "https://img.youtube.com/vi/{$video->youtube_id}/hqdefault.jpg" : asset('images/static/gambar_profile_statis.jpg') }}'; } else { this.src='{{ asset('images/static/gambar_profile_statis.jpg') }}'; }"
                                         class="w-full h-full object-cover">
                                    
                                    <!-- Play Button Overlay -->
                                    <div class="absolute inset-0 bg-slate-950/20 group-hover:bg-slate-950/40 transition-colors duration-300 flex items-center justify-center pointer-events-none">
                                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-theme-secondary text-slate-950 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-play text-sm sm:text-base ml-0.5"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bottom Card Info Area -->
                                <div class="p-5 sm:p-6 flex flex-col justify-between flex-1 bg-white">
                                    <div>
                                        <!-- Vertical Accent Bar + Date -->
                                        <div class="flex items-center gap-2 mb-2.5">
                                            <span class="w-1 h-3.5 bg-theme-secondary rounded-full inline-block shrink-0"></span>
                                            <span class="text-xs font-semibold text-slate-500 tracking-wide">{{ $formattedDate }}</span>
                                        </div>

                                        <!-- Video Title (2-Line Clamp) -->
                                        <h3 class="text-base sm:text-lg font-bold font-headline text-slate-900 line-clamp-2 leading-snug group-hover:text-theme-primary transition-colors" itemprop="caption">
                                            {{ $cleanTitle }}
                                        </h3>
                                    </div>

                                    <!-- Bottom Action Link "Tonton Video" -->
                                    <div class="pt-4 mt-auto border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-700 group-hover:text-theme-primary transition-colors">
                                        <span class="inline-flex items-center gap-1.5">
                                            <span>Tonton Video</span>
                                            <i class="fas fa-external-link-alt text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                                        </span>
                                        <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity text-theme-secondary"></i>
                                    </div>
                                </div>

                            </article>
                        @endforeach

                    </div>

                    <!-- PAGINATION NAVIGATION -->
                    <div class="mt-12 flex justify-center">
                        {{ $videos->links('user.video.partials.pagination') }}
                    </div>

                @endif

            </div>
        </main>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 4. INTERACTIVE YOUTUBE VIDEO PLAYER MODAL                     -->
    <!-- ------------------------------------------------------------- -->
    <div x-show="videoModalOpen" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] bg-slate-950/90 backdrop-blur-md flex items-center justify-center p-4 sm:p-6"
         @click.self="closeVideoModal()">
        
        <!-- Modal Top Action Buttons (Close & External Link) -->
        <div class="absolute top-4 right-4 sm:top-6 sm:right-6 flex items-center gap-2.5 z-20">
            <!-- Open in YouTube External Button -->
            <a :href="activeYoutubeUrl" 
               target="_blank" 
               rel="noopener noreferrer"
               title="Buka di YouTube"
               class="h-10 sm:h-11 px-3.5 sm:px-4 rounded-full bg-theme-secondary hover:scale-105 active:scale-95 text-slate-950 flex items-center gap-2 transition font-bold text-xs shadow-lg spring-hover cursor-pointer">
                <i class="fab fa-youtube text-sm"></i>
                <span class="hidden sm:inline">Buka di YouTube</span>
            </a>

            <!-- Close Button -->
            <button type="button" 
                    @click="closeVideoModal()"
                    title="Tutup"
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition border border-white/20 text-lg cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="relative max-w-4xl w-full flex flex-col items-center justify-center"
             x-show="videoModalOpen"
             x-transition:enter="transition ease-out duration-300 transform-gpu"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform-gpu"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- 16:9 Responsive Video Iframe Embed -->
            <div class="w-full aspect-video rounded-2xl overflow-hidden shadow-2xl border border-white/10 bg-black">
                <template x-if="videoModalOpen && activeYoutubeId">
                    <iframe :src="'https://www.youtube.com/embed/' + activeYoutubeId + '?autoplay=1&rel=0&enablejsapi=1'"
                            title="Pemutar Video SMADA"
                            class="w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                    </iframe>
                </template>
            </div>

            <!-- Modal Video Info Banner -->
            <div class="mt-4 text-center text-white max-w-2xl px-4 flex flex-col items-center gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-3 bg-theme-secondary rounded-full"></span>
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-wider" x-text="activeVideoDate"></span>
                </div>
                <h3 class="text-base sm:text-lg font-bold font-headline" x-text="activeVideoTitle"></h3>
            </div>

        </div>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. SHARED FOOTER COMPONENT                                    -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')
</div>
@endsection
