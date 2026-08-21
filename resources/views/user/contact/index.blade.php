@extends('layouts.app')

@php
    $pageTitle = 'Hubungi Kami - SMA Negeri 2 Situbondo';
    $pageDescription = 'Kami selalu terbuka untuk pertanyaan, saran, maupun masukan dari Anda. Silakan isi formulir di bawah ini atau hubungi kami melalui kontak yang tersedia.';

    // Data tema dinamis
    $primaryColor = $colorSetting?->primary_color ?? '#001c4d';
    $secondaryColor = $colorSetting?->secondary_color ?? '#f59e0b';

    // Fallback data profil kontak jika belum terisi di database
    $contactAddress = 'Jl. Argopuro No.17, Mimbaan, Kec. Panji, Kabupaten Situbondo, Jawa Timur 68322';
    $contactPhone = '(0338) 671234';
    $contactEmail = 'info@sman2situbondo.sch.id';

    // Google Maps Embed URL resmi SMAN 2 Situbondo
    $mapsEmbedUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.766324391696!2d114.01258907499708!3d-7.708204676352932!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd72750e3860bb7%3A0x6b1076b1f28b7468!2sSMAN%202%20Situbondo!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid';

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
       DYNAMIC THEME CSS VARIABLES (CONSISTENT WITH ALL PAGES & FOOTER SYSTEM)
       ========================================================================= */
    :root {
        --primary-main: {{ $primaryColor }};
        --secondary-gold: {{ $secondaryColor }};
        --secondary-main: {{ $secondaryColor }};
        --primary-deep: color-mix(in srgb, var(--primary-main) 80%, black);
        --primary-light: color-mix(in srgb, var(--primary-main) 12%, white);
        --secondary-hover: color-mix(in srgb, var(--secondary-gold) 85%, black);
        --theme-surface: #FAFAFB;
        --theme-card-bg: #FFFFFF;
        --theme-border: #E2E8F0;
    }

    .bg-theme-primary {
        background-color: var(--primary-main) !important;
    }

    .bg-theme-primary-deep {
        background-color: var(--primary-deep) !important;
    }

    .bg-theme-secondary {
        background-color: var(--secondary-gold) !important;
    }

    .hover-bg-primary:hover {
        background-color: var(--primary-main) !important;
        color: #ffffff !important;
    }

    .hover-bg-secondary:hover {
        background-color: var(--secondary-gold) !important;
        color: #020617 !important;
    }

    .text-theme-primary {
        color: var(--primary-main) !important;
    }

    .text-theme-secondary {
        color: var(--secondary-gold) !important;
    }

    .hover-text-primary:hover {
        color: var(--primary-main) !important;
    }

    .hover-text-secondary:hover {
        color: var(--secondary-gold) !important;
    }

    .border-theme-primary {
        border-color: var(--primary-main) !important;
    }

    .border-theme-secondary {
        border-color: var(--secondary-gold) !important;
    }

    /* Spring Hover Effects */
    .spring-hover {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
    }
    .spring-hover:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col justify-between bg-[#FAFAFB] text-slate-800 antialiased selection:bg-amber-100 selection:text-amber-900">
    
    <div>
        <!-- ------------------------------------------------------------- -->
        <!-- 1. SHARED HEADER & NAVBAR COMPONENT                           -->
        <!-- ------------------------------------------------------------- -->
        @include('user.partials.navbar')

        <!-- ------------------------------------------------------------- -->
        <!-- 2. MAIN CONTACT SECTION                                       -->
        <!-- ------------------------------------------------------------- -->
        <main class="py-12 sm:py-16 md:py-20 w-full relative">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl space-y-10 sm:space-y-12">
                
                <!-- Centered Main Headline Header -->
                <div class="text-center max-w-3xl mx-auto" data-aos="fade-up" data-aos-duration="600">
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
                    <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3.5 shadow-xs" 
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
                    <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3.5 shadow-xs" 
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

                <!-- ========================================================= -->
                <!-- CARD 1: FORMULIR KIRIM PESAN                              -->
                <!-- ========================================================= -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 md:p-10 shadow-xs hover:shadow-md transition-all duration-300" 
                     data-aos="fade-up" data-aos-duration="650">
                    
                    <!-- Section Title with Vertical Accent Line -->
                    <div class="flex items-center gap-2.5 mb-6">
                        <span class="w-2 h-7 rounded-full bg-theme-secondary shrink-0 shadow-xs"></span>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 font-headline">
                            Kirim Pesan
                        </h2>
                    </div>

                    <!-- Contact Form Component -->
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Row 1: Nama Lengkap (Left) + Alamat Email (Right) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            
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
                                       class="w-full px-4 py-3 bg-slate-50/50 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 border @error('name') border-rose-400 ring-1 ring-rose-300 @else border-slate-200 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs">
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
                                       class="w-full px-4 py-3 bg-slate-50/50 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 border @error('email') border-rose-400 ring-1 ring-rose-300 @else border-slate-200 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs">
                                @error('email')
                                    <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- Row 2: Nomor Telepon (Left) + Subjek Pesan (Right) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            
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
                                       class="w-full px-4 py-3 bg-slate-50/50 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 border @error('phone') border-rose-400 ring-1 ring-rose-300 @else border-slate-200 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs">
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
                                            class="w-full px-4 py-3 bg-slate-50/50 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 border @error('subject') border-rose-400 ring-1 ring-rose-300 @else border-slate-200 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs appearance-none pr-10 cursor-pointer">
                                        <option value="" disabled {{ old('subject') ? '' : 'selected' }} class="text-slate-400 font-normal">Pilih Subjek</option>
                                        @foreach($subjects as $sub)
                                            <option value="{{ $sub }}" {{ old('subject') === $sub ? 'selected' : '' }} class="text-slate-900 font-medium py-1">
                                                {{ $sub }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-theme-secondary">
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
                                      class="w-full px-4 py-3 bg-slate-50/50 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 border @error('message') border-rose-400 ring-1 ring-rose-300 @else border-slate-200 @enderror focus:bg-white focus:outline-hidden focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 transition shadow-2xs resize-y">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Action Button -->
                        <div class="flex justify-end pt-3">
                            <button type="submit" 
                                    id="btn-submit-contact"
                                    class="spring-hover group inline-flex items-center justify-center gap-3 px-8 py-3.5 rounded-xl bg-theme-primary text-white font-black text-xs sm:text-sm shadow-md hover:shadow-lg border-2 border-theme-secondary cursor-pointer active:scale-95 transition-all">
                                <span>Kirim Pesan</span>
                                <span class="w-7 h-7 rounded-lg bg-theme-secondary text-slate-950 flex items-center justify-center text-xs shrink-0 shadow-2xs group-hover:translate-x-1 transition-transform">
                                    <i class="fas fa-paper-plane text-[11px]"></i>
                                </span>
                            </button>
                        </div>

                    </form>

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
