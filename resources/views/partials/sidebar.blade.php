@php
    $currentAdmin = Auth::guard('admin')->user();
@endphp

<aside class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 sticky top-0 h-screen z-30">
    <!-- Brand Header -->
    <div class="h-16 shrink-0 flex items-center gap-3 px-6 border-b border-slate-100">
        <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold shadow-sm shadow-indigo-600/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
        <div>
            <h1 class="font-bold text-slate-900 text-sm tracking-tight leading-tight">SMAN 2 Situbondo</h1>
            <p class="text-[11px] text-slate-500 font-medium">Admin Portal</p>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto custom-scrollbar">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <div class="pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3">Konten Web</div>

        <!-- Manajemen Landing Page -->
        <a href="{{ Route::has('admin.banners.index') ? route('admin.banners.index') : url('/admin/banners') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.banners.*') || request()->is('admin/banners*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.banners.*') || request()->is('admin/banners*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Manajemen Landing Page</span>
        </a>

        <!-- Profil Sekolah -->
        <a href="{{ route('admin.school_profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.school_profile.*') || request()->is('admin/school-profile*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.school_profile.*') || request()->is('admin/school-profile*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Profil Sekolah</span>
        </a>

        <!-- Berita -->
        <a href="{{ Route::has('admin.news.index') ? route('admin.news.index') : url('/admin/news') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.news.*') || request()->is('admin/news*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.news.*') || request()->is('admin/news*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <span>Berita Sekolah</span>
        </a>


        <!-- Pengumuman -->
        <a href="{{ Route::has('admin.announcements.index') ? route('admin.announcements.index') : url('/admin/announcements') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.announcements.*') || request()->is('admin/announcements*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.announcements.*') || request()->is('admin/announcements*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <span>Pengumuman</span>
        </a>

        <!-- Galeri Foto -->
        <a href="{{ Route::has('admin.galleries.index') ? route('admin.galleries.index') : url('/admin/galleries') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.galleries.*') || request()->is('admin/galleries*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.galleries.*') || request()->is('admin/galleries*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Galeri Foto</span>
        </a>

        <!-- Video Youtube -->
        <a href="{{ Route::has('admin.videos.index') ? route('admin.videos.index') : url('/admin/videos') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.videos.*') || request()->is('admin/videos*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.videos.*') || request()->is('admin/videos*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span>Video Youtube</span>
        </a>

        <div class="pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3">Master Data & Dokumen</div>

        <!-- Data Siswa -->
        <a href="{{ url('/admin/students') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->is('admin/students*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->is('admin/students*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span>Data Siswa</span>
        </a>

        <!-- Data Pegawai -->
        <a href="{{ Route::has('admin.employees.index') ? route('admin.employees.index') : url('/admin/employees') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.employees.*') || request()->is('admin/employees*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.employees.*') || request()->is('admin/employees*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span>Data Pegawai</span>
        </a>

        <!-- Kelola SPMB -->
        <a href="{{ url('/admin/spmb') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->is('admin/spmb*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->is('admin/spmb*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Kelola SPMB</span>
        </a>

        <!-- Pesan Masuk -->
        <a href="{{ Route::has('admin.contact.index') ? route('admin.contact.index') : url('/admin/contact') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.contact.*') || request()->is('admin/contact*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.contact.*') || request()->is('admin/contact*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 012-2V7a2 2 0 01-2-2H5a2 2 0 01-2 2v10a2 2 0 012 2z"/>
            </svg>
            <span>Pesan Masuk</span>
            @if(isset($stats['unread']) && $stats['unread'] > 0)
                <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                    {{ $stats['unread'] }}
                </span>
            @elseif(isset($stats['contact_messages']['unread']) && $stats['contact_messages']['unread'] > 0)
                <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                    {{ $stats['contact_messages']['unread'] }}
                </span>
            @endif
        </a>

        <div class="pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3">Pengaturan System</div>

        <!-- Pengaturan Warna -->
        <a href="{{ route('admin.color_settings.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.color_settings.*') || request()->is('admin/color-settings*') ? 'font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.color_settings.*') || request()->is('admin/color-settings*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
            </svg>
            <span>Pengaturan Warna</span>
        </a>

        <!-- Admin Profile Footnote -->
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between px-2">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white text-xs shrink-0 shadow-sm">
                    {{ strtoupper(substr($currentAdmin->name ?? 'A', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-800 truncate">{{ $currentAdmin->name ?? 'Administrator SMAN 2 Situbondo' }}</p>
                    <p class="text-[10px] text-slate-500 truncate">{{ $currentAdmin->email ?? 'admin@smada.sch.id' }}</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" title="Logout" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </nav>
</aside>
