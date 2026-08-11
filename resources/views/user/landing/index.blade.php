@extends('layouts.app')

@section('content')
<!-- Tailwind CDN & Alpine.js for 100% Exact Layout Parsing -->
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- Google Fonts: Poppins & Playfair Display -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@1,500;1,700&display=swap" rel="stylesheet">

<style>
  :root {
    --navy-950: #0a1a3f;
    --navy-900: {{ $colorSetting?->primary_color ?? '#0d2456' }};
    --navy-800: #12306e;
    --navy-700: #173a86;
    --teal-900: #0b3a44;
    --teal-700: #0f5560;
    --gold-400: #f6b93b;
    --orange-500: {{ $colorSetting?->secondary_color ?? '#f2941d' }};
    --orange-600: #e07f0a;
  }
  body { font-family: 'Poppins', sans-serif; }
  .font-script { font-family: 'Playfair Display', serif; }
  .bg-navy-950 { background-color: var(--navy-950); }
  .bg-navy-900 { background-color: var(--navy-900); }
  .bg-navy-800 { background-color: var(--navy-800); }
  .bg-navy-700 { background-color: var(--navy-700); }
  .text-navy-900 { color: var(--navy-900); }
  .text-navy-700 { color: var(--navy-700); }
  .border-navy-700 { border-color: var(--navy-700); }
  .text-gold { color: var(--gold-400); }
  .bg-orange { background-color: var(--orange-500); }
  .bg-orange:hover { background-color: var(--orange-600); }
  .text-orange { color: var(--orange-500); }
  .border-orange { border-color: var(--orange-500); }
  .hero-bg {
    background:
      radial-gradient(circle at 15% 30%, rgba(255,255,255,0.04) 0, transparent 40%),
      linear-gradient(115deg, var(--teal-900) 0%, var(--navy-900) 55%, var(--navy-950) 100%);
  }
  .card-shadow { box-shadow: 0 20px 45px -15px rgba(10,26,63,0.35); }
</style>

<div x-data="{ 
    activeTab: 'siswa', 
    showPopup: {{ $activePopup ? 'true' : 'false' }}, 
    activeSlide: 0, 
    totalSlides: {{ count($banners) > 0 ? count($banners) : 1 }} 
}" class="bg-[#f4f6fa] text-slate-800 min-h-screen font-sans">

  <!-- ============ TOP UTILITY BAR ============ -->
  <div class="hidden md:flex justify-end items-center gap-6 text-xs px-8 py-1.5 bg-white border-b border-slate-100 text-slate-500">
    <span class="flex items-center gap-1">🇮🇩 Indonesia</span>
    <span class="flex items-center gap-1">✉️ info@sman2situbondo.sch.id</span>
    <span class="flex items-center gap-1">📞 (0338) 671870</span>
    <a href="#admin-login" class="bg-orange text-white font-semibold px-4 py-1 rounded-sm">Masuk</a>
  </div>

  <!-- ============ MAIN NAVBAR ============ -->
  <header class="sticky top-0 z-40 bg-white shadow-sm">
    <nav class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
      <a href="{{ route('home') }}" class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-full bg-navy-900 flex items-center justify-center text-gold text-[10px] font-bold text-center leading-tight">SMA<br/>2</div>
        <div class="leading-tight">
          <p class="font-bold text-navy-900 text-sm uppercase">SMA NEGERI 2</p>
          <p class="text-[11px] text-slate-500 tracking-wide uppercase">SITUBONDO</p>
        </div>
      </a>
      <ul class="hidden lg:flex items-center gap-7 text-[13px] font-medium text-slate-600">
        <li class="text-navy-900 font-semibold"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="flex items-center gap-1 cursor-pointer"><a href="#profil">Profil <span class="text-[10px]">▾</span></a></li>
        <li class="cursor-pointer"><a href="#profil">Fasilitas KBM</a></li>
        <li class="flex items-center gap-1 cursor-pointer"><a href="#civitas">Digital Akademik <span class="text-[10px]">▾</span></a></li>
        <li class="cursor-pointer"><a href="#pengumuman">Ekstrakurikuler</a></li>
        <li class="cursor-pointer"><a href="#berita">Berita</a></li>
        <li class="cursor-pointer"><a href="#contact">Kontak</a></li>
      </ul>
      <a href="#spmb" class="bg-navy-900 text-white text-xs font-semibold px-5 py-2 rounded-sm hover:opacity-90 transition">SPMB</a>
    </nav>
  </header>

  <!-- ============ HERO (DYNAMIC BANNERS LOOP FROM DATABASE) ============ -->
  <section class="relative hero-bg overflow-hidden min-h-[480px]" x-init="if (totalSlides > 1) { setInterval(() => { activeSlide = (activeSlide + 1) % totalSlides }, 6000) }">
    @if(count($banners) > 0)
      @foreach($banners as $index => $banner)
        <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700" class="max-w-7xl mx-auto px-6 pt-14 pb-28 grid lg:grid-cols-2 gap-8 items-center relative z-10">
          <div>
            <h1 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-4">
              {{ $banner->title }}
            </h1>
            <p class="text-slate-200/80 text-sm max-w-md mb-6 leading-relaxed">
              {{ $banner->description ?? 'Selamat datang di website resmi SMA Negeri 2 Situbondo — sekolah unggulan yang berkomitmen mencetak generasi berprestasi, berkarakter, dan siap bersaing di era global.' }}
            </p>
            <div class="flex gap-3">
              <a href="#spmb" class="bg-orange text-white text-xs font-semibold px-5 py-2.5 rounded-sm hover:opacity-90 transition">SPMB 2026</a>
              <a href="#elearning" class="border border-white/70 text-white text-xs font-semibold px-5 py-2.5 rounded-sm hover:bg-white hover:text-slate-900 transition">E-Learning</a>
            </div>
          </div>

          <div class="relative text-right lg:pr-6">
            <p class="text-slate-300 text-sm mb-1">Selamat Hari Jadi</p>
            <p class="text-slate-300 text-sm mb-3">SMA Negeri 2 Situbondo</p>
            <p class="font-script italic text-white text-3xl md:text-4xl">Semangat</p>
            <p class="font-extrabold text-transparent text-5xl md:text-6xl -my-1" style="-webkit-text-stroke:1.5px #f6b93b;">PRIMA</p>
            <p class="font-script italic text-white text-3xl md:text-4xl mb-2">Nusantara</p>
            <p class="text-gold font-semibold tracking-wide">February 14<sup>th</sup>, 2026</p>
          </div>
        </div>
      @endforeach
    @else
      <div class="max-w-7xl mx-auto px-6 pt-14 pb-28 grid lg:grid-cols-2 gap-8 items-center relative z-10">
        <div>
          <h1 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-4">SMA Negeri 2<br/>Situbondo</h1>
          <p class="text-slate-200/80 text-sm max-w-md mb-6 leading-relaxed">
            Selamat datang di website resmi SMA Negeri 2 Situbondo — sekolah unggulan yang berkomitmen
            mencetak generasi berprestasi, berkarakter, dan siap bersaing di era global.
          </p>
          <div class="flex gap-3">
            <a href="#spmb" class="bg-orange text-white text-xs font-semibold px-5 py-2.5 rounded-sm">SPMB 2026</a>
            <a href="#elearning" class="border border-white/70 text-white text-xs font-semibold px-5 py-2.5 rounded-sm">E-Learning</a>
          </div>
        </div>

        <div class="relative text-right lg:pr-6">
          <p class="text-slate-300 text-sm mb-1">Selamat Hari Jadi</p>
          <p class="text-slate-300 text-sm mb-3">SMA Negeri 2 Situbondo</p>
          <p class="font-script italic text-white text-3xl md:text-4xl">Semangat</p>
          <p class="font-extrabold text-transparent text-5xl md:text-6xl -my-1" style="-webkit-text-stroke:1.5px #f6b93b;">PRIMA</p>
          <p class="font-script italic text-white text-3xl md:text-4xl mb-2">Nusantara</p>
          <p class="text-gold font-semibold tracking-wide">February 14<sup>th</sup>, 2026</p>
        </div>
      </div>
    @endif

    @if(count($banners) > 1)
      <div class="absolute bottom-16 left-1/2 -translate-x-1/2 z-20 flex space-x-2">
        @foreach($banners as $index => $b)
          <button @click="activeSlide = {{ $index }}" class="w-2.5 h-2.5 rounded-full transition" :class="activeSlide === {{ $index }} ? 'bg-gold-400 w-7' : 'bg-white/50'"></button>
        @endforeach
      </div>
    @endif
  </section>

  <!-- ============ PROFIL SEKOLAH (OVERLAPPING CARD 5 BUTTONS) ============ -->
  <section class="max-w-4xl mx-auto px-6 -mt-16 relative z-20" id="profil">
    <div class="bg-white rounded-md card-shadow p-8">
      <p class="text-center text-navy-900 font-bold tracking-widest text-sm mb-1">PROFIL SEKOLAH</p>
      <div class="w-10 h-0.5 bg-orange mx-auto mb-6"></div>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <a href="#profil" class="bg-navy-800 hover:bg-navy-700 transition rounded-md flex flex-col items-center justify-center gap-2 py-6 text-white text-xs font-semibold shadow-sm">
          <span class="text-xl">👁️</span> Visi Misi
        </a>
        <a href="#profil" class="bg-navy-800 hover:bg-navy-700 transition rounded-md flex flex-col items-center justify-center gap-2 py-6 text-white text-xs font-semibold shadow-sm">
          <span class="text-xl">🧩</span> Struktur Organisasi
        </a>
        <a href="#siswa" class="bg-navy-800 hover:bg-navy-700 transition rounded-md flex flex-col items-center justify-center gap-2 py-6 text-white text-xs font-semibold shadow-sm">
          <span class="text-xl">👥</span> Data Siswa
        </a>
        <a href="#elearning" class="col-span-1 md:col-start-1 bg-navy-800 hover:bg-navy-700 transition rounded-md flex flex-col items-center justify-center gap-2 py-6 text-white text-xs font-semibold shadow-sm">
          <span class="text-xl">💻</span> E-Learning
        </a>
        <a href="#bukudigital" class="bg-navy-800 hover:bg-navy-700 transition rounded-md flex flex-col items-center justify-center gap-2 py-6 text-white text-xs font-semibold shadow-sm">
          <span class="text-xl">📱</span> Buku Digital
        </a>
      </div>
    </div>
  </section>

  <!-- ============ SAPA KEPALA SEKOLAH ============ -->
  <section class="bg-navy-900 mt-14">
    <div class="max-w-6xl mx-auto px-6 py-12 relative">
      <div class="flex justify-between items-start mb-6">
        <h2 class="text-white text-lg font-semibold">Sapa <span class="text-orange">Kepala Sekolah</span></h2>
        <a href="#profil" class="bg-orange text-white text-[11px] font-semibold px-4 py-1.5 rounded-full hover:opacity-90 transition">Selengkapnya →</a>
      </div>
      <div class="grid md:grid-cols-[220px_1fr] gap-8 items-start">
        <img src="/build/assets/kepala sekolah smada.png" class="rounded-md w-full h-64 object-cover border-2 border-white/10 shadow-lg" alt="Kepala Sekolah"/>
        <div>
          <h3 class="text-white font-bold tracking-wide text-lg">NIKMATIL HASANAH, S.Pd, M.Pd</h3>
          <p class="text-gold text-xs mb-4 font-medium">19640516 200604 2 012</p>
          <p class="text-slate-300 text-sm leading-relaxed text-justify">
            Assalamu'alaikum Wr. Wb. Puji syukur dipanjatkan kehadirat Tuhan Yang Maha Esa atas
            diperkenankannya membangun website ini. Kami berharap website SMA Negeri 2 Situbondo ini
            dapat menjadi jembatan informasi antara sekolah, siswa, orang tua, dan masyarakat luas,
            serta turut mendukung pengembangan pendidikan yang berkualitas menuju generasi yang
            unggul dan berprestasi di kancah Nusantara.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ BERITA SMADA (TOP 5 PUBLISHED DINAMIS FROM DB) ============ -->
  <section class="max-w-6xl mx-auto px-6 py-12" id="berita">
    <div class="flex justify-between items-center mb-4">
      <h2 class="font-bold text-navy-900 text-lg">Berita Smada</h2>
      <a href="#berita" class="text-xs text-slate-400 hover:text-orange transition font-medium">Selengkapnya »</a>
    </div>
    <div class="w-full h-px bg-slate-200 mb-6"></div>

    @if(count($newsList) > 0)
      <div class="grid md:grid-cols-3 gap-5">
        @php $firstNews = $newsList->first(); @endphp
        <div class="bg-white rounded-md overflow-hidden shadow-sm border border-slate-100 flex flex-col justify-between">
          <div>
            <img src="{{ $firstNews->thumbnail_url ?? '/build/assets/banner smada.png' }}" class="w-full h-36 object-cover" alt="{{ $firstNews->title }}"/>
            <div class="p-4 space-y-1.5">
              <h3 class="font-bold text-sm text-navy-900 leading-snug hover:text-orange transition uppercase">
                <a href="#berita">{{ $firstNews->title }}</a>
              </h3>
              <p class="text-[11px] text-slate-400">📅 {{ $firstNews->published_at ? $firstNews->published_at->format('F d, Y') : 'September 10, 2025' }}</p>
              <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                {{ $firstNews->summary }}
              </p>
            </div>
          </div>
          <div class="p-4 pt-0">
            <a href="#berita" class="inline-block border border-navy-700 text-navy-700 hover:bg-navy-700 hover:text-white text-[11px] font-semibold px-3 py-1 rounded-full transition">Selengkapnya</a>
          </div>
        </div>

        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($newsList->slice(1, 4) as $item)
            <div class="bg-white rounded-md shadow-sm border border-slate-100 flex gap-3 p-3 items-center hover:shadow-md transition">
              <img src="{{ $item->thumbnail_url ?? '/build/assets/banner smada.png' }}" class="w-16 h-16 rounded object-cover shrink-0" alt="{{ $item->title }}"/>
              <div class="space-y-1">
                <p class="text-xs font-semibold text-navy-900 leading-snug line-clamp-2 uppercase">
                  <a href="#berita" class="hover:text-orange transition">{{ $item->title }}</a>
                </p>
                <p class="text-[10px] text-slate-400">📅 {{ $item->published_at ? $item->published_at->format('F d, Y') : 'Agustus 25, 2025' }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @else
      <div class="text-center py-10 text-slate-400 bg-white rounded-md border border-dashed border-slate-200">
        Belum ada berita yang dipublikasikan.
      </div>
    @endif
  </section>

  <!-- ============ SMADA FACT (REAL DATABASE CALCULATIONS) ============ -->
  <section class="bg-navy-900 py-10">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <div class="inline-block bg-white rounded-full px-6 py-2 mb-6 shadow">
        <span class="font-bold text-navy-900 text-sm uppercase">SMADA <span class="text-orange">FACT</span></span>
      </div>

      <div class="flex justify-center gap-3 mb-8">
        <button @click="activeTab = 'siswa'" :class="activeTab === 'siswa' ? 'bg-orange text-white' : 'border border-white/60 text-white'" class="text-xs font-semibold px-5 py-1.5 rounded-full transition">Peserta Didik</button>
        <button @click="activeTab = 'guru'" :class="activeTab === 'guru' ? 'bg-orange text-white' : 'border border-white/60 text-white'" class="text-xs font-semibold px-5 py-1.5 rounded-full transition">Guru</button>
        <button @click="activeTab = 'staf'" :class="activeTab === 'staf' ? 'bg-orange text-white' : 'border border-white/60 text-white'" class="text-xs font-semibold px-5 py-1.5 rounded-full transition">Staff</button>
      </div>

      <!-- Tab Content: PESERTA DIDIK -->
      <div x-show="activeTab === 'siswa'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="border border-gold/40 rounded-md py-6 bg-navy-950/40">
          <p class="text-orange text-3xl font-extrabold">{{ $studentStats['total'] }}</p>
          <p class="text-slate-300 text-[11px] mt-1 font-medium">Total Peserta Didik</p>
        </div>
        <div class="border border-gold/40 rounded-md py-6 bg-navy-950/40">
          <p class="text-orange text-3xl font-extrabold">{{ $studentStats['kelas_10'] }}</p>
          <p class="text-slate-300 text-[11px] mt-1 font-medium">Siswa Kelas X</p>
        </div>
        <div class="border border-gold/40 rounded-md py-6 bg-navy-950/40">
          <p class="text-orange text-3xl font-extrabold">{{ $studentStats['kelas_11'] + $studentStats['kelas_12'] }}</p>
          <p class="text-slate-300 text-[11px] mt-1 font-medium">Siswa Kelas XI &amp; XII</p>
        </div>
      </div>

      <!-- Tab Content: GURU -->
      <div x-show="activeTab === 'guru'" class="max-w-md mx-auto border border-gold/40 rounded-md py-8 bg-navy-950/40">
        <p class="text-orange text-4xl font-extrabold">{{ $employeeStats['guru'] }}</p>
        <p class="text-slate-300 text-xs mt-1 font-medium">Guru (Tenaga Pendidik)</p>
      </div>

      <!-- Tab Content: STAFF -->
      <div x-show="activeTab === 'staf'" class="max-w-md mx-auto border border-gold/40 rounded-md py-8 bg-navy-950/40">
        <p class="text-orange text-4xl font-extrabold">{{ $employeeStats['staf'] }}</p>
        <p class="text-slate-300 text-xs mt-1 font-medium">Staff (Tenaga Kependidikan)</p>
      </div>
    </div>
  </section>

  <!-- ============ AGENDA & EKSTRAKURIKULER ============ -->
  <section class="max-w-6xl mx-auto px-6 py-12 grid md:grid-cols-[2fr_1fr] gap-10" id="pengumuman">
    <div>
      <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold text-navy-900 text-lg">Agenda &amp; Pengumuman</h2>
        <a href="#pengumuman" class="text-xs text-slate-400 hover:text-orange transition font-medium">Selengkapnya »</a>
      </div>
      <div class="w-full h-px bg-slate-200 mb-5"></div>

      @if(count($announcementsList) > 0)
        <div class="grid sm:grid-cols-2 gap-4">
          @php $firstAnn = $announcementsList->first(); @endphp
          <div class="bg-slate-50 rounded-md p-4 flex flex-col justify-between border border-slate-100">
            <div>
              <p class="text-xs font-bold text-navy-900 uppercase leading-snug mb-1">{{ $firstAnn->title }}</p>
              <p class="text-[10px] text-slate-400 mb-3">📅 {{ $firstAnn->published_at ? $firstAnn->published_at->format('F d, Y') : 'Juli 14, 2025' }}</p>
              <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed mb-4">{{ $firstAnn->summary }}</p>
            </div>
            <div>
              <a href="#pengumuman" class="inline-block border border-navy-700 text-navy-700 text-[10px] font-semibold px-3 py-1 rounded-full hover:bg-navy-700 hover:text-white transition">Selengkapnya</a>
            </div>
          </div>

          <div class="space-y-4">
            @foreach($announcementsList->slice(1, 2) as $annItem)
              <div class="bg-slate-50 rounded-md p-4 border border-slate-100 space-y-1">
                <p class="text-xs font-bold text-navy-900 uppercase leading-snug">{{ $annItem->title }}</p>
                <p class="text-[10px] text-slate-400">📅 {{ $annItem->published_at ? $annItem->published_at->format('F d, Y') : 'Mei 26, 2025' }}</p>
                <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed my-1">{{ $annItem->summary }}</p>
                <a href="#pengumuman" class="inline-block border border-navy-700 text-navy-700 text-[10px] font-semibold px-3 py-1 rounded-full hover:bg-navy-700 hover:text-white transition">Selengkapnya</a>
              </div>
            @endforeach
          </div>
        </div>
      @else
        <div class="text-center py-8 text-slate-400 bg-slate-50 rounded-md border border-dashed border-slate-200">
          Belum ada pengumuman yang dipublikasikan.
        </div>
      @endif
    </div>

    <div>
      <h2 class="font-bold text-navy-900 text-lg mb-3">Ekstrakurikuler</h2>
      <div class="w-full h-px bg-slate-200 mb-5"></div>
      <dl class="text-xs divide-y divide-slate-200">
        <div class="flex justify-between py-2.5"><dt class="text-slate-500">Musik</dt><dd class="text-navy-900 font-medium">Seni Musik</dd></div>
        <div class="flex justify-between py-2.5"><dt class="text-slate-500">Kharismada</dt><dd class="text-navy-900 font-medium">Paskibra</dd></div>
        <div class="flex justify-between py-2.5"><dt class="text-slate-500">Jurnalistik</dt><dd class="text-navy-900 font-medium">Media &amp; Pers</dd></div>
        <div class="flex justify-between py-2.5"><dt class="text-slate-500">Pecinta Alam</dt><dd class="text-navy-900 font-medium">SISPALA</dd></div>
      </dl>
      <a href="#ekstra" class="inline-block w-full mt-4 border border-navy-700 text-navy-700 text-xs font-semibold py-2 rounded-full text-center hover:bg-navy-700 hover:text-white transition">Cek Jadwal Lainnya</a>
    </div>
  </section>

  <!-- ============ ATMOSFER SEKOLAH (INSTAGRAM REAL FEED 10 PHOTOS) ============ -->
  <section class="bg-navy-950 py-12" id="media">
    <div class="max-w-5xl mx-auto px-6">
      <div class="text-center mb-8">
        <h2 class="text-white text-2xl font-bold tracking-widest uppercase">ATMOSFER <span class="text-orange">SEKOLAH</span></h2>
        <p class="text-xs text-slate-400 mt-1">@sman2situbondoofficial</p>
      </div>

      <div class="flex justify-center items-center gap-4 overflow-x-auto pb-6 scrollbar-thin scrollbar-thumb-amber-500">
        @if(!empty($instagramPosts) && count($instagramPosts) > 0)
          @foreach($instagramPosts as $idx => $photoUrl)
            <div class="shrink-0 transition transform hover:scale-105 {{ $idx % 3 === 1 ? 'w-56 h-64 border-4 border-white rounded-sm shadow-2xl relative z-10' : 'w-52 h-40 border border-slate-700 rounded-sm shadow-xl relative z-0' }}">
              <img src="{{ $photoUrl }}" class="w-full h-full object-cover" alt="Atmosfer Sekolah {{ $idx + 1 }}"/>
            </div>
          @endforeach
        @else
          <img src="/build/assets/banner smada.png" class="w-52 h-40 object-cover rounded-sm -rotate-6 shadow-xl -mr-6 relative z-0 border border-slate-700" alt="Galeri upacara"/>
          <img src="/build/assets/banner smada.png" class="w-56 h-64 object-cover rounded-sm shadow-2xl relative z-10 border-4 border-white" alt="Galeri kegiatan"/>
          <img src="/build/assets/kepala sekolah smada.png" class="w-52 h-40 object-cover rounded-sm rotate-6 shadow-xl -ml-6 relative z-0 border border-slate-700" alt="Galeri rapat"/>
        @endif
      </div>
    </div>
  </section>

  <!-- ============ TAGLINE ============ -->
  <section class="bg-white text-center py-14 border-t border-b border-slate-100">
    <p class="font-script italic text-navy-900/70 text-lg">Dari</p>
    <p class="font-script italic text-orange text-4xl md:text-5xl font-bold my-1">SMADA PRIMA</p>
    <p class="font-script italic text-navy-900/70 text-lg">Untuk Bangsa</p>
  </section>

  <!-- ============ FOOTER (EXACT MATCH WITH TWITTER REMOVED) ============ -->
  <footer class="bg-navy-900 text-slate-300 relative border-t border-navy-800" id="contact">
    <div class="max-w-6xl mx-auto px-6 py-12 grid md:grid-cols-[1fr_1fr_1fr_1.2fr] gap-8 text-sm">
      <div class="space-y-3">
        <div class="w-12 h-12 rounded-full bg-white text-navy-900 flex items-center justify-center font-bold text-xs shadow-md">SMA 2</div>
        <h4 class="text-white font-bold text-sm tracking-wide uppercase">SMA Negeri 2 Situbondo</h4>
        <p class="text-xs text-slate-400 leading-relaxed">
          Sekolah unggulan yang berkomitmen mencetak generasi berprestasi, berkarakter, dan berdaya saing global.
        </p>
      </div>

      <div>
        <h4 class="text-white font-semibold mb-3 border-b border-slate-700 pb-1.5 inline-block">Informasi Tentang</h4>
        <ul class="space-y-2 text-xs text-slate-400">
          <li><a href="#profil" class="hover:text-white transition">&bull; Visi &amp; Misi</a></li>
          <li><a href="#profil" class="hover:text-white transition">&bull; Sejarah Sekolah</a></li>
          <li><a href="#profil" class="hover:text-white transition">&bull; Struktur Organisasi</a></li>
          <li><a href="#civitas" class="hover:text-white transition">&bull; Data Pegawai</a></li>
          <li><a href="#siswa" class="hover:text-white transition">&bull; Data Siswa</a></li>
          <li><a href="#pengumuman" class="hover:text-white transition">&bull; Kontak &amp; Pengumuman</a></li>
        </ul>
      </div>

      <div>
        <h4 class="text-white font-semibold mb-3 border-b border-slate-700 pb-1.5 inline-block">Link Lainnya</h4>
        <ul class="space-y-2 text-xs text-slate-400">
          <li><a href="#spmb" class="hover:text-white transition">&bull; Beasiswa</a></li>
          <li><a href="#media" class="hover:text-white transition">&bull; Video Pembelajaran</a></li>
          <li><a href="#berita" class="hover:text-white transition">&bull; Berita Terkini</a></li>
          <li><a href="#profil" class="hover:text-white transition">&bull; Fasilitas</a></li>
          <li><a href="#spmb" class="hover:text-white transition">&bull; SPMB</a></li>
        </ul>
      </div>

      <div>
        <h4 class="text-white font-semibold mb-3 border-b border-slate-700 pb-1.5 inline-block">Kontak Kami</h4>
        <p class="text-xs text-slate-400 leading-relaxed">Jl. Anggrek No. 1 Patokan, Kec. Situbondo,<br/>Jawa Timur, Indonesia</p>
        <p class="text-xs text-slate-400 mt-2">Telp : (0338) 671870</p>
        <p class="text-xs text-slate-400">Email : info@sman2situbondo.sch.id</p>
      </div>
    </div>

    <div class="border-t border-white/10 py-4">
      <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-2 text-[11px] text-slate-500">
        <p>Copyright &copy; 2026 SMA Negeri 2 Situbondo</p>
        <div class="flex gap-4 text-slate-400 font-medium">
          <!-- Facebook -->
          <a href="https://www.facebook.com/Sma.Negeri.2.Situbondo/" target="_blank" rel="noopener" class="hover:text-white transition">Facebook</a>
          <!-- YouTube -->
          <a href="https://www.youtube.com/c/SMADAPRIMA/videos" target="_blank" rel="noopener" class="hover:text-white transition">YouTube</a>
          <!-- Instagram -->
          <a href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener" class="hover:text-white transition">Instagram</a>
        </div>
      </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-6 flex flex-col gap-3 z-50">
      <a href="#" class="w-11 h-11 rounded-full bg-orange text-white flex items-center justify-center shadow-lg hover:scale-110 transition" title="Telepon">📞</a>
      <a href="https://wa.me/628123456789" target="_blank" rel="noopener" class="w-11 h-11 rounded-full bg-green-500 text-white flex items-center justify-center shadow-lg hover:scale-110 transition" title="WhatsApp">💬</a>
    </div>
    
    <a href="#" class="fixed bottom-4 left-4 bg-black text-white p-3 rounded-lg shadow-lg hover:bg-gray-800 flex items-center justify-center h-10 w-10 z-50 transition" title="Ke Atas">
      ▲
    </a>
  </footer>

  <!-- POP-UP EVENT MODAL -->
  @if($activePopup)
    <div x-show="showPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm" x-transition>
      <div class="bg-white rounded-md overflow-hidden max-w-md w-full shadow-2xl relative border border-slate-200">
        <button @click="showPopup = false" class="absolute top-3 right-3 w-7 h-7 rounded-full bg-black text-white flex items-center justify-center text-xs font-bold z-10 hover:bg-red-600 transition">
          ✕
        </button>
        @if($activePopup->image_url)
          <img src="{{ $activePopup->image_url }}" alt="{{ $activePopup->title }}" class="w-full h-44 object-cover">
        @endif
        <div class="p-5 space-y-2">
          <h4 class="font-bold text-navy-900 text-base leading-tight uppercase font-sans">{{ $activePopup->title }}</h4>
          <p class="text-xs text-slate-600 leading-relaxed">{{ $activePopup->description }}</p>
          <button @click="showPopup = false" class="w-full py-2 rounded-sm font-semibold text-xs text-white uppercase tracking-wider transition bg-navy-900 hover:bg-navy-800 shadow">
            Tutup
          </button>
        </div>
      </div>
    </div>
  @endif

</div>
@endsection
