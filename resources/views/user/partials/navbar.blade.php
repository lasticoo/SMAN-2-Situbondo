<!-- 1. TOP UTILITY BAR (SHARED COMPONENT) -->
<div class="bg-gray-100 py-1.5 text-xs border-b border-gray-200 relative z-50">
    <div class="container mx-auto px-4 flex justify-end items-center">
        <div class="flex flex-wrap space-x-3 sm:space-x-4 items-center font-medium text-gray-700 text-[11px] sm:text-xs">
            <a class="hover-text-primary transition-colors duration-200 hidden sm:inline-flex items-center gap-1.5 group" href="mailto:smadasit@yahoo.com">
                <i class="far fa-envelope text-[10px] text-gray-400 group-hover:text-theme-primary transition-colors"></i>
                <span>smadasit@yahoo.com</span>
            </a>
            <a class="hover-text-primary transition-colors duration-200 inline-flex items-center gap-1.5 group" href="tel:0338671618">
                <i class="fas fa-phone-alt text-[10px] text-gray-400 group-hover:text-theme-primary transition-colors"></i>
                <span>(0338) 671618</span>
            </a>
            <a class="hover-text-primary transition-colors duration-200" href="{{ route('home') }}#alumni">Alumni</a>
            <a class="hover-text-primary transition-colors duration-200" href="{{ route('home') }}#siklus">SIKLUS</a>
            <a class="bg-theme-secondary text-slate-950 px-2.5 sm:px-3.5 py-0.5 sm:py-1 font-bold rounded-lg shadow-2xs hover:shadow-md hover:scale-105 active:scale-95 transition-all duration-200 spring-hover inline-flex items-center gap-1" href="{{ route('home') }}#mysmada">
                <i class="fas fa-user-circle text-[11px]"></i>
                <span>MySmada</span>
            </a>
        </div>
    </div>
</div>

<!-- 2. MAIN HEADER NAVBAR (SHARED COMPONENT - HOVER GRACE BRIDGE & MOBILE TOUCH RETENTION) -->
<header class="bg-white py-3.5 shadow-md sticky top-0 border-b border-gray-100 transition-all duration-300" 
        style="z-index: 999; background-color: #ffffff;"
        x-data="{ 
            mobileMenuOpen: false,
            activeDropdown: null,
            closeTimer: null,
            mobileProfilOpen: true,
            mobileCivitasOpen: false,
            mobileMediaOpen: false,
            openDropdown(name) {
                if (this.closeTimer) clearTimeout(this.closeTimer);
                this.activeDropdown = name;
            },
            closeDropdownWithDelay() {
                if (this.closeTimer) clearTimeout(this.closeTimer);
                this.closeTimer = setTimeout(() => {
                    this.activeDropdown = null;
                }, 350);
            },
            toggleDropdown(name) {
                if (this.closeTimer) clearTimeout(this.closeTimer);
                this.activeDropdown = this.activeDropdown === name ? null : name;
            }
        }"
        @click.away="activeDropdown = null"
        @keydown.escape.window="activeDropdown = null; mobileMenuOpen = false">
    <div class="container mx-auto px-4 flex justify-between md:justify-end items-center">
        <!-- Mobile Brand Title -->
        <a href="{{ route('home') }}" class="md:hidden font-extrabold text-theme-primary text-base font-headline uppercase tracking-wider transition-transform duration-200 hover:scale-102">
            SMAN 2 SITUBONDO
        </a>

        <!-- Desktop Nav Menu with Persistent Hover Bridge & Click Support -->
        <nav class="hidden md:flex space-x-6 text-sm font-semibold text-gray-700 items-center">
            <!-- BERANDA -->
            <a class="relative py-1 flex items-center uppercase transition-colors duration-200 {{ request()->routeIs('home') ? 'text-theme-primary font-bold' : 'hover-text-primary' }} group" href="{{ route('home') }}">
                <span>BERANDA</span>
                <span class="absolute bottom-0 left-0 h-0.5 bg-theme-secondary rounded-full transition-all duration-300 {{ request()->routeIs('home') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            
            <!-- PROFIL Dropdown (Hover Bridge + 350ms Grace Delay + Click Support) -->
            <div class="relative" 
                 @mouseenter="openDropdown('profile')" 
                 @mouseleave="closeDropdownWithDelay()">
                <button type="button" 
                        @click="toggleDropdown('profile')"
                        class="relative py-1 flex items-center gap-1.5 uppercase transition-colors duration-200 cursor-pointer {{ request()->routeIs('profile.*') ? 'text-theme-primary font-bold' : 'hover-text-primary' }}"
                        aria-haspopup="true" 
                        :aria-expanded="activeDropdown === 'profile'">
                    <span>PROFIL</span>
                    <i class="fas fa-chevron-down text-[9px] transition-transform duration-250" :class="activeDropdown === 'profile' ? 'rotate-180 text-theme-primary' : ''"></i>
                    <span class="absolute bottom-0 left-0 h-0.5 bg-theme-secondary rounded-full transition-all duration-300 {{ request()->routeIs('profile.*') ? 'w-full' : 'w-0' }}" :class="activeDropdown === 'profile' ? 'w-full' : ''"></span>
                </button>
                
                <!-- Dropdown Menu Box (Solid Background & High Z-Index 1000) -->
                <div x-show="activeDropdown === 'profile'" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200 transform-gpu"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-98"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150 transform-gpu"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-98"
                     @mouseenter="openDropdown('profile')"
                     @mouseleave="closeDropdownWithDelay()"
                     style="z-index: 1000;"
                     class="absolute left-0 top-full pt-2 w-56 before:content-[''] before:absolute before:-top-3 before:left-0 before:w-full before:h-3">
                    <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.25)] rounded-2xl p-2 border border-gray-200 ring-1 ring-black/10" style="background-color: #ffffff !important;">
                        <a href="{{ route('profile.index') }}" class="flex items-center justify-between px-3.5 py-2.5 text-xs font-black text-theme-primary hover:bg-slate-100 rounded-xl transition-all duration-150 group/item border-b border-gray-100 mb-1">
                            <span>Halaman Profil Utama</span>
                            <i class="fas fa-arrow-right text-[9px] text-theme-secondary"></i>
                        </a>
                        <a href="{{ route('profile.index', ['open' => 'vision']) }}" class="flex items-center justify-between px-3.5 py-2.5 text-xs font-bold text-slate-900 hover:text-theme-primary hover:bg-slate-100 rounded-xl transition-all duration-150 group/item">
                            <span>Visi, Misi &amp; Tujuan</span>
                            <i class="fas fa-chevron-right text-[9px] opacity-0 group-hover/item:opacity-100 group-hover/item:translate-x-0.5 transition-all text-theme-secondary"></i>
                        </a>
                        <a href="{{ route('profile.index', ['open' => 'history']) }}" class="flex items-center justify-between px-3.5 py-2.5 text-xs font-bold text-slate-900 hover:text-theme-primary hover:bg-slate-100 rounded-xl transition-all duration-150 group/item">
                            <span>Sejarah Singkat</span>
                            <i class="fas fa-chevron-right text-[9px] opacity-0 group-hover/item:opacity-100 group-hover/item:translate-x-0.5 transition-all text-theme-secondary"></i>
                        </a>
                        <a href="{{ route('profile.index', ['open' => 'structure']) }}" class="flex items-center justify-between px-3.5 py-2.5 text-xs font-bold text-slate-900 hover:text-theme-primary hover:bg-slate-100 rounded-xl transition-all duration-150 group/item">
                            <span>Struktur Organisasi</span>
                            <i class="fas fa-chevron-right text-[9px] opacity-0 group-hover/item:opacity-100 group-hover/item:translate-x-0.5 transition-all text-theme-secondary"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- CIVITAS AKADEMIK Dropdown -->
            <div class="relative" 
                 @mouseenter="openDropdown('civitas')" 
                 @mouseleave="closeDropdownWithDelay()">
                <button type="button" 
                        @click="toggleDropdown('civitas')"
                        class="relative py-1 flex items-center gap-1.5 uppercase transition-colors duration-200 cursor-pointer {{ request()->routeIs('civitas.*') || request()->routeIs('employee.*') || request()->routeIs('student.*') ? 'text-theme-primary font-bold' : 'hover-text-primary' }}"
                        aria-haspopup="true" 
                        :aria-expanded="activeDropdown === 'civitas'">
                    <span>CIVITAS AKADEMIK</span>
                    <i class="fas fa-chevron-down text-[9px] transition-transform duration-250" :class="activeDropdown === 'civitas' ? 'rotate-180 text-theme-primary' : ''"></i>
                    <span class="absolute bottom-0 left-0 h-0.5 bg-theme-secondary rounded-full transition-all duration-300 {{ request()->routeIs('civitas.*') || request()->routeIs('employee.*') || request()->routeIs('student.*') ? 'w-full' : 'w-0' }}" :class="activeDropdown === 'civitas' ? 'w-full' : ''"></span>
                </button>
                <div x-show="activeDropdown === 'civitas'" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200 transform-gpu"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-98"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150 transform-gpu"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-98"
                     @mouseenter="openDropdown('civitas')"
                     @mouseleave="closeDropdownWithDelay()"
                     style="z-index: 1000;"
                     class="absolute left-0 top-full pt-2 w-52 before:content-[''] before:absolute before:-top-3 before:left-0 before:w-full before:h-3">
                    <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.25)] rounded-2xl p-2 border border-gray-200 ring-1 ring-black/10" style="background-color: #ffffff !important;">
                        <a class="flex items-center justify-between px-3.5 py-2.5 text-xs font-bold text-slate-900 hover:text-theme-primary hover:bg-slate-100 rounded-xl transition-all duration-150 group/item {{ request()->routeIs('civitas.*') || request()->routeIs('employee.*') ? 'text-theme-primary bg-slate-50' : '' }}" href="{{ route('civitas.index') }}">
                            <span>Data Pegawai</span>
                            <i class="fas fa-chevron-right text-[9px] opacity-0 group-hover/item:opacity-100 group-hover/item:translate-x-0.5 transition-all text-theme-secondary"></i>
                        </a>
                        <a class="flex items-center justify-between px-3.5 py-2.5 text-xs font-bold text-slate-900 hover:text-theme-primary hover:bg-slate-100 rounded-xl transition-all duration-150 group/item {{ request()->routeIs('student.*') ? 'text-theme-primary bg-slate-50' : '' }}" href="{{ route('student.index') }}">
                            <span>Data Siswa</span>
                            <i class="fas fa-chevron-right text-[9px] opacity-0 group-hover/item:opacity-100 group-hover/item:translate-x-0.5 transition-all text-theme-secondary"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- PENGUMUMAN -->
            <a class="relative py-1 flex items-center uppercase transition-colors duration-200 {{ request()->routeIs('announcement.*') || request()->routeIs('agenda.*') ? 'text-theme-primary font-bold' : 'hover-text-primary' }} group" href="{{ route('announcement.index') }}">
                <span>PENGUMUMAN</span>
                <span class="absolute bottom-0 left-0 h-0.5 bg-theme-secondary rounded-full transition-all duration-300 {{ request()->routeIs('announcement.*') || request()->routeIs('agenda.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>

            <!-- MEDIA Dropdown -->
            <div class="relative" 
                 @mouseenter="openDropdown('media')" 
                 @mouseleave="closeDropdownWithDelay()">
                <button type="button" 
                        @click="toggleDropdown('media')"
                        class="relative py-1 flex items-center gap-1.5 uppercase hover-text-primary transition-colors duration-200 cursor-pointer"
                        aria-haspopup="true"
                        :aria-expanded="activeDropdown === 'media'">
                    <span>MEDIA</span>
                    <i class="fas fa-chevron-down text-[9px] transition-transform duration-250" :class="activeDropdown === 'media' ? 'rotate-180 text-theme-primary' : ''"></i>
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-theme-secondary rounded-full transition-all duration-300" :class="activeDropdown === 'media' ? 'w-full' : ''"></span>
                </button>
                <div x-show="activeDropdown === 'media'" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200 transform-gpu"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-98"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150 transform-gpu"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-98"
                     @mouseenter="openDropdown('media')"
                     @mouseleave="closeDropdownWithDelay()"
                     style="z-index: 1000;"
                     class="absolute left-0 top-full pt-2 w-48 before:content-[''] before:absolute before:-top-3 before:left-0 before:w-full before:h-3">
                    <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.25)] rounded-2xl p-2 border border-gray-200 ring-1 ring-black/10" style="background-color: #ffffff !important;">
                        <a class="flex items-center justify-between px-3.5 py-2.5 text-xs font-bold text-slate-900 hover:text-theme-primary hover:bg-slate-100 rounded-xl transition-all duration-150 group/item" href="{{ route('home') }}#media">
                            <span>Galeri</span>
                            <i class="fas fa-chevron-right text-[9px] opacity-0 group-hover/item:opacity-100 group-hover/item:translate-x-0.5 transition-all text-theme-secondary"></i>
                        </a>
                        <a class="flex items-center justify-between px-3.5 py-2.5 text-xs font-bold text-slate-900 hover:text-theme-primary hover:bg-slate-100 rounded-xl transition-all duration-150 group/item" href="{{ route('home') }}#media">
                            <span>Video</span>
                            <i class="fas fa-chevron-right text-[9px] opacity-0 group-hover/item:opacity-100 group-hover/item:translate-x-0.5 transition-all text-theme-secondary"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- BERITA -->
            <a class="relative py-1 flex items-center uppercase hover-text-primary transition-colors duration-200 group" href="{{ route('home') }}#berita">
                <span>BERITA</span>
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-theme-secondary rounded-full transition-all duration-300 group-hover:w-full"></span>
            </a>

            <!-- CONTACT -->
            <a class="relative py-1 flex items-center uppercase hover-text-primary transition-colors duration-200 group" href="{{ route('home') }}#contact">
                <span>CONTACT</span>
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-theme-secondary rounded-full transition-all duration-300 group-hover:w-full"></span>
            </a>

            <!-- SPMB Action Button (Dynamic Secondary Accent) -->
            <a class="bg-theme-secondary text-slate-950 px-4 py-1.5 rounded-full font-extrabold text-xs shadow-xs hover:shadow-md hover:scale-105 active:scale-95 transition-all duration-200 uppercase spring-hover inline-flex items-center gap-1.5" href="{{ route('home') }}#spmb">
                <span>SPMB</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </nav>

        <!-- Mobile Hamburger Toggle Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-700 p-2 focus:outline-none rounded-xl border border-gray-200 hover:bg-gray-50 active:scale-95 transition-all duration-200" aria-label="Toggle Mobile Menu">
            <i class="fas text-xl transition-transform duration-300" :class="mobileMenuOpen ? 'fa-times rotate-90 text-theme-primary' : 'fa-bars'"></i>
        </button>
    </div>

    <!-- Mobile Drawer Menu (Interactive Accordions with Large Touch Targets) -->
    <div x-show="mobileMenuOpen" 
         x-cloak 
         x-transition:enter="transition ease-out duration-250 transform-gpu"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform-gpu"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-white/98 backdrop-blur-xl border-b border-gray-200 px-5 py-5 space-y-3 font-semibold text-sm shadow-xl max-h-[85vh] overflow-y-auto">
        <a class="block py-2 hover-text-primary transition-colors border-b border-gray-100" href="{{ route('home') }}">BERANDA</a>
        
        <!-- PROFIL Mobile Accordion -->
        <div class="border-b border-gray-100 pb-2">
            <button @click="mobileProfilOpen = !mobileProfilOpen" class="flex items-center justify-between w-full py-2 text-theme-primary font-bold uppercase text-xs">
                <span>PROFIL</span>
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="mobileProfilOpen ? 'rotate-180 text-theme-secondary' : ''"></i>
            </button>
            <div x-show="mobileProfilOpen" x-collapse class="space-y-1 pl-3 border-l-2 border-theme-secondary my-1">
                <a href="{{ route('profile.index') }}" class="block py-1.5 text-theme-primary font-bold text-xs hover-text-primary">Halaman Profil Utama</a>
                <a href="{{ route('profile.index', ['open' => 'vision']) }}" class="block w-full text-left py-1.5 text-gray-600 hover-text-primary text-xs transition-colors">Visi, Misi &amp; Tujuan</a>
                <a href="{{ route('profile.index', ['open' => 'history']) }}" class="block w-full text-left py-1.5 text-gray-600 hover-text-primary text-xs transition-colors">Sejarah Singkat</a>
                <a href="{{ route('profile.index', ['open' => 'structure']) }}" class="block w-full text-left py-1.5 text-gray-600 hover-text-primary text-xs transition-colors">Struktur Organisasi</a>
            </div>
        </div>

        <!-- CIVITAS AKADEMIK Mobile Accordion -->
        <div class="border-b border-gray-100 pb-2">
            <button @click="mobileCivitasOpen = !mobileCivitasOpen" class="flex items-center justify-between w-full py-2 text-gray-700 font-bold uppercase text-xs hover-text-primary">
                <span>CIVITAS AKADEMIK</span>
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="mobileCivitasOpen ? 'rotate-180 text-theme-secondary' : ''"></i>
            </button>
            <div x-show="mobileCivitasOpen" x-collapse class="space-y-1 pl-3 border-l-2 border-theme-secondary my-1">
                <a class="block py-1.5 text-xs font-semibold hover-text-primary transition-colors {{ request()->routeIs('civitas.*') || request()->routeIs('employee.*') ? 'text-theme-primary font-bold' : 'text-gray-600' }}" href="{{ route('civitas.index') }}">Data Pegawai</a>
                <a class="block py-1.5 text-xs font-semibold hover-text-primary transition-colors {{ request()->routeIs('student.*') ? 'text-theme-primary font-bold' : 'text-gray-600' }}" href="{{ route('student.index') }}">Data Siswa</a>
            </div>
        </div>

        <a class="block py-2 hover-text-primary transition-colors border-b border-gray-100 {{ request()->routeIs('announcement.*') || request()->routeIs('agenda.*') ? 'text-theme-primary font-bold' : '' }}" href="{{ route('announcement.index') }}">PENGUMUMAN</a>

        <!-- MEDIA Mobile Accordion -->
        <div class="border-b border-gray-100 pb-2">
            <button @click="mobileMediaOpen = !mobileMediaOpen" class="flex items-center justify-between w-full py-2 text-gray-700 font-bold uppercase text-xs hover-text-primary">
                <span>MEDIA</span>
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="mobileMediaOpen ? 'rotate-180 text-theme-secondary' : ''"></i>
            </button>
            <div x-show="mobileMediaOpen" x-collapse class="space-y-1 pl-3 border-l-2 border-theme-secondary my-1">
                <a class="block py-1.5 text-gray-600 hover-text-primary text-xs" href="{{ route('home') }}#media">Galeri</a>
                <a class="block py-1.5 text-gray-600 hover-text-primary text-xs" href="{{ route('home') }}#media">Video</a>
            </div>
        </div>

        <a class="block py-2 hover-text-primary transition-colors border-b border-gray-100" href="{{ route('home') }}#berita">BERITA</a>
        <a class="block py-2 hover-text-primary transition-colors" href="{{ route('home') }}#contact">CONTACT</a>
        
        <div class="pt-3">
            <a class="inline-flex items-center justify-center w-full bg-theme-secondary text-slate-950 px-4 py-2.5 rounded-full font-extrabold shadow-sm active:scale-95 transition-all text-xs uppercase" href="{{ route('home') }}#spmb">
                <span>Daftar SPMB</span>
                <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
            </a>
        </div>
    </div>
</header>

