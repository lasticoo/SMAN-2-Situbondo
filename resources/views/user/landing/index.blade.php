@extends('layouts.app')

@section('content')
@php
    $getImageUrl = function (?string $path, string $default = '/build/assets/banner smada.png'): string {
        if (empty($path)) return $default;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            if (\Illuminate\Support\Str::contains($path, ['fbcdn.net', 'cdninstagram.com', 'instagram.com'])) {
                return route('instagram.proxy', ['url' => base64_encode($path)]);
            }
            return $path;
        }
        if (\Illuminate\Support\Str::startsWith($path, '/')) {
            return $path;
        }
        return \Illuminate\Support\Facades\Storage::url($path);
    };
@endphp

<!-- Dynamic High-Priority Preloading for Above-The-Fold Assets & Active Popup -->
@if(isset($activePopup) && $activePopup->image_url)
    <link rel="preload" as="image" href="{{ $getImageUrl($activePopup->image_url) }}" fetchpriority="high">
@endif

<!-- Tailwind CDN & Alpine.js for 100% Exact Layout Parsing -->
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- Font Awesome 6 Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Google Fonts: Inter & Hanken Grotesk for Figma Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800;900&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@1,600;1,700&display=swap" rel="stylesheet">

<!-- AOS (Animate On Scroll) Library CDN for Buttery Smooth 60FPS Animations (13KB Lightweight) -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>

<style>
  [x-cloak] { display: none !important; }

  :root {
    --primary-main: {{ $colorSetting?->primary_color ?? '#001c4d' }};
    --secondary-gold: {{ $colorSetting?->secondary_color ?? '#f59e0b' }};
    --primary-deep: color-mix(in srgb, var(--primary-main) 80%, black);
    --primary-light: color-mix(in srgb, var(--primary-main) 12%, white);
    --secondary-hover: color-mix(in srgb, var(--secondary-gold) 85%, black);
  }
  html { scroll-behavior: smooth; }
  body { font-family: 'Inter', sans-serif; overflow-x: hidden; width: 100%; }
  .font-headline { font-family: 'Hanken Grotesk', sans-serif; }
  .font-serif-italic { font-family: 'Playfair Display', serif; }

  /* Hardware Acceleration (GPU Layer Compositing) for Buttery Smooth 60FPS Animations */
  .spring-hover, .animate-tick-pulse, .animate-spin-slow {
    will-change: transform;
    transform: translateZ(0);
    backface-visibility: hidden;
  }

  /* Dynamic CSS Theme Classes mapped to Database Theme Colors */
  .bg-theme-primary { background-color: var(--primary-main) !important; }
  .bg-theme-primary-deep { background-color: var(--primary-deep) !important; }
  .text-theme-primary { color: var(--primary-main) !important; }
  .border-theme-primary { border-color: var(--primary-main) !important; }

  .bg-theme-secondary { background-color: var(--secondary-gold) !important; }
  .text-theme-secondary { color: var(--secondary-gold) !important; }
  .border-theme-secondary { border-color: var(--secondary-gold) !important; }

  /* Dynamic Hover Effects */
  .hover-text-primary:hover { color: var(--primary-main) !important; }
  .hover-bg-primary:hover { background-color: var(--primary-main) !important; color: #ffffff !important; }
  .hover-border-primary:hover { border-color: var(--primary-main) !important; }

  .hover-text-secondary:hover { color: var(--secondary-gold) !important; }
  .hover-bg-secondary:hover { background-color: var(--secondary-gold) !important; }

  /* ========================================================================== */
  /* HIGH-END SPRING PHYSICS & ULTRA-SMOOTH GPU ANIMATIONS                     */
  /* ========================================================================== */
  @media (prefers-reduced-motion: no-preference) {
    /* Ultra-Smooth Spring Physics Hover Scaling */
    .spring-hover {
      transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease !important;
      will-change: transform, box-shadow;
    }
    .spring-hover:hover {
      transform: translateY(-6px) scale(1.02) !important;
      box-shadow: 0 20px 30px -10px rgba(0, 28, 77, 0.2), 0 10px 15px -5px rgba(245, 158, 11, 0.15) !important;
    }

    /* Image Zoom Reveal Container */
    .img-zoom-box {
      overflow: hidden;
    }
    .img-zoom-box img {
      transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) !important;
      will-change: transform;
    }
    .img-zoom-box:hover img {
      transform: scale(1.08) !important;
    }

    /* Floating Micro-Animation */
    @keyframes subtle-float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }
    .animate-float {
      animation: subtle-float 4.5s ease-in-out infinite;
      will-change: transform;
    }

    /* Pulse Glow Ring */
    @keyframes pulse-glow {
      0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
      50% { box-shadow: 0 0 22px 6px rgba(245, 158, 11, 0.6); }
    }
    .glow-pulse {
      animation: pulse-glow 3s infinite;
    }

    /* Slow Rotating Clock Icon for Countdown */
    @keyframes spin-slow {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .animate-spin-slow {
      animation: spin-slow 12s linear infinite;
      will-change: transform;
    }

    /* Lightweight GPU-Accelerated Tick Animation for Countdown Seconds */
    @keyframes tick-pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.15); opacity: 0.9; }
    }
    .animate-tick-pulse {
      animation: tick-pulse 1s infinite ease-in-out;
      will-change: transform;
    }
  }
</style>

<div x-data="{ 
    activeTab: 'siswa', 
    siswaSubTab: 'total',
    showPopup: {{ (isset($activePopups) && count($activePopups) > 0) || $activePopup ? 'true' : 'false' }}, 
    popupIndex: 0,
    activeSlide: 0, 
    totalSlides: {{ count($banners) > 0 ? count($banners) : 1 }} 
}" class="min-h-screen font-sans antialiased text-gray-800 bg-gray-50 overflow-x-hidden">

    <!-- ------------------------------------------------------------- -->
    <!-- 1 & 2. SHARED NAVBAR & TOPBAR COMPONENT -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.navbar')

    <!-- ------------------------------------------------------------- -->
    <!-- 3. HERO SECTION (DYNAMIC BANNERS LOOP FROM DATABASE) -->
    <!-- ------------------------------------------------------------- -->
    <section class="relative h-[480px] sm:h-[550px] md:h-[600px] flex items-center overflow-hidden bg-theme-primary" x-init="if (totalSlides > 1) { setInterval(() => { activeSlide = (activeSlide + 1) % totalSlides }, 6000) }">
        @if(count($banners) > 0)
            @foreach($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0 w-full h-full flex items-center">
                    <img src="{{ $getImageUrl($banner->image_url) }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-transparent"></div>
                    <div class="container mx-auto px-4 relative z-10 text-white">
                        <div class="max-w-2xl space-y-3 sm:space-y-4">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight font-headline text-white drop-shadow-md" data-aos="fade-up" data-aos-duration="900" data-aos-delay="100">
                                {{ $banner->title }}
                            </h1>
                            <p class="text-shadow text-xs sm:text-sm md:text-base leading-relaxed text-slate-200" data-aos="fade-up" data-aos-duration="900" data-aos-delay="200">
                                <strong class="text-theme-secondary font-bold">Smada Prima</strong><br>
                                {{ $banner->description ?? 'Assalamu\'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning.' }}
                            </p>
                            <div class="flex flex-col sm:flex-row space-y-2.5 sm:space-y-0 sm:space-x-4 pt-2" data-aos="zoom-in-up" data-aos-duration="900" data-aos-delay="300">
                                <a class="bg-theme-primary-deep hover:opacity-90 text-white font-bold py-2.5 px-7 rounded-full transition duration-300 shadow-lg text-xs sm:text-sm uppercase tracking-wider spring-hover text-center" href="#spmb">SPMB</a>
                                <a class="border-2 border-theme-secondary text-theme-secondary hover:bg-theme-secondary hover:text-slate-950 font-bold py-2.5 px-7 rounded-full transition duration-300 text-xs sm:text-sm uppercase tracking-wider spring-hover text-center" href="#siklus">SIKLUS</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Fallback Hero Frame -->
            <div class="absolute inset-0 w-full h-full flex items-center bg-gradient-to-r from-black/90 via-black/70 to-transparent bg-theme-primary">
                <div class="container mx-auto px-4 relative z-10 text-white">
                    <div class="max-w-2xl space-y-3 sm:space-y-4">
                        <span class="inline-block px-3.5 py-1 rounded-full text-[11px] sm:text-xs font-semibold uppercase tracking-wider text-theme-secondary bg-white/10 border border-theme-secondary animate-float">
                            Selamat Hari Jadi SMA Negeri 2 Situbondo
                        </span>
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight font-headline text-white" data-aos="fade-up" data-aos-duration="900" data-aos-delay="100">SMA Negeri 2<br>Situbondo</h1>
                        <p class="text-shadow text-xs sm:text-sm md:text-base leading-relaxed text-slate-200" data-aos="fade-up" data-aos-duration="900" data-aos-delay="200">
                            <strong class="text-theme-secondary font-bold">Smada Prima</strong><br>
                            Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning. Berbagai informasi tentang pendidikan dapat diakses dalam website sekolah ini, Khususnya Informasi tentang SMAN 2 SITUBONDO.
                        </p>
                        <div class="flex flex-col sm:flex-row space-y-2.5 sm:space-y-0 sm:space-x-4 pt-2" data-aos="zoom-in-up" data-aos-duration="900" data-aos-delay="300">
                            <a class="bg-theme-primary-deep hover:opacity-90 text-white font-bold py-2.5 px-7 rounded-full transition duration-300 shadow-lg text-xs sm:text-sm uppercase tracking-wider spring-hover text-center" href="#spmb">SPMB</a>
                            <a class="border-2 border-theme-secondary text-theme-secondary hover:bg-theme-secondary hover:text-slate-950 font-bold py-2.5 px-7 rounded-full transition duration-300 text-xs sm:text-sm uppercase tracking-wider spring-hover text-center" href="#siklus">SIKLUS</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 4. QUICK LINKS GRID (DYNAMIC SECONDARY ACCENTS FOR ICONS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-8 sm:py-10 mb-10 md:mb-16 bg-white relative -mt-14 sm:-mt-16 z-20 mx-4 md:mx-auto md:max-w-4xl rounded-xl shadow-xl border-t-4 border-theme-secondary" data-aos="zoom-in-up" data-aos-duration="900" id="profil">
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 font-headline uppercase tracking-wider">PROFIL <span class="text-theme-primary">SEKOLAH</span></h2>
            <div class="w-16 h-1 bg-theme-secondary mx-auto mt-2 rounded-full"></div>
        </div>

        <!-- Row 1: 3 Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5 sm:gap-4 px-4 sm:px-8 pb-4">
            <a class="bg-theme-primary-deep text-white rounded-lg p-4 sm:p-5 flex flex-col items-center justify-center shadow-md group spring-hover" data-aos="zoom-in" data-aos-delay="100" href="#profil">
                <i class="fas fa-eye text-2xl sm:text-3xl mb-2.5 sm:mb-3 text-theme-secondary group-hover:scale-110 transition duration-300"></i>
                <span class="font-semibold text-xs sm:text-sm">Visi Misi</span>
            </a>
            <a class="bg-theme-primary-deep text-white rounded-lg p-4 sm:p-5 flex flex-col items-center justify-center shadow-md group spring-hover" data-aos="zoom-in" data-aos-delay="200" href="#profil">
                <i class="fas fa-sitemap text-2xl sm:text-3xl mb-2.5 sm:mb-3 text-theme-secondary group-hover:scale-110 transition duration-300"></i>
                <span class="font-semibold text-xs sm:text-sm">Struktur Organisasi</span>
            </a>
            <a class="bg-theme-primary-deep text-white rounded-lg p-4 sm:p-5 flex flex-col items-center justify-center shadow-md group spring-hover" data-aos="zoom-in" data-aos-delay="300" href="#siswa">
                <i class="fas fa-users text-2xl sm:text-3xl mb-2.5 sm:mb-3 text-theme-secondary group-hover:scale-110 transition duration-300"></i>
                <span class="font-semibold text-xs sm:text-sm">Data Siswa</span>
            </a>
        </div>

        <!-- Row 2: 2 Buttons Centered -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4 px-4 sm:px-8 pb-4 max-w-xl mx-auto">
            <a class="bg-theme-primary-deep text-white rounded-lg p-4 sm:p-5 flex flex-col items-center justify-center shadow-md group spring-hover" data-aos="zoom-in" data-aos-delay="400" href="#spmb">
                <i class="fas fa-user-plus text-2xl sm:text-3xl mb-2.5 sm:mb-3 text-theme-secondary group-hover:scale-110 transition duration-300"></i>
                <span class="font-semibold text-xs sm:text-sm">SPMB</span>
            </a>
            <a class="bg-theme-primary-deep text-white rounded-lg p-4 sm:p-5 flex flex-col items-center justify-center shadow-md group spring-hover" data-aos="zoom-in" data-aos-delay="500" href="#siklus">
                <i class="fas fa-user-graduate text-2xl sm:text-3xl mb-2.5 sm:mb-3 text-theme-secondary group-hover:scale-110 transition duration-300"></i>
                <span class="font-semibold text-xs sm:text-sm">SIKLUS</span>
            </a>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. SAPA KEPALA SEKOLAH (DYNAMIC THEME BACKGROUND & ACCENTS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-10 sm:py-12 text-white bg-theme-primary-deep">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-6 sm:mb-8 border-b-2 border-theme-secondary pb-2" data-aos="fade-up">
                <h2 class="text-xl sm:text-2xl font-bold font-headline">Sapa <span class="text-theme-secondary">Kepala Sekolah</span></h2>
                <a class="bg-theme-secondary text-slate-950 font-bold py-1.5 px-4 sm:px-5 rounded-full text-xs sm:text-sm hover:opacity-90 transition shadow spring-hover" href="#profil">
                    Lainnya <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 items-center">
                <div class="md:col-span-1 flex justify-center" data-aos="fade-right" data-aos-duration="1000">
                    <picture>
                        <source srcset="{{ asset('images/static/kepseksmada.webp') }}" type="image/webp">
                        <img alt="NIKMATIL HASANAH, S.Pd, M.Pd" class="w-48 sm:w-64 h-auto object-cover rounded-lg shadow-2xl border-2 border-theme-secondary spring-hover" src="{{ asset('images/static/kepseksmada.png') }}" loading="lazy" decoding="async" width="256" height="427">
                    </picture>
                </div>
                <div class="md:col-span-2 space-y-2.5 sm:space-y-3" data-aos="fade-left" data-aos-duration="1000">
                    <h3 class="font-bold text-xl sm:text-2xl leading-tight font-headline text-white">NIKMATIL HASANAH, S.Pd, M.Pd</h3>
                    <p class="text-xs sm:text-sm text-theme-secondary font-bold">19640516 200604 2 012</p>
                    <p class="text-xs sm:text-base text-slate-100 leading-relaxed text-justify">
                        Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning. Berbagai informasi tentang pendidikan dapat diakses dalam website sekolah ini, Khususnya Informasi tentang SMAN 2 SITUBONDO. Kami terus mengembangkan web ini mengikuti perkembangan teknologi yang sangat cepat. Dengan penuh harapan, kiranya website sekolah ini dapat memberikan manfaat yang maksimal dalam pengembangan dan pemanfaatannya untuk kebutuhan informasi dalam lingkungan sekolah SMAN 2 SITUBONDO and turut memajukan pendidikan di Indonesia. Wassalamu'alaikum Wr. Wb.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 6. NEWS SECTION (BERITA SMADA WITH PURE WHITE MATCHING CARD BACKDROPS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-10 sm:py-12 bg-slate-100" id="berita">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-6 sm:mb-8 border-b-2 border-theme-secondary pb-2" data-aos="fade-up">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 font-headline">Berita <span class="text-theme-secondary">Smada</span></h2>
                <a class="text-xs sm:text-sm text-gray-500 hover-text-primary transition flex items-center gap-1 font-semibold" href="#berita">
                    Selengkapnya <i class="fas fa-arrow-right text-xs text-theme-secondary"></i>
                </a>
            </div>

            @if(count($newsList) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                    <!-- Main News (Left 1 Featured Card) -->
                    @php $firstNews = $newsList->first(); @endphp
                    <div class="lg:col-span-1 bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 flex flex-col justify-between img-zoom-box spring-hover" data-aos="fade-right" data-aos-duration="900">
                        <div>
                            <div class="w-full h-48 sm:h-52 bg-white flex items-center justify-center overflow-hidden border-b border-gray-100">
                                <img alt="{{ $firstNews->title }}" class="w-full h-full object-contain p-1.5" src="{{ $getImageUrl($firstNews->thumbnail_url) }}">
                            </div>
                            <div class="p-4 sm:p-5 space-y-2">
                                <h3 class="font-bold text-base sm:text-lg leading-snug hover-text-primary font-headline text-gray-900 uppercase">
                                    <a href="#berita">{{ $firstNews->title }}</a>
                                </h3>
                                <p class="text-[11px] sm:text-xs text-gray-500 flex items-center gap-1 font-medium">
                                    <i class="far fa-calendar-alt text-theme-secondary"></i> {{ $firstNews->published_at ? $firstNews->published_at->format('F d, Y') : 'September 10, 2025' }}
                                </p>
                                <p class="text-xs sm:text-sm text-gray-700 line-clamp-4 leading-relaxed font-normal">
                                    {{ $firstNews->summary }}
                                </p>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5 pt-0">
                            <a class="inline-block border border-theme-primary text-theme-primary hover-bg-secondary hover:text-slate-950 px-4 py-1.5 rounded-full text-xs font-bold transition shadow-sm" href="#berita">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- News List Grid (Right 4 Side Cards in 2x2 with Zoomed-Out Image & Pure White Card Background) -->
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 content-start">
                        @foreach($newsList->slice(1, 4) as $idx => $item)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden flex items-stretch hover:shadow-md transition min-h-[160px] sm:h-[176px] img-zoom-box spring-hover" data-aos="fade-left" data-aos-duration="800" data-aos-delay="{{ ($idx + 1) * 100 }}">
                                <div class="w-28 sm:w-32 md:w-36 min-h-[160px] sm:h-[176px] bg-white flex items-center justify-center flex-shrink-0 border-r border-gray-100">
                                    <img alt="{{ $item->title }}" class="w-full h-full object-contain p-1.5" src="{{ $getImageUrl($item->thumbnail_url) }}">
                                </div>
                                <div class="p-3.5 sm:p-4 flex flex-col justify-between flex-1 min-h-[160px] sm:h-[176px]">
                                    <div>
                                        <h4 class="font-bold text-xs leading-tight hover-text-primary line-clamp-2 text-gray-900 uppercase font-headline">
                                            <a href="#berita">{{ $item->title }}</a>
                                        </h4>
                                        <p class="text-[10px] sm:text-[11px] text-gray-400 flex items-center gap-1 font-medium mt-1">
                                            <i class="far fa-calendar-alt text-theme-secondary text-[10px]"></i> {{ $item->published_at ? $item->published_at->format('F d, Y') : 'August 28, 2025' }}
                                        </p>
                                        <p class="text-xs text-gray-700 line-clamp-2 leading-relaxed mt-1 font-normal">
                                            {{ $item->summary }}
                                        </p>
                                    </div>
                                    <div class="pt-1">
                                        <a href="#berita" class="text-xs text-theme-primary font-bold hover:text-theme-secondary inline-flex items-center gap-1">
                                            Selengkapnya <i class="fas fa-arrow-right text-[10px] text-theme-secondary"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-10 text-gray-400 bg-white rounded-lg border border-dashed border-gray-200">
                    Belum ada berita yang dipublikasikan.
                </div>
            @endif
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 7. SMADA FACT SECTION (DYNAMIC THEME ACCENTS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-10 sm:py-12 text-white relative bg-theme-primary overflow-hidden">
        <div class="absolute inset-0 bg-theme-primary-deep bg-opacity-70"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            
            <div class="inline-block bg-white text-theme-primary font-bold py-1.5 sm:py-2 px-8 sm:px-12 rounded-full mb-6 text-lg sm:text-xl shadow border-2 border-theme-secondary" data-aos="zoom-in">
                SMADA <span class="text-theme-secondary">FACT</span>
            </div>

            <!-- Primary Tabs -->
            <div class="flex flex-wrap justify-center gap-2.5 sm:gap-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                <button @click="activeTab = 'siswa'" :class="activeTab === 'siswa' ? 'bg-theme-secondary text-slate-950 border-theme-secondary font-bold' : 'bg-transparent text-white border-white'" class="px-4 sm:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold border transition spring-hover">
                    PESERTA DIDIK
                </button>
                <button @click="activeTab = 'guru'" :class="activeTab === 'guru' ? 'bg-theme-secondary text-slate-950 border-theme-secondary font-bold' : 'bg-transparent text-white border-white'" class="px-4 sm:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold border transition spring-hover">
                    GURU
                </button>
                <button @click="activeTab = 'staf'" :class="activeTab === 'staf' ? 'bg-theme-secondary text-slate-950 border-theme-secondary font-bold' : 'bg-transparent text-white border-white'" class="px-4 sm:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold border transition spring-hover">
                    STAFF
                </button>
            </div>

            <!-- Sub-Filter Siswa (Filter Pills) -->
            <div x-show="activeTab === 'siswa'" class="flex flex-wrap justify-center gap-1.5 sm:gap-2 mb-8" data-aos="fade-up" data-aos-delay="200">
                <button @click="siswaSubTab = 'total'" :class="siswaSubTab === 'total' ? 'bg-white text-slate-900 font-bold border-2 border-theme-secondary' : 'bg-white/20 text-white'" class="px-3 sm:px-4 py-1 sm:py-1.5 rounded-full text-[11px] sm:text-xs transition">
                    Total Seluruh Siswa
                </button>
                <button @click="siswaSubTab = 'x'" :class="siswaSubTab === 'x' ? 'bg-white text-slate-900 font-bold border-2 border-theme-secondary' : 'bg-white/20 text-white'" class="px-3 sm:px-4 py-1 sm:py-1.5 rounded-full text-[11px] sm:text-xs transition">
                    Siswa Kelas X
                </button>
                <button @click="siswaSubTab = 'xi'" :class="siswaSubTab === 'xi' ? 'bg-white text-slate-900 font-bold border-2 border-theme-secondary' : 'bg-white/20 text-white'" class="px-3 sm:px-4 py-1 sm:py-1.5 rounded-full text-[11px] sm:text-xs transition">
                    Siswa Kelas XI
                </button>
                <button @click="siswaSubTab = 'xii'" :class="siswaSubTab === 'xii' ? 'bg-white text-slate-900 font-bold border-2 border-theme-secondary' : 'bg-white/20 text-white'" class="px-3 sm:px-4 py-1 sm:py-1.5 rounded-full text-[11px] sm:text-xs transition">
                    Siswa Kelas XII
                </button>
            </div>

            <!-- Tab Content: PESERTA DIDIK (1 Single Information Card Dynamic Filter) -->
            <div x-show="activeTab === 'siswa'" class="max-w-md mx-auto bg-black/50 border-2 border-theme-secondary rounded-lg p-6 sm:p-8 shadow-xl transition-all duration-300" data-aos="flip-up" data-aos-duration="800" data-aos-delay="300">
                <template x-if="siswaSubTab === 'total'">
                    <div>
                        <div class="text-4xl sm:text-5xl font-bold text-theme-secondary mb-2 font-headline">{{ $studentStats['total'] }}</div>
                        <div class="text-xs sm:text-sm font-medium uppercase tracking-wider text-slate-200">Total Seluruh Siswa</div>
                    </div>
                </template>
                <template x-if="siswaSubTab === 'x'">
                    <div>
                        <div class="text-4xl sm:text-5xl font-bold text-theme-secondary mb-2 font-headline">{{ $studentStats['kelas_10'] }}</div>
                        <div class="text-xs sm:text-sm font-medium uppercase tracking-wider text-slate-200">Siswa Kelas X</div>
                    </div>
                </template>
                <template x-if="siswaSubTab === 'xi'">
                    <div>
                        <div class="text-4xl sm:text-5xl font-bold text-theme-secondary mb-2 font-headline">{{ $studentStats['kelas_11'] }}</div>
                        <div class="text-xs sm:text-sm font-medium uppercase tracking-wider text-slate-200">Siswa Kelas XI</div>
                    </div>
                </template>
                <template x-if="siswaSubTab === 'xii'">
                    <div>
                        <div class="text-4xl sm:text-5xl font-bold text-theme-secondary mb-2 font-headline">{{ $studentStats['kelas_12'] }}</div>
                        <div class="text-xs sm:text-sm font-medium uppercase tracking-wider text-slate-200">Siswa Kelas XII</div>
                    </div>
                </template>
            </div>

            <!-- Tab Content: GURU -->
            <div x-show="activeTab === 'guru'" class="max-w-md mx-auto bg-black/50 border-2 border-theme-secondary rounded-lg p-6 sm:p-8 shadow-xl" data-aos="flip-up" data-aos-duration="800" data-aos-delay="300">
                <div class="text-4xl sm:text-5xl font-bold text-theme-secondary mb-2 font-headline">{{ $employeeStats['guru'] }}</div>
                <div class="text-xs sm:text-sm font-medium uppercase tracking-wider text-slate-200">Guru (Tenaga Pendidik)</div>
            </div>

            <!-- Tab Content: STAFF -->
            <div x-show="activeTab === 'staf'" class="max-w-md mx-auto bg-black/50 border-2 border-theme-secondary rounded-lg p-6 sm:p-8 shadow-xl" data-aos="flip-up" data-aos-duration="800" data-aos-delay="300">
                <div class="text-4xl sm:text-5xl font-bold text-theme-secondary mb-2 font-headline">{{ $employeeStats['staf'] }}</div>
                <div class="text-xs sm:text-sm font-medium uppercase tracking-wider text-slate-200">Staff (Tenaga Kependidikan)</div>
            </div>

        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 8. AGENDA & PENGUMUMAN + EKSTRAKURIKULER (IMAGE FULL HEIGHT & DYNAMIC THEME) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-10 sm:py-12 bg-white" id="pengumuman">
        <div class="container mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Agenda & Pengumuman Left -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-end border-b-2 border-theme-secondary pb-2" data-aos="fade-up">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 font-headline">Agenda &amp; <span class="text-theme-secondary">Pengumuman</span></h2>
                    <a class="text-xs sm:text-sm text-gray-500 hover-text-primary transition flex items-center gap-1 font-semibold" href="#pengumuman">
                        Selengkapnya <i class="fas fa-arrow-right text-xs text-theme-secondary"></i>
                    </a>
                </div>

                @if(count($announcementsList) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
                        <!-- Main Announcement Card -->
                        @php $firstAnn = $announcementsList->first(); @endphp
                        <div class="bg-gray-50 rounded-lg p-4 sm:p-5 shadow flex flex-col justify-between border border-gray-100 h-full spring-hover" data-aos="fade-right" data-aos-duration="900">
                            <div>
                                <img alt="{{ $firstAnn->title }}" class="w-full h-40 sm:h-44 object-cover mb-4 rounded-lg" src="{{ $getImageUrl($firstAnn->thumbnail_url) }}">
                                <h3 class="font-bold mb-2 text-slate-900 text-xs sm:text-sm uppercase leading-snug font-headline">{{ $firstAnn->title }}</h3>
                                <p class="text-[11px] sm:text-xs text-gray-500 mb-3 flex items-center gap-1 font-medium">
                                    <i class="far fa-calendar-alt text-theme-secondary"></i> {{ $firstAnn->published_at ? $firstAnn->published_at->format('F d, Y') : 'July 16, 2022' }}
                                </p>
                                <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed mb-4">
                                    {{ $firstAnn->summary }}
                                </p>
                            </div>
                            <div>
                                <a class="inline-block border border-theme-primary text-theme-primary hover-bg-secondary hover:text-slate-950 px-4 py-1.5 rounded-full text-xs font-bold transition shadow-sm" href="#pengumuman">Selengkapnya</a>
                            </div>
                        </div>

                        <!-- Side Announcement Cards (Fixed Height per Card matching Berita Smada Image Frame) -->
                        <div class="flex flex-col justify-start gap-4">
                            @foreach($announcementsList->slice(1, 2) as $idx => $annItem)
                                <div class="bg-gray-50 rounded-lg shadow border border-gray-100 flex items-stretch overflow-hidden hover:shadow-md transition min-h-[160px] sm:h-[180px] img-zoom-box spring-hover" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ ($idx + 1) * 200 }}">
                                    <div class="w-28 sm:w-32 md:w-36 min-h-[160px] sm:h-[180px] bg-white flex items-center justify-center flex-shrink-0 border-r border-gray-100">
                                        <img alt="{{ $annItem->title }}" class="w-full h-full object-contain p-1.5" src="{{ $getImageUrl($annItem->thumbnail_url) }}">
                                    </div>
                                    <div class="p-3.5 sm:p-4 flex flex-col justify-between flex-1 min-h-[160px] sm:h-[180px]">
                                        <div>
                                            <h3 class="font-bold mb-1 text-xs text-slate-900 uppercase leading-snug font-headline">{{ $annItem->title }}</h3>
                                            <p class="text-[10px] sm:text-[11px] text-gray-400 mb-1.5 flex items-center gap-1 font-medium">
                                                <i class="far fa-calendar-alt text-theme-secondary"></i> {{ $annItem->published_at ? $annItem->published_at->format('F d, Y') : 'May 05, 2022' }}
                                            </p>
                                            <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed mb-2">
                                                {{ $annItem->summary }}
                                            </p>
                                        </div>
                                        <div>
                                            <a class="inline-block border border-theme-primary text-theme-primary hover-bg-secondary hover:text-slate-950 px-3 py-1 rounded-full text-[11px] font-bold transition shadow-sm" href="#pengumuman">Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                        Belum ada pengumuman yang dipublikasikan.
                    </div>
                @endif
            </div>

            <!-- Ekstrakurikuler Right -->
            <div data-aos="fade-left">
                <div class="mb-6 border-b-2 border-theme-secondary pb-2">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 font-headline">Ekstrakurikuler</h2>
                </div>
                <ul class="space-y-4 font-semibold text-xs sm:text-sm text-gray-700">
                    <li class="border-b border-gray-200 pb-3 hover-text-primary transition" data-aos="fade-left" data-aos-delay="100"><span class="text-theme-secondary font-bold mr-2">&bull;</span><a href="#ekstra">Musik</a></li>
                    <li class="border-b border-gray-200 pb-3 hover-text-primary transition" data-aos="fade-left" data-aos-delay="200"><span class="text-theme-secondary font-bold mr-2">&bull;</span><a href="#ekstra">Kharismada</a></li>
                    <li class="border-b border-gray-200 pb-3 hover-text-primary transition" data-aos="fade-left" data-aos-delay="300"><span class="text-theme-secondary font-bold mr-2">&bull;</span><a href="#ekstra">Jurnalistik</a></li>
                    <li class="border-b border-gray-200 pb-3 hover-text-primary transition" data-aos="fade-left" data-aos-delay="400"><span class="text-theme-secondary font-bold mr-2">&bull;</span><a href="#ekstra">Pecinta Alam</a></li>
                </ul>
                <a class="inline-block border border-theme-primary text-theme-primary hover-bg-secondary hover:text-slate-950 px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition mt-4 w-full text-center shadow-sm spring-hover" data-aos="fade-up" data-aos-delay="500" href="#ekstra">Ekstrakurikuler Lainnya</a>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 9. ATMOSFER SEKOLAH (INSTAGRAM REAL FEED - DYNAMIC THEME ACCENTS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-12 sm:py-16 text-white relative overflow-hidden bg-theme-primary-deep" id="media">
        <!-- Glow Overlay Behind Carousel -->
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-theme-secondary opacity-15 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-8 sm:mb-10" data-aos="fade-up">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold tracking-widest font-headline uppercase">ATMOSFER <span class="text-theme-secondary">SEKOLAH</span></h2>
                <div class="w-20 h-1 bg-theme-secondary mx-auto mt-2 rounded-full"></div>
            </div>

            <!-- Horizontal Carousel Frame - 10 Posts Real IG Scraping Direct Click -->
            <div class="flex items-center space-x-3.5 sm:space-x-4 overflow-x-auto pb-6 sm:pb-8 scrollbar-thin scrollbar-thumb-theme-secondary max-w-6xl mx-auto" data-aos="zoom-in-up" data-aos-duration="900" style="-webkit-overflow-scrolling: touch;">
                @if(!empty($instagramPosts) && count($instagramPosts) > 0)
                    @foreach($instagramPosts as $idx => $photoUrl)
                        <a href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener noreferrer" class="block flex-shrink-0 w-56 sm:w-64 h-72 sm:h-80 rounded-xl overflow-hidden shadow-xl transition transform cursor-pointer border-2 border-white/20 hover:border-theme-secondary bg-black/40 spring-hover" title="Klik untuk membuka postingan di Instagram @sman2situbondoofficial">
                            <img alt="Atmosfer Sekolah {{ $idx + 1 }}" class="w-full h-full object-cover" src="{{ $getImageUrl($photoUrl) }}" referrerpolicy="no-referrer">
                        </a>
                    @endforeach
                @else
                    <a href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener noreferrer" class="w-56 sm:w-64 h-72 sm:h-80 flex-shrink-0 bg-black/40 rounded-xl overflow-hidden shadow-lg border-2 border-white/20 hover:border-theme-secondary block spring-hover">
                        <img alt="Atmosfer 1" class="w-full h-full object-cover" src="/build/assets/banner smada.png">
                    </a>
                    <a href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener noreferrer" class="w-56 sm:w-64 h-72 sm:h-80 flex-shrink-0 bg-black/40 rounded-xl overflow-hidden shadow-lg border-2 border-white/20 hover:border-theme-secondary block spring-hover">
                        <img alt="Atmosfer 2 Poster" class="w-full h-full object-cover" src="/build/assets/banner smada.png">
                    </a>
                    <a href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener noreferrer" class="w-56 sm:w-64 h-72 sm:h-80 flex-shrink-0 bg-black/40 rounded-xl overflow-hidden shadow-lg border-2 border-white/20 hover:border-theme-secondary block spring-hover">
                        <img alt="Atmosfer 3" class="w-full h-full object-cover" src="/build/assets/kepala sekolah smada.png">
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 10. MOTTO BANNER SECTION (DYNAMIC THEME ACCENTS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-8 sm:py-10 bg-white border-y-4 border-theme-secondary">
        <div class="container mx-auto px-4 text-center" data-aos="zoom-in" data-aos-duration="900">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-serif-italic text-theme-primary font-headline">
                Dari<br>
                <span class="font-bold uppercase tracking-widest text-3xl sm:text-4xl md:text-5xl text-theme-secondary font-sans">SMADA PRIMA</span><br>
                Untuk Bangsa
            </h2>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 11. FOOTER (PREMIUM CLEAN DYNAMIC THEME) -->
    <!-- ------------------------------------------------------------- -->
    <!-- ------------------------------------------------------------- -->
    <!-- 6. SHARED FOOTER COMPONENT -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')

    <!-- ------------------------------------------------------------- -->
    <!-- POP-UP EVENT MODAL (WITH DYNAMIC REALTIME COUNTDOWN TO END_DATE) -->
    <!-- ------------------------------------------------------------- -->
    @php
        $popupsList = isset($activePopups) && count($activePopups) > 0 ? $activePopups : ($activePopup ? collect([$activePopup]) : collect());
    @endphp

    @if(count($popupsList) > 0)
        <div x-cloak x-show="showPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-xl overflow-hidden max-w-md w-full shadow-2xl relative border border-gray-200">
                <!-- Close Button (Sequential Close if More Popups Exist) -->
                <button @click="if (popupIndex < {{ count($popupsList) - 1 }}) { popupIndex++ } else { showPopup = false }" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/70 text-white flex items-center justify-center text-xs font-bold z-20 hover:bg-red-600 transition shadow" title="Tutup">
                    <i class="fas fa-times"></i>
                </button>

                @foreach($popupsList as $pIdx => $popupItem)
                    <div x-cloak x-show="popupIndex === {{ $pIdx }}" x-transition:enter="transition ease-out duration-200 transform-gpu" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        @if($popupItem->image_url)
                            <div class="w-full bg-white flex items-center justify-center overflow-hidden border-b border-gray-100 p-2 sm:p-3 min-h-[180px] sm:min-h-[220px]">
                                <img src="{{ $getImageUrl($popupItem->image_url) }}" alt="{{ $popupItem->title }}" class="w-full h-auto max-h-[300px] sm:max-h-[360px] object-contain rounded-lg transform-gpu" loading="eager" fetchpriority="high" decoding="sync">
                            </div>
                        @endif
                        <div class="p-4 sm:p-5 space-y-3">
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base leading-tight uppercase font-headline">{{ $popupItem->title }}</h4>
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $popupItem->description }}</p>

                            <!-- Dynamic Real-time Countdown Timer targeting end_date -->
                            @if($popupItem->end_date)
                                <div x-data="popupCountdown('{{ \Illuminate\Support\Carbon::parse($popupItem->end_date)->endOfDay()->toIso8601String() }}')"
                                     x-init="startTimer()"
                                     x-destroy="stopTimer()"
                                     class="my-3">
                                    <template x-if="isValid && !isExpired">
                                        <div class="bg-slate-50 rounded-xl p-3 border border-gray-200/80 shadow-inner">
                                            <div class="flex items-center justify-between text-[11px] font-semibold text-gray-600 mb-2">
                                                <span class="flex items-center gap-1.5 text-theme-primary font-headline uppercase tracking-wider">
                                                    <i class="far fa-clock text-theme-secondary text-xs animate-spin-slow"></i> Berlangsung Sampai:
                                                </span>
                                                <span class="text-[10px] bg-theme-secondary text-slate-950 font-bold px-2.5 py-0.5 rounded-full shadow-sm flex items-center gap-1">
                                                    <i class="fas fa-bullhorn text-[9px]"></i> INFO PENTING
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-4 gap-1.5 sm:gap-2 text-center">
                                                <div class="bg-white rounded-lg p-1.5 sm:p-2 shadow-sm border border-gray-100 spring-hover">
                                                    <span class="block text-sm sm:text-base font-extrabold text-theme-primary font-headline" x-text="days">0</span>
                                                    <span class="block text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Hari</span>
                                                </div>
                                                <div class="bg-white rounded-lg p-1.5 sm:p-2 shadow-sm border border-gray-100 spring-hover">
                                                    <span class="block text-sm sm:text-base font-extrabold text-theme-primary font-headline" x-text="hours">00</span>
                                                    <span class="block text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jam</span>
                                                </div>
                                                <div class="bg-white rounded-lg p-1.5 sm:p-2 shadow-sm border border-gray-100 spring-hover">
                                                    <span class="block text-sm sm:text-base font-extrabold text-theme-primary font-headline" x-text="minutes">00</span>
                                                    <span class="block text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Menit</span>
                                                </div>
                                                <div class="bg-white rounded-lg p-1.5 sm:p-2 shadow-sm border border-gray-100 spring-hover">
                                                    <span class="block text-sm sm:text-base font-extrabold text-theme-secondary font-headline animate-tick-pulse" x-text="seconds">00</span>
                                                    <span class="block text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Detik</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            @endif

                            <div class="pt-1">
                                <button @click="if (popupIndex < {{ count($popupsList) - 1 }}) { popupIndex++ } else { showPopup = false }" class="w-full py-2 rounded-lg font-bold text-xs text-white uppercase tracking-wider transition shadow bg-theme-primary hover:opacity-90 spring-hover">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<!-- ------------------------------------------------------------- -->
<!-- INITIALIZE AOS ANIMATION ENGINE & POPUP COUNTDOWN CONTROLLER -->
<!-- ------------------------------------------------------------- -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 60,
                disableMutationObserver: false
            });
        }
    });

    /**
     * Client-Side Lightweight Real-Time Popup Countdown Engine
     */
    function popupCountdown(targetIso) {
        return {
            targetTime: targetIso ? new Date(targetIso).getTime() : null,
            days: 0,
            hours: '00',
            minutes: '00',
            seconds: '00',
            isValid: false,
            isExpired: false,
            timer: null,

            startTimer() {
                if (!this.targetTime || isNaN(this.targetTime)) {
                    this.isValid = false;
                    return;
                }
                this.isValid = true;
                this.updateCountdown();
                this.timer = setInterval(() => {
                    this.updateCountdown();
                }, 1000);
            },

            updateCountdown() {
                const now = new Date().getTime();
                const distance = this.targetTime - now;

                if (distance <= 0) {
                    this.isExpired = true;
                    this.days = 0;
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    this.stopTimer();
                    return;
                }

                this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
            },

            stopTimer() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            }
        };
    }
</script>
@endsection
