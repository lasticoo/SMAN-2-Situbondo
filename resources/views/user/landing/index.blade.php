@extends('layouts.app')

@section('content')
<div x-data="{ activeTab: 'siswa', showPopup: {{ $activePopup ? 'true' : 'false' }}, activeSlide: 0, totalSlides: {{ count($banners) > 0 ? count($banners) : 1 }} }" class="min-h-screen font-sans bg-slate-50 text-slate-800">

    <!-- ------------------------------------------------------------- -->
    <!-- 1. TOPBAR ATAS -->
    <!-- ------------------------------------------------------------- -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1">
                    <span class="inline-block w-4 h-3 bg-red-600 border border-slate-700"></span>
                    <select class="bg-transparent border-none text-xs text-slate-300 focus:ring-0 cursor-pointer">
                        <option class="bg-slate-800">Indonesia</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    smadasit@yahoo.com
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    (0338) 671618
                </span>
                <a href="#alumni" class="hover:text-amber-400 transition">Alumni</a>
                <a href="#siklus" class="hover:text-amber-400 transition">SIKLUS</a>
                <a href="#mysmada" class="px-2.5 py-1 rounded text-xs font-semibold text-white transition shadow-sm" style="background-color: var(--secondary-color, #f59e0b)">MySmada</a>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 2. NAVBAR UTAMA -->
    <!-- ------------------------------------------------------------- -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shadow" style="background-color: var(--primary-color, #1e3a8a)">
                    S2
                </div>
                <div>
                    <h1 class="font-extrabold text-lg tracking-tight leading-none text-slate-900">SMA NEGERI 2</h1>
                    <span class="text-xs font-semibold tracking-widest uppercase text-slate-500">SITUBONDO</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden lg:flex items-center gap-6 font-semibold text-sm text-slate-700">
                <a href="{{ route('home') }}" class="transition" style="color: var(--primary-color, #1e3a8a)">BERANDA</a>
                <a href="#profil" class="hover:text-blue-900 transition">PROFIL</a>
                <a href="#tentang" class="hover:text-blue-900 transition">TENTANG KAMI</a>
                <a href="#civitas" class="hover:text-blue-900 transition">CIVITAS AKADEMIK</a>
                <a href="#pengumuman" class="hover:text-blue-900 transition">PENGUMUMAN</a>
                <a href="#media" class="hover:text-blue-900 transition">MEDIA</a>
                <a href="#berita" class="hover:text-blue-900 transition">BERITA</a>
                <a href="#contact" class="hover:text-blue-900 transition">CONTACT</a>
                <a href="#spmb" class="px-4 py-1.5 rounded-full text-white font-bold text-xs uppercase shadow transition hover:opacity-90" style="background-color: var(--primary-color, #1e3a8a)">SPMB</a>
            </nav>
        </div>
    </header>

    <!-- ------------------------------------------------------------- -->
    <!-- 3. HERO BANNER SECTION (DINAMIS SAMA DENGAN FIGMA) -->
    <!-- ------------------------------------------------------------- -->
    <section class="relative bg-slate-900 text-white overflow-hidden min-h-[520px] flex items-center" x-init="if (totalSlides > 1) { setInterval(() => { activeSlide = (activeSlide + 1) % totalSlides }, 6000) }">
        
        @if(count($banners) > 0)
            @foreach($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0 w-full h-full">
                    <!-- Background Image & Gradient Overlay -->
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-transparent"></div>
                    
                    <!-- Banner Content -->
                    <div class="relative max-w-7xl mx-auto px-4 h-full flex items-center pt-12 pb-28">
                        <div class="max-w-2xl space-y-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider text-amber-400 bg-amber-400/10 border border-amber-400/30">
                                Selamat Hari Jadi SMAN 2 Situbondo
                            </span>
                            <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight text-white drop-shadow-md">
                                {{ $banner->title }}
                            </h2>
                            <p class="text-slate-300 text-sm md:text-base leading-relaxed max-w-xl">
                                {{ $banner->description ?? 'Mewujudkan Peserta Didik yang Berakhlak Mulia, Cerdas, Berprestasi, dan Berwawasan Lingkungan.' }}
                            </p>
                            <div class="flex items-center gap-4 pt-4">
                                <a href="#spmb" class="px-6 py-3 rounded-lg font-bold text-sm text-white shadow-lg transition hover:scale-105" style="background-color: var(--primary-color, #1e3a8a)">
                                    SPMB
                                </a>
                                <a href="#elearning" class="px-6 py-3 rounded-lg font-bold text-sm text-white border border-white/40 bg-white/10 backdrop-blur hover:bg-white/20 transition">
                                    E-LEARNING
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Fallback Frame jika belum ada Banner di Database -->
            <div class="relative w-full h-full py-20 px-4">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 opacity-90"></div>
                <div class="relative max-w-7xl mx-auto px-4 pt-8 pb-24 text-left">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider text-amber-400 bg-amber-400/10 border border-amber-400/30 mb-4">
                        Selamat Hari Jadi SMA Negeri 2 Situbondo
                    </span>
                    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">
                        SMA Negeri 2 Situbondo
                    </h2>
                    <p class="text-slate-300 text-sm md:text-base max-w-xl mb-6">
                        Smada Prima — Mewujudkan Peserta Didik yang Berakhlak Mulia, Cerdas, Berprestasi, dan Berwawasan Lingkungan.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#spmb" class="px-6 py-3 rounded-lg font-bold text-sm text-white shadow-lg" style="background-color: var(--primary-color, #1e3a8a)">SPMB</a>
                        <a href="#elearning" class="px-6 py-3 rounded-lg font-bold text-sm text-white border border-white/40 bg-white/10">E-LEARNING</a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Carousel Indicators -->
        @if(count($banners) > 1)
            <div class="absolute bottom-16 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                @foreach($banners as $index => $b)
                    <button @click="activeSlide = {{ $index }}" class="w-3 h-3 rounded-full transition" :class="activeSlide === {{ $index }} ? 'bg-amber-400 w-8' : 'bg-white/50'"></button>
                @endforeach
            </div>
        @endif
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 4. FLOATING GRID - PROFIL SEKOLAH (5 BUTTONS FIGMA) -->
    <!-- ------------------------------------------------------------- -->
    <div class="relative max-w-5xl mx-auto px-4 -mt-16 z-30">
        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-2xl border border-slate-100">
            <div class="text-center mb-6">
                <h3 class="text-lg font-extrabold uppercase tracking-widest text-slate-800 inline-block relative pb-2">
                    PROFIL <span style="color: var(--primary-color, #1e3a8a)">SEKOLAH</span>
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-12 h-1 rounded-full" style="background-color: var(--primary-color, #1e3a8a)"></span>
                </h3>
            </div>

            <!-- Grid 5 Button Sesuai Figma -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- 1. Visi Misi -->
                <a href="#profil" class="flex items-center justify-center gap-3 p-4 rounded-xl text-white font-bold text-sm shadow-md transition transform hover:-translate-y-1" style="background-color: var(--primary-color, #1e3a8a)">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Visi Misi
                </a>

                <!-- 2. Struktur Organisasi -->
                <a href="#profil" class="flex items-center justify-center gap-3 p-4 rounded-xl text-white font-bold text-sm shadow-md transition transform hover:-translate-y-1" style="background-color: var(--primary-color, #1e3a8a)">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Struktur Organisasi
                </a>

                <!-- 3. Data Siswa -->
                <a href="#siswa" class="flex items-center justify-center gap-3 p-4 rounded-xl text-white font-bold text-sm shadow-md transition transform hover:-translate-y-1" style="background-color: var(--primary-color, #1e3a8a)">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Data Siswa
                </a>

                <!-- 4. E-Learning -->
                <a href="#elearning" class="flex items-center justify-center gap-3 p-4 rounded-xl text-white font-bold text-sm shadow-md transition transform hover:-translate-y-1" style="background-color: var(--primary-color, #1e3a8a)">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    E-Learning
                </a>

                <!-- 5. Buku Digital -->
                <a href="#bukudigital" class="flex items-center justify-center gap-3 p-4 rounded-xl text-white font-bold text-sm shadow-md transition transform hover:-translate-y-1 sm:col-span-2 lg:col-span-1" style="background-color: var(--primary-color, #1e3a8a)">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Buku Digital
                </a>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. SAPA KEPALA SEKOLAH (STATIS SESAUI FIGMA) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-16 mt-12 text-white" style="background-color: var(--primary-color, #1e3a8a)">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8 border-b border-blue-800/60 pb-4">
                <h3 class="text-xl md:text-2xl font-bold">
                    Sapa <span class="text-amber-400">Kepala Sekolah</span>
                </h3>
                <a href="#profile" class="px-4 py-1.5 rounded-full text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 transition flex items-center gap-1 shadow">
                    Lainnya &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <!-- Foto Kepala Sekolah -->
                <div class="md:col-span-4 flex justify-center">
                    <div class="relative w-64 h-80 rounded-2xl overflow-hidden shadow-2xl border-4 border-white/20">
                        <img src="/build/assets/kepala sekolah smada.png" alt="Kepala Sekolah SMAN 2 Situbondo" class="w-full h-full object-cover object-top">
                    </div>
                </div>

                <!-- Teks Sambutan Kepala Sekolah -->
                <div class="md:col-span-8 space-y-4">
                    <div>
                        <h4 class="text-2xl font-extrabold text-white">NIKMATIL HASANAH, S.Pd, M.Pd</h4>
                        <p class="text-amber-300 text-xs font-semibold tracking-wider">NIP: 19640516 200604 2 012</p>
                    </div>
                    <p class="text-slate-200 text-sm md:text-base leading-relaxed text-justify">
                        Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning. Berbagai informasi tentang pendidikan dapat diakses dalam website sekolah ini, khususnya Informasi tentang SMAN 2 SITUBONDO. Kami terus mengembangkan web ini mengikuti perkembangan teknologi yang sangat cepat. Dengan penuh harapan, kiranya website sekolah ini dapat memberikan manfaat yang maksimal dalam pengembangan dan pemanfaatannya untuk kebutuhan informasi dalam lingkungan sekolah SMAN 2 SITUBONDO and turut memajukan pendidikan di Indonesia. Wassalamu'alaikum Wr. Wb.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 6. BERITA SMADA (TOP 5 RINGKASAN DINAMIS DARI DATABASE) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-16 bg-white" id="berita">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8 border-b border-slate-200 pb-4">
                <h3 class="text-xl md:text-2xl font-extrabold text-slate-900">
                    Berita <span style="color: var(--primary-color, #1e3a8a)">Smada</span>
                </h3>
                <a href="#berita" class="text-xs font-bold text-slate-600 hover:text-blue-900 transition flex items-center gap-1">
                    Selengkapnya &rarr;
                </a>
            </div>

            @if(count($newsList) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Main Featured News (Berita 1 Terbaru) -->
                    @php $firstNews = $newsList->first(); @endphp
                    <div class="lg:col-span-5 bg-slate-50 rounded-2xl overflow-hidden shadow-md border border-slate-100 flex flex-col">
                        <img src="{{ $firstNews->thumbnail_url ?? '/build/assets/banner smada.png' }}" alt="{{ $firstNews->title }}" class="w-full h-56 object-cover">
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <span class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $firstNews->published_at ? $firstNews->published_at->format('F d, Y') : 'Terbaru' }}
                                </span>
                                <h4 class="font-extrabold text-slate-900 text-lg leading-snug hover:text-blue-800 transition">
                                    {{ $firstNews->title }}
                                </h4>
                                <p class="text-slate-600 text-xs line-clamp-3 leading-relaxed">
                                    {{ $firstNews->summary }}
                                </p>
                            </div>
                            <a href="#berita" class="inline-block px-4 py-1.5 rounded-full text-xs font-bold border border-slate-300 text-slate-700 hover:bg-slate-100 transition w-fit">
                                Selengkapnya
                            </a>
                        </div>
                    </div>

                    <!-- Grid 4 Side News (Berita 2 - 5 Terbaru) -->
                    <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($newsList->slice(1, 4) as $item)
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                                <img src="{{ $item->thumbnail_url ?? '/build/assets/banner smada.png' }}" alt="{{ $item->title }}" class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                <div class="space-y-1">
                                    <h5 class="font-bold text-slate-900 text-xs leading-snug line-clamp-2 hover:text-blue-800 transition">
                                        {{ $item->title }}
                                    </h5>
                                    <span class="text-[10px] text-slate-400 block">
                                        {{ $item->published_at ? $item->published_at->format('F d, Y') : 'Terbaru' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                    Belum ada berita yang dipublikasikan.
                </div>
            @endif
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 7. SMADA FACT (STATISTIK SISWA & GURU DINAMIS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-16 text-white" style="background-color: var(--primary-color, #1e3a8a)">
        <div class="max-w-7xl mx-auto px-4 text-center">

            <!-- Pill Badge Header -->
            <div class="inline-block bg-white text-slate-900 px-6 py-2 rounded-full font-black text-sm uppercase tracking-widest shadow mb-6">
                SMADA <span class="text-amber-500">FACT</span>
            </div>

            <!-- Tab Switcher -->
            <div class="flex justify-center gap-3 mb-10">
                <button @click="activeTab = 'siswa'" :class="activeTab === 'siswa' ? 'bg-amber-500 text-white' : 'bg-blue-900/60 text-slate-200 border border-blue-700'" class="px-5 py-2 rounded-lg font-extrabold text-xs uppercase tracking-wider transition shadow">
                    PESERTA DIDIK
                </button>
                <button @click="activeTab = 'guru'" :class="activeTab === 'guru' ? 'bg-amber-500 text-white' : 'bg-blue-900/60 text-slate-200 border border-blue-700'" class="px-5 py-2 rounded-lg font-extrabold text-xs uppercase tracking-wider transition shadow">
                    GURU
                </button>
                <button @click="activeTab = 'staf'" :class="activeTab === 'staf' ? 'bg-amber-500 text-white' : 'bg-blue-900/60 text-slate-200 border border-blue-700'" class="px-5 py-2 rounded-lg font-extrabold text-xs uppercase tracking-wider transition shadow">
                    STAFF
                </button>
            </div>

            <!-- Tab Content: PESERTA DIDIK -->
            <div x-show="activeTab === 'siswa'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
                <div class="bg-blue-950/70 rounded-2xl p-6 border border-blue-800/80 shadow-xl">
                    <div class="text-4xl font-extrabold text-amber-400 mb-1">{{ $studentStats['total'] }}</div>
                    <div class="text-xs uppercase font-bold text-slate-300">Total Peserta Didik</div>
                </div>
                <div class="bg-blue-950/70 rounded-2xl p-6 border border-blue-800/80 shadow-xl">
                    <div class="text-4xl font-extrabold text-white mb-1">{{ $studentStats['kelas_10'] }}</div>
                    <div class="text-xs uppercase font-bold text-slate-300">Siswa Kelas X</div>
                </div>
                <div class="bg-blue-950/70 rounded-2xl p-6 border border-blue-800/80 shadow-xl">
                    <div class="text-4xl font-extrabold text-white mb-1">{{ $studentStats['kelas_11'] }}</div>
                    <div class="text-xs uppercase font-bold text-slate-300">Siswa Kelas XI</div>
                </div>
                <div class="bg-blue-950/70 rounded-2xl p-6 border border-blue-800/80 shadow-xl">
                    <div class="text-4xl font-extrabold text-white mb-1">{{ $studentStats['kelas_12'] }}</div>
                    <div class="text-xs uppercase font-bold text-slate-300">Siswa Kelas XII</div>
                </div>
            </div>

            <!-- Tab Content: GURU -->
            <div x-show="activeTab === 'guru'" class="max-w-md mx-auto bg-blue-950/70 rounded-2xl p-8 border border-blue-800/80 shadow-xl">
                <div class="text-5xl font-extrabold text-amber-400 mb-2">{{ $employeeStats['guru'] }}</div>
                <div class="text-sm uppercase font-bold text-slate-200">Total Tenaga Pendidik (Guru)</div>
            </div>

            <!-- Tab Content: STAFF -->
            <div x-show="activeTab === 'staf'" class="max-w-md mx-auto bg-blue-950/70 rounded-2xl p-8 border border-blue-800/80 shadow-xl">
                <div class="text-5xl font-extrabold text-amber-400 mb-2">{{ $employeeStats['staf'] }}</div>
                <div class="text-sm uppercase font-bold text-slate-200">Total Tenaga Kependidikan (Staf)</div>
            </div>

        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 8. AGENDA & PENGUMUMAN + EKSTRAKURIKULER -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-16 bg-slate-50" id="pengumuman">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Left Column: Agenda & Pengumuman (Top 5 Published) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <h3 class="text-xl font-extrabold text-slate-900">
                            Agenda & <span style="color: var(--primary-color, #1e3a8a)">Pengumuman</span>
                        </h3>
                        <a href="#pengumuman" class="text-xs font-bold text-slate-600 hover:text-blue-900 transition">
                            Selengkapnya &rarr;
                        </a>
                    </div>

                    @if(count($announcementsList) > 0)
                        <div class="space-y-4">
                            @foreach($announcementsList as $ann)
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-md transition">
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-slate-900 text-sm hover:text-blue-800 transition">
                                            {{ $ann->title }}
                                        </h4>
                                        <span class="text-xs text-slate-400 block">
                                            📅 {{ $ann->published_at ? $ann->published_at->format('F d, Y') : 'Terbaru' }}
                                        </span>
                                        <p class="text-xs text-slate-600 line-clamp-2 mt-1">
                                            {{ $ann->summary }}
                                        </p>
                                    </div>
                                    <a href="#pengumuman" class="px-4 py-1.5 rounded-full text-xs font-semibold border border-slate-300 text-slate-700 hover:bg-slate-100 transition whitespace-nowrap">
                                        Selengkapnya
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-400 bg-white rounded-xl border border-dashed border-slate-200">
                            Belum ada pengumuman yang dipublikasikan.
                        </div>
                    @endif
                </div>

                <!-- Right Column: Ekstrakurikuler -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="border-b border-slate-200 pb-3">
                        <h3 class="text-xl font-extrabold text-slate-900">Ekstrakurikuler</h3>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <ul class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                            <li class="py-3 flex items-center justify-between">
                                <span>Musik</span>
                                <span class="text-xs text-amber-500 font-bold">Seni</span>
                            </li>
                            <li class="py-3 flex items-center justify-between">
                                <span>Kharismada</span>
                                <span class="text-xs text-amber-500 font-bold">Paskibra</span>
                            </li>
                            <li class="py-3 flex items-center justify-between">
                                <span>Jurnalistik</span>
                                <span class="text-xs text-amber-500 font-bold">Media</span>
                            </li>
                            <li class="py-3 flex items-center justify-between">
                                <span>Pecinta Alam</span>
                                <span class="text-xs text-amber-500 font-bold">Outdoor</span>
                            </li>
                        </ul>
                        <button class="w-full py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                            Ekstrakurikuler Lainnya
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 9. ATMOSFER SEKOLAH (INSTAGRAM FEED SLIDER REAL 10 POSTS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-16 bg-slate-950 text-white" id="media">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-black uppercase tracking-widest text-white">
                    ATMOSFER <span class="text-amber-400">SEKOLAH</span>
                </h3>
                <p class="text-xs text-slate-400 mt-1">@sman2situbondoofficial</p>
            </div>

            <!-- Horizontal Scroll Container -->
            <div class="flex gap-4 overflow-x-auto pb-6 scrollbar-thin scrollbar-thumb-amber-500 scrollbar-track-slate-900 snap-x">
                @if(!empty($instagramPosts) && count($instagramPosts) > 0)
                    @foreach($instagramPosts as $photoUrl)
                        <div class="flex-none w-64 h-64 rounded-xl overflow-hidden bg-slate-900 border border-slate-800 shadow-lg snap-center">
                            <img src="{{ $photoUrl }}" alt="Atmosfer SMAN 2 Situbondo" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                        </div>
                    @endforeach
                @else
                    <!-- Frame horizontal default untuk 10 foto instagram -->
                    @for($i = 1; $i <= 10; $i++)
                        <div class="flex-none w-64 h-64 rounded-xl overflow-hidden bg-slate-900 border border-slate-800 shadow-lg flex items-center justify-center snap-center">
                            <img src="/build/assets/banner smada.png" alt="Atmosfer Sekolah" class="w-full h-full object-cover opacity-70">
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 10. QUOTE BANNER SECTION -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-16 bg-white text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-lg italic text-slate-500 block mb-1">Dari</span>
            <h2 class="text-4xl md:text-5xl font-black text-amber-500 tracking-wide uppercase mb-1">
                SMADA PRIMA
            </h2>
            <span class="text-lg italic text-slate-500 block">Untuk Bangsa</span>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 11. FOOTER UTAMA (SESUAI FIGMA) -->
    <!-- ------------------------------------------------------------- -->
    <footer class="bg-slate-950 text-slate-300 pt-16 pb-8 border-t border-slate-900" style="background-color: var(--primary-color, #1e3a8a)">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 mb-12">
            
            <!-- Left Info Card & Back To Top Button -->
            <div class="md:col-span-4 space-y-4">
                <div class="bg-white/10 backdrop-blur p-6 rounded-2xl border border-white/10 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center font-bold text-xs">S2</div>
                        <h4 class="font-extrabold text-white text-sm">SMAN 2 SITUBONDO</h4>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        SMA Negeri 2 Situbondo berkomitmen menyelenggarakan pendidikan menengah berkualitas berwawasan lingkungan dan teknologi.
                    </p>
                </div>
                <a href="#" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-slate-900 text-white hover:bg-amber-500 transition shadow">
                    ^
                </a>
            </div>

            <!-- Informasi Tentang -->
            <div class="md:col-span-3 space-y-3">
                <h5 class="font-extrabold text-white text-sm tracking-wider uppercase border-b border-blue-800 pb-2">Informasi Tentang</h5>
                <ul class="space-y-2 text-xs text-slate-300">
                    <li><a href="#profil" class="hover:text-amber-400 transition">&bull; Visi Misi & Tujuan</a></li>
                    <li><a href="#profil" class="hover:text-amber-400 transition">&bull; Sejarah Singkat</a></li>
                    <li><a href="#profil" class="hover:text-amber-400 transition">&bull; Struktur Organisasi</a></li>
                    <li><a href="#civitas" class="hover:text-amber-400 transition">&bull; Data Pegawai</a></li>
                    <li><a href="#siswa" class="hover:text-amber-400 transition">&bull; Data Siswa</a></li>
                    <li><a href="#profil" class="hover:text-amber-400 transition">&bull; Sarana & Prasarana</a></li>
                </ul>
            </div>

            <!-- Link Lainnya -->
            <div class="md:col-span-2 space-y-3">
                <h5 class="font-extrabold text-white text-sm tracking-wider uppercase border-b border-blue-800 pb-2">Link Lainnya</h5>
                <ul class="space-y-2 text-xs text-slate-300">
                    <li><a href="#elearning" class="hover:text-amber-400 transition">&bull; Elearning</a></li>
                    <li><a href="#media" class="hover:text-amber-400 transition">&bull; Video Pembelajaran</a></li>
                    <li><a href="#bukudigital" class="hover:text-amber-400 transition">&bull; Buku Digital</a></li>
                    <li><a href="#literasi" class="hover:text-amber-400 transition">&bull; Literasi</a></li>
                    <li><a href="#spmb" class="hover:text-amber-400 transition">&bull; SPMB</a></li>
                </ul>
            </div>

            <!-- Kontak Kami -->
            <div class="md:col-span-3 space-y-3" id="contact">
                <h5 class="font-extrabold text-white text-sm tracking-wider uppercase border-b border-blue-800 pb-2">Kontak Kami</h5>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Jl. Anggrek No. 1 Patokan, Kab. Situbondo, Indonesia.
                </p>
                <p class="text-xs text-slate-300">
                    Telp. : (0338) 671618<br>
                    Email : smadasit@yahoo.com
                </p>
            </div>
        </div>

        <!-- Footer Bottom Bar (Social Media Icons - X Removed) -->
        <div class="max-w-7xl mx-auto px-4 border-t border-blue-900/80 pt-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-400">
            <p>Copyright &copy; 2026 SMA NEGERI 2 SITUBONDO</p>
            <div class="flex items-center gap-4">
                <!-- Facebook -->
                <a href="https://www.facebook.com/Sma.Negeri.2.Situbondo/" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-blue-900 flex items-center justify-center text-white hover:bg-amber-500 transition">
                    FB
                </a>
                <!-- Instagram -->
                <a href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-blue-900 flex items-center justify-center text-white hover:bg-amber-500 transition">
                    IG
                </a>
                <!-- YouTube -->
                <a href="https://www.youtube.com/c/SMADAPRIMA/videos" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-blue-900 flex items-center justify-center text-white hover:bg-amber-500 transition">
                    YT
                </a>
            </div>
        </div>
    </footer>

    <!-- ------------------------------------------------------------- -->
    <!-- 12. POP-UP EVENT MODAL (DARI DATABASE POPUPS TABEL) -->
    <!-- ------------------------------------------------------------- -->
    @if($activePopup)
        <div x-show="showPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-transition>
            <div class="bg-white rounded-2xl overflow-hidden max-w-md w-full shadow-2xl relative border border-slate-200">
                <button @click="showPopup = false" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-slate-900/80 text-white flex items-center justify-center text-sm font-bold z-10 hover:bg-red-600 transition">
                    &times;
                </button>
                @if($activePopup->image_url)
                    <img src="{{ $activePopup->image_url }}" alt="{{ $activePopup->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6 space-y-3">
                    <h4 class="font-extrabold text-slate-900 text-lg leading-tight">{{ $activePopup->title }}</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $activePopup->description }}</p>
                    <button @click="showPopup = false" class="w-full py-2.5 rounded-xl font-bold text-xs text-white uppercase tracking-wider transition shadow" style="background-color: var(--primary-color, #1e3a8a)">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
