@extends('layouts.app')

@section('content')
@php
    $primaryColor = $colorSetting?->primary_color ?? '#1a365d';
    $secondaryColor = $colorSetting?->secondary_color ?? '#e67e22';

    $getImageUrl = function (?string $path, string $default = ''): string {
        static $resolvedCache = [];
        if (empty($path)) return $default;
        
        // 1. Normalize Windows backslashes, leading/trailing whitespace
        $clean = trim(str_replace('\\', '/', $path));
        if (empty($clean)) return $default;
        
        if (isset($resolvedCache[$clean])) {
            return $resolvedCache[$clean];
        }
        
        // 2. Data URI or Full External Web URL (http, https, protocol-relative)
        if (\Illuminate\Support\Str::startsWith($clean, ['http://', 'https://', '//', 'data:image/'])) {
            return $resolvedCache[$clean] = $clean;
        }
        
        // 3. Absolute local filesystem path on server (e.g. C:/laragon/www/smada/public/...)
        $publicBasePath = str_replace('\\', '/', public_path());
        if (\Illuminate\Support\Str::startsWith($clean, $publicBasePath)) {
            $rel = ltrim(substr($clean, strlen($publicBasePath)), '/');
            return $resolvedCache[$clean] = asset($rel);
        }
        
        // 4. Starts with / (e.g. /images/..., /storage/..., /build/..., /uploads/...)
        if (\Illuminate\Support\Str::startsWith($clean, '/')) {
            return $resolvedCache[$clean] = asset(ltrim($clean, '/'));
        }
        
        // 5. Stored as public/... or app/public/...
        if (\Illuminate\Support\Str::startsWith($clean, 'public/')) {
            $clean = substr($clean, 7);
        }
        if (\Illuminate\Support\Str::startsWith($clean, 'app/public/')) {
            $clean = substr($clean, 11);
        }
        
        // 6. Explicit storage prefix (e.g. storage/employees/xyz.png)
        if (\Illuminate\Support\Str::startsWith($clean, 'storage/')) {
            return $resolvedCache[$clean] = asset($clean);
        }
        
        // 7. Direct file in public/ directory
        if (file_exists(public_path($clean))) {
            return $resolvedCache[$clean] = asset($clean);
        }
        
        // 8. Stored on Laravel 'public' disk
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($clean)) {
            return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url($clean);
        }
        
        // 9. Stored in subfolder without folder prefix (check common folders for filename only)
        $subfolders = ['employees/', 'employee/', 'guru/', 'pegawai/', 'school_profile/', 'structure/', 'images/static/', 'images/', 'uploads/'];
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
        
        // 10. Fallback: Storage disk URL or Asset URL
        return $resolvedCache[$clean] = \Illuminate\Support\Facades\Storage::disk('public')->url($clean);
    };
@endphp

<!-- High-Speed Resource Hints & Asset Preloading -->
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Tailwind CDN & Alpine.js for Exact Layout Parsing & Smooth Transitions -->
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Font Awesome 6 Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Google Fonts: Inter & Hanken Grotesk / Outfit for Premium Typography -->
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800;900&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

<!-- AOS (Animate On Scroll) Library CDN for Smooth 60FPS Animations -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>

<!-- DYNAMIC THEME SYSTEM INJECTOR (BALANCED PRIMARY & SECONDARY THEME) -->
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
    body { 
        font-family: 'Inter', 'Plus Jakarta Sans', sans-serif; 
        background-color: #f8fafc;
        color: #1e293b;
        overflow-x: hidden; 
        width: 100%; 
        -webkit-font-smoothing: antialiased; 
        -moz-osx-font-smoothing: grayscale; 
    }
    .font-headline { font-family: 'Hanken Grotesk', 'Outfit', sans-serif; }

    /* Hardware Acceleration for 60FPS Micro-Animations */
    .spring-hover, .animate-float, .glow-pulse, .img-zoom-box img {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        perspective: 1000px;
    }

    /* Dynamic Theme System Utility Classes */
    .bg-theme-primary { background-color: var(--primary-main) !important; }
    .bg-theme-primary-deep { background-color: var(--primary-deep) !important; }
    .text-theme-primary { color: var(--primary-main) !important; }
    .border-theme-primary { border-color: var(--primary-main) !important; }

    .bg-theme-secondary { background-color: var(--secondary-gold) !important; }
    .text-theme-secondary { color: var(--secondary-gold) !important; }
    .border-theme-secondary { border-color: var(--secondary-gold) !important; }

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
        will-change: transform;
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
            transform: translateY(-6px) translate3d(0, 0, 0) !important;
            box-shadow: 0 20px 35px -10px rgba(0, 28, 77, 0.14), 0 10px 20px -5px rgba(245, 158, 11, 0.16) !important;
        }
    }

    /* Floating Micro-Animation */
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
</style>

<div class="min-h-screen flex flex-col justify-between" x-data="{ selectedEmployee: null }" @keydown.escape.window="selectedEmployee = null">

    <div>
        <!-- ------------------------------------------------------------- -->
        <!-- 1. SHARED HEADER & NAVBAR COMPONENT (MATCHING LANDING PAGE)   -->
        <!-- ------------------------------------------------------------- -->
        @include('user.partials.navbar')

        <!-- ------------------------------------------------------------- -->
        <!-- 2. MAIN CIVITAS AKADEMIK CONTENT AREA                         -->
        <!-- ------------------------------------------------------------- -->
        <main class="py-12 sm:py-16">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

                <!-- ------------------------------------------------------------- -->
                <!-- SECTION 1: HEADER SECTION (MATCHING DESIGN REFERENCE & SYSTEM) -->
                <!-- ------------------------------------------------------------- -->
                <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10" data-aos="fade-up" data-aos-duration="600">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-theme-primary font-headline tracking-tight mb-3">
                        Data Pegawai &amp; Guru
                    </h1>
                    <div class="w-20 h-1.5 bg-theme-secondary mx-auto rounded-full mb-4"></div>
                    <p class="text-slate-600 text-xs sm:text-sm md:text-base mt-2 leading-relaxed font-normal max-w-2xl mx-auto">
                        Mengenal lebih dekat para pendidik dan tenaga kependidikan yang berdedikasi di SMA Negeri 2 Situbondo.
                    </p>
                </div>

                <!-- ------------------------------------------------------------- -->
                <!-- SECTION 2: FILTER & SEARCH SECTION (PERFECTLY ALIGNED GRID)   -->
                <!-- ------------------------------------------------------------- -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/90 mb-8 sm:mb-10" data-aos="fade-up" data-aos-delay="100">
                    <form action="{{ route('civitas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-5 items-end">
                        
                        <!-- Search Input (Nama / NIP) -->
                        <div class="md:col-span-5">
                            <label for="search" class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">
                                Cari Nama / NIP
                            </label>
                            <div class="relative flex items-center">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none z-10"></i>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ $search ?? '' }}" 
                                       placeholder="Masukkan nama atau NIP..." 
                                       class="w-full h-11 pl-12 pr-10 bg-slate-50/70 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-theme-primary/30 focus:border-theme-primary transition duration-150">
                                @if(!empty($search))
                                    <a href="{{ route('civitas.index', ['position' => $positionFilter]) }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs z-10" title="Reset Pencarian">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Jabatan Dropdown (Full Text Description) -->
                        <div class="md:col-span-5">
                            <label for="position" class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">
                                Jabatan
                            </label>
                            <div class="relative">
                                <select id="position" 
                                        name="position" 
                                        class="w-full h-11 py-2.5 px-3.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-theme-primary/30 focus:border-theme-primary transition duration-150 cursor-pointer">
                                    <option value="" {{ empty($positionFilter) ? 'selected' : '' }}>Semua Jabatan</option>
                                    <option value="pimpinan" {{ strtolower($positionFilter ?? '') === 'pimpinan' ? 'selected' : '' }}>Kepala Sekolah &amp; Wakil Kepala Sekolah (Pimpinan)</option>
                                    <option value="guru" {{ strtolower($positionFilter ?? '') === 'guru' ? 'selected' : '' }}>Guru / Tenaga Pendidik</option>
                                    <option value="staff" {{ strtolower($positionFilter ?? '') === 'staff' ? 'selected' : '' }}>Staf Tata Usaha &amp; Tenaga Kependidikan</option>
                                    
                                    @if(isset($availablePositions) && count($availablePositions) > 0)
                                        <optgroup label="Jabatan Spesifik">
                                            @foreach($availablePositions as $posOption)
                                                <option value="{{ $posOption }}" {{ ($positionFilter ?? '') === $posOption ? 'selected' : '' }}>
                                                    {{ $posOption }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Terapkan Button (Dynamic Secondary Theme Accent) -->
                        <div class="md:col-span-2">
                            <button type="submit" 
                                    class="w-full h-11 px-4 bg-theme-secondary text-slate-950 font-extrabold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-md hover:scale-102 active:scale-98 transition-all duration-150 uppercase tracking-wide flex items-center justify-center cursor-pointer spring-hover">
                                <span>Terapkan</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ------------------------------------------------------------- -->
                <!-- SECTION 3: EMPLOYEE CARDS GRID (4 COLUMNS ON DESKTOP)        -->
                <!-- ------------------------------------------------------------- -->
                @if($employees->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 sm:gap-6">
                        @foreach($employees as $emp)
                            @php
                                $posLower = strtolower($emp->position);
                                $isKepalaSekolah = str_contains($posLower, 'kepala sekolah') && !str_contains($posLower, 'wakil');
                                $isPimpinan = str_contains($posLower, 'kepala') || str_contains($posLower, 'wakil') || str_contains($posLower, 'wakasek');
                                $isGuru = str_contains($posLower, 'guru') || str_contains($posLower, 'pendidik') || str_contains($posLower, 'pengajar');
                                $isStaff = !$isPimpinan && !$isGuru;
                            @endphp

                            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 shadow-2xs hover:shadow-xl hover:border-theme-secondary transition-all duration-300 flex flex-col group spring-hover relative" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 100 }}" data-aos-duration="700">
                                
                                <!-- Card Top Image / Photo Area with Full-Height Portrait Silhouette Fallback -->
                                <div class="h-[280px] sm:h-[300px] w-full relative overflow-hidden bg-gradient-to-b from-slate-100 via-slate-200 to-slate-300 flex items-center justify-center img-zoom-box">
                                    
                                    @if(!empty($emp->photo_url))
                                        <img src="{{ $getImageUrl($emp->photo_url) }}" 
                                             alt="{{ $emp->name }}" 
                                             class="w-full h-full object-cover object-top transition-transform duration-600 transform-gpu group-hover:scale-108" 
                                             loading="lazy"
                                             decoding="async"
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        
                                        <!-- Fallback Full-Height Profile Avatar when image fails to load -->
                                        <div class="w-full h-full hidden items-end justify-center bg-gradient-to-b from-slate-100 via-slate-200 to-slate-300 overflow-hidden">
                                            <svg class="w-4/5 h-4/5 text-slate-300/90 group-hover:scale-105 transition-transform duration-500 transform-gpu translate-y-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                    @else
                                        <!-- Profile Full-Height Silhouette Fallback when no photo is available -->
                                        <div class="w-full h-full flex items-end justify-center bg-gradient-to-b from-slate-100 via-slate-200 to-slate-300 overflow-hidden">
                                            <svg class="w-4/5 h-4/5 text-slate-300/90 group-hover:scale-105 transition-transform duration-500 transform-gpu translate-y-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Top Left Pill Badge (Kepala Sekolah in Secondary Color, All Others in Primary Color) -->
                                    @if($isKepalaSekolah)
                                        <span class="absolute top-3.5 left-3.5 bg-theme-secondary text-slate-950 text-[10px] sm:text-[11px] font-extrabold px-3 py-0.5 rounded-full shadow-md z-10 uppercase tracking-wider animate-float">
                                            Pimpinan
                                        </span>
                                    @elseif($isPimpinan)
                                        <span class="absolute top-3.5 left-3.5 bg-theme-primary text-white text-[10px] sm:text-[11px] font-extrabold px-3 py-0.5 rounded-full shadow-md z-10 uppercase tracking-wider">
                                            Pimpinan
                                        </span>
                                    @elseif($isGuru)
                                        <span class="absolute top-3.5 left-3.5 bg-theme-primary text-white text-[10px] sm:text-[11px] font-extrabold px-3 py-0.5 rounded-full shadow-md z-10 uppercase tracking-wider">
                                            Guru
                                        </span>
                                    @else
                                        <span class="absolute top-3.5 left-3.5 bg-theme-primary text-white text-[10px] sm:text-[11px] font-extrabold px-3 py-0.5 rounded-full shadow-md z-10 uppercase tracking-wider">
                                            Staff
                                        </span>
                                    @endif
                                </div>

                                <!-- Card Bottom Content Area -->
                                <div class="p-5 flex flex-col flex-grow justify-between bg-white rounded-b-2xl">
                                    <div>
                                        <!-- Name -->
                                        <h3 class="font-bold text-sm sm:text-base text-theme-primary font-headline line-clamp-2 leading-snug">
                                            {{ $emp->name }}
                                        </h3>

                                        <!-- NIP (If Available & Determined to be published) -->
                                        @if(!empty($emp->nip))
                                            <p class="text-[10px] sm:text-[11px] text-slate-500 font-mono mt-1 font-medium tracking-tight">
                                                NIP. {{ $emp->nip }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Position / Jabatan Row at Bottom (Text in Primary Color, 2 Icons in Secondary Color) -->
                                    <div class="pt-3.5 border-t border-slate-100 mt-4 flex items-center justify-between text-xs font-semibold">
                                        <div class="flex items-center gap-2 pr-2 min-w-0">
                                            @if($isPimpinan)
                                                <i class="fas fa-briefcase text-theme-secondary text-[11px] flex-shrink-0"></i>
                                            @elseif($isGuru)
                                                <i class="fas fa-book-open text-theme-secondary text-[11px] flex-shrink-0"></i>
                                            @else
                                                <i class="fas fa-id-card text-theme-secondary text-[11px] flex-shrink-0"></i>
                                            @endif
                                            <span class="text-theme-primary text-[11px] sm:text-xs font-bold leading-tight break-words" title="{{ $emp->position }}">
                                                {{ $emp->position }}
                                            </span>
                                        </div>

                                        @if(!empty($emp->extra_info))
                                            <button type="button" 
                                                    @click="selectedEmployee = {{ json_encode([
                                                        'name' => $emp->name,
                                                        'nip' => $emp->nip,
                                                        'position' => $emp->position,
                                                        'photo_url' => $emp->photo_url ? $getImageUrl($emp->photo_url) : null,
                                                        'extra_info' => $emp->extra_info,
                                                    ]) }}" 
                                                    class="text-theme-secondary hover:text-theme-primary text-xs p-1 rounded-full hover:bg-slate-100 transition cursor-pointer flex-shrink-0" 
                                                    title="Lihat Detail Profil">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- ------------------------------------------------------------- -->
                    <!-- SECTION 4: PAGINATION CONTROLS (< 1 2 3 ... 12 >)             -->
                    <!-- ------------------------------------------------------------- -->
                    <div class="mt-10 sm:mt-14 flex justify-center" data-aos="fade-up">
                        {{ $employees->links('user.employee.partials.pagination') }}
                    </div>
                @else
                    <!-- NATURAL EMPTY STATE (ACCORDING TO PROJECT SYSTEM) -->
                    <div class="bg-white rounded-2xl p-12 text-center border border-slate-200/80 shadow-xs max-w-xl mx-auto my-6" data-aos="fade-up">
                        <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 border border-slate-100 text-2xl">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800 font-headline">Tidak Ada Data Pegawai</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1.5 max-w-md mx-auto">
                            @if(!empty($search) || !empty($positionFilter))
                                Tidak ditemukan pegawai atau guru yang sesuai dengan kriteria pencarian Anda. Silakan coba kata kunci atau filter lain.
                            @else
                                Data pegawai dan guru belum tersedia di database.
                            @endif
                        </p>
                        @if(!empty($search) || !empty($positionFilter))
                            <div class="mt-5">
                                <a href="{{ route('civitas.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-theme-primary text-white text-xs font-bold shadow-xs hover:opacity-90 transition spring-hover">
                                    <i class="fas fa-undo text-[10px]"></i>
                                    <span>Reset Filter</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </main>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- SECTION 5: INTERACTIVE EMPLOYEE DETAIL MODAL (EXTRA INFO)     -->
    <!-- ------------------------------------------------------------- -->
    <div x-show="selectedEmployee !== null" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200 transform-gpu"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150 transform-gpu"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="z-index: 99999;"
         class="fixed inset-0 bg-black/80 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
        
        <div @click.away="selectedEmployee = null" class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border border-slate-200 my-auto">
            <!-- Close Button X -->
            <button @click="selectedEmployee = null" type="button" aria-label="Tutup Detail" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-slate-100 text-slate-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition duration-200 focus:outline-none z-50 cursor-pointer shadow-xs border border-slate-200" title="Tutup Detail (Esc)">
                <i class="fas fa-times text-sm"></i>
            </button>

            <!-- Employee Info Header -->
            <div class="flex items-center space-x-4 mb-5 pb-4 border-b border-slate-100">
                <template x-if="selectedEmployee?.photo_url">
                    <img :src="selectedEmployee.photo_url" :alt="selectedEmployee.name" class="w-14 h-14 rounded-full object-cover border-2 border-theme-secondary shadow-sm">
                </template>
                <template x-if="!selectedEmployee?.photo_url">
                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl border border-slate-200">
                        <i class="fas fa-user-circle"></i>
                    </div>
                </template>
                <div>
                    <h3 class="text-base sm:text-lg font-bold font-headline text-theme-primary" x-text="selectedEmployee?.name"></h3>
                    <p class="text-xs text-theme-secondary font-bold uppercase tracking-wider mt-0.5" x-text="selectedEmployee?.position"></p>
                    <p class="text-[11px] text-slate-500 font-mono" x-show="selectedEmployee?.nip" x-text="'NIP. ' + selectedEmployee?.nip"></p>
                </div>
            </div>

            <!-- Extra Info Body -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Informasi Tambahan:</h4>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed max-h-[50vh] overflow-y-auto" x-text="selectedEmployee?.extra_info"></div>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- SECTION 6: SHARED FOOTER COMPONENT                            -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')

</div>

<!-- Initialize AOS Animations On Page Load -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                once: true,
                duration: 800,
                easing: 'ease-out-cubic',
                offset: 60,
                disableMutationObserver: false
            });
        }
    });
</script>
@endsection
