@extends('layouts.app')

@section('content')
@php
    $primaryColor = $colorSetting?->primary_color ?? '#001c4d';
    $secondaryColor = $colorSetting?->secondary_color ?? '#f59e0b';
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

<!-- DYNAMIC THEME SYSTEM INJECTOR (BALANCED PRIMARY DOMINANCE & SECONDARY ACCENT) -->
<style>
    :root {
        --primary-main: {{ $primaryColor }};
        --secondary-gold: {{ $secondaryColor }};
        --primary-deep: color-mix(in srgb, var(--primary-main) 80%, black);
        --primary-light: color-mix(in srgb, var(--primary-main) 12%, white);
        --primary-soft: color-mix(in srgb, var(--primary-main) 4%, white);
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
    .spring-hover, .animate-float, .glow-pulse {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        perspective: 1000px;
    }

    /* Dynamic Theme System Utility Classes */
    .bg-theme-primary { background-color: var(--primary-main) !important; }
    .bg-theme-primary-deep { background-color: var(--primary-deep) !important; }
    .bg-theme-primary-light { background-color: var(--primary-light) !important; }
    .bg-theme-primary-soft { background-color: var(--primary-soft) !important; }
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

    /* Ultra-Smooth Spring Physics Hover Scaling */
    @media (prefers-reduced-motion: no-preference) {
        .spring-hover {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease !important;
            will-change: transform, box-shadow;
        }
        .spring-hover:hover {
            transform: translateY(-3px) translate3d(0, 0, 0) !important;
            box-shadow: 0 12px 20px -8px rgba(0, 28, 77, 0.15), 0 6px 12px -4px rgba(245, 158, 11, 0.18) !important;
        }
    }

    /* Floating Micro-Animation */
    @keyframes subtle-float {
        0%, 100% { transform: translateY(0) translate3d(0, 0, 0); }
        50% { transform: translateY(-4px) translate3d(0, 0, 0); }
    }
    .animate-float {
        animation: subtle-float 4s ease-in-out infinite;
        will-change: transform;
    }
</style>

<div class="min-h-screen flex flex-col justify-between">

    <div>
        <!-- ------------------------------------------------------------- -->
        <!-- 1. SHARED HEADER & NAVBAR COMPONENT (MATCHING LANDING PAGE)   -->
        <!-- ------------------------------------------------------------- -->
        @include('user.partials.navbar')

        <!-- ------------------------------------------------------------- -->
        <!-- 2. MAIN DATA SISWA CONTENT AREA                               -->
        <!-- ------------------------------------------------------------- -->
        <main class="py-12 sm:py-16">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">

                <!-- ------------------------------------------------------------- -->
                <!-- SECTION 1: HEADER SECTION (ELEGANT & CLEAN)                   -->
                <!-- ------------------------------------------------------------- -->
                <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10" data-aos="fade-up" data-aos-duration="700">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-theme-primary font-headline tracking-tight mb-3">
                        Data Siswa
                    </h1>
                    <div class="w-20 h-1.5 bg-theme-secondary mx-auto rounded-full mb-4" data-aos="zoom-in" data-aos-delay="150"></div>
                    <p class="text-slate-600 text-xs sm:text-sm md:text-base mt-2 leading-relaxed font-normal max-w-2xl mx-auto">
                        Direktori lengkap peserta didik SMAN 2 Situbondo. Data disajikan untuk keperluan transparansi akademik dengan mengedepankan privasi dasar siswa.
                    </p>
                </div>

                <!-- ------------------------------------------------------------- -->
                <!-- SECTION 2: FILTER & SEARCH SECTION (MATCHING CIVITAS AKADEMIK)-->
                <!-- ------------------------------------------------------------- -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/90 mb-6 sm:mb-8" data-aos="fade-up" data-aos-delay="100">
                    <form action="{{ route('student.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-5 items-end">
                        
                        <!-- Search Input (Nama) -->
                        <div class="md:col-span-6">
                            <label for="search" class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">
                                Cari Nama Siswa
                            </label>
                            <div class="relative flex items-center">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none z-10"></i>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ $search ?? '' }}" 
                                       placeholder="Masukkan nama atau NISN..." 
                                       class="w-full h-11 pl-12 pr-10 bg-slate-50/70 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-theme-primary/30 focus:border-theme-primary transition duration-150">
                                @if(!empty($search))
                                    <a href="{{ route('student.index', array_filter(['class' => $classFilter])) }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs z-10" title="Reset Pencarian">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Kelas Dropdown Filter (Based on Table & Grade Levels) -->
                        <div class="md:col-span-4">
                            <label for="class" class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">
                                Kelas
                            </label>
                            <div class="relative">
                                <select id="class" 
                                        name="class" 
                                        class="w-full h-11 py-2.5 px-3.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-theme-primary/30 focus:border-theme-primary transition duration-150 cursor-pointer">
                                    <option value="" {{ empty($classFilter) ? 'selected' : '' }}>Semua Kelas</option>
                                    
                                    <optgroup label="Tingkat Kelas">
                                        <option value="12" {{ ($classFilter ?? '') === '12' ? 'selected' : '' }}>Semua Kelas XII (12)</option>
                                        <option value="11" {{ ($classFilter ?? '') === '11' ? 'selected' : '' }}>Semua Kelas XI (11)</option>
                                        <option value="10" {{ ($classFilter ?? '') === '10' ? 'selected' : '' }}>Semua Kelas X (10)</option>
                                    </optgroup>

                                    @if(isset($availableClasses) && count($availableClasses) > 0)
                                        <optgroup label="Kelas Spesifik">
                                            @foreach($availableClasses as $clsOption)
                                                <option value="{{ $clsOption }}" {{ ($classFilter ?? '') === $clsOption ? 'selected' : '' }}>
                                                    {{ $clsOption }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Terapkan Button (Dynamic Secondary Accent) -->
                        <div class="md:col-span-2">
                            <button type="submit" 
                                    class="w-full h-11 px-4 bg-theme-secondary text-slate-950 font-extrabold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-md active:scale-98 transition-all duration-150 uppercase tracking-wide flex items-center justify-center cursor-pointer spring-hover">
                                <span>Terapkan</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ------------------------------------------------------------- -->
                <!-- SECTION 3: SUMMARY STATUS BAR                                 -->
                <!-- ------------------------------------------------------------- -->
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4 px-1 text-xs font-medium text-slate-600" data-aos="fade-up" data-aos-delay="150">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-theme-secondary"></span>
                        <span>Menampilkan <strong class="text-theme-primary font-bold">{{ $students->total() }}</strong> Siswa Aktif</span>
                    </div>

                    @if(!empty($classFilter) || !empty($search))
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-slate-400">Filter Aktif:</span>
                            @if(!empty($classFilter))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-theme-primary/10 text-theme-primary font-bold border border-theme-primary/20">
                                    <i class="fas fa-filter text-[10px] text-theme-secondary"></i>
                                    <span>Kelas: {{ $classFilter }}</span>
                                </span>
                            @endif
                            @if(!empty($search))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-theme-secondary/20 text-slate-900 font-bold border border-theme-secondary/30">
                                    <i class="fas fa-search text-[10px]"></i>
                                    <span>"{{ $search }}"</span>
                                </span>
                            @endif
                            <a href="{{ route('student.index') }}" class="text-slate-400 hover:text-red-500 transition ml-1" title="Hapus Semua Filter">
                                <i class="fas fa-times-circle"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- ------------------------------------------------------------- -->
                <!-- SECTION 4: DATA SISWA TABLE (PRISTINE, CLEAN & ELEGANT)       -->
                <!-- ------------------------------------------------------------- -->
                @if($students->count() > 0)
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 shadow-2xs" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/90 border-b border-slate-200/90 text-[11px] sm:text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        <th scope="col" class="py-4 px-4 sm:px-6 w-20 text-center">No</th>
                                        <th scope="col" class="py-4 px-4 sm:px-6">Nama Siswa</th>
                                        <th scope="col" class="py-4 px-4 sm:px-6 w-44 sm:w-56 text-right sm:text-center">Kelas</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                    @foreach($students as $idx => $student)
                                        <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                                            <!-- Continuous Row Number -->
                                            <td class="py-4 px-4 sm:px-6 text-center text-slate-400 font-semibold font-mono text-xs">
                                                {{ $students->firstItem() + $idx }}
                                            </td>

                                            <!-- Student Name (Clean, Crisp, Bold Primary Color) -->
                                            <td class="py-4 px-4 sm:px-6 font-bold text-theme-primary group-hover:text-theme-secondary transition-colors font-headline text-sm sm:text-base">
                                                {{ $student->name }}
                                            </td>

                                            <!-- Kelas / Angkatan -->
                                            <td class="py-4 px-4 sm:px-6 text-right sm:text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/70 group-hover:border-theme-primary/40 group-hover:text-theme-primary transition-colors duration-150">
                                                    {{ !empty($student->class) ? $student->class : 'Umum' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ------------------------------------------------------------- -->
                    <!-- SECTION 5: PAGINATION CONTROLS (< 1 2 3 ... 70 >)             -->
                    <!-- ------------------------------------------------------------- -->
                    <div class="mt-8 sm:mt-12 flex justify-center" data-aos="fade-up">
                        {{ $students->links('user.employee.partials.pagination') }}
                    </div>
                @else
                    <!-- NATURAL EMPTY STATE (ACCORDING TO PROJECT SYSTEM) -->
                    <div class="bg-white rounded-2xl p-12 text-center border border-slate-200/80 shadow-xs max-w-xl mx-auto my-6" data-aos="fade-up">
                        <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 border border-slate-100 text-2xl">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800 font-headline">Tidak Ada Data Siswa</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1.5 max-w-md mx-auto">
                            @if(!empty($search) || !empty($classFilter))
                                Tidak ditemukan data siswa yang sesuai dengan kriteria pencarian atau filter kelas yang dipilih. Silakan coba kata kunci lain.
                            @else
                                Data siswa belum tersedia di database.
                            @endif
                        </p>
                        @if(!empty($search) || !empty($classFilter))
                            <div class="mt-5">
                                <a href="{{ route('student.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-theme-primary text-white text-xs font-bold shadow-xs hover:opacity-90 transition spring-hover">
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
