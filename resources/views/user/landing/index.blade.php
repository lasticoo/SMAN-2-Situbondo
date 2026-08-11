@extends('layouts.app')

@section('content')
<div x-data="{ activeTab: 'siswa', showPopup: {{ $activePopup ? 'true' : 'false' }}, activeSlide: 0, totalSlides: {{ count($banners) > 0 ? count($banners) : 1 }} }" class="min-h-screen font-sans bg-slate-100 text-slate-800 antialiased">

    <!-- ------------------------------------------------------------- -->
    <!-- 1. TOPBAR ATAS (Sesuai Figma) -->
    <!-- ------------------------------------------------------------- -->
    <div class="bg-slate-900 text-slate-300 text-[11px] py-1.5 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1 cursor-pointer">
                    <span class="inline-block w-3.5 h-2.5 bg-red-600 border border-slate-700"></span>
                    <select class="bg-transparent border-none text-[11px] text-slate-300 focus:ring-0 cursor-pointer py-0">
                        <option class="bg-slate-800">Indonesian</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-5 text-slate-300">
                <span>smadasit@yahoo.com</span>
                <span>(0338) 671618</span>
                <a href="#alumni" class="hover:text-amber-400 transition">Alumni</a>
                <a href="#siklus" class="hover:text-amber-400 transition">SIKLUS</a>
                <a href="#mysmada" class="px-2.5 py-0.5 rounded text-[11px] font-bold text-white transition shadow-sm bg-amber-500 hover:bg-amber-600">MySmada</a>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 2. HEADER NAVBAR UTAMA -->
    <!-- ------------------------------------------------------------- -->
    <header class="bg-white sticky top-0 z-40 shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-white text-sm shadow" style="background-color: var(--primary-color, #0d2348)">
                    S2
                </div>
                <div>
                    <h1 class="font-extrabold text-base tracking-tight leading-none text-slate-900 uppercase">SMA NEGERI 2</h1>
                    <span class="text-[10px] font-bold tracking-widest uppercase text-slate-500">SITUBONDO</span>
                </div>
            </a>

            <!-- Menu Navigasi Sesuai Figma -->
            <nav class="hidden lg:flex items-center gap-5 font-bold text-xs tracking-wider text-slate-700">
                <a href="{{ route('home') }}" class="text-blue-900 border-b-2 border-blue-900 pb-0.5">BERANDA</a>
                <a href="#profil" class="hover:text-blue-900 transition">PROFIL ▾</a>
                <a href="#tentang" class="hover:text-blue-900 transition">TENTANG KAMI</a>
                <a href="#civitas" class="hover:text-blue-900 transition">CIVITAS AKADEMIK ▾</a>
                <a href="#pengumuman" class="hover:text-blue-900 transition">PENGUMUMAN</a>
                <a href="#media" class="hover:text-blue-900 transition">MEDIA ▾</a>
                <a href="#berita" class="hover:text-blue-900 transition">BERITA</a>
                <a href="#contact" class="hover:text-blue-900 transition">CONTACT</a>
                <a href="#spmb" class="px-4 py-1.5 rounded text-white font-extrabold text-xs tracking-wider uppercase transition shadow hover:opacity-90" style="background-color: var(--primary-color, #0d2348)">SPMB</a>
            </nav>
        </div>
    </header>

    <!-- ------------------------------------------------------------- -->
    <!-- 3. HERO BANNER SECTION (FIGMA DESIGN MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <section class="relative text-white overflow-hidden min-h-[500px] flex items-center" style="background-color: var(--primary-color, #0d2348)" x-init="if (totalSlides > 1) { setInterval(() => { activeSlide = (activeSlide + 1) % totalSlides }, 6000) }">
        
        @if(count($banners) > 0)
            @foreach($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0 w-full h-full">
                    <!-- Background Image Overlay -->
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover opacity-35">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#0d2348]/95 via-[#0d2348]/80 to-transparent"></div>
                    
                    <div class="relative max-w-7xl mx-auto px-6 h-full flex items-center pt-10 pb-28">
                        <div class="max-w-xl space-y-3">
                            <span class="text-xs font-semibold text-slate-300 block tracking-wide">
                                Selamat Hari Jadi SMA Negeri 2 Situbondo
                            </span>
                            <h2 class="text-4xl md:text-5xl font-black tracking-tight leading-tight text-white">
                                {{ $banner->title }}
                            </h2>
                            <p class="text-slate-300 text-xs md:text-sm leading-relaxed">
                                {{ $banner->description ?? 'Smada Prima — Assalamu\'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa...' }}
                            </p>
                            <div class="flex items-center gap-3 pt-3">
                                <a href="#spmb" class="px-6 py-2.5 rounded font-extrabold text-xs text-white uppercase tracking-wider shadow transition hover:opacity-90" style="background-color: var(--primary-color, #0d2348)">
                                    SPMB
                                </a>
                                <a href="#elearning" class="px-6 py-2.5 rounded font-extrabold text-xs text-white border border-white/60 bg-white/10 hover:bg-white/20 transition uppercase tracking-wider">
                                    E-LEARNING
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Fallback Banner Frame jika DB belum berisi banner -->
            <div class="relative w-full h-full py-16 px-6">
                <div class="absolute inset-0 bg-gradient-to-r from-[#0d2348] via-[#112952] to-[#0d2348] opacity-90"></div>
                <div class="relative max-w-7xl mx-auto px-4 pt-6 pb-24 text-left">
                    <span class="text-xs font-semibold text-slate-300 block mb-2">
                        Selamat Hari Jadi SMA Negeri 2 Situbondo
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black tracking-tight text-white mb-3">
                        SMA Negeri 2 Situbondo
                    </h2>
                    <p class="text-slate-300 text-xs md:text-sm max-w-lg mb-6 leading-relaxed">
                        Smada Prima — Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini...
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="#spmb" class="px-6 py-2.5 rounded font-extrabold text-xs text-white uppercase tracking-wider shadow" style="background-color: var(--primary-color, #0d2348)">SPMB</a>
                        <a href="#elearning" class="px-6 py-2.5 rounded font-extrabold text-xs text-white border border-white/60 bg-white/10">E-LEARNING</a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Banner Indicators -->
        @if(count($banners) > 1)
            <div class="absolute bottom-20 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                @foreach($banners as $index => $b)
                    <button @click="activeSlide = {{ $index }}" class="w-2.5 h-2.5 rounded-full transition" :class="activeSlide === {{ $index }} ? 'bg-amber-400 w-6' : 'bg-white/40'"></button>
                @endforeach
            </div>
        @endif
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 4. FLOATING CARD - PROFIL SEKOLAH (5 BUTTONS PERSIS FIGMA) -->
    <!-- ------------------------------------------------------------- -->
    <div class="relative max-w-4xl mx-auto px-4 -mt-20 z-30">
        <div class="bg-white rounded-xl p-6 md:p-8 shadow-xl border border-slate-200">
            <div class="text-center mb-6">
                <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 inline-block relative pb-1">
                    PROFIL <span style="color: var(--primary-color, #0d2348)">SEKOLAH</span>
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-10 h-0.5" style="background-color: var(--primary-color, #0d2348)"></span>
                </h3>
            </div>

            <!-- Grid 5 Button Figma (Row 1: 3 Buttons, Row 2: 2 Buttons Centered) -->
            <div class="space-y-4 max-w-3xl mx-auto">
                <!-- Row 1 (3 Buttons) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- 1. Visi Misi -->
                    <a href="#profil" class="flex items-center justify-center gap-2 p-3.5 rounded text-white font-bold text-xs shadow transition transform hover:-translate-y-0.5" style="background-color: var(--primary-color, #0d2348)">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Visi Misi
                    </a>

                    <!-- 2. Struktur Organisasi -->
                    <a href="#profil" class="flex items-center justify-center gap-2 p-3.5 rounded text-white font-bold text-xs shadow transition transform hover:-translate-y-0.5" style="background-color: var(--primary-color, #0d2348)">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Struktur Organisasi
                    </a>

                    <!-- 3. Data Siswa -->
                    <a href="#siswa" class="flex items-center justify-center gap-2 p-3.5 rounded text-white font-bold text-xs shadow transition transform hover:-translate-y-0.5" style="background-color: var(--primary-color, #0d2348)">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Data Siswa
                    </a>
                </div>

                <!-- Row 2 (2 Buttons Centered) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl mx-auto">
                    <!-- 4. E-Learning -->
                    <a href="#elearning" class="flex items-center justify-center gap-2 p-3.5 rounded text-white font-bold text-xs shadow transition transform hover:-translate-y-0.5" style="background-color: var(--primary-color, #0d2348)">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        E-Learning
                    </a>

                    <!-- 5. Buku Digital -->
                    <a href="#bukudigital" class="flex items-center justify-center gap-2 p-3.5 rounded text-white font-bold text-xs shadow transition transform hover:-translate-y-0.5" style="background-color: var(--primary-color, #0d2348)">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Buku Digital
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. SAPA KEPALA SEKOLAH (SESUAI FIGMA) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-14 mt-10 text-white" style="background-color: var(--primary-color, #0d2348)">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center mb-6 border-b border-blue-900 pb-3">
                <h3 class="text-lg md:text-xl font-bold">
                    Sapa <span class="text-amber-400">Kepala Sekolah</span>
                </h3>
                <a href="#profile" class="px-4 py-1 rounded-full text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 transition flex items-center gap-1 shadow">
                    Lainnya &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <!-- Foto Kepala Sekolah (Nikmatil Hasanah) -->
                <div class="md:col-span-4 flex justify-center">
                    <div class="w-56 h-72 rounded-xl overflow-hidden shadow-2xl border-2 border-white/20">
                        <img src="/build/assets/kepala sekolah smada.png" alt="Kepala Sekolah SMAN 2 Situbondo" class="w-full h-full object-cover object-top">
                    </div>
                </div>

                <!-- Text Sambutan -->
                <div class="md:col-span-8 space-y-3">
                    <div>
                        <h4 class="text-xl md:text-2xl font-black text-white">NIKMATIL HASANAH, S.Pd, M.Pd</h4>
                        <p class="text-amber-300 text-xs font-semibold">19640516 200604 2 012</p>
                    </div>
                    <p class="text-slate-200 text-xs md:text-sm leading-relaxed text-justify">
                        Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning. Berbagai informasi tentang pendidikan dapat diakses dalam website sekolah ini, khususnya Informasi tentang SMAN 2 SITUBONDO. Kami terus mengembangkan web ini mengikuti perkembangan teknologi yang sangat cepat. Dengan penuh harapan, kiranya website sekolah ini dapat memberikan manfaat yang maksimal dalam pengembangan dan pemanfaatannya untuk kebutuhan informasi dalam lingkungan sekolah SMAN 2 SITUBONDO and turut memajukan pendidikan di Indonesia. Wassalamu'alaikum Wr. Wb.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 6. BERITA SMADA (GRID LAYOUT FIGMA: LEFT MAIN 1 CARD + RIGHT 2X2 4 CARDS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-14 bg-white" id="berita">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 pb-3">
                <h3 class="text-lg md:text-xl font-black text-slate-900">
                    Berita <span style="color: var(--primary-color, #0d2348)">Smada</span>
                </h3>
                <a href="#berita" class="text-xs font-bold text-slate-500 hover:text-blue-900 transition">
                    Selengkapnya &rarr;
                </a>
            </div>

            @if(count($newsList) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left Featured Card (Berita Utama Terbaru) -->
                    @php $firstNews = $newsList->first(); @endphp
                    <div class="lg:col-span-5 bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <img src="{{ $firstNews->thumbnail_url ?? '/build/assets/banner smada.png' }}" alt="{{ $firstNews->title }}" class="w-full h-48 object-cover">
                            <div class="p-5 space-y-2">
                                <h4 class="font-extrabold text-slate-900 text-sm leading-snug uppercase">
                                    {{ $firstNews->title }}
                                </h4>
                                <span class="text-[11px] text-slate-400 block font-semibold">
                                    📅 {{ $firstNews->published_at ? $firstNews->published_at->format('F d, Y') : 'September 13, 2025' }}
                                </span>
                                <p class="text-slate-600 text-xs line-clamp-3 leading-relaxed">
                                    {{ $firstNews->summary }}
                                </p>
                            </div>
                        </div>
                        <div class="p-5 pt-0">
                            <a href="#berita" class="inline-block px-4 py-1.5 rounded-full text-xs font-bold border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                                Selengkapnya
                            </a>
                        </div>
                    </div>

                    <!-- Right Side (Grid 2x2: 4 News Cards) -->
                    <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($newsList->slice(1, 4) as $item)
                            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center gap-3 hover:shadow-md transition">
                                <img src="{{ $item->thumbnail_url ?? '/build/assets/banner smada.png' }}" alt="{{ $item->title }}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                <div class="space-y-1">
                                    <h5 class="font-bold text-slate-900 text-xs uppercase leading-snug line-clamp-2">
                                        {{ $item->title }}
                                    </h5>
                                    <span class="text-[10px] text-slate-400 block font-medium">
                                        📅 {{ $item->published_at ? $item->published_at->format('F d, Y') : 'September 13, 2025' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-10 text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                    Belum ada berita yang dipublikasikan.
                </div>
            @endif
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 7. SMADA FACT (3 STAT BOXES SESUAI FIGMA) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-14 text-white" style="background-color: var(--primary-color, #0d2348)">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <!-- Pill Badge SMADA FACT -->
            <div class="inline-block bg-white text-slate-900 px-6 py-1.5 rounded-full font-black text-xs uppercase tracking-widest shadow mb-5">
                SMADA <span class="text-amber-500">FACT</span>
            </div>

            <!-- Filter Buttons: PESERTA DIDIK (Active), GURU, STAFF -->
            <div class="flex justify-center gap-3 mb-8">
                <button @click="activeTab = 'siswa'" :class="activeTab === 'siswa' ? 'bg-amber-500 text-white' : 'bg-transparent text-slate-200 border border-blue-800'" class="px-5 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition">
                    PESERTA DIDIK
                </button>
                <button @click="activeTab = 'guru'" :class="activeTab === 'guru' ? 'bg-amber-500 text-white' : 'bg-transparent text-slate-200 border border-blue-800'" class="px-5 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition">
                    GURU
                </button>
                <button @click="activeTab = 'staf'" :class="activeTab === 'staf' ? 'bg-amber-500 text-white' : 'bg-transparent text-slate-200 border border-blue-800'" class="px-5 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition">
                    STAFF
                </button>
            </div>

            <!-- 3 Stat Cards in Row Sesuai Figma -->
            <div x-show="activeTab === 'siswa'" class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="bg-[#081833] rounded-xl p-6 border border-amber-500/40 shadow-lg">
                    <div class="text-3xl md:text-4xl font-black text-amber-400 mb-1">{{ $studentStats['total'] }}</div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-300">Total Peserta Didik</div>
                </div>
                <div class="bg-[#081833] rounded-xl p-6 border border-blue-900 shadow-lg">
                    <div class="text-3xl md:text-4xl font-black text-amber-400 mb-1">{{ $studentStats['kelas_10'] }}</div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-300">Siswa Kelas X</div>
                </div>
                <div class="bg-[#081833] rounded-xl p-6 border border-blue-900 shadow-lg">
                    <div class="text-3xl md:text-4xl font-black text-amber-400 mb-1">{{ $studentStats['kelas_11'] + $studentStats['kelas_12'] }}</div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-300">Siswa Kelas XI & XII</div>
                </div>
            </div>

            <div x-show="activeTab === 'guru'" class="max-w-md mx-auto bg-[#081833] rounded-xl p-8 border border-amber-500/40 shadow-lg">
                <div class="text-4xl font-black text-amber-400 mb-2">{{ $employeeStats['guru'] }}</div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-300">Guru</div>
            </div>

            <div x-show="activeTab === 'staf'" class="max-w-md mx-auto bg-[#081833] rounded-xl p-8 border border-amber-500/40 shadow-lg">
                <div class="text-4xl font-black text-amber-400 mb-2">{{ $employeeStats['staf'] }}</div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-300">Staff</div>
            </div>

        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 8. AGENDA & PENGUMUMAN + EKSTRAKURIKULER (SESUAI FIGMA) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-14 bg-white" id="pengumuman">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Left: Agenda & Pengumuman -->
                <div class="lg:col-span-8 space-y-5">
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <h3 class="text-lg font-black text-slate-900">
                            Agenda & <span style="color: var(--primary-color, #0d2348)">Pengumuman</span>
                        </h3>
                        <a href="#pengumuman" class="text-xs font-bold text-slate-500 hover:text-blue-900 transition">
                            Selengkapnya &rarr;
                        </a>
                    </div>

                    @if(count($announcementsList) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Main Featured Announcement Card -->
                            @php $firstAnn = $announcementsList->first(); @endphp
                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-sm space-y-3 flex flex-col justify-between">
                                <div class="space-y-2">
                                    <h4 class="font-extrabold text-slate-900 text-sm uppercase leading-snug">
                                        {{ $firstAnn->title }}
                                    </h4>
                                    <span class="text-[10px] text-slate-400 block font-semibold">
                                        📅 {{ $firstAnn->published_at ? $firstAnn->published_at->format('F d, Y') : 'July 16, 2022' }}
                                    </span>
                                    <p class="text-xs text-slate-600 line-clamp-3">
                                        {{ $firstAnn->summary }}
                                    </p>
                                </div>
                                <a href="#pengumuman" class="inline-block px-4 py-1.5 rounded-full text-xs font-bold border border-slate-300 text-slate-700 hover:bg-white transition w-fit">
                                    Selengkapnya
                                </a>
                            </div>

                            <!-- Side Announcement Cards -->
                            <div class="space-y-4">
                                @foreach($announcementsList->slice(1, 2) as $annItem)
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                                        <h5 class="font-bold text-slate-900 text-xs uppercase leading-snug">
                                            {{ $annItem->title }}
                                        </h5>
                                        <span class="text-[10px] text-slate-400 block font-semibold">
                                            📅 {{ $annItem->published_at ? $annItem->published_at->format('F d, Y') : 'May 05, 2022' }}
                                        </span>
                                        <p class="text-[11px] text-slate-600 line-clamp-2">
                                            {{ $annItem->summary }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            Belum ada pengumuman yang dipublikasikan.
                        </div>
                    @endif
                </div>

                <!-- Right: Ekstrakurikuler -->
                <div class="lg:col-span-4 space-y-5">
                    <div class="border-b border-slate-200 pb-3">
                        <h3 class="text-lg font-black text-slate-900">Ekstrakurikuler</h3>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-3">
                        <ul class="divide-y divide-slate-100 text-xs font-bold text-slate-800">
                            <li class="py-2.5">Musik</li>
                            <li class="py-2.5">Kharismada</li>
                            <li class="py-2.5">Jurnalistik</li>
                            <li class="py-2.5">Pecinta Alam</li>
                        </ul>
                        <button class="w-full py-2 rounded text-xs font-bold border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                            Ekstrakurikuler Lainnya
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 9. ATMOSFER SEKOLAH (3 FRAMED PHOTOS SESUAI FIGMA) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-14 bg-slate-950 text-white" id="media">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-8">
                <h3 class="text-xl font-black uppercase tracking-widest text-white">
                    ATMOSFER <span class="text-amber-400">SEKOLAH</span>
                </h3>
            </div>

            <!-- 3 Framed Photos Display (Center Highlighted with Border) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center max-w-5xl mx-auto">
                <div class="h-64 rounded-xl overflow-hidden border border-slate-800 shadow-lg">
                    <img src="/build/assets/banner smada.png" alt="Atmosfer Kegiatan Sekolah 1" class="w-full h-full object-cover">
                </div>
                <div class="h-72 rounded-xl overflow-hidden border-2 border-amber-500 shadow-2xl transform scale-105">
                    <img src="/build/assets/banner smada.png" alt="Atmosfer Poster Prestasi 2" class="w-full h-full object-cover">
                </div>
                <div class="h-64 rounded-xl overflow-hidden border border-slate-800 shadow-lg">
                    <img src="/build/assets/kepala sekolah smada.png" alt="Atmosfer Rapat Guru 3" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 10. QUOTE BANNER SECTION -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-14 bg-white text-center border-t border-b border-slate-100">
        <div class="max-w-3xl mx-auto px-4">
            <span class="text-sm italic font-serif text-slate-500 block mb-1">Dari</span>
            <h2 class="text-3xl md:text-4xl font-black text-amber-500 tracking-wide uppercase mb-1">
                SMADA PRIMA
            </h2>
            <span class="text-sm italic font-serif text-slate-500 block">Untuk Bangsa</span>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 11. FOOTER UTAMA (SESUAI FIGMA WITH WHATSAPP & ACCESSIBILITY BUTTONS) -->
    <!-- ------------------------------------------------------------- -->
    <footer class="relative text-slate-300 pt-12 pb-6" style="background-color: var(--primary-color, #0d2348)">
        
        <!-- Floating Right Action Icons (Accessibility & WhatsApp Sesuai Figma) -->
        <div class="fixed bottom-6 right-6 z-40 flex flex-col gap-2">
            <a href="#" class="w-10 h-10 rounded-full bg-amber-500 text-slate-900 flex items-center justify-center font-bold shadow-lg hover:scale-110 transition">
                ♿
            </a>
            <a href="https://wa.me/628123456789" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shadow-lg hover:scale-110 transition">
                💬
            </a>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-12 gap-8 mb-10">
            
            <!-- Left Box Card & Back To Top -->
            <div class="md:col-span-4 space-y-4">
                <div class="bg-white rounded-lg p-6 shadow-md text-slate-900 border border-slate-200 min-h-[140px]">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 rounded bg-blue-900 text-white flex items-center justify-center font-bold text-xs">S2</div>
                        <span class="font-extrabold text-xs text-blue-950">SMAN 2 SITUBONDO</span>
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Jl. Anggrek No. 1 Patokan, Kab. Situbondo, Jawa Timur, Indonesia.
                    </p>
                </div>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded bg-slate-900 text-white text-xs font-bold hover:bg-amber-500 transition shadow">
                    ▲
                </a>
            </div>

            <!-- Informasi Tentang -->
            <div class="md:col-span-3 space-y-2">
                <h5 class="font-extrabold text-white text-xs tracking-wider uppercase border-b border-blue-900 pb-2">Informasi Tentang</h5>
                <ul class="space-y-1.5 text-xs text-slate-300">
                    <li><a href="#profil" class="hover:text-amber-400 transition">&bull; Visi Misi & Tujuan</a></li>
                    <li><a href="#profil" class="hover:text-amber-400 transition">&bull; Sejarah Singkat</a></li>
                    <li><a href="#profil" class="hover:text-amber-400 transition">&bull; Struktur Organisasi</a></li>
                    <li><a href="#civitas" class="hover:text-amber-400 transition">&bull; Data Pegawai</a></li>
                    <li><a href="#siswa" class="hover:text-amber-400 transition">&bull; Data Siswa</a></li>
                    <li><a href="#profil" class="hover:text-amber-400 transition">&bull; Sarana & Prasarana</a></li>
                </ul>
            </div>

            <!-- Link Lainnya -->
            <div class="md:col-span-2 space-y-2">
                <h5 class="font-extrabold text-white text-xs tracking-wider uppercase border-b border-blue-900 pb-2">Link Lainnya</h5>
                <ul class="space-y-1.5 text-xs text-slate-300">
                    <li><a href="#elearning" class="hover:text-amber-400 transition">&bull; Elearning</a></li>
                    <li><a href="#media" class="hover:text-amber-400 transition">&bull; Video Pembelajaran</a></li>
                    <li><a href="#bukudigital" class="hover:text-amber-400 transition">&bull; Buku Digital</a></li>
                    <li><a href="#literasi" class="hover:text-amber-400 transition">&bull; Literasi</a></li>
                    <li><a href="#spmb" class="hover:text-amber-400 transition">&bull; SPMB</a></li>
                </ul>
            </div>

            <!-- Kontak Kami -->
            <div class="md:col-span-3 space-y-2" id="contact">
                <h5 class="font-extrabold text-white text-xs tracking-wider uppercase border-b border-blue-900 pb-2">Kontak Kami</h5>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Jl. Anggrek No. 1 Patokan, Kab. Situbondo, Indonesia.
                </p>
                <p class="text-xs text-slate-300">
                    Telp. : (0338) 671618<br>
                    Email : smadasit@yahoo.com
                </p>
            </div>
        </div>

        <!-- Footer Bottom Bar (Social Media Icons - X Button Removed) -->
        <div class="max-w-7xl mx-auto px-6 border-t border-blue-900/60 pt-4 flex flex-col md:flex-row justify-between items-center gap-3 text-[11px] text-slate-400">
            <p>Copyright &copy; 2026 SMA NEGERI 2 SITUBONDO</p>
            <div class="flex items-center gap-3">
                <!-- Facebook -->
                <a href="https://www.facebook.com/Sma.Negeri.2.Situbondo/" target="_blank" rel="noopener" class="w-7 h-7 rounded-full bg-blue-900 flex items-center justify-center text-white hover:bg-amber-500 transition text-xs">
                    fb
                </a>
                <!-- Instagram -->
                <a href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener" class="w-7 h-7 rounded-full bg-blue-900 flex items-center justify-center text-white hover:bg-amber-500 transition text-xs">
                    ig
                </a>
                <!-- YouTube -->
                <a href="https://www.youtube.com/c/SMADAPRIMA/videos" target="_blank" rel="noopener" class="w-7 h-7 rounded-full bg-blue-900 flex items-center justify-center text-white hover:bg-amber-500 transition text-xs">
                    yt
                </a>
            </div>
        </div>
    </footer>

    <!-- ------------------------------------------------------------- -->
    <!-- 12. POP-UP EVENT MODAL -->
    <!-- ------------------------------------------------------------- -->
    @if($activePopup)
        <div x-show="showPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-transition>
            <div class="bg-white rounded-xl overflow-hidden max-w-md w-full shadow-2xl relative border border-slate-200">
                <button @click="showPopup = false" class="absolute top-3 right-3 w-7 h-7 rounded-full bg-slate-900/80 text-white flex items-center justify-center text-xs font-bold z-10 hover:bg-red-600 transition">
                    &times;
                </button>
                @if($activePopup->image_url)
                    <img src="{{ $activePopup->image_url }}" alt="{{ $activePopup->title }}" class="w-full h-44 object-cover">
                @endif
                <div class="p-5 space-y-2">
                    <h4 class="font-extrabold text-slate-900 text-sm leading-tight uppercase">{{ $activePopup->title }}</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $activePopup->description }}</p>
                    <button @click="showPopup = false" class="w-full py-2 rounded font-extrabold text-xs text-white uppercase tracking-wider shadow transition" style="background-color: var(--primary-color, #0d2348)">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
