@extends('layouts.app')

@section('content')
<!-- Include Font Awesome 6 for icons matching exact HTML template -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div x-data="{ activeTab: 'siswa', showPopup: {{ $activePopup ? 'true' : 'false' }}, activeSlide: 0, totalSlides: {{ count($banners) > 0 ? count($banners) : 1 }} }" class="min-h-screen font-sans antialiased text-gray-800 bg-gray-50">

    <!-- ------------------------------------------------------------- -->
    <!-- 1. TOP BAR (EXACT HTML TEMPLATE MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <div class="bg-gray-100 py-1 text-xs border-b border-gray-200">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-4 h-3 bg-red-600 border border-slate-300"></span>
                <span class="font-medium">Indonesian <i class="fas fa-chevron-down ml-1 text-[10px]"></i></span>
            </div>
            <div class="flex space-x-4 items-center font-medium">
                <a class="hover:text-blue-900 transition" href="mailto:smadasit@yahoo.com">smadasit@yahoo.com</a>
                <a class="hover:text-blue-900 transition" href="tel:0338671618">(0338) 671618</a>
                <a class="hover:text-blue-900 transition" href="#alumni">Alumni</a>
                <a class="hover:text-blue-900 transition" href="#siklus">SIKLUS</a>
                <a class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-3 py-1 font-bold rounded-lg shadow-sm transition" href="#mysmada">MySmada</a>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------- -->
    <!-- 2. MAIN HEADER (EXACT HTML TEMPLATE MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <header class="bg-white py-4 shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <!-- Logo -->
            <a class="flex items-center gap-3" href="{{ route('home') }}">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-white text-sm shadow" style="background-color: var(--primary-color, #001c4d)">
                    S2
                </div>
                <div>
                    <h1 class="font-extrabold text-base tracking-tight leading-none text-slate-900 uppercase">SMA NEGERI 2</h1>
                    <span class="text-[10px] font-bold tracking-widest uppercase text-slate-500">SITUBONDO</span>
                </div>
            </a>

            <!-- Navigation Menu -->
            <nav class="hidden md:flex space-x-6 text-sm font-semibold text-gray-700">
                <a class="text-blue-900 border-b-2 border-blue-900 pb-0.5" href="{{ route('home') }}">BERANDA</a>
                
                <div class="relative group">
                    <button class="hover:text-blue-900 flex items-center uppercase py-1">PROFIL <i class="fas fa-chevron-down ml-1 text-[10px]"></i></button>
                    <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-900" href="#profil">Visi, Misi &amp; Tujuan</a>
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-900" href="#profil">Sejarah Singkat</a>
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-900" href="#profil">Struktur Organisasi</a>
                    </div>
                </div>

                <a class="hover:text-blue-900 flex items-center" href="#tentang">TENTANG KAMI</a>

                <div class="relative group">
                    <button class="hover:text-blue-900 flex items-center uppercase py-1">CIVITAS AKADEMIK <i class="fas fa-chevron-down ml-1 text-[10px]"></i></button>
                    <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-900" href="#civitas">Data Pegawai</a>
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-900" href="#siswa">Data Siswa</a>
                    </div>
                </div>

                <a class="hover:text-blue-900 flex items-center" href="#pengumuman">PENGUMUMAN</a>

                <div class="relative group">
                    <button class="hover:text-blue-900 flex items-center uppercase py-1">MEDIA <i class="fas fa-chevron-down ml-1 text-[10px]"></i></button>
                    <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-900" href="#media">Galeri</a>
                        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-900" href="#media">Video</a>
                    </div>
                </div>

                <a class="hover:text-blue-900 flex items-center" href="#berita">BERITA</a>
                <a class="hover:text-blue-900 flex items-center" href="#contact">CONTACT</a>
                <a class="text-blue-900 font-extrabold flex items-center" href="#spmb">SPMB</a>
            </nav>

            <button class="md:hidden text-gray-700">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </header>

    <!-- ------------------------------------------------------------- -->
    <!-- 3. HERO SECTION (DYNAMIC CAROUSEL LOOP FROM BANNERS DB) -->
    <!-- ------------------------------------------------------------- -->
    <section class="relative h-[550px] md:h-[600px] flex items-center overflow-hidden" style="background-color: var(--primary-color, #001c4d)" x-init="if (totalSlides > 1) { setInterval(() => { activeSlide = (activeSlide + 1) % totalSlides }, 6000) }">
        @if(count($banners) > 0)
            @foreach($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0 w-full h-full flex items-center">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover opacity-40">
                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                    <div class="container mx-auto px-4 relative z-10 text-white">
                        <div class="max-w-2xl space-y-4">
                            <h1 class="text-4xl md:text-5xl font-bold leading-tight font-display-lg drop-shadow-md">
                                {{ $banner->title }}
                            </h1>
                            <p class="text-shadow text-sm md:text-base leading-relaxed text-slate-200">
                                <strong>Smada Prima</strong><br>
                                {{ $banner->description ?? 'Assalamu\'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning.' }}
                            </p>
                            <div class="flex space-x-4 pt-2">
                                <a class="px-6 py-2.5 rounded-full font-bold text-sm text-white transition duration-300 shadow-lg" style="background-color: var(--primary-color, #163269)" href="#spmb">SPMB</a>
                                <a class="border-2 border-white hover:bg-white hover:text-black text-white font-bold py-2 px-6 rounded-full transition duration-300 text-sm" href="#elearning">E-LEARNING</a>
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
                        <h1 class="text-4xl md:text-5xl font-bold leading-tight font-display-lg">SMA Negeri 2<br>Situbondo</h1>
                        <p class="text-shadow text-sm md:text-base leading-relaxed text-slate-200">
                            <strong>Smada Prima</strong><br>
                            Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning. Berbagai informasi tentang pendidikan dapat diakses dalam website sekolah ini, Khususnya Informasi tentang SMAN 2 SITUBONDO.
                        </p>
                        <div class="flex space-x-4 pt-2">
                            <a class="px-6 py-2.5 rounded-full font-bold text-sm text-white transition duration-300 shadow-lg" style="background-color: var(--primary-color, #163269)" href="#spmb">SPMB</a>
                            <a class="border-2 border-white hover:bg-white hover:text-black text-white font-bold py-2 px-6 rounded-full transition duration-300 text-sm" href="#elearning">E-LEARNING</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(count($banners) > 1)
            <div class="absolute bottom-20 left-1/2 -translate-x-1/2 z-20 flex space-x-2">
                @foreach($banners as $index => $b)
                    <button @click="activeSlide = {{ $index }}" class="w-3 h-3 rounded-full transition" :class="activeSlide === {{ $index }} ? 'bg-amber-400 w-8' : 'bg-white/50'"></button>
                @endforeach
            </div>
        @endif
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 4. QUICK LINKS GRID (PROFIL SEKOLAH 5 BUTTONS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-10 bg-white relative -mt-16 z-20 mx-4 md:mx-auto md:max-w-4xl rounded-xl shadow-lg border-t-4" style="border-top-color: var(--primary-color, #163269)">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold text-gray-800 font-headline-md uppercase tracking-wider">PROFIL <span style="color: var(--primary-color, #163269)">SEKOLAH</span></h2>
            <div class="w-16 h-1 mx-auto mt-2 rounded-full" style="background-color: var(--primary-color, #163269)"></div>
        </div>

        <!-- Row 1: 3 Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-8 pb-4">
            <a class="text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-xl transition transform hover:-translate-y-1 shadow-md" style="background: linear-gradient(135deg, var(--primary-color, #001c4d), #163269)" href="#profil">
                <i class="fas fa-eye text-3xl mb-3 text-amber-400"></i>
                <span class="font-semibold text-sm">Visi Misi</span>
            </a>
            <a class="text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-xl transition transform hover:-translate-y-1 shadow-md" style="background: linear-gradient(135deg, var(--primary-color, #001c4d), #163269)" href="#profil">
                <i class="fas fa-sitemap text-3xl mb-3 text-amber-400"></i>
                <span class="font-semibold text-sm">Struktur Organisasi</span>
            </a>
            <a class="text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-xl transition transform hover:-translate-y-1 shadow-md" style="background: linear-gradient(135deg, var(--primary-color, #001c4d), #163269)" href="#siswa">
                <i class="fas fa-users text-3xl mb-3 text-amber-400"></i>
                <span class="font-semibold text-sm">Data Siswa</span>
            </a>
        </div>

        <!-- Row 2: 2 Buttons Centered -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-8 pb-4 max-w-xl mx-auto">
            <a class="text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-xl transition transform hover:-translate-y-1 shadow-md" style="background: linear-gradient(135deg, var(--primary-color, #001c4d), #163269)" href="#elearning">
                <i class="fas fa-laptop text-3xl mb-3 text-amber-400"></i>
                <span class="font-semibold text-sm">E-Learning</span>
            </a>
            <a class="text-white rounded-lg p-5 flex flex-col items-center justify-center hover:shadow-xl transition transform hover:-translate-y-1 shadow-md" style="background: linear-gradient(135deg, var(--primary-color, #001c4d), #163269)" href="#bukudigital">
                <i class="fas fa-book text-3xl mb-3 text-amber-400"></i>
                <span class="font-semibold text-sm">Buku Digital</span>
            </a>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 5. NEWS SECTION (BERITA SMADA TOP 5 DINAMIS DATABASE) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-12 bg-slate-100" id="berita">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-8 border-b-2 pb-2" style="border-bottom-color: var(--primary-color, #163269)">
                <h2 class="text-2xl font-bold text-gray-800 font-headline-md">Berita Smada</h2>
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
                                <h3 class="font-bold text-lg leading-snug hover:text-blue-900 font-headline-md text-slate-900 uppercase">
                                    <a href="#berita">{{ $firstNews->title }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 flex items-center gap-1 font-medium">
                                    <i class="far fa-calendar-alt text-amber-500"></i> {{ $firstNews->published_at ? $firstNews->published_at->format('F d, Y') : 'September 10, 2025' }}
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
                                    <h4 class="font-semibold text-xs leading-tight hover:text-blue-900 line-clamp-2 text-slate-900 uppercase">
                                        <a href="#berita">{{ $item->title }}</a>
                                    </h4>
                                    <p class="text-[11px] text-gray-400 flex items-center gap-1 font-medium">
                                        <i class="far fa-calendar-alt text-amber-500 text-[10px]"></i> {{ $item->published_at ? $item->published_at->format('F d, Y') : 'August 28, 2025' }}
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
    <!-- 6. SAPA KEPALA SEKOLAH (EXACT HTML TEMPLATE MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-12 text-white" style="background-color: var(--primary-color, #163269)">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8 border-b border-blue-500 pb-2">
                <h2 class="text-2xl font-bold font-headline-md">Sapa <span class="text-amber-400">Kepala Sekolah</span></h2>
                <a class="bg-amber-500 text-slate-950 font-bold py-1 px-4 rounded-full text-sm hover:bg-amber-600 transition shadow" href="#profil">
                    Lainnya <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <div class="md:col-span-1 flex justify-center">
                    <img alt="NIKMATIL HASANAH, S.Pd, M.Pd" class="w-64 h-auto object-cover rounded-lg shadow-2xl border-2 border-white/20" src="/build/assets/kepala sekolah smada.png">
                </div>
                <div class="md:col-span-2 space-y-3">
                    <h3 class="font-bold text-2xl leading-tight font-headline-md text-white">NIKMATIL HASANAH, S.Pd, M.Pd</h3>
                    <p class="text-sm text-blue-200 font-bold">19640516 200604 2 012</p>
                    <p class="text-base text-blue-100 leading-relaxed text-justify">
                        Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa, atas diperkenankannya pembuatan website ini, kami telah dapat mengembangkan website sekolah yang mengacu pada ICT-based learning. Berbagai informasi tentang pendidikan dapat diakses dalam website sekolah ini, Khususnya Informasi tentang SMAN 2 SITUBONDO. Kami terus mengembangkan web ini mengikuti perkembangan teknologi yang sangat cepat. Dengan penuh harapan, kiranya website sekolah ini dapat memberikan manfaat yang maksimal dalam pengembangan dan pemanfaatannya untuk kebutuhan informasi dalam lingkungan sekolah SMAN 2 SITUBONDO and turut memajukan pendidikan di Indonesia. Wassalamu'alaikum Wr. Wb.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 7. SMADA FACT SECTION (EXACT HTML MATCH + INTERACTIVE TABS) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-12 text-white relative bg-cover bg-center" style="background-color: var(--primary-color, #001c4d)">
        <div class="absolute inset-0 bg-opacity-70" style="background-color: var(--primary-color, #163269)"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            
            <div class="inline-block bg-white text-slate-900 font-bold py-2 px-12 rounded-full mb-8 text-xl shadow">
                SMADA <span class="text-amber-500">FACT</span>
            </div>

            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <button @click="activeTab = 'siswa'" :class="activeTab === 'siswa' ? 'bg-amber-500 text-slate-950' : 'bg-transparent text-white border-white'" class="px-6 py-2 rounded-full text-sm font-semibold border transition">
                    PESERTA DIDIK
                </button>
                <button @click="activeTab = 'guru'" :class="activeTab === 'guru' ? 'bg-amber-500 text-slate-950' : 'bg-transparent text-white border-white'" class="px-6 py-2 rounded-full text-sm font-semibold border transition">
                    GURU
                </button>
                <button @click="activeTab = 'staf'" :class="activeTab === 'staf' ? 'bg-amber-500 text-slate-950' : 'bg-transparent text-white border-white'" class="px-6 py-2 rounded-full text-sm font-semibold border transition">
                    STAFF
                </button>
            </div>

            <!-- Tab Content: PESERTA DIDIK -->
            <div x-show="activeTab === 'siswa'" class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="bg-black bg-opacity-50 border border-amber-500 rounded-lg p-6 shadow-xl">
                    <div class="text-4xl font-bold text-amber-400 mb-2 font-display-lg">{{ $studentStats['total'] }}</div>
                    <div class="text-sm font-medium uppercase tracking-wider text-slate-200">Total Peserta Didik</div>
                </div>
                <div class="bg-black bg-opacity-50 border border-amber-500 rounded-lg p-6 shadow-xl">
                    <div class="text-4xl font-bold text-amber-400 mb-2 font-display-lg">{{ $studentStats['kelas_10'] }}</div>
                    <div class="text-sm font-medium uppercase tracking-wider text-slate-200">Siswa Kelas X</div>
                </div>
                <div class="bg-black bg-opacity-50 border border-amber-500 rounded-lg p-6 shadow-xl">
                    <div class="text-4xl font-bold text-amber-400 mb-2 font-display-lg">{{ $studentStats['kelas_11'] + $studentStats['kelas_12'] }}</div>
                    <div class="text-sm font-medium uppercase tracking-wider text-slate-200">Siswa Kelas XI &amp; XII</div>
                </div>
            </div>

            <!-- Tab Content: GURU -->
            <div x-show="activeTab === 'guru'" class="max-w-md mx-auto bg-black bg-opacity-50 border border-amber-500 rounded-lg p-8 shadow-xl">
                <div class="text-5xl font-bold text-amber-400 mb-2 font-display-lg">{{ $employeeStats['guru'] }}</div>
                <div class="text-sm font-medium uppercase tracking-wider text-slate-200">Guru</div>
            </div>

            <!-- Tab Content: STAFF -->
            <div x-show="activeTab === 'staf'" class="max-w-md mx-auto bg-black bg-opacity-50 border border-amber-500 rounded-lg p-8 shadow-xl">
                <div class="text-5xl font-bold text-amber-400 mb-2 font-display-lg">{{ $employeeStats['staf'] }}</div>
                <div class="text-sm font-medium uppercase tracking-wider text-slate-200">Staff</div>
            </div>

        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 8. AGENDA & PENGUMUMAN + EKSTRAKURIKULER (EXACT HTML MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-12 bg-white" id="pengumuman">
        <div class="container mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Agenda & Pengumuman Left -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-end border-b-2 pb-2" style="border-bottom-color: var(--primary-color, #163269)">
                    <h2 class="text-2xl font-bold text-gray-800 font-headline-md">Agenda &amp; Pengumuman</h2>
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
                                <h3 class="font-bold mb-1 text-slate-900 text-sm uppercase leading-snug">{{ $firstAnn->title }}</h3>
                                <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                    <i class="far fa-calendar-alt text-amber-500"></i> {{ $firstAnn->published_at ? $firstAnn->published_at->format('F d, Y') : 'July 16, 2022' }}
                                </p>
                                <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed mb-4">
                                    {{ $firstAnn->summary }}
                                </p>
                            </div>
                            <div>
                                <a class="inline-block border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white px-4 py-1 rounded-full text-xs transition" href="#pengumuman">Selengkapnya</a>
                            </div>
                        </div>

                        <!-- Side Announcement Cards -->
                        <div class="space-y-4">
                            @foreach($announcementsList->slice(1, 2) as $annItem)
                                <div class="bg-gray-50 p-4 rounded-lg shadow border border-gray-100 space-y-1">
                                    <h3 class="font-bold mb-1 text-xs text-slate-900 uppercase leading-snug">{{ $annItem->title }}</h3>
                                    <p class="text-[11px] text-gray-400 mb-2 flex items-center gap-1">
                                        <i class="far fa-calendar-alt text-amber-500"></i> {{ $annItem->published_at ? $annItem->published_at->format('F d, Y') : 'May 05, 2022' }}
                                    </p>
                                    <p class="text-xs text-gray-600 line-clamp-2 mb-2 leading-relaxed">
                                        {{ $annItem->summary }}
                                    </p>
                                    <a class="inline-block border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white px-3 py-1 rounded-full text-[11px] transition" href="#pengumuman">Selengkapnya</a>
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
                <div class="mb-6 border-b-2 pb-2" style="border-bottom-color: var(--primary-color, #163269)">
                    <h2 class="text-2xl font-bold text-gray-800 font-headline-md">Ekstrakurikuler</h2>
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
    <!-- 9. ATMOSFER SEKOLAH (EXACT HTML MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-16 bg-gray-900 text-white relative overflow-hidden" id="media">
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold tracking-widest font-headline-lg uppercase">ATMOSFER <span class="text-amber-400">SEKOLAH</span></h2>
            </div>
            <div class="flex justify-center items-center space-x-4 overflow-x-auto pb-8">
                <div class="w-64 h-80 flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700">
                    <img alt="Atmosfer 1" class="w-full h-full object-cover hover:scale-105 transition duration-500" src="/build/assets/banner smada.png">
                </div>
                <div class="w-80 h-96 flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-2xl relative z-10 border-4 border-white">
                    <img alt="Atmosfer 2 Poster" class="w-full h-full object-cover hover:scale-105 transition duration-500" src="/build/assets/banner smada.png">
                </div>
                <div class="w-64 h-80 flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700">
                    <img alt="Atmosfer 3" class="w-full h-full object-cover hover:scale-105 transition duration-500" src="/build/assets/kepala sekolah smada.png">
                </div>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 10. MOTTO BANNER SECTION (EXACT HTML MATCH) -->
    <!-- ------------------------------------------------------------- -->
    <section class="py-8 bg-white border-b-4" style="border-bottom-color: var(--primary-color, #163269)">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-serif italic font-headline-lg" style="color: var(--primary-color, #163269)">
                Dari<br>
                <span class="font-bold uppercase tracking-widest text-4xl md:text-5xl text-amber-500">SMADA PRIMA</span><br>
                Untuk Bangsa
            </h2>
        </div>
    </section>

    <!-- ------------------------------------------------------------- -->
    <!-- 11. FOOTER (EXACT HTML MATCH WITH TWITTER REMOVED) -->
    <!-- ------------------------------------------------------------- -->
    <footer class="text-white pt-12 pb-6" style="background-color: var(--primary-color, #001c4d)">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Brand & Accreditation -->
            <div class="text-center md:text-left flex flex-col items-center md:items-start">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded bg-white text-slate-900 flex items-center justify-center font-extrabold text-sm shadow">S2</div>
                    <span class="font-extrabold text-white text-base tracking-wider">SMAN 2 SITUBONDO</span>
                </div>
                <p class="text-xs text-blue-100 leading-relaxed max-w-xs">
                    SMA Negeri 2 Situbondo berkomitmen mencetak generasi bangsa unggul, berakhlak mulia, dan berwawasan lingkungan.
                </p>
            </div>

            <!-- Informasi Tentang -->
            <div>
                <h4 class="font-bold mb-4 text-lg border-b border-blue-500 pb-2 inline-block font-headline-md">Informasi Tentang</h4>
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
                <h4 class="font-bold mb-4 text-lg border-b border-blue-500 pb-2 inline-block font-headline-md">Link Lainnya</h4>
                <ul class="space-y-2 text-sm text-blue-100">
                    <li><a class="hover:text-white hover:underline" href="#elearning">&bull; Elearning</a></li>
                    <li><a class="hover:text-white hover:underline" href="#media">&bull; Video Pembelajaran</a></li>
                    <li><a class="hover:text-white hover:underline" href="#bukudigital">&bull; Buku Digital</a></li>
                    <li><a class="hover:text-white hover:underline" href="#literasi">&bull; Literasi</a></li>
                    <li><a class="hover:text-white hover:underline" href="#spmb">&bull; SPMB</a></li>
                </ul>
            </div>

            <!-- Kontak Kami -->
            <div id="contact">
                <h4 class="font-bold mb-4 text-lg border-b border-blue-500 pb-2 inline-block font-headline-md">Kontak Kami</h4>
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
                <a class="hover:text-amber-400 transition" href="https://www.facebook.com/Sma.Negeri.2.Situbondo/" target="_blank" rel="noopener" title="facebook"><i class="fab fa-facebook"></i></a>
                <!-- YouTube -->
                <a class="hover:text-amber-400 transition" href="https://www.youtube.com/c/SMADAPRIMA/videos" target="_blank" rel="noopener" title="youtube"><i class="fab fa-youtube"></i></a>
                <!-- Instagram -->
                <a class="hover:text-amber-400 transition" href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener" title="instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <!-- ------------------------------------------------------------- -->
    <!-- FLOATING ACTION BUTTONS (ACCESSIBILITY, WHATSAPP, TOP) -->
    <!-- ------------------------------------------------------------- -->
    <div class="fixed bottom-4 right-4 flex flex-col space-y-2 z-50">
        <a class="bg-amber-500 text-black p-3 rounded-full shadow-lg hover:bg-amber-600 flex items-center justify-center h-12 w-12 transition" href="#" title="Aksesibilitas">
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
                    <h4 class="font-bold text-gray-900 text-base leading-tight uppercase font-headline-md">{{ $activePopup->title }}</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $activePopup->description }}</p>
                    <button @click="showPopup = false" class="w-full py-2 rounded-lg font-bold text-xs text-white uppercase tracking-wider transition shadow" style="background-color: var(--primary-color, #163269)">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
