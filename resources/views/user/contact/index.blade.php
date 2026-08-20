@extends('layouts.app')

@php
    $pageTitle = 'Hubungi Kami - SMA Negeri 2 Situbondo';
    $pageDescription = 'Kami selalu terbuka untuk pertanyaan, saran, maupun masukan dari Anda. Silakan isi formulir di bawah ini atau hubungi kami melalui kontak yang tersedia.';

    // Fallback data profil kontak jika belum terisi di database
    $contactAddress = 'Jl. Argopuro No.17, Mimbaan, Kec. Panji, Kabupaten Situbondo, Jawa Timur 68322';
    $contactPhone = '(0338) 671234';
    $contactEmail = 'info@sman2situbondo.sch.id';

    // Google Maps Embed URL resmi SMAN 2 Situbondo
    $mapsEmbedUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.5181745437895!2d114.00414347590892!3d-7.734748776659775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7275466fa89c3%3A0xad5b376d491be7f2!2sSMA%20Negeri%202%20Situbondo!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid';

    // Schema.org JSON-LD Structured Data
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'ContactPage',
                '@id' => route('contact.index') . '#webpage',
                'url' => route('contact.index'),
                'name' => $pageTitle,
                'description' => $pageDescription,
                'inLanguage' => 'id-ID',
                'mainEntity' => [
                    '@type' => 'EducationalOrganization',
                    'name' => 'SMA Negeri 2 Situbondo',
                    'url' => url('/'),
                    'logo' => asset('images/static/gambar_profile_statis.jpg'),
                    'telephone' => '+62-338-671234',
                    'email' => $contactEmail,
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => 'Jl. Argopuro No.17, Mimbaan, Kec. Panji',
                        'addressLocality' => 'Situbondo',
                        'addressRegion' => 'Jawa Timur',
                        'postalCode' => '68322',
                        'addressCountry' => 'ID',
                    ],
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => route('contact.index') . '#breadcrumb',
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
                        'name' => 'Hubungi Kami',
                        'item' => route('contact.index'),
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
    <meta name="author" content="SMA Negeri 2 Situbondo">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ route('contact.index') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SMA Negeri 2 Situbondo">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ route('contact.index') }}">
    <meta property="og:image" content="{{ asset('images/static/gambar_profile_statis.jpg') }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ asset('images/static/gambar_profile_statis.jpg') }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@push('styles')
<style>
    /* =========================================================================
       DYNAMIC THEME CSS VARIABLES (CONSISTENT WITH NEWS & ANNOUNCEMENT PATTERNS)
       ========================================================================= */
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

    .hover-text-primary:hover { color: var(--primary-main) !important; }
    .hover-bg-primary:hover { background-color: var(--primary-main) !important; color: #ffffff !important; }
    .hover-text-secondary:hover { color: var(--secondary-gold) !important; }
    .hover-bg-secondary:hover { background-color: var(--secondary-gold) !important; }

    /* Spring Physics Hover Lift Animation */
    .spring-hover {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.2s ease, opacity 0.2s ease;
        will-change: transform;
        transform: translate3d(0, 0, 0);
    }
    .spring-hover:hover {
        transform: translateY(-2px) scale(1.01) translate3d(0, 0, 0);
        box-shadow: 0 12px 24px -6px rgba(241, 158, 56, 0.38);
    }
    .spring-hover:active {
        transform: translateY(0) scale(0.99);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col justify-between overflow-x-hidden w-full max-w-full bg-[#f8fafd] relative">
    
    <!-- Background Ambient Dual-Tone Glow Orbs -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-96 h-96 bg-theme-primary/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-48 right-10 w-96 h-96 bg-theme-secondary/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full relative z-10">
        <!-- ------------------------------------------------------------- -->
        <!-- 1. SHARED HEADER & NAVBAR COMPONENT                           -->
        <!-- ------------------------------------------------------------- -->
        @include('user.partials.navbar')

        <!-- ------------------------------------------------------------- -->
        <!-- 2. MAIN CONTACT SECTION & MODULAR 2-COLUMN CONTAINER          -->
        <!-- ------------------------------------------------------------- -->
        <main class="py-12 sm:py-16 md:py-20 w-full relative">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                
                <!-- Centered Main Headline Header (Exact Match Mockup) -->
                <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14" data-aos="fade-up" data-aos-duration="600">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-theme-secondary text-slate-950 border border-theme-secondary shadow-sm mb-3">
                        <i class="fas fa-headset text-slate-950 text-[11px] animate-pulse"></i>
                        <span>Layanan Informasi &amp; Aspirasi</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-theme-primary font-headline tracking-tight mb-2 sm:mb-3">
                        Hubungi Kami
                    </h1>
                    <div class="w-20 h-1.5 bg-theme-secondary mx-auto rounded-full mb-4 shadow-xs"></div>
                    <p class="text-xs sm:text-sm md:text-base text-slate-500 leading-relaxed font-normal max-w-2xl mx-auto">
                        Kami selalu terbuka untuk pertanyaan, saran, maupun masukan dari Anda. Silakan isi formulir di bawah ini atau hubungi kami melalui kontak yang tersedia.
                    </p>
                </div>

                <!-- Global Success Alert Notification -->
                @if(session('success'))
                    <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3.5 shadow-xs" 
                         data-aos="fade-down" role="alert">
                        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-2xs">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <div class="flex-1 text-xs sm:text-sm">
                            <span class="font-bold block mb-0.5">Pesan Berhasil Terkirim!</span>
                            <p class="text-emerald-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Global Error Alert Notification -->
                @if($errors->any())
                    <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3.5 shadow-xs" 
                         data-aos="fade-down" role="alert">
                        <div class="w-7 h-7 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-2xs">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                        </div>
                        <div class="flex-1 text-xs sm:text-sm">
                            <span class="font-bold block mb-1">Terdapat kesalahan pada formulir:</span>
                            <ul class="list-disc list-inside space-y-0.5 text-rose-700 text-xs">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Outer Frame Container (Matching Mockup with 2 Balanced Modular Cards) -->
                <div class="bg-white/95 backdrop-blur-md rounded-3xl border border-slate-200/90 p-5 sm:p-7 md:p-9 shadow-sm hover:shadow-md transition-all duration-300" 
                     data-aos="fade-up" data-aos-duration="650" data-aos-delay="100">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-stretch">
                        
                        <!-- ========================================================= -->
                        <!-- LEFT CARD: INFORMASI KONTAK (5 COLS)                      -->
                        <!-- ========================================================= -->
                        <div class="lg:col-span-5 flex flex-col justify-between space-y-6">
                            
                            <div>
                                <!-- Section Title with Vertical Accent Line -->
                                <div class="flex items-center gap-2.5 mb-6">
                                    <span class="w-2 h-7 rounded-full bg-theme-secondary shrink-0 shadow-xs"></span>
                                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-headline">
                                        Informasi Kontak
                                    </h2>
                                </div>

                                <!-- Contact Items List with Dual-Tone Harmony -->
                                <div class="space-y-4 text-xs sm:text-sm">
                                    
                                    <!-- 1. Alamat -->
                                    <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-slate-50/70 hover:bg-slate-50 border border-slate-100/90 hover:border-theme-secondary/60 transition-all duration-200 group">
                                        <div class="w-11 h-11 rounded-2xl bg-theme-primary text-white flex items-center justify-center shrink-0 mt-0.5 shadow-md border-2 border-theme-secondary ring-2 ring-theme-secondary/20 group-hover:scale-105 transition-all duration-300">
                                            <i class="fas fa-map-marker-alt text-base text-theme-secondary"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="block text-[11px] font-extrabold text-slate-900 uppercase tracking-wider mb-0.5">Alamat</span>
                                            <p class="text-slate-600 leading-relaxed font-normal text-xs sm:text-sm">
                                                {{ $contactAddress }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- 2. Telepon -->
                                    <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-slate-50/70 hover:bg-slate-50 border border-slate-100/90 hover:border-theme-secondary/60 transition-all duration-200 group">
                                        <div class="w-11 h-11 rounded-2xl bg-theme-primary text-white flex items-center justify-center shrink-0 mt-0.5 shadow-md border-2 border-theme-secondary ring-2 ring-theme-secondary/20 group-hover:scale-105 transition-all duration-300">
                                            <i class="fas fa-phone-alt text-base text-theme-secondary"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="block text-[11px] font-extrabold text-slate-900 uppercase tracking-wider mb-0.5">Telepon</span>
                                            <a href="tel:0338671234" class="text-slate-600 hover-text-primary transition-colors font-normal text-xs sm:text-sm">
                                                {{ $contactPhone }}
                                            </a>
                                        </div>
                                    </div>

                                    <!-- 3. Email -->
                                    <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-slate-50/70 hover:bg-slate-50 border border-slate-100/90 hover:border-theme-secondary/60 transition-all duration-200 group">
                                        <div class="w-11 h-11 rounded-2xl bg-theme-primary text-white flex items-center justify-center shrink-0 mt-0.5 shadow-md border-2 border-theme-secondary ring-2 ring-theme-secondary/20 group-hover:scale-105 transition-all duration-300">
                                            <i class="fas fa-envelope text-base text-theme-secondary"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="block text-[11px] font-extrabold text-slate-900 uppercase tracking-wider mb-0.5">Email</span>
                                            <a href="mailto:{{ $contactEmail }}" class="text-slate-600 hover-text-primary transition-colors font-normal text-xs sm:text-sm">
                                                {{ $contactEmail }}
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Interactive Google Maps Frame (Bounded, Zoomable, Fluid) -->
                            <div class="mt-4 pt-2">
                                <div class="w-full aspect-[4/3] rounded-2xl overflow-hidden border border-slate-200/90 bg-slate-100 relative shadow-xs hover:border-theme-primary/30 transition-all duration-300">
                                    <iframe 
                                        src="{{ $mapsEmbedUrl }}" 
                                        class="w-full h-full border-0" 
                                        allowfullscreen="" 
                                        loading="lazy" 
                                        referrerpolicy="no-referrer-when-downgrade"
                                        title="Lokasi SMAN 2 Situbondo di Google Maps">
                                    </iframe>
                                    <!-- Subtle Map Brand Overlay Link -->
                                    <a href="https://maps.app.goo.gl/ftYMjJRxN9KV7v6D6" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="absolute bottom-2.5 right-2.5 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-800 border border-slate-200/80 shadow-xs hover:text-theme-primary hover:border-theme-primary/40 transition-all flex items-center gap-1.5">
                                        <span>Buka di Google Maps</span>
                                        <i class="fas fa-external-link-alt text-[10px] text-theme-secondary"></i>
                                    </a>
                                </div>
                            </div>

                        </div>

                        <!-- ========================================================= -->
                        <!-- RIGHT CARD: FORMULIR KIRIM PESAN (7 COLS)                 -->
                        <!-- ========================================================= -->
                        <div class="lg:col-span-7 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-slate-100 pt-6 lg:pt-0 lg:pl-8">
                            
                            <div>
                                <!-- Section Title with Vertical Accent Line -->
                                <div class="flex items-center gap-2.5 mb-6">
                                    <span class="w-2 h-7 rounded-full bg-theme-secondary shrink-0 shadow-xs"></span>
                                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-headline">
                                        Kirim Pesan
                                    </h2>
                                </div>

                                <!-- Contact Form Component -->
                                <form action="{{ route('contact.store') }}" method="POST" class="space-y-4 sm:space-y-5">
                                    @csrf

                                    <!-- Row 1: Nama Lengkap (Left) + Alamat Email (Right) -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                        
                                        <!-- Nama Lengkap -->
                                        <div>
                                            <label for="contact-name" class="block text-xs font-bold text-slate-900 mb-1.5">
                                                Nama Lengkap <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" 
                                                   id="contact-name" 
                                                   name="name" 
                                                   value="{{ old('name') }}" 
                                                   placeholder="Masukkan nama Anda" 
                                                   required
                                                   class="w-full px-3.5 py-2.5 bg-white rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 border @error('name') border-rose-400 ring-1 ring-rose-300 @else border-slate-300 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs">
                                            @error('name')
                                                <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Alamat Email -->
                                        <div>
                                            <label for="contact-email" class="block text-xs font-bold text-slate-900 mb-1.5">
                                                Alamat Email <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="email" 
                                                   id="contact-email" 
                                                   name="email" 
                                                   value="{{ old('email') }}" 
                                                   placeholder="Masukkan email Anda" 
                                                   required
                                                   class="w-full px-3.5 py-2.5 bg-white rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 border @error('email') border-rose-400 ring-1 ring-rose-300 @else border-slate-300 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs">
                                            @error('email')
                                                <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>

                                    <!-- Row 2: Nomor Telepon (Left) + Subjek Pesan (Right) -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                        
                                        <!-- Nomor Telepon -->
                                        <div>
                                            <label for="contact-phone" class="block text-xs font-bold text-slate-900 mb-1.5">
                                                Nomor Telepon
                                            </label>
                                            <input type="tel" 
                                                   id="contact-phone" 
                                                   name="phone" 
                                                   value="{{ old('phone') }}" 
                                                   placeholder="Masukkan nomor telepon" 
                                                   class="w-full px-3.5 py-2.5 bg-white rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 border @error('phone') border-rose-400 ring-1 ring-rose-300 @else border-slate-300 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs">
                                            @error('phone')
                                                <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Subjek Pesan Dropdown -->
                                        <div>
                                            <label for="contact-subject" class="block text-xs font-bold text-slate-900 mb-1.5">
                                                Subjek Pesan <span class="text-rose-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <select id="contact-subject" 
                                                        name="subject" 
                                                        required
                                                        class="w-full px-3.5 py-2.5 bg-white rounded-xl text-xs sm:text-sm font-semibold text-slate-900 border @error('subject') border-rose-400 ring-1 ring-rose-300 @else border-slate-300 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs appearance-none pr-9 cursor-pointer">
                                                    <option value="" disabled {{ old('subject') ? '' : 'selected' }} class="text-slate-400 font-normal">Pilih Subjek</option>
                                                    @foreach($subjects as $sub)
                                                        <option value="{{ $sub }}" {{ old('subject') === $sub ? 'selected' : '' }} class="text-slate-900 font-medium py-1">
                                                            {{ $sub }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-theme-secondary">
                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                </div>
                                            </div>
                                            @error('subject')
                                                <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>

                                    <!-- Row 3: Pesan Multi-Baris (Textarea) -->
                                    <div>
                                        <label for="contact-message" class="block text-xs font-bold text-slate-900 mb-1.5">
                                            Pesan <span class="text-rose-500">*</span>
                                        </label>
                                        <textarea id="contact-message" 
                                                  name="message" 
                                                  rows="5" 
                                                  placeholder="Tuliskan pesan Anda di sini..." 
                                                  required
                                                  class="w-full px-3.5 py-2.5 bg-white rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 border @error('message') border-rose-400 ring-1 ring-rose-300 @else border-slate-300 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs resize-y">{{ old('message') }}</textarea>
                                        @error('message')
                                            <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Submit Action Button (Bottom-Right, Dual-Tone Accent Color, Arrow Icon) -->
                                    <div class="flex justify-end pt-2">
                                        <button type="submit" 
                                                id="btn-submit-contact"
                                                class="spring-hover group inline-flex items-center justify-center gap-3 px-8 py-3.5 rounded-xl bg-theme-primary hover:bg-theme-primary-deep text-white font-black text-xs sm:text-sm shadow-md hover:shadow-lg border-2 border-theme-secondary hover:border-theme-secondary cursor-pointer active:scale-95 transition-all">
                                            <span>Kirim Pesan</span>
                                            <span class="w-7 h-7 rounded-lg bg-theme-secondary text-slate-950 flex items-center justify-center text-xs shrink-0 shadow-2xs group-hover:translate-x-1 transition-transform"><i class="fas fa-paper-plane text-[11px]"></i></span>
                                        </button>
                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </main>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 3. SHARED FOOTER COMPONENT                                    -->
    <!-- ------------------------------------------------------------- -->
    @include('user.partials.footer')

</div>
@endsection
