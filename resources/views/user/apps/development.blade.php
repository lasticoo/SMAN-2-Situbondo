@extends('layouts.app')

@php
    $pageTitle = $app['name'] . ' - Aplikasi Dalam Masa Pengembangan | SMAN 2 Situbondo';
    $pageDescription = $app['description'];
    $primaryColor = $colorSetting?->primary_color ?? '#001c4d';
    $secondaryColor = $colorSetting?->secondary_color ?? '#f59e0b';
@endphp

@push('styles')
<!-- Dynamic High-Speed Resource Hints & Preloading -->
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Google Fonts: Inter & Hanken Grotesk -->
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Font Awesome 6 Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- AOS Animation CSS -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

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
    body { font-family: 'Inter', sans-serif; overflow-x: hidden; width: 100%; }
    .font-headline { font-family: 'Hanken Grotesk', sans-serif; }

    /* Dynamic CSS Theme Utility Classes */
    .bg-theme-primary { background-color: var(--primary-main) !important; }
    .bg-theme-primary-deep { background-color: var(--primary-deep) !important; }
    .text-theme-primary { color: var(--primary-main) !important; }
    .border-theme-primary { border-color: var(--primary-main) !important; }

    .bg-theme-secondary { background-color: var(--secondary-gold) !important; }
    .text-theme-secondary { color: var(--secondary-gold) !important; }
    .border-theme-secondary { border-color: var(--secondary-gold) !important; }

    .hover-text-primary:hover { color: var(--primary-main) !important; }
    .hover-bg-primary:hover { background-color: var(--primary-main) !important; color: #ffffff !important; }
    .hover-text-secondary:hover { color: var(--secondary-gold) !important; }
    .hover-bg-secondary:hover { background-color: var(--secondary-gold) !important; color: #020617 !important; }

    /* GPU-Accelerated Micro-Animations */
    .spring-hover {
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease !important;
        will-change: transform, box-shadow;
        transform: translateZ(0);
        backface-visibility: hidden;
    }
    .spring-hover:hover {
        transform: translateY(-6px) scale(1.02) translateZ(0) !important;
        box-shadow: 0 20px 30px -10px rgba(0, 28, 77, 0.25), 0 10px 15px -5px rgba(245, 158, 11, 0.2) !important;
    }

    /* Floating Micro-Animation */
    @keyframes subtle-float {
        0%, 100% { transform: translateY(0) translateZ(0); }
        50% { transform: translateY(-8px) translateZ(0); }
    }
    .animate-float {
        animation: subtle-float 4.5s ease-in-out infinite;
        will-change: transform;
    }

    /* Gear Rotation Animations */
    @keyframes spin-cw {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes spin-ccw {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(-360deg); }
    }
    .animate-spin-cw {
        transform-origin: center;
        animation: spin-cw 14s linear infinite;
    }
    .animate-spin-ccw {
        transform-origin: center;
        animation: spin-ccw 10s linear infinite;
    }

    /* Hammer / Arm Movement Animation */
    @keyframes hammer-action {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-22deg); }
        45% { transform: rotate(18deg); }
        60% { transform: rotate(-6deg); }
        75% { transform: rotate(8deg); }
    }
    .animate-hammer {
        transform-origin: 40px 110px;
        animation: hammer-action 2.2s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
    }

    /* Spark Pulse Effect */
    @keyframes spark-blink {
        0%, 100% { opacity: 0.2; transform: scale(0.8); }
        50% { opacity: 1; transform: scale(1.35); }
    }
    .animate-spark {
        animation: spark-blink 1.8s ease-in-out infinite;
    }

    /* Striped Progress Bar Moving Gradient */
    @keyframes progress-stripes {
        0% { background-position: 40px 0; }
        100% { background-position: 0 0; }
    }
    .animate-progress-stripes {
        background-image: linear-gradient(
            45deg,
            rgba(255, 255, 255, 0.22) 25%,
            transparent 25%,
            transparent 50%,
            rgba(255, 255, 255, 0.22) 50%,
            rgba(255, 255, 255, 0.22) 75%,
            transparent 75%,
            transparent
        );
        background-size: 32px 32px;
        animation: progress-stripes 1.5s linear infinite;
    }

    /* Reduced Motion Preference Override */
    @media (prefers-reduced-motion: reduce) {
        .animate-float, .animate-spin-cw, .animate-spin-ccw, .animate-hammer, .animate-spark, .animate-progress-stripes {
            animation: none !important;
        }
        .spring-hover:hover {
            transform: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col justify-between bg-slate-950 text-slate-800 antialiased selection:bg-amber-100 selection:text-amber-900 overflow-x-hidden">
    
    <div>
        <!-- ------------------------------------------------------------- -->
        <!-- 1. SHARED HEADER & TOP UTILITY BAR                            -->
        <!-- ------------------------------------------------------------- -->
        @include('user.partials.navbar')

        <!-- ------------------------------------------------------------- -->
        <!-- 2. HERO PANGGUNG APLIKASI: APLIKASI DALAM MASA PENGEMBANGAN    -->
        <!-- ------------------------------------------------------------- -->
        <main class="relative w-full overflow-hidden text-white py-14 sm:py-20 md:py-24"
              style="background: radial-gradient(circle at 80% 30%, var(--primary-main, #001c4d) 0%, var(--primary-deep, #000e26) 55%, #030712 100%);">
            
            <!-- Dynamic Grid Matrix Texture -->
            <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.12)_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none opacity-35 z-1"></div>

            <!-- Glowing Ambient Lighting Accent Orbs -->
            <div class="absolute top-10 right-1/4 w-96 h-96 bg-theme-secondary/15 rounded-full blur-3xl pointer-events-none z-1"></div>
            <div class="absolute bottom-10 left-10 w-80 h-80 bg-theme-primary/30 rounded-full blur-3xl pointer-events-none z-1"></div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                
                <!-- Split 2-Column Responsive Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                    
                    <!-- ===================================================== -->
                    <!-- LEFT COLUMN: INFORMATIVE TEXT & PROGRESS OVERVIEW     -->
                    <!-- ===================================================== -->
                    <div class="lg:col-span-6 space-y-5 sm:space-y-6 text-left" data-aos="fade-right" data-aos-duration="700">
                        
                        <!-- Eyebrow Pill Badge -->
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-theme-secondary text-slate-950 text-xs font-black uppercase tracking-wider shadow-md">
                            <span class="w-2 h-2 rounded-full bg-slate-950 animate-ping"></span>
                            <span class="w-2 h-2 rounded-full bg-slate-950 -ml-4"></span>
                            <span>{{ $app['tag'] }}</span>
                        </div>

                        <!-- Main Headline -->
                        <div class="space-y-2">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black font-headline tracking-tight text-white leading-tight drop-shadow-md">
                                Aplikasi Dalam Masa <span class="text-theme-secondary">Pengembangan</span>
                            </h1>
                            <div class="w-20 h-1.5 bg-theme-secondary rounded-full"></div>
                        </div>

                        <!-- Informative Description -->
                        <p class="text-sm sm:text-base text-slate-200 leading-relaxed font-normal max-w-xl">
                            {{ $app['description'] }} Tim pengembang kami sedang melakukan konfigurasi server dan penyempurnaan sistem agar aplikasi dapat segera dinikmati dengan optimal.
                        </p>

                        <!-- Interactive Development Progress Box -->
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/15 shadow-xl max-w-xl space-y-3">
                            <div class="flex items-center justify-between text-xs font-extrabold uppercase tracking-wider text-slate-200">
                                <span class="flex items-center gap-2">
                                    <i class="{{ $app['icon'] }} text-theme-secondary animate-bounce"></i>
                                    <span>{{ $app['progress_label'] }}</span>
                                </span>
                                <span class="text-theme-secondary font-black text-sm">{{ $app['progress'] }}</span>
                            </div>

                            <!-- Progress Track & Bar -->
                            <div class="w-full h-3.5 bg-slate-900/70 rounded-full overflow-hidden p-0.5 border border-white/10">
                                <div class="h-full bg-theme-secondary rounded-full animate-progress-stripes transition-all duration-1000 shadow-sm" style="width: {{ $app['progress'] }};"></div>
                            </div>

                            <p class="text-[11px] sm:text-xs text-slate-300 flex items-center gap-1.5">
                                <i class="fas fa-info-circle text-theme-secondary text-xs"></i>
                                <span>Akan segera dapat diakses oleh seluruh warga sekolah SMAN 2 Situbondo.</span>
                            </p>
                        </div>

                        <!-- Action CTA Buttons -->
                        <div class="pt-3 flex flex-wrap items-center gap-3.5">
                            <a href="{{ route('home') }}" 
                               class="spring-hover px-6 py-3 rounded-full bg-theme-secondary text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-lg hover:opacity-95 text-center min-h-[44px] flex items-center justify-center gap-2">
                                <i class="fas fa-arrow-left text-xs"></i>
                                <span>Kembali ke Beranda</span>
                            </a>

                            <a href="{{ route('contact.index') }}" 
                               class="spring-hover px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm uppercase tracking-wider border border-white/25 text-center min-h-[44px] flex items-center justify-center gap-2 backdrop-blur-xs">
                                <i class="far fa-envelope text-theme-secondary"></i>
                                <span>Hubungi Layanan Informasi</span>
                            </a>
                        </div>

                    </div>

                    <!-- ===================================================== -->
                    <!-- RIGHT COLUMN: ANIMATED VECTOR WORKER & CONSTRUCTION   -->
                    <!-- ===================================================== -->
                    <div class="lg:col-span-6 flex items-center justify-center" data-aos="fade-left" data-aos-duration="700">
                        <div class="relative w-full max-w-lg aspect-square sm:aspect-[4/3] rounded-3xl bg-slate-950/70 border-2 border-white/15 p-6 sm:p-8 shadow-2xl backdrop-blur-xl flex items-center justify-center overflow-hidden ring-1 ring-white/10 group">
                            
                            <!-- Ambient Glow Backdrop Behind Illustration -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-theme-primary-deep/60 via-theme-secondary/10 to-transparent pointer-events-none"></div>

                            <!-- Vector Construction Scene (Pure SVG with High-Performance CSS Animations) -->
                            <svg viewBox="0 0 400 320" class="w-full h-full relative z-10 drop-shadow-2xl" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Ilustrasi Pekerja Membangun Aplikasi {{ $app['name'] }}">
                                
                                <defs>
                                    <!-- Dynamic Gradients -->
                                    <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#FCD34D" />
                                        <stop offset="50%" stop-color="#F59E0B" />
                                        <stop offset="100%" stop-color="#D97706" />
                                    </linearGradient>

                                    <linearGradient id="primaryGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#3B82F6" />
                                        <stop offset="100%" stop-color="#1E3A8A" />
                                    </linearGradient>

                                    <linearGradient id="scaffoldGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#475569" />
                                        <stop offset="100%" stop-color="#64748B" />
                                    </linearGradient>
                                </defs>

                                <!-- 1. Background Grid & Scaffolding Beam Structure -->
                                <g opacity="0.35">
                                    <line x1="40" y1="280" x2="360" y2="280" stroke="#94A3B8" stroke-width="4" stroke-linecap="round" />
                                    <line x1="80" y1="280" x2="80" y2="120" stroke="url(#scaffoldGrad)" stroke-width="3" stroke-dasharray="6,4" />
                                    <line x1="320" y1="280" x2="320" y2="100" stroke="url(#scaffoldGrad)" stroke-width="3" stroke-dasharray="6,4" />
                                    <line x1="80" y1="120" x2="320" y2="120" stroke="url(#scaffoldGrad)" stroke-width="3" />
                                    <line x1="80" y1="200" x2="320" y2="200" stroke="url(#scaffoldGrad)" stroke-width="2" stroke-dasharray="4,4" />
                                </g>

                                <!-- 2. Floating Blueprint Paper with Holographic Glow -->
                                <g class="animate-float" transform="translate(240, 45)">
                                    <rect x="0" y="0" width="110" height="75" rx="8" fill="#1E293B" stroke="url(#goldGradient)" stroke-width="2" />
                                    <!-- Blueprint Schematics Lines -->
                                    <line x1="12" y1="15" x2="98" y2="15" stroke="#38BDF8" stroke-width="2" stroke-linecap="round" />
                                    <line x1="12" y1="28" x2="65" y2="28" stroke="#94A3B8" stroke-width="1.5" />
                                    <line x1="12" y1="40" x2="85" y2="40" stroke="#94A3B8" stroke-width="1.5" />
                                    <line x1="12" y1="52" x2="45" y2="52" stroke="#94A3B8" stroke-width="1.5" />
                                    
                                    <!-- Stamp / Seal Badge -->
                                    <circle cx="85" cy="50" r="12" fill="url(#goldGradient)" opacity="0.9" />
                                    <path d="M80 50 L84 54 L91 46" fill="none" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>

                                <!-- 3. Animated Mechanical Gears (Spinning in Realtime) -->
                                <!-- Big Gear -->
                                <g transform="translate(195, 100)" class="animate-spin-cw">
                                    <circle cx="0" cy="0" r="32" fill="none" stroke="url(#goldGradient)" stroke-width="10" stroke-dasharray="16,8" />
                                    <circle cx="0" cy="0" r="14" fill="#0F172A" stroke="url(#goldGradient)" stroke-width="3" />
                                    <circle cx="0" cy="0" r="5" fill="#F59E0B" />
                                </g>

                                <!-- Small Interlocking Gear -->
                                <g transform="translate(242, 140)" class="animate-spin-ccw">
                                    <circle cx="0" cy="0" r="20" fill="none" stroke="#38BDF8" stroke-width="7" stroke-dasharray="10,6" />
                                    <circle cx="0" cy="0" r="8" fill="#0F172A" stroke="#38BDF8" stroke-width="2" />
                                </g>

                                <!-- 4. Spark Particle Effects -->
                                <g class="animate-spark" transform="translate(195, 70)">
                                    <circle cx="0" cy="0" r="3" fill="#FDE047" />
                                    <line x1="-8" y1="-8" x2="8" y2="8" stroke="#FDE047" stroke-width="1.5" stroke-linecap="round" />
                                    <line x1="8" y1="-8" x2="-8" y2="8" stroke="#FDE047" stroke-width="1.5" stroke-linecap="round" />
                                </g>

                                <!-- 5. Main Character: Construction Worker & Builder (Kuli / Tukang Terampil) -->
                                <g transform="translate(50, 60)">
                                    
                                    <!-- Worker Body & Clothes -->
                                    <!-- Legs -->
                                    <rect x="72" y="160" width="16" height="60" rx="4" fill="#1E293B" />
                                    <rect x="94" y="160" width="16" height="60" rx="4" fill="#0F172A" />
                                    <!-- Safety Boots -->
                                    <rect x="68" y="212" width="22" height="10" rx="3" fill="#78350F" />
                                    <rect x="94" y="212" width="22" height="10" rx="3" fill="#78350F" />
                                    
                                    <!-- Torso & Safety Vest -->
                                    <path d="M68 95 L114 95 L118 165 L64 165 Z" fill="#2563EB" />
                                    <!-- High-Vis Vest (Kuning Neon / Emas) -->
                                    <path d="M68 95 L114 95 L118 150 L64 150 Z" fill="url(#goldGradient)" opacity="0.95" />
                                    <!-- Vest Reflective Silver Stripes -->
                                    <rect x="67" y="120" width="48" height="6" fill="#F8FAFC" opacity="0.9" />
                                    <line x1="80" y1="95" x2="80" y2="150" stroke="#F8FAFC" stroke-width="3" />
                                    <line x1="102" y1="95" x2="102" y2="150" stroke="#F8FAFC" stroke-width="3" />

                                    <!-- Head & Face -->
                                    <circle cx="91" cy="72" r="16" fill="#FCD34D" /> <!-- Face Skin -->
                                    <circle cx="97" cy="70" r="2.5" fill="#0F172A" /> <!-- Eye -->
                                    <path d="M94 78 Q98 82 102 78" stroke="#78350F" stroke-width="1.5" fill="none" stroke-linecap="round" /> <!-- Smile -->

                                    <!-- Safety Helmet (Hard Hat Emas SMADA) -->
                                    <path d="M72 65 C72 45 110 45 110 65 Z" fill="#F59E0B" stroke="#D97706" stroke-width="1.5" />
                                    <rect x="68" y="63" width="46" height="5" rx="2" fill="#FBBF24" />
                                    <!-- Flashlight / Badge on Helmet -->
                                    <circle cx="91" cy="54" r="3.5" fill="#FFFFFF" />

                                    <!-- Left Hand Resting on Hip -->
                                    <path d="M68 100 L50 120 L64 135" stroke="#2563EB" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    <circle cx="64" cy="135" r="6" fill="#FCD34D" />

                                    <!-- Right Arm with Animated Tool Working on System -->
                                    <g class="animate-hammer">
                                        <path d="M110 100 L135 90 L155 75" stroke="#2563EB" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                        <circle cx="155" cy="75" r="6" fill="#FCD34D" /> <!-- Hand Glove -->
                                        <!-- Tool (Palu Konstruksi / Hammer) -->
                                        <rect x="150" y="55" width="6" height="30" rx="1.5" fill="#78350F" transform="rotate(-30 150 55)" />
                                        <rect x="156" y="44" width="16" height="10" rx="2" fill="#94A3B8" transform="rotate(-30 156 44)" />
                                    </g>
                                </g>

                                <!-- 6. Safety Traffic Cones & Bricks at Ground Base -->
                                <g transform="translate(290, 240)">
                                    <!-- Safety Cone -->
                                    <polygon points="20,40 35,5 45,5 60,40" fill="#F97316" />
                                    <polygon points="24,30 33,16 47,16 56,30" fill="#FFFFFF" />
                                    <rect x="12" y="38" width="56" height="6" rx="2" fill="#EA580C" />
                                </g>

                                <!-- Construction Bricks Stack -->
                                <g transform="translate(190, 248)">
                                    <rect x="0" y="16" width="36" height="14" rx="2" fill="#B45309" stroke="#78350F" stroke-width="1" />
                                    <rect x="40" y="16" width="36" height="14" rx="2" fill="#D97706" stroke="#78350F" stroke-width="1" />
                                    <rect x="20" y="0" width="36" height="14" rx="2" fill="#F59E0B" stroke="#78350F" stroke-width="1" />
                                </g>

                            </svg>

                            <!-- Subtle Tag in Corner -->
                            <div class="absolute bottom-3 left-3 bg-slate-900/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-slate-300 border border-white/10 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>Pengembangan Aktif</span>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <!-- Wave divider transition to features preview -->
            <div class="absolute bottom-0 inset-x-0 h-6 sm:h-10 bg-gradient-to-t from-gray-50 to-transparent pointer-events-none"></div>
        </main>

        <!-- ------------------------------------------------------------- -->
        <!-- 3. PREVIEW RENCANA FITUR APLIKASI (3 CLEAN CARDS)            -->
        <!-- ------------------------------------------------------------- -->
        <section class="py-12 sm:py-16 bg-gray-50 relative -mt-6 z-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                
                <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12" data-aos="fade-up">
                    <span class="text-xs font-black uppercase tracking-widest text-theme-secondary mb-1 block">Fitur Mendatang</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-headline">
                        Keunggulan Layanan <span class="text-theme-primary">{{ $app['name'] }}</span>
                    </h2>
                    <div class="w-16 h-1 bg-theme-secondary mx-auto mt-2.5 rounded-full"></div>
                </div>

                <!-- 3 Feature Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($app['cards'] as $index => $card)
                        <div class="bg-white rounded-2xl p-6 sm:p-7 border border-gray-200/90 shadow-sm hover:shadow-md transition-all spring-hover flex flex-col justify-between" 
                             data-aos="fade-up" 
                             data-aos-delay="{{ ($index + 1) * 100 }}">
                            <div class="space-y-3.5">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-theme-primary flex items-center justify-center text-xl shadow-2xs border border-blue-100">
                                    <i class="{{ $card['icon'] }}"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 font-headline">{{ $card['title'] }}</h3>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                    {{ $card['desc'] }}
                                </p>
                            </div>
                            <div class="pt-4 border-t border-gray-100 mt-4 flex items-center text-xs font-bold text-theme-primary">
                                <i class="fas fa-check-circle text-theme-secondary mr-1.5"></i>
                                <span>{{ $card['badge'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 4. SHARED FOOTER COMPONENT                                    -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')

</div>

@push('scripts')
<!-- AOS (Animate On Scroll) Library JS -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 700,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50
            });
        }
    });
</script>
@endpush
@endsection
