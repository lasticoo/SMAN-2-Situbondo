<!-- 1. TOP UTILITY BAR (SHARED COMPONENT) -->
<div class="bg-gray-100 py-1.5 text-xs border-b border-gray-200">
    <div class="container mx-auto px-4 flex justify-end items-center">
        <div class="flex flex-wrap space-x-3 sm:space-x-4 items-center font-medium text-gray-700 text-[11px] sm:text-xs">
            <a class="hover-text-primary transition hidden sm:inline" href="mailto:smadasit@yahoo.com">smadasit@yahoo.com</a>
            <a class="hover-text-primary transition" href="tel:0338671618">(0338) 671618</a>
            <a class="hover-text-primary transition" href="{{ route('home') }}#alumni">Alumni</a>
            <a class="hover-text-primary transition" href="{{ route('home') }}#siklus">SIKLUS</a>
            <a class="bg-theme-secondary text-slate-950 px-2.5 sm:px-3.5 py-0.5 sm:py-1 font-bold rounded-lg shadow-sm hover-bg-secondary transition spring-hover" href="{{ route('home') }}#mysmada">MySmada</a>
        </div>
    </div>
</div>

<!-- 2. MAIN HEADER NAVBAR (SHARED COMPONENT) -->
<header class="bg-white py-3.5 shadow-sm sticky top-0 z-50 border-b border-gray-100" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4 flex justify-between md:justify-end items-center">
        <!-- Mobile Brand Title -->
        <a href="{{ route('home') }}" class="md:hidden font-extrabold text-theme-primary text-base font-headline uppercase tracking-wider">SMAN 2 SITUBONDO</a>

        <!-- Desktop Nav Menu -->
        <nav class="hidden md:flex space-x-6 text-sm font-semibold text-gray-700 items-center">
            <a class="{{ request()->routeIs('home') ? 'text-theme-primary font-bold border-b-2 border-theme-secondary pb-0.5' : 'hover-text-primary' }} flex items-center uppercase" href="{{ route('home') }}">BERANDA</a>
            
            <!-- PROFIL Dropdown -->
            <div class="relative group">
                <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'text-theme-primary font-bold border-b-2 border-theme-secondary pb-0.5' : 'hover-text-primary' }} flex items-center uppercase py-1">
                    PROFIL <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i>
                </a>
                <div class="absolute left-0 mt-2 w-52 bg-white shadow-xl rounded-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                    <a href="{{ route('profile.index', ['open' => 'vision']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover-text-primary">Visi, Misi &amp; Tujuan</a>
                    <a href="{{ route('profile.index', ['open' => 'history']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover-text-primary">Sejarah Singkat</a>
                    <a href="{{ route('profile.index', ['open' => 'structure']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover-text-primary">Struktur Organisasi</a>
                </div>
            </div>


            <div class="relative group">
                <button class="hover-text-primary flex items-center uppercase py-1">CIVITAS AKADEMIK <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i></button>
                <div class="absolute left-0 mt-2 w-52 bg-white shadow-xl rounded-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover-text-primary" href="{{ route('home') }}#civitas">Data Pegawai</a>
                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover-text-primary" href="{{ route('home') }}#siswa">Data Siswa</a>
                </div>
            </div>

            <a class="hover-text-primary flex items-center uppercase" href="{{ route('home') }}#pengumuman">PENGUMUMAN</a>

            <div class="relative group">
                <button class="hover-text-primary flex items-center uppercase py-1">MEDIA <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i></button>
                <div class="absolute left-0 mt-2 w-52 bg-white shadow-xl rounded-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover-text-primary" href="{{ route('home') }}#media">Galeri</a>
                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover-text-primary" href="{{ route('home') }}#media">Video</a>
                </div>
            </div>

            <a class="hover-text-primary flex items-center uppercase" href="{{ route('home') }}#berita">BERITA</a>
            <a class="hover-text-primary flex items-center uppercase" href="{{ route('home') }}#contact">CONTACT</a>
            <a class="bg-theme-secondary text-slate-950 px-3.5 py-1 rounded-full font-extrabold shadow-sm hover-bg-secondary transition uppercase spring-hover" href="{{ route('home') }}#spmb">SPMB</a>
        </nav>

        <!-- Mobile Hamburger Toggle Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-700 p-2 focus:outline-none rounded-lg border border-gray-200 hover:bg-gray-50" aria-label="Toggle Mobile Menu">
            <i class="fas text-xl" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
        </button>
    </div>

    <!-- Mobile Drawer Menu -->
    <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-white border-b border-gray-200 px-4 py-4 space-y-3 font-semibold text-sm">
        <a class="block py-1 hover-text-primary" href="{{ route('home') }}">BERANDA</a>
        <div class="space-y-1 pl-3 border-l-2 border-theme-secondary">
            <a href="{{ route('profile.index') }}" class="block text-xs font-bold text-theme-primary uppercase py-1">PROFIL</a>
            <a href="{{ route('profile.index', ['open' => 'vision']) }}" class="block w-full text-left py-1 text-gray-600 hover-text-primary">Visi, Misi &amp; Tujuan</a>
            <a href="{{ route('profile.index', ['open' => 'history']) }}" class="block w-full text-left py-1 text-gray-600 hover-text-primary">Sejarah Singkat</a>
            <a href="{{ route('profile.index', ['open' => 'structure']) }}" class="block w-full text-left py-1 text-gray-600 hover-text-primary">Struktur Organisasi</a>
        </div>
        <a class="block py-1 hover-text-primary" href="{{ route('home') }}#pengumuman">PENGUMUMAN</a>
        <a class="block py-1 hover-text-primary" href="{{ route('home') }}#berita">BERITA</a>
        <a class="block py-1 hover-text-primary" href="{{ route('home') }}#contact">CONTACT</a>
        <a class="inline-block bg-theme-secondary text-slate-950 px-4 py-1.5 rounded-full font-bold shadow-sm" href="{{ route('home') }}#spmb">SPMB</a>
    </div>
</header>
