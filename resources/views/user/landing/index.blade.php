@extends('layouts.app')

@section('content')
<!-- Tailwind CDN & Alpine.js for 100% Exact Layout Parsing -->
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- Font Awesome 6 Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Google Fonts: Inter & Hanken Grotesk for Figma Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800;900&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@1,600;1,700&display=swap" rel="stylesheet">

<style>
  :root {
    --primary-main: {{ $colorSetting?->primary_color ?? '#001c4d' }};
    --primary-deep: #0d2348;
    --secondary-gold: {{ $colorSetting?->secondary_color ?? '#f59e0b' }};
  }
  body { font-family: 'Inter', sans-serif; }
  .font-headline { font-family: 'Hanken Grotesk', sans-serif; }
  .font-serif-italic { font-family: 'Playfair Display', serif; }
  .bg-navy-main { background-color: var(--primary-main); }
  .bg-navy-deep { background-color: var(--primary-deep); }
  .text-gold-main { color: var(--secondary-gold); }
  .bg-gold-main { background-color: var(--secondary-gold); }
  .border-gold-main { border-color: var(--secondary-gold); }
</style>

<div x-data="{ 
    activeTab: 'siswa', 
    siswaSubTab: 'total',
    showPopup: {{ $activePopup ? 'true' : 'false' }}, 
    activeSlide: 0, 
    totalSlides: {{ count($banners) > 0 ? count($banners) : 1 }} 
}" class="min-h-screen font-sans antialiased text-gray-800 bg-gray-50">

    <!-- ------------------------------------------------------------- -->
    <!-- 1. TOP BAR (EXACT FIGMA MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <div class="bg-gray-100 py-1 text-xs border-b border-gray-200">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-4 h-3 bg-red-600 border border-slate-300 shadow-sm"></span>
                <span class="font-medium text-gray-700">Indonesian <i class="fas fa-chevron-down ml-1 text-[10px] text-gray-500"></i></span>
            </div>
            <div class="flex space-x-4 items-center font-medium text-gray-700">
                <a class="hover:text-blue-900 transition" href="mailto:smadasit@yahoo.com">smadasit@yahoo.com</a>
                <a class="hover:text-blue-900 transition" href="tel:0338671618">(0338) 671618</a>
                <a class="hover:text-blue-900 transition" href="#alumni">Alumni</a>
                <a class="hover:text-blue-900 transition" href="#siklus">SIKLUS</a>
                <a class="bg-gold-main text-slate-950 px-3.5 py-1 font-bold rounded-lg shadow-sm hover:opacity-90 transition" href="#mysmada">MySmada</a>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 2. MAIN HEADER NAVBAR (PERINTAH 1: LOGO ATAS DIHAPUS) -->
    <!-- ------------------------------------------------------------- -->
    <header class="bg-white py-4 shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <!-- Navigation Menu (Logo Dihapus Sesuai Instruksi Perintah 1) -->
            <nav class="w-full flex items-center justify-between text-sm font-semibold text-gray-700">
                <div class="hidden md:flex space-x-6">
                    <a class="text-blue-900 font-bold border-b-2 border-blue-900 pb-0.5" href="{{ route('home') }}">BERANDA</a>
                    
                    <div class="relative group">
                        <button class="hover:text-blue-900 flex items-center uppercase py-1">PROFIL <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i></button>
                        <div class="absolute left-0 mt-2 w-52 bg-white shadow-xl rounded-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900" href="#profil">Visi, Misi &amp; Tujuan</a>
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900" href="#profil">Sejarah Singkat</a>
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900" href="#profil">Struktur Organisasi</a>
                        </div>
                    </div>

                    <a class="hover:text-blue-900 flex items-center" href="#tentang">TENTANG KAMI</a>

                    <div class="relative group">
                        <button class="hover:text-blue-900 flex items-center uppercase py-1">CIVITAS AKADEMIK <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i></button>
                        <div class="absolute left-0 mt-2 w-52 bg-white shadow-xl rounded-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900" href="#civitas">Data Pegawai</a>
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900" href="#siswa">Data Siswa</a>
                        </div>
                    </div>

                    <a class="hover:text-blue-900 flex items-center" href="#pengumuman">PENGUMUMAN</a>

                    <div class="relative group">
                        <button class="hover:text-blue-900 flex items-center uppercase py-1">MEDIA <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i></button>
                        <div class="absolute left-0 mt-2 w-52 bg-white shadow-xl rounded-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900" href="#media">Galeri</a>
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900" href="#media">Video</a>
                        </div>
                    </div>

                    <a class="hover:text-blue-900 flex items-center" href="#berita">BERITA</a>
                    <a class="hover:text-blue-900 flex items-center" href="#contact">CONTACT</a>
                </div>
                
                <a class="text-blue-900 font-extrabold flex items-center uppercase ml-auto" href="#spmb">SPMB</a>
            </nav>

            <button class="md:hidden text-gray-700 ml-4">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </header>

    <!-- ------------------------------------------------------------- -->
    <!-- 3. HERO SECTION (DYNAMIC BANNERS LOOP FROM DATABASE) -->
    <!-- ------------------------------------------------------------- -->
    <section class="relative h-[550px] md:h-[600px] flex items-center overflow-hidden bg-navy-main" x-init="if (totalSlides > 1) { setInterval(() => { activeSlide = (activeSlide + 1) % totalSlides }, 6000) }">
        @if(count($banners) > 0)
            @foreach($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0 w-full h-full flex items-center">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#001c4d]/95 via-[#001c4d]/75 to-transparent"></div>
                    <div class="container mx-auto px-4 relative z-10 text-white">
                        <div class="max-w-2xl space-y-4">
                            <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider text-amber-400 bg-amber-400/10 border border-amber-400/30">
                                Selamat Hari Jadi SMA Negeri 2 Situbondo
                            </span>
                            <h1 class="text-4xl md:text-5xl font-bold leading-tight font-headline text-white drop-shadow-md">
                                {{ $banner->title }}
                            </h1>
                            <p class="text-shadow text-sm md:text-base leading-relaxed text-slate-200">
                                <strong>Smada Prima</strong><br>
                                {{ $banner->description ?? 'Assalamu\'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning.' }}
                            </p>
                            <div class="flex space-x-4 pt-2">
                                <a class="bg-navy-deep hover:opacity-90 text-white font-bold py-2.5 px-7 rounded-full transition duration-300 shadow-lg text-sm uppercase tracking-wider" href="#spmb">SPMB</a>
                                <a class="border-2 border-white hover:bg-white hover:text-black text-white font-bold py-2.5 px-7 rounded-full transition duration-300 text-sm uppercase tracking-wider" href="#elearning">E-LEARNING</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Fallback Hero Frame -->
            <div class="absolute inset-0 w-full h-full flex items-center bg-gradient-to-r from-[#001c4d] via-[#163269] to-[#001c4d]">
                <div class="container mx-auto px-4 relative z-10 text-white">
                    <div class="max-w-2xl space-y-4">
                        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider text-amber-400 bg-amber-400/10 border border-amber-400/30">
                            Selamat Hari Jadi SMA Negeri 2 Situbondo
                        </span>
                        <h1 class="text-4xl md:text-5xl font-bold leading-tight font-headline text-white">SMA Negeri 2<br>Situbondo</h1>
                        <p class="text-shadow text-sm md:text-base leading-relaxed text-slate-200">
                            <strong>Smada Prima</strong><br>
                            Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning. Berbagai informasi tentang pendidikan dapat diakses dalam website sekolah ini, Khususnya Informasi tentang SMAN 2 SITUBONDO.
                        </p>
                        <div class="flex space-x-4 pt-2">
                            <a class="bg-navy-deep hover:opacity-90 text-white font-bold py-2.5 px-7 rounded-full transition duration-300 shadow-lg text-sm uppercase tracking-wider" href="#spmb">SPMB</a>
                            <a class="border-2 border-white hover:bg-white hover:text-black text-white font-bold py-2.5 px-7 rounded-full transition duration-300 text-sm uppercase tracking-wider" href="#elearning">E-LEARNING</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(count($banners) > 1)
            <div class="absolute bottom-16 left-1/2 -translate-x-1/2 z-20 flex space-x-2">
                @foreach($banners as $index => $b)
                    <button @click="activeSlide = {{ $index }}" class="w-3 h-3 rounded-full transition" :class="activeSlide === {{ $index }} ? 'bg-gold-main w-8' : 'bg-white/50'"></button>
                @endforeach
            </div>
        @endif
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 4. QUICK LINKS GRID (PERINTAH 2: WARNA IKON STATIS PUTIH) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-10 bg-white relative -mt-16 z-20 mx-4 md:mx-auto md:max-w-4xl rounded-xl shadow-xl border-t-4 border-blue-900" id="profil">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold text-gray-800 font-headline uppercase tracking-wider">PROFIL <span class="text-blue-900">SEKOLAH</span></h2>
            <div class="w-16 h-1 bg-blue-900 mx-auto mt-2 rounded-full"></div>
        </div>

        <!-- Row 1: 3 Buttons (Ikon Statis Putih text-white Sesuai Perintah 2) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-8 pb-4">
            <a class="bg-navy-deep text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-2xl transition transform hover:-translate-y-1 shadow-md" href="#profil">
                <i class="fas fa-eye text-3xl mb-3 text-white"></i>
                <span class="font-semibold text-sm">Visi Misi</span>
            </a>
            <a class="bg-navy-deep text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-2xl transition transform hover:-translate-y-1 shadow-md" href="#profil">
                <i class="fas fa-sitemap text-3xl mb-3 text-white"></i>
                <span class="font-semibold text-sm">Struktur Organisasi</span>
            </a>
            <a class="bg-navy-deep text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-2xl transition transform hover:-translate-y-1 shadow-md" href="#siswa">
                <i class="fas fa-users text-3xl mb-3 text-white"></i>
                <span class="font-semibold text-sm">Data Siswa</span>
            </a>
        </div>

        <!-- Row 2: 2 Buttons Centered (Ikon Statis Putih text-white Sesuai Perintah 2) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-8 pb-4 max-w-xl mx-auto">
            <a class="bg-navy-deep text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-2xl transition transform hover:-translate-y-1 shadow-md" href="#elearning">
                <i class="fas fa-laptop text-3xl mb-3 text-white"></i>
                <span class="font-semibold text-sm">E-Learning</span>
            </a>
            <a class="bg-navy-deep text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-2xl transition transform hover:-translate-y-1 shadow-md" href="#bukudigital">
                <i class="fas fa-book text-3xl mb-3 text-white"></i>
                <span class="font-semibold text-sm">Buku Digital</span>
            </a>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. SAPA KEPALA SEKOLAH (EXACT FIGMA MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-12 text-white bg-navy-deep">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8 border-b border-blue-500 pb-2">
                <h2 class="text-2xl font-bold font-headline">Sapa <span class="text-gold-main">Kepala Sekolah</span></h2>
                <a class="bg-gold-main text-slate-950 font-bold py-1 px-4 rounded-full text-sm hover:opacity-90 transition shadow" href="#profil">
                    Lainnya <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <div class="md:col-span-1 flex justify-center">
                    <img alt="NIKMATIL HASANAH, S.Pd, M.Pd" class="w-64 h-auto object-cover rounded-lg shadow-2xl border-2 border-white/20" src="/build/assets/kepala sekolah smada.png">
                </div>
                <div class="md:col-span-2 space-y-3">
                    <h3 class="font-bold text-2xl leading-tight font-headline text-white">NIKMATIL HASANAH, S.Pd, M.Pd</h3>
                    <p class="text-sm text-amber-300 font-bold">19640516 200604 2 012</p>
                    <p class="text-base text-blue-100 leading-relaxed text-justify">
                        Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning. Berbagai informasi tentang pendidikan dapat diakses dalam website sekolah ini, Khususnya Informasi tentang SMAN 2 SITUBONDO. Kami terus mengembangkan web ini mengikuti perkembangan teknologi yang sangat cepat. Dengan penuh harapan, kiranya website sekolah ini dapat memberikan manfaat yang maksimal dalam pengembangan dan pemanfaatannya untuk kebutuhan informasi dalam lingkungan sekolah SMAN 2 SITUBONDO and turut memajukan pendidikan di Indonesia. Wassalamu'alaikum Wr. Wb.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 6. NEWS SECTION (BERITA SMADA TOP 5 PUBLISHED DINAMIS DATABASE) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-12 bg-slate-100" id="berita">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-8 border-b-2 border-blue-900 pb-2">
                <h2 class="text-2xl font-bold text-gray-800 font-headline">Berita Smada</h2>
                <a class="text-sm text-gray-500 hover:text-blue-900 transition flex items-center gap-1 font-semibold" href="#berita">
                    Selengkapnya <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            @if(count($newsList) > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Main News (Left 1 Featured Card) -->
                    @php $firstNews = $newsList->first(); @endphp
                    <div class="md:col-span-1 bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 flex flex-col justify-between">
                        <div>
                            <img alt="{{ $firstNews->title }}" class="w-full h-48 object-cover" src="{{ $firstNews->thumbnail_url ?? '/build/assets/banner smada.png' }}">
                            <div class="p-5 space-y-2">
                                <h3 class="font-bold text-lg leading-snug hover:text-blue-900 font-headline text-gray-900 uppercase">
                                    <a href="#berita">{{ $firstNews->title }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 flex items-center gap-1 font-medium">
                                    <i class="far fa-calendar-alt text-gold-main"></i> {{ $firstNews->published_at ? $firstNews->published_at->format('F d, Y') : 'September 10, 2025' }}
                                </p>
                                <p class="text-sm text-gray-600 line-clamp-3 leading-relaxed">
                                    {{ $firstNews->summary }}
                                </p>
                            </div>
                        </div>
                        <div class="p-5 pt-0">
                            <a class="inline-block border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white px-4 py-1.5 rounded-full text-xs font-bold transition shadow-sm" href="#berita">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- News List Grid (Right 4 Side Cards in 2x2) -->
                    <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($newsList->slice(1, 4) as $item)
                            <div class="flex space-x-3 bg-white p-3.5 rounded-lg shadow-sm border border-gray-100 items-start hover:shadow-md transition">
                                <img alt="{{ $item->title }}" class="w-24 h-20 object-cover rounded flex-shrink-0" src="{{ $item->thumbnail_url ?? '/build/assets/banner smada.png' }}">
                                <div class="space-y-1">
                                    <h4 class="font-semibold text-xs leading-tight hover:text-blue-900 line-clamp-2 text-gray-900 uppercase">
                                        <a href="#berita">{{ $item->title }}</a>
                                    </h4>
                                    <p class="text-[11px] text-gray-400 flex items-center gap-1 font-medium">
                                        <i class="far fa-calendar-alt text-gold-main text-[10px]"></i> {{ $item->published_at ? $item->published_at->format('F d, Y') : 'August 28, 2025' }}
                                    </p>
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
    <!-- 7. SMADA FACT SECTION (PERINTAH 5: 4 FILTER SISWA + DINAMIS COLOR) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-12 text-white relative bg-navy-main">
        <div class="absolute inset-0 bg-navy-deep bg-opacity-70"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            
            <div class="inline-block bg-white text-blue-900 font-bold py-2 px-12 rounded-full mb-6 text-xl shadow">
                SMADA <span class="text-gold-main">FACT</span>
            </div>

            <!-- Primary Tabs -->
            <div class="flex flex-wrap justify-center gap-3 mb-4">
                <button @click="activeTab = 'siswa'" :class="activeTab === 'siswa' ? 'bg-gold-main text-slate-950 border-gold-main' : 'bg-transparent text-white border-white'" class="px-6 py-2 rounded-full text-sm font-semibold border transition">
                    PESERTA DIDIK
                </button>
                <button @click="activeTab = 'guru'" :class="activeTab === 'guru' ? 'bg-gold-main text-slate-950 border-gold-main' : 'bg-transparent text-white border-white'" class="px-6 py-2 rounded-full text-sm font-semibold border transition">
                    GURU
                </button>
                <button @click="activeTab = 'staf'" :class="activeTab === 'staf' ? 'bg-gold-main text-slate-950 border-gold-main' : 'bg-transparent text-white border-white'" class="px-6 py-2 rounded-full text-sm font-semibold border transition">
                    STAFF
                </button>
            </div>

            <!-- Sub-Filter Siswa (Perintah 5: 4 Filter Siswa) -->
            <div x-show="activeTab === 'siswa'" class="flex flex-wrap justify-center gap-2 mb-8">
                <button @click="siswaSubTab = 'total'" :class="siswaSubTab === 'total' ? 'bg-white text-slate-900 font-bold' : 'bg-white/20 text-white'" class="px-4 py-1 rounded-full text-xs transition">
                    Total Seluruh Siswa
                </button>
                <button @click="siswaSubTab = 'x'" :class="siswaSubTab === 'x' ? 'bg-white text-slate-900 font-bold' : 'bg-white/20 text-white'" class="px-4 py-1 rounded-full text-xs transition">
                    Siswa Kelas X
                </button>
                <button @click="siswaSubTab = 'xi'" :class="siswaSubTab === 'xi' ? 'bg-white text-slate-900 font-bold' : 'bg-white/20 text-white'" class="px-4 py-1 rounded-full text-xs transition">
                    Siswa Kelas XI
                </button>
                <button @click="siswaSubTab = 'xii'" :class="siswaSubTab === 'xii' ? 'bg-white text-slate-900 font-bold' : 'bg-white/20 text-white'" class="px-4 py-1 rounded-full text-xs transition">
                    Siswa Kelas XII
                </button>
            </div>

            <!-- Tab Content: PESERTA DIDIK (Interactive Breakdown 4 Cards) -->
            <div x-show="activeTab === 'siswa'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
                <div :class="siswaSubTab === 'total' ? 'border-2 border-gold-main bg-black/70 scale-105' : 'border border-gold-main/50 bg-black/40'" class="rounded-lg p-5 transition duration-300 shadow-xl">
                    <div class="text-3xl font-bold text-gold-main mb-1 font-headline">{{ $studentStats['total'] }}</div>
                    <div class="text-xs font-medium uppercase tracking-wider text-slate-200">Total Seluruh Siswa</div>
                </div>
                <div :class="siswaSubTab === 'x' ? 'border-2 border-gold-main bg-black/70 scale-105' : 'border border-gold-main/50 bg-black/40'" class="rounded-lg p-5 transition duration-300 shadow-xl">
                    <div class="text-3xl font-bold text-gold-main mb-1 font-headline">{{ $studentStats['kelas_10'] }}</div>
                    <div class="text-xs font-medium uppercase tracking-wider text-slate-200">Siswa Kelas X</div>
                </div>
                <div :class="siswaSubTab === 'xi' ? 'border-2 border-gold-main bg-black/70 scale-105' : 'border border-gold-main/50 bg-black/40'" class="rounded-lg p-5 transition duration-300 shadow-xl">
                    <div class="text-3xl font-bold text-gold-main mb-1 font-headline">{{ $studentStats['kelas_11'] }}</div>
                    <div class="text-xs font-medium uppercase tracking-wider text-slate-200">Siswa Kelas XI</div>
                </div>
                <div :class="siswaSubTab === 'xii' ? 'border-2 border-gold-main bg-black/70 scale-105' : 'border border-gold-main/50 bg-black/40'" class="rounded-lg p-5 transition duration-300 shadow-xl">
                    <div class="text-3xl font-bold text-gold-main mb-1 font-headline">{{ $studentStats['kelas_12'] }}</div>
                    <div class="text-xs font-medium uppercase tracking-wider text-slate-200">Siswa Kelas XII</div>
                </div>
            </div>

            <!-- Tab Content: GURU -->
            <div x-show="activeTab === 'guru'" class="max-w-md mx-auto bg-black/50 border border-gold-main rounded-lg p-8 shadow-xl">
                <div class="text-5xl font-bold text-gold-main mb-2 font-headline">{{ $employeeStats['guru'] }}</div>
                <div class="text-sm font-medium uppercase tracking-wider text-slate-200">Guru (Tenaga Pendidik)</div>
            </div>

            <!-- Tab Content: STAFF -->
            <div x-show="activeTab === 'staf'" class="max-w-md mx-auto bg-black/50 border border-gold-main rounded-lg p-8 shadow-xl">
                <div class="text-5xl font-bold text-gold-main mb-2 font-headline">{{ $employeeStats['staf'] }}</div>
                <div class="text-sm font-medium uppercase tracking-wider text-slate-200">Staff (Tenaga Kependidikan)</div>
            </div>

        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 8. AGENDA & PENGUMUMAN + EKSTRAKURIKULER (EXACT FIGMA MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-12 bg-white" id="pengumuman">
        <div class="container mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Agenda & Pengumuman Left -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-end border-b-2 border-blue-900 pb-2">
                    <h2 class="text-2xl font-bold text-gray-800 font-headline">Agenda &amp; Pengumuman</h2>
                    <a class="text-sm text-gray-500 hover:text-blue-900 transition flex items-center gap-1 font-semibold" href="#pengumuman">
                        Selengkapnya <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                @if(count($announcementsList) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Main Announcement Card -->
                        @php $firstAnn = $announcementsList->first(); @endphp
                        <div class="bg-gray-50 rounded-lg p-4 shadow flex flex-col justify-between border border-gray-100">
                            <div>
                                <img alt="{{ $firstAnn->title }}" class="w-full h-40 object-cover mb-4 rounded" src="{{ $firstAnn->thumbnail_url ?? '/build/assets/banner smada.png' }}">
                                <h3 class="font-bold mb-1 text-slate-900 text-sm uppercase leading-snug font-headline">{{ $firstAnn->title }}</h3>
                                <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                    <i class="far fa-calendar-alt text-gold-main"></i> {{ $firstAnn->published_at ? $firstAnn->published_at->format('F d, Y') : 'July 16, 2022' }}
                                </p>
                                <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed mb-4">
                                    {{ $firstAnn->summary }}
                                </p>
                            </div>
                            <div>
                                <a class="inline-block border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white px-4 py-1 rounded-full text-xs font-bold transition" href="#pengumuman">Selengkapnya</a>
                            </div>
                        </div>

                        <!-- Side Announcement Cards -->
                        <div class="space-y-4">
                            @foreach($announcementsList->slice(1, 2) as $annItem)
                                <div class="bg-gray-50 p-4 rounded-lg shadow border border-gray-100 space-y-1">
                                    <h3 class="font-bold mb-1 text-xs text-slate-900 uppercase leading-snug font-headline">{{ $annItem->title }}</h3>
                                    <p class="text-[11px] text-gray-400 mb-2 flex items-center gap-1">
                                        <i class="far fa-calendar-alt text-gold-main"></i> {{ $annItem->published_at ? $annItem->published_at->format('F d, Y') : 'May 05, 2022' }}
                                    </p>
                                    <p class="text-xs text-gray-600 line-clamp-2 mb-2 leading-relaxed">
                                        {{ $annItem->summary }}
                                    </p>
                                    <a class="inline-block border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white px-3 py-1 rounded-full text-[11px] font-bold transition" href="#pengumuman">Selengkapnya</a>
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
            <div>
                <div class="mb-6 border-b-2 border-blue-900 pb-2">
                    <h2 class="text-2xl font-bold text-gray-800 font-headline">Ekstrakurikuler</h2>
                </div>
                <ul class="space-y-4 font-semibold text-sm text-gray-700">
                    <li class="border-b border-gray-200 pb-3 hover:text-blue-900 transition"><a href="#ekstra">Musik</a></li>
                    <li class="border-b border-gray-200 pb-3 hover:text-blue-900 transition"><a href="#ekstra">Kharismada</a></li>
                    <li class="border-b border-gray-200 pb-3 hover:text-blue-900 transition"><a href="#ekstra">Jurnalistik</a></li>
                    <li class="border-b border-gray-200 pb-3 hover:text-blue-900 transition"><a href="#ekstra">Pecinta Alam</a></li>
                </ul>
                <a class="inline-block border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white px-4 py-2 rounded-full text-sm font-bold transition mt-4 w-full text-center shadow-sm" href="#ekstra">Ekstrakurikuler Lainnya</a>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 9. ATMOSFER SEKOLAH (PERINTAH 3: INSTAGRAM REAL FEED 10 POSTS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-16 bg-gray-900 text-white relative overflow-hidden" id="media">
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold tracking-widest font-headline uppercase">ATMOSFER <span class="text-gold-main">SEKOLAH</span></h2>
                <p class="text-xs text-gray-400 mt-1 font-mono">@sman2situbondoofficial</p>
            </div>

            <!-- Horizontal Carousel Frame - 10 Posts -->
            <div class="flex items-center space-x-4 overflow-x-auto pb-8 scrollbar-thin scrollbar-thumb-amber-500 max-w-6xl mx-auto">
                @if(!empty($instagramPosts) && count($instagramPosts) > 0)
                    @foreach($instagramPosts as $idx => $photoUrl)
                        <div class="flex-shrink-0 rounded-xl overflow-hidden shadow-xl transition transform hover:scale-105 border {{ $idx === 1 ? 'w-80 h-96 border-4 border-white z-10 shadow-2xl' : 'w-64 h-80 border-gray-700 bg-gray-800' }}">
                            <img alt="Atmosfer Sekolah {{ $idx + 1 }}" class="w-full h-full object-cover" src="{{ $photoUrl }}">
                        </div>
                    @endforeach
                @else
                    <div class="w-64 h-80 flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700">
                        <img alt="Atmosfer 1" class="w-full h-full object-cover" src="/build/assets/banner smada.png">
                    </div>
                    <div class="w-80 h-96 flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-2xl border-4 border-white z-10">
                        <img alt="Atmosfer 2 Poster" class="w-full h-full object-cover" src="/build/assets/banner smada.png">
                    </div>
                    <div class="w-64 h-80 flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700">
                        <img alt="Atmosfer 3" class="w-full h-full object-cover" src="/build/assets/kepala sekolah smada.png">
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 10. MOTTO BANNER SECTION (EXACT FIGMA MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-10 bg-white border-b-4 border-blue-900">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-serif-italic text-blue-900 font-headline">
                Dari<br>
                <span class="font-bold uppercase tracking-widest text-4xl md:text-5xl text-gold-main font-sans">SMADA PRIMA</span><br>
                Untuk Bangsa
            </h2>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 11. FOOTER (EXACT FIGMA MATCH WITH TWITTER REMOVED) -->
    <!-- ------------------------------------------------------------- -->
    <footer class="bg-navy-main text-white pt-12 pb-6 border-t border-blue-900" id="contact">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Brand & Accreditation -->
            <div class="text-center md:text-left flex flex-col items-center md:items-start">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded bg-white text-slate-900 flex items-center justify-center font-black text-sm shadow">S2</div>
                    <span class="font-extrabold text-white text-base tracking-wider font-headline uppercase">SMAN 2 SITUBONDO</span>
                </div>
                <p class="text-xs text-blue-100 leading-relaxed max-w-xs">
                    SMA Negeri 2 Situbondo berkomitmen mencetak generasi bangsa unggul, berakhlak mulia, dan berwawasan lingkungan.
                </p>
            </div>

            <!-- Informasi Tentang -->
            <div>
                <h4 class="font-bold mb-4 text-lg border-b border-blue-500 pb-2 inline-block font-headline">Informasi Tentang</h4>
                <ul class="space-y-2 text-sm text-blue-100">
                    <li><a class="hover:text-white hover:underline" href="#profil">&bull; Visi Misi &amp; Tujuan</a></li>
                    <li><a class="hover:text-white hover:underline" href="#profil">&bull; Sejarah Singkat</a></li>
                    <li><a class="hover:text-white hover:underline" href="#profil">&bull; Struktur Organisasi</a></li>
                    <li><a class="hover:text-white hover:underline" href="#civitas">&bull; Data Pegawai</a></li>
                    <li><a class="hover:text-white hover:underline" href="#siswa">&bull; Data Siswa</a></li>
                    <li><a class="hover:text-white hover:underline" href="#profil">&bull; Sarana &amp; Prasarana</a></li>
                </ul>
            </div>

            <!-- Link Lainnya -->
            <div>
                <h4 class="font-bold mb-4 text-lg border-b border-blue-500 pb-2 inline-block font-headline">Link Lainnya</h4>
                <ul class="space-y-2 text-sm text-blue-100">
                    <li><a class="hover:text-white hover:underline" href="#elearning">&bull; Elearning</a></li>
                    <li><a class="hover:text-white hover:underline" href="#media">&bull; Video Pembelajaran</a></li>
                    <li><a class="hover:text-white hover:underline" href="#bukudigital">&bull; Buku Digital</a></li>
                    <li><a class="hover:text-white hover:underline" href="#literasi">&bull; Literasi</a></li>
                    <li><a class="hover:text-white hover:underline" href="#spmb">&bull; SPMB</a></li>
                </ul>
            </div>

            <!-- Kontak Kami -->
            <div>
                <h4 class="font-bold mb-4 text-lg border-b border-blue-500 pb-2 inline-block font-headline">Kontak Kami</h4>
                <p class="text-sm text-blue-100 mb-2">Jl. Anggrek No. 1 Patokan, Kab. Situbondo - Indonesia</p>
                <p class="text-sm text-blue-100 mb-2">Telp. : (0338) 671618</p>
                <p class="text-sm text-blue-100">Email : smadasit@yahoo.com</p>
            </div>
        </div>

        <!-- Copyright & Socials (Twitter/X Removed) -->
        <div class="container mx-auto px-4 mt-4 flex flex-col md:flex-row justify-between items-center text-xs text-blue-300 border-t border-blue-800 pt-4">
            <p>Copyright &copy; 2026 SMA NEGERI 2 SITUBONDO</p>
            <div class="flex space-x-4 mt-4 md:mt-0 text-white text-lg">
                <!-- Facebook -->
                <a class="hover:text-gold-main transition" href="https://www.facebook.com/Sma.Negeri.2.Situbondo/" target="_blank" rel="noopener" title="facebook"><i class="fab fa-facebook"></i></a>
                <!-- YouTube -->
                <a class="hover:text-gold-main transition" href="https://www.youtube.com/c/SMADAPRIMA/videos" target="_blank" rel="noopener" title="youtube"><i class="fab fa-youtube"></i></a>
                <!-- Instagram -->
                <a class="hover:text-gold-main transition" href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener" title="instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <!-- ------------------------------------------------------------- -->
    <!-- FLOATING ACTION BUTTONS (ACCESSIBILITY, WHATSAPP, TOP) -->
    <!-- ------------------------------------------------------------- -->
    <div class="fixed bottom-4 right-4 flex flex-col space-y-2 z-50">
        <a class="bg-gold-main text-slate-950 p-3 rounded-full shadow-lg hover:opacity-90 flex items-center justify-center h-12 w-12 transition" href="#" title="Aksesibilitas">
            <i class="fas fa-universal-access text-xl"></i>
        </a>
        <a class="bg-green-500 text-white p-3 rounded-full shadow-lg hover:bg-green-600 flex items-center justify-center h-12 w-12 transition" href="https://wa.me/628123456789" target="_blank" rel="noopener" title="WhatsApp">
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>
    </div>
    
    <a class="fixed bottom-4 left-4 bg-black text-white p-3 rounded-lg shadow-lg hover:bg-gray-800 flex items-center justify-center h-10 w-10 z-50 transition" href="#" title="Ke Atas">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- ------------------------------------------------------------- -->
    <!-- POP-UP EVENT MODAL -->
    <!-- ------------------------------------------------------------- -->
    @if($activePopup)
        <div x-show="showPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm" x-transition>
            <div class="bg-white rounded-xl overflow-hidden max-w-md w-full shadow-2xl relative border border-gray-200">
                <button @click="showPopup = false" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-xs font-bold z-10 hover:bg-red-600 transition">
                    <i class="fas fa-times"></i>
                </button>
                @if($activePopup->image_url)
                    <img src="{{ $activePopup->image_url }}" alt="{{ $activePopup->title }}" class="w-full h-44 object-cover">
                @endif
                <div class="p-5 space-y-2">
                    <h4 class="font-bold text-gray-900 text-base leading-tight uppercase font-headline">{{ $activePopup->title }}</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $activePopup->description }}</p>
                    <button @click="showPopup = false" class="w-full py-2 rounded-lg font-bold text-xs text-white uppercase tracking-wider transition shadow bg-blue-900 hover:opacity-90">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
