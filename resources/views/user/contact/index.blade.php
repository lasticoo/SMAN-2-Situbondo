@extends('layouts.app')

@php
    $pageTitle = 'Hubungi Kami - SMA Negeri 2 Situbondo';
    $pageDescription = 'Kami selalu terbuka untuk pertanyaan, saran, maupun masukan dari Anda. Silakan isi formulir di bawah ini atau hubungi kami melalui kontak yang tersedia.';

    // Data tema dinamis
    $primaryColor = $colorSetting->primary_color ?? '#05479E';
    $secondaryColor = $colorSetting->secondary_color ?? '#F19E38';

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
       DYNAMIC THEME CSS VARIABLES (CONSISTENT WITH NEWS & ANNOUNCEMENT PATTERNS)
       ========================================================================= */
    :root {
        --primary-main: {{ $primaryColor ?? '#05479E' }};
        --primary-deep: #032b69;
        --secondary-main: {{ $secondaryColor ?? '#F19E38' }};
        --secondary-gold: {{ $secondaryColor ?? '#F19E38' }};
        --theme-surface: #FAFAFB;
        --theme-card-bg: #FFFFFF;
        --theme-border: #E2E8F0;
    }

    .bg-theme-primary {
        background-color: var(--primary-main) !important;
    }

    .bg-theme-secondary {
        background-color: var(--secondary-gold) !important;
    }

    .hover-bg-primary:hover {
        background-color: var(--primary-main) !important;
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

                <!-- ========================================================= -->
                <!-- CARD 2: INFORMASI KONTAK & INTERACTIVE GOOGLE MAPS        -->
                <!-- ========================================================= -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 md:p-10 shadow-xs hover:shadow-md transition-all duration-300" 
                     data-aos="fade-up" data-aos-duration="650" data-aos-delay="150">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                        
                        <!-- Left: Contact Details (5 cols) -->
                        <div class="lg:col-span-5 space-y-5">
                            
                            <!-- Eyebrow Tag -->
                            <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-theme-secondary">
                                <span class="w-1.5 h-3.5 bg-theme-secondary rounded-full inline-block"></span>
                                <span>Sekretariat &amp; Layanan</span>
                            </div>

                            <!-- Section Title -->
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-headline tracking-tight">
                                Informasi Kontak
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                Untuk informasi lebih lanjut dan pelayanan langsung, silakan kunjungi kantor sekretariat SMAN 2 Situbondo atau hubungi kami melalui kanal resmi berikut.
                            </p>

                            <!-- Contact Badges List with Dynamic Colors -->
                            <div class="space-y-4 text-xs sm:text-sm pt-1">
                                
                                <!-- 1. Alamat -->
                                <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-50/70 border border-slate-100 hover:border-theme-secondary/50 transition-all group">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-theme-primary flex items-center justify-center shrink-0 mt-0.5 border border-blue-100 shadow-2xs group-hover:scale-105 transition-transform">
                                        <i class="fas fa-map-marker-alt text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-extrabold text-slate-900 uppercase tracking-wider mb-0.5">Alamat</span>
                                        <p class="text-slate-600 leading-relaxed font-normal text-xs sm:text-sm">
                                            {{ $contactAddress }}
                                        </p>
                                    </div>
                                </div>

                                <!-- 2. Telepon -->
                                <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-slate-50/70 border border-slate-100 hover:border-theme-secondary/50 transition-all group">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 shadow-2xs group-hover:scale-105 transition-transform">
                                        <i class="fas fa-phone-alt text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-extrabold text-slate-900 uppercase tracking-wider mb-0.5">Telepon</span>
                                        <a href="tel:0338671234" class="text-slate-700 font-semibold hover-text-primary transition-colors text-xs sm:text-sm">
                                            {{ $contactPhone }}
                                        </a>
                                    </div>
                                </div>

                                <!-- 3. Email -->
                                <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-slate-50/70 border border-slate-100 hover:border-theme-secondary/50 transition-all group">
                                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100 shadow-2xs group-hover:scale-105 transition-transform">
                                        <i class="fas fa-envelope text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-extrabold text-slate-900 uppercase tracking-wider mb-0.5">Email</span>
                                        <a href="mailto:{{ $contactEmail }}" class="text-slate-700 font-semibold hover-text-primary transition-colors text-xs sm:text-sm">
                                            {{ $contactEmail }}
                                        </a>
                                    </div>
                                </div>

                            </div>

                            <div class="pt-2">
                                <a href="https://maps.app.goo.gl/ftYMjJRxN9KV7v6D6" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-theme-primary hover:text-theme-secondary transition-colors">
                                    <span>Buka di Google Maps</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>

                        </div>

                        <!-- Right: Responsive Interactive Google Maps Embed (7 cols) -->
                        <div class="lg:col-span-7">
                            <div class="rounded-2xl overflow-hidden border border-slate-200/90 shadow-xs h-72 sm:h-80 md:h-96 w-full relative">
                                <iframe 
                                    src="{{ $mapsEmbedUrl }}" 
                                    class="w-full h-full border-0" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Lokasi SMAN 2 Situbondo di Google Maps">
                                </iframe>
                                <a href="https://maps.app.goo.gl/ftYMjJRxN9KV7v6D6" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-800 border border-slate-200/80 shadow-xs hover:text-theme-primary hover:border-theme-primary/40 transition-all flex items-center gap-1.5">
                                    <span>Buka di Google Maps</span>
                                    <i class="fas fa-external-link-alt text-[10px] text-theme-secondary"></i>
                                </a>
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
