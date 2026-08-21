<!-- SHARED FOOTER COMPONENT (Matching Landing Page) -->
<footer class="bg-theme-primary-deep text-white pt-10 sm:pt-12 pb-6 border-t border-white/10 relative overflow-hidden" id="contact" style="background-color: var(--primary-deep, #0e2347);">
    <!-- Ambient Subtle Lighting -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 mb-8 relative z-10">
        <!-- Brand & Accreditation -->
        <div class="text-center sm:text-left flex flex-col items-center sm:items-start">
            <h3 class="font-extrabold text-white text-base sm:text-lg tracking-wider font-headline uppercase mb-3 border-b-2 border-theme-secondary pb-1 inline-block">
                SMAN 2 SITUBONDO
            </h3>
            <p class="text-xs text-slate-200 leading-relaxed max-w-xs font-normal">
                SMA Negeri 2 Situbondo berkomitmen mencetak generasi bangsa unggul, berakhlak mulia, dan berwawasan lingkungan.
            </p>
        </div>

        <!-- Informasi Tentang -->
        <div>
            <h4 class="font-bold mb-3 sm:mb-4 text-xs sm:text-sm text-white border-b-2 border-theme-secondary pb-1.5 inline-block font-headline tracking-wider uppercase">Informasi Tentang</h4>
            <ul class="space-y-2 sm:space-y-2.5 text-xs text-slate-200 font-medium">
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('profile.index', ['open' => 'vision']) }}">&bull; Visi Misi &amp; Tujuan</a></li>
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('profile.index', ['open' => 'history']) }}">&bull; Sejarah Singkat</a></li>
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('profile.index', ['open' => 'structure']) }}">&bull; Struktur Organisasi</a></li>
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('home') }}#civitas">&bull; Data Pegawai</a></li>
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('home') }}#siswa">&bull; Data Siswa</a></li>
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('profile.index') }}">&bull; Sarana &amp; Prasarana</a></li>
            </ul>
        </div>

        <!-- Link Lainnya / Aplikasi Kami -->
        <div>
            <h4 class="font-bold mb-3 sm:mb-4 text-xs sm:text-sm text-white border-b-2 border-theme-secondary pb-1.5 inline-block font-headline tracking-wider uppercase">Aplikasi Kami</h4>
            <ul class="space-y-2 sm:space-y-2.5 text-xs text-slate-200 font-medium">
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('home') }}#elearning">&bull; Elearning</a></li>
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('home') }}#video">&bull; Video Pembelajaran</a></li>
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('home') }}#buku-digital">&bull; Buku Digital</a></li>
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('home') }}#literasi">&bull; Literasi</a></li>
                <li><a class="hover-text-secondary transition hover:underline" href="{{ route('home') }}#spmb">&bull; SPMB</a></li>
            </ul>
        </div>

        <!-- Kontak Kami -->
        <div>
            <h4 class="font-bold mb-3 sm:mb-4 text-xs sm:text-sm text-white border-b-2 border-theme-secondary pb-1.5 inline-block font-headline tracking-wider uppercase">Kontak Kami</h4>
            <p class="text-xs text-slate-200 mb-2 leading-relaxed">&bull; Jl. Anggrek No. 1 Patokan, Kab. Situbondo - Indonesia</p>
            <p class="text-xs text-slate-200 mb-2 leading-relaxed">&bull; Telp. : (0338) 671618</p>
            <p class="text-xs text-slate-200 leading-relaxed">&bull; Email : smadasit@yahoo.com</p>
        </div>
    </div>

    <!-- Copyright & Socials -->
    <div class="container mx-auto px-4 mt-6 flex flex-col md:flex-row justify-between items-center text-xs text-slate-300 border-t border-white/10 pt-4 relative z-10">
        <p>Copyright &copy; 2026 SMA NEGERI 2 SITUBONDO</p>
        <div class="flex space-x-4 mt-4 md:mt-0 text-white text-lg">
            <a class="hover-text-secondary transition" href="https://www.facebook.com/Sma.Negeri.2.Situbondo/" target="_blank" rel="noopener" title="facebook"><i class="fab fa-facebook"></i></a>
            <a class="hover-text-secondary transition" href="https://www.youtube.com/c/SMADAPRIMA/videos" target="_blank" rel="noopener" title="youtube"><i class="fab fa-youtube"></i></a>
            <a class="hover-text-secondary transition" href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener" title="instagram"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</footer>

<!-- FLOATING ACTION BUTTONS (INSTAGRAM, WHATSAPP, TOP) -->
<div class="fixed bottom-4 right-4 flex flex-col space-y-2 z-50 animate-float">
    <a class="bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white p-3 rounded-full shadow-lg hover:opacity-90 flex items-center justify-center h-11 w-11 sm:h-12 sm:w-12 transition spring-hover" href="https://www.instagram.com/sman2situbondoofficial/" target="_blank" rel="noopener noreferrer" title="Instagram Resmi @sman2situbondoofficial">
        <i class="fab fa-instagram text-xl sm:text-2xl"></i>
    </a>
    <a class="bg-green-500 text-white p-3 rounded-full shadow-lg hover:bg-green-600 flex items-center justify-center h-11 w-11 sm:h-12 sm:w-12 transition spring-hover" href="https://wa.me/628123456789" target="_blank" rel="noopener" title="WhatsApp">
        <i class="fab fa-whatsapp text-xl sm:text-2xl"></i>
    </a>
</div>

<a class="fixed bottom-4 left-4 bg-black text-white p-2.5 sm:p-3 rounded-lg shadow-lg hover:bg-gray-800 flex items-center justify-center h-9 w-9 sm:h-10 sm:w-10 z-50 transition spring-hover" href="#" title="Ke Atas">
    <i class="fas fa-chevron-up text-xs sm:text-sm"></i>
</a>
