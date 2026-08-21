@extends('layouts.app')

@section('content')
@php
    $primaryColor = $colorSetting?->primary_color ?? '#1e3a8a';
    $secondaryColor = $colorSetting?->secondary_color ?? '#f59e0b';

    $getImageUrl = function (?string $path, string $default = '/build/assets/banner smada.png'): string {
        if (empty($path)) return $default;
        
        // 1. Normalize Windows backslashes, leading/trailing whitespace
        $clean = trim(str_replace('\\', '/', $path));
        if (empty($clean)) return $default;
        
        // 2. Data URI or Full External Web URL (http, https, protocol-relative)
        if (\Illuminate\Support\Str::startsWith($clean, ['http://', 'https://', '//', 'data:image/'])) {
            return $clean;
        }
        
        // 3. Absolute local filesystem path on server (e.g. C:/laragon/www/smada/public/...)
        $publicBasePath = str_replace('\\', '/', public_path());
        if (\Illuminate\Support\Str::startsWith($clean, $publicBasePath)) {
            $rel = ltrim(substr($clean, strlen($publicBasePath)), '/');
            return asset($rel);
        }
        
        // 4. Starts with / (e.g. /images/..., /storage/..., /build/..., /uploads/...)
        if (\Illuminate\Support\Str::startsWith($clean, '/')) {
            return asset(ltrim($clean, '/'));
        }
        
        // 5. Stored as public/... or app/public/...
        if (\Illuminate\Support\Str::startsWith($clean, 'public/')) {
            $clean = substr($clean, 7);
        }
        if (\Illuminate\Support\Str::startsWith($clean, 'app/public/')) {
            $clean = substr($clean, 11);
        }
        
        // 6. Explicit storage prefix (e.g. storage/school_profile/xyz.png)
        if (\Illuminate\Support\Str::startsWith($clean, 'storage/')) {
            return asset($clean);
        }
        
        // 7. Direct file in public/ directory (e.g. images/static/..., build/assets/..., uploads/...)
        if (file_exists(public_path($clean))) {
            return asset($clean);
        }
        
        // 8. Stored on Laravel 'public' disk (e.g. school_profile/xyz.png, structure/xyz.png)
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($clean)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($clean);
        }
        
        // 9. Stored in subfolder without folder prefix (check common folders for filename only)
        $subfolders = ['school_profile/', 'structure/', 'structures/', 'images/static/', 'images/', 'uploads/'];
        foreach ($subfolders as $folder) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($folder . $clean)) {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($folder . $clean);
            }
            if (file_exists(public_path($folder . $clean))) {
                return asset($folder . $clean);
            }
            if (file_exists(public_path('storage/' . $folder . $clean))) {
                return asset('storage/' . $folder . $clean);
            }
        }
        
        // 10. Fallback: Storage disk URL or Asset URL
        return \Illuminate\Support\Facades\Storage::disk('public')->url($clean);
    };
@endphp

<!-- High-Speed Resource Hints & Asset Preloading -->
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
<link rel="dns-prefetch" href="//unpkg.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Tailwind CDN & Alpine.js for 100% Exact Layout & Animation Parsing Across All Pages -->
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Font Awesome 6 Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts: Inter, Plus Jakarta Sans, & Hanken Grotesk for Figma Typography -->
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800;900&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
<!-- AOS (Animate On Scroll) Library CDN for Buttery Smooth 60FPS Animations -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>

<style>
    :root {
        --primary-main: {{ $primaryColor }};
        --secondary-gold: {{ $secondaryColor }};
        --primary-deep: color-mix(in srgb, var(--primary-main) 80%, black);
        --primary-light: color-mix(in srgb, var(--primary-main) 12%, white);
        --secondary-hover: color-mix(in srgb, var(--secondary-gold) 85%, black);
    }
    
    [x-cloak] { display: none !important; }
    
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', sans-serif; overflow-x: hidden; width: 100%; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    .font-headline { font-family: 'Hanken Grotesk', sans-serif; }

    /* Hardware Acceleration (GPU Layer Compositing) for Buttery Smooth 60FPS Animations */
    .spring-hover, .animate-tick-pulse, .animate-spin-slow, .img-zoom-box img, .animate-float, .glow-pulse {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        perspective: 1000px;
    }

    /* Dynamic CSS Theme Classes Mapped to Database Color Settings */
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

    /* Image Zoom Reveal Container */
    .img-zoom-box {
        overflow: hidden;
        contain: paint;
    }
    .img-zoom-box img {
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    .img-zoom-box:hover img {
        transform: scale(1.08) translate3d(0, 0, 0) !important;
    }

    /* Ultra-Smooth Spring Physics Hover Scaling */
    @media (prefers-reduced-motion: no-preference) {
        .spring-hover {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease !important;
            will-change: transform, box-shadow;
        }
        .spring-hover:hover {
            transform: translateY(-8px) scale(1.02) translate3d(0, 0, 0) !important;
            box-shadow: 0 20px 35px -10px rgba(0, 28, 77, 0.18), 0 10px 20px -5px rgba(245, 158, 11, 0.2) !important;
        }
    }

    /* Floating Micro-Animation */
    @keyframes subtle-float {
        0%, 100% { transform: translateY(0) translate3d(0, 0, 0); }
        50% { transform: translateY(-8px) translate3d(0, 0, 0); }
    }
    .animate-float {
        animation: subtle-float 4.5s ease-in-out infinite;
        will-change: transform;
    }

    /* Pulse Glow Ring */
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
        50% { box-shadow: 0 0 24px 8px rgba(245, 158, 11, 0.6); }
    }
    .glow-pulse {
        animation: pulse-glow 3s infinite;
    }
</style>

<div x-data="{ 
    mobileMenuOpen: false, 
    activeModal: '{{ request('open') ?? '' }}', 
    openModal(type) { 
        this.activeModal = type; 
        document.body.style.overflow = 'hidden'; 
    }, 
    closeModal() { 
        this.activeModal = ''; 
        document.body.style.overflow = 'auto'; 
    } 
}" 
@keydown.escape.window="closeModal()"
x-init="if (activeModal) { document.body.style.overflow = 'hidden'; }"
class="min-h-screen font-sans antialiased text-slate-800 bg-gray-50 flex flex-col justify-between overflow-x-hidden">

    <div>
        <!-- ------------------------------------------------------------- -->
        <!-- 1. SHARED TOPBAR AND NAVBAR COMPONENT -->
        <!-- ------------------------------------------------------------- -->
        @include('user.partials.navbar')

        <!-- ------------------------------------------------------------- -->
        <!-- 2. HERO HEADLINE & DESKRIPSI PROFIL -->
        <!-- ------------------------------------------------------------- -->
        <main class="py-12 sm:py-16">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Headline Title & Sekilas About Us (Dynamic Fluid Container with Generous Spacing) -->
                <div class="text-center max-w-4xl mx-auto px-2 sm:px-4" style="margin-bottom: 7.5rem;" data-aos="fade-up" data-aos-duration="800">
                    <h1 class="text-3xl sm:text-4xl lg:text-[44px] font-extrabold font-headline text-theme-primary tracking-tight mb-3">
                        Profil SMAN 2 Situbondo
                    </h1>
                    <div class="w-20 h-1 bg-theme-secondary mx-auto rounded-full mb-6"></div>
                    
                    @if(!empty($profile?->about_us))
                        <div class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-3xl mx-auto text-center font-normal break-words space-y-3">
                            {!! nl2br(e($profile->about_us)) !!}
                        </div>
                    @endif
                </div>

                <!-- ------------------------------------------------------------- -->
                <!-- 3. THREE (3) CARDS GRID SECTION -->
                <!-- ------------------------------------------------------------- -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 max-w-6xl mx-auto px-4 sm:px-6" style="margin-bottom: 8rem;">
                    
                    <!-- CARD 1: Visi, Misi & Tujuan -->
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200/80 hover:border-theme-primary transition duration-300 transform-gpu spring-hover flex flex-col justify-between h-full" data-aos="fade-up" data-aos-delay="100">
                        <!-- Top Accent Bar (Secondary Gold) -->
                        <div class="h-1.5 bg-theme-secondary w-full"></div>
                        <div class="p-6 sm:p-8 flex flex-col flex-1">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-theme-primary-deep text-white flex items-center justify-center mb-5 text-xl shadow-sm">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold font-headline text-theme-primary mb-3">
                                Visi, Misi &amp; Tujuan
                            </h2>
                            <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-6 line-clamp-3">
                                @php
                                    $visionSummary = strip_tags(($profile?->vision ?? '') . ' ' . ($profile?->mission ?? '') . ' ' . ($profile?->goals ?? ''));
                                @endphp
                                {{ !empty(trim($visionSummary)) ? Str::limit($visionSummary, 120, '...') : 'Menjadi institusi pendidikan terdepan yang menghasilkan lulusan berakhlak mulia, berprestasi akademik, dan siap...' }}
                            </p>
                        </div>
                        <button @click="openModal('vision')" class="inline-flex items-center text-xs sm:text-sm font-bold text-theme-primary hover-text-secondary transition group cursor-pointer mt-auto self-start">
                            Lihat Detail <i class="fas fa-arrow-right ml-1.5 text-xs text-theme-secondary group-hover:translate-x-1 transition-transform"></i>
                        </button>
                        </div>
                    </div>

                    <!-- CARD 2: Sejarah Singkat -->
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200/80 hover:border-theme-primary transition duration-300 transform-gpu spring-hover flex flex-col justify-between h-full" data-aos="fade-up" data-aos-delay="200">
                        <!-- Top Accent Bar (Secondary Gold) -->
                        <div class="h-1.5 bg-theme-secondary w-full"></div>
                        <div class="p-6 sm:p-8 flex flex-col flex-1">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-theme-primary-deep text-white flex items-center justify-center mb-5 text-xl shadow-sm">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold font-headline text-theme-primary mb-3">
                                Sejarah Singkat
                            </h2>
                            <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-6 line-clamp-3">
                                @php
                                    $historySummary = strip_tags($profile?->history ?? '');
                                @endphp
                                {{ !empty(trim($historySummary)) ? Str::limit($historySummary, 120, '...') : 'Sejak didirikan, SMAN 2 Situbondo telah mengukir sejarah panjang dalam dunia pendidikan lokal, terus berkembang dan...' }}
                            </p>
                        </div>
                        <button @click="openModal('history')" class="inline-flex items-center text-xs sm:text-sm font-bold text-theme-primary hover-text-secondary transition group cursor-pointer mt-auto self-start">
                            Lihat Detail <i class="fas fa-arrow-right ml-1.5 text-xs text-theme-secondary group-hover:translate-x-1 transition-transform"></i>
                        </button>
                        </div>
                    </div>

                    <!-- CARD 3: Struktur Organisasi -->
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200/80 hover:border-theme-primary transition duration-300 transform-gpu spring-hover flex flex-col justify-between h-full" data-aos="fade-up" data-aos-delay="300">
                        <!-- Top Accent Bar (Secondary Gold) -->
                        <div class="h-1.5 bg-theme-secondary w-full"></div>
                        <div class="p-6 sm:p-8 flex flex-col flex-1">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-theme-primary-deep text-white flex items-center justify-center mb-5 text-xl shadow-sm">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold font-headline text-theme-primary mb-3">
                                Struktur Organisasi
                            </h2>
                            <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-6 line-clamp-3">
                                Tata kelola sekolah didukung oleh struktur organisasi yang profesional dan transparan, memastikan setiap aspek...
                            </p>
                        </div>
                        <button @click="openModal('structure')" class="inline-flex items-center text-xs sm:text-sm font-bold text-theme-primary hover-text-secondary transition group cursor-pointer mt-auto self-start">
                            Lihat Detail <i class="fas fa-arrow-right ml-1.5 text-xs text-theme-secondary group-hover:translate-x-1 transition-transform"></i>
                        </button>
                        </div>
                    </div>

                </div>

                <!-- ------------------------------------------------------------- -->
                <!-- 4. SEKRETARIAT & LAYANAN (INFORMASI KONTAK & GOOGLE MAPS)     -->
                <!-- ------------------------------------------------------------- -->
                <div class="max-w-6xl mx-auto px-4 sm:px-6 mb-20 sm:mb-28" data-aos="zoom-in-up" data-aos-duration="900">
                    <div class="bg-white rounded-3xl border border-gray-200/90 p-6 sm:p-8 md:p-10 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                            
                            <!-- Left: Contact Details (5 cols) -->
                            <div class="lg:col-span-5 space-y-5">
                                
                                <!-- Eyebrow Tag -->
                                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-theme-secondary text-slate-950 shadow-2xs">
                                    <i class="fas fa-building text-[11px]"></i>
                                    <span>Sekretariat &amp; Layanan</span>
                                </div>

                                <!-- Section Title -->
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-theme-primary font-headline tracking-tight">
                                    Informasi Kontak
                                </h2>

                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                    Untuk informasi lebih lanjut dan pelayanan langsung, silakan kunjungi kantor sekretariat SMAN 2 Situbondo atau hubungi kami melalui kanal resmi berikut.
                                </p>

                                <!-- Contact Badges List -->
                                <div class="space-y-3.5 text-xs sm:text-sm pt-1">
                                    
                                    <!-- 1. Alamat -->
                                    <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-theme-secondary/50 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-theme-primary flex items-center justify-center shrink-0 mt-0.5 border border-blue-100 shadow-2xs group-hover:scale-105 transition-transform">
                                            <i class="fas fa-map-marker-alt text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="block text-[11px] font-extrabold text-slate-900 uppercase tracking-wider mb-0.5">Alamat</span>
                                            <p class="text-slate-600 leading-relaxed font-normal text-xs sm:text-sm">
                                                Jl. Argopuro No.17, Mimbaan, Kec. Panji, Kabupaten Situbondo, Jawa Timur 68322
                                            </p>
                                        </div>
                                    </div>

                                    <!-- 2. Telepon -->
                                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-theme-secondary/50 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 shadow-2xs group-hover:scale-105 transition-transform">
                                            <i class="fas fa-phone-alt text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="block text-[11px] font-extrabold text-slate-900 uppercase tracking-wider mb-0.5">Telepon</span>
                                            <a href="tel:0338671234" class="text-slate-700 font-semibold hover-text-primary transition-colors text-xs sm:text-sm">
                                                (0338) 671234
                                            </a>
                                        </div>
                                    </div>

                                    <!-- 3. Email -->
                                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-theme-secondary/50 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100 shadow-2xs group-hover:scale-105 transition-transform">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="block text-[11px] font-extrabold text-slate-900 uppercase tracking-wider mb-0.5">Email</span>
                                            <a href="mailto:info@sman2situbondo.sch.id" class="text-slate-700 font-semibold hover-text-primary transition-colors text-xs sm:text-sm">
                                                info@sman2situbondo.sch.id
                                            </a>
                                        </div>
                                    </div>

                                </div>

                                <div class="pt-2">
                                    <a href="https://maps.app.goo.gl/ftYMjJRxN9KV7v6D6" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-theme-primary hover:text-theme-secondary transition-colors group">
                                        <span>Buka di Google Maps</span>
                                        <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>

                            </div>

                            <!-- Right: Responsive Interactive Google Maps Embed (7 cols) -->
                            <div class="lg:col-span-7">
                                <div class="rounded-2xl overflow-hidden border border-slate-200/90 shadow-sm h-72 sm:h-80 md:h-96 w-full relative">
                                    <iframe 
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.766324391696!2d114.01258907499708!3d-7.708204676352932!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd72750e3860bb7%3A0x6b1076b1f28b7468!2sSMAN%202%20Situbondo!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                                        class="w-full h-full border-0" 
                                        allowfullscreen="" 
                                        loading="lazy" 
                                        referrerpolicy="no-referrer-when-downgrade"
                                        title="Lokasi SMAN 2 Situbondo di Google Maps">
                                    </iframe>
                                    <a href="https://maps.app.goo.gl/ftYMjJRxN9KV7v6D6" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-md px-3.5 py-1.5 rounded-full text-[11px] font-extrabold text-slate-900 border border-gray-200 shadow-md hover:bg-theme-secondary hover:text-slate-950 transition-all flex items-center gap-1.5 spring-hover">
                                        <i class="fas fa-external-link-alt text-[10px]"></i>
                                        <span>Buka Peta Penuh</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. SHARED FOOTER COMPONENT -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')

    <!-- ------------------------------------------------------------- -->
    <!-- 6. DETAIL POP-UP MODALS (WITH FUNCTIONAL CLOSE "X" BUTTONS) -->
    <!-- ------------------------------------------------------------- -->

    <!-- MODAL 1: VISI, MISI & TUJUAN -->
    <div x-show="activeModal === 'vision'" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200 transform-gpu"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150 transform-gpu"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="z-index: 99999;"
         class="fixed inset-0 bg-black/80 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
        
        <div @click.away="closeModal()" class="bg-white rounded-2xl max-w-3xl w-full p-6 sm:p-8 shadow-2xl relative border-2 border-theme-primary/20 my-auto">
            <!-- Close Button X -->
            <button @click="closeModal()" type="button" aria-label="Tutup Modal Visi Misi" class="absolute top-4 right-4 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gray-100 text-gray-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition duration-200 focus:outline-none z-50 cursor-pointer shadow-md border border-gray-200" title="Tutup Modal (Esc)">
                <i class="fas fa-times text-base sm:text-lg"></i>
            </button>

            <!-- Header -->
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-theme-secondary">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-theme-primary flex items-center justify-center text-lg font-bold border border-blue-100">
                    <i class="fas fa-eye text-theme-primary"></i>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-extrabold font-headline text-theme-primary">
                        Visi, Misi &amp; Tujuan
                    </h3>
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">SMAN 2 Situbondo</p>
                </div>
            </div>

            <!-- Content Body -->
            <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2 text-slate-700 text-xs sm:text-sm leading-relaxed">
                <!-- Visi -->
                <div class="border-l-4 border-theme-secondary bg-amber-50/70 p-5 rounded-r-2xl border-y border-r border-amber-200/80">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-theme-secondary text-slate-950 font-extrabold text-[10px] uppercase tracking-wider shadow-xs">Visi Utama</span>
                    </div>
                    <p class="text-slate-900 font-bold text-sm sm:text-base leading-relaxed italic">
                        "{{ !empty($profile?->vision) ? trim($profile->vision) : 'Menjadi institusi pendidikan terdepan yang menghasilkan lulusan berakhlak mulia, berprestasi akademik, berwawasan lingkungan, dan siap bersaing di era global.' }}"
                    </p>
                </div>

                <!-- Misi -->
                <div class="border-l-4 border-theme-primary bg-blue-50/70 p-5 rounded-r-2xl border-y border-r border-blue-200/80">
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="px-2.5 py-0.5 rounded-full bg-theme-primary text-white font-extrabold text-[10px] uppercase tracking-wider shadow-xs">Misi Sekolah</span>
                    </div>
                    <div class="text-slate-900 space-y-2 font-semibold">
                        {!! !empty($profile?->mission) ? nl2br(e($profile->mission)) : '1. Menyelenggarakan proses pembelajaran yang inovatif, efektif, dan berbasis teknologi informasi.<br>2. Membentuk karakter peserta didik yang beriman, bertakwa, dan berakhlak mulia.<br>3. Meningkatkan prestasi akademik dan non-akademik peserta didik.' !!}
                    </div>
                </div>

                <!-- Tujuan -->
                <div class="border-l-4 border-slate-800 bg-slate-100/80 p-5 rounded-r-2xl border-y border-r border-slate-200">
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-800 text-white font-extrabold text-[10px] uppercase tracking-wider shadow-xs">Tujuan Strategis</span>
                    </div>
                    <div class="text-slate-900 space-y-2 font-semibold">
                        {!! !empty($profile?->goals) ? nl2br(e($profile->goals)) : '1. Terwujudnya mutu lulusan yang berdaya saing tinggi dan diterima di perguruan tinggi favorit.<br>2. Terwujudnya budaya sekolah yang berkarakter, beretika, dan berakhlak mulia.<br>3. Terwujudnya tata kelola sekolah yang transparan dan profesional.' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: SEJARAH SINGKAT -->
    <div x-show="activeModal === 'history'" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200 transform-gpu"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150 transform-gpu"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="z-index: 99999;"
         class="fixed inset-0 bg-black/80 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
        
        <div @click.away="closeModal()" class="bg-white rounded-2xl max-w-3xl w-full p-6 sm:p-8 shadow-2xl relative border-2 border-theme-primary/20 my-auto">
            <!-- Close Button X -->
            <button @click="closeModal()" type="button" aria-label="Tutup Modal Sejarah Singkat" class="absolute top-4 right-4 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gray-100 text-gray-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition duration-200 focus:outline-none z-50 cursor-pointer shadow-md border border-gray-200" title="Tutup Modal (Esc)">
                <i class="fas fa-times text-base sm:text-lg"></i>
            </button>

            <!-- Header -->
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-theme-secondary">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-theme-primary flex items-center justify-center text-lg font-bold border border-blue-100">
                    <i class="fas fa-book-open text-theme-primary"></i>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-extrabold font-headline text-theme-primary">
                        Sejarah Singkat
                    </h3>
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">SMAN 2 Situbondo</p>
                </div>
            </div>

            <!-- Content Body -->
            <div class="max-h-[70vh] overflow-y-auto pr-2 text-slate-700 text-xs sm:text-sm leading-relaxed">
                <div class="bg-slate-50 p-6 rounded-2xl border border-gray-200/80 text-slate-800 relative">
                    <i class="fas fa-quote-left text-3xl text-gray-300 absolute top-4 right-4 pointer-events-none"></i>
                    <div class="relative z-10 space-y-4 font-medium text-slate-900 leading-relaxed">
                        {!! !empty($profile?->history) ? nl2br(e($profile->history)) : 'Sejak didirikan, SMAN 2 Situbondo telah mengukir sejarah panjang dalam dunia pendidikan lokal, terus berkembang dan beradaptasi dengan tantangan zaman. Berdiri sejak tahun 1978, SMAN 2 Situbondo terus meluluskan alumni-alumni terbaik yang berkontribusi aktif dalam berbagai bidang pembangunan bangsa.' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 3: STRUKTUR ORGANISASI -->
    <div x-show="activeModal === 'structure'" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200 transform-gpu"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150 transform-gpu"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="z-index: 99999;"
         class="fixed inset-0 bg-black/80 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
        
        <div @click.away="closeModal()" class="bg-white rounded-2xl max-w-5xl w-full p-6 sm:p-8 shadow-2xl relative border-2 border-theme-primary/20 my-auto">
            <!-- Close Button X (Explicitly requested in Point 3) -->
            <button @click="closeModal()" type="button" aria-label="Tutup Modal Struktur Organisasi" class="absolute top-4 right-4 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gray-100 text-gray-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition duration-200 focus:outline-none z-50 cursor-pointer shadow-md border border-gray-200" title="Tutup Modal (Esc)">
                <i class="fas fa-times text-base sm:text-lg"></i>
            </button>

            <!-- Header -->
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-theme-secondary">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-theme-primary flex items-center justify-center text-lg font-bold border border-blue-100">
                    <i class="fas fa-sitemap text-theme-primary"></i>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-extrabold font-headline text-theme-primary">
                        Struktur Organisasi
                    </h3>
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">SMAN 2 Situbondo</p>
                </div>
            </div>

            <!-- Content Body: Image Display -->
            <div class="overflow-y-auto flex items-center justify-center bg-slate-900 rounded-2xl p-4 border border-slate-800 shadow-inner" style="max-height: 60vh;">
                @if(!empty($profile?->structure_image_url))
                    <img src="{{ $getImageUrl($profile->structure_image_url) }}" 
                         alt="Struktur Organisasi SMAN 2 Situbondo" 
                         class="max-w-full h-auto object-contain rounded-xl shadow-md"
                         loading="lazy" 
                         decoding="async"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="py-16 text-center text-gray-400" style="display: none;">
                        <i class="fas fa-image text-5xl mb-3 block text-gray-500"></i>
                        <p class="font-medium text-sm">Gambar Struktur Organisasi tidak dapat dimuat.</p>
                    </div>
                @else
                    <div class="py-16 text-center text-gray-400">
                        <i class="fas fa-image text-5xl mb-3 block text-gray-500"></i>
                        <p class="font-medium text-sm">Gambar Struktur Organisasi belum diunggah oleh Admin.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Initialize AOS Scroll Animation Engine -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50,
                disableMutationObserver: false,
                throttleDelay: 99
            });
        }
    });
</script>
@endsection
