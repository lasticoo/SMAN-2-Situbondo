@extends('layouts.admin')

@section('content')
<div class="flex min-h-screen bg-slate-50 text-slate-800 font-sans">

    {{-- =====================================================================
         SIDEBAR NAVIGATION (PATEN FIXED SIDEBAR - NO OVERLAP)
    ====================================================================== --}}
    @include('partials.sidebar')

    {{-- =====================================================================
         MAIN CONTENT AREA (RIGHT CANVAS SIDE-BY-SIDE WITH SIDEBAR)
    ====================================================================== --}}
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">

        <!-- Top Header Bar -->
        <header class="h-16 shrink-0 bg-white border-b border-slate-200 px-6 md:px-8 flex items-center justify-between sticky top-0 z-20 shadow-xs">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Dashboard Administrator</h2>
                <p class="text-xs text-slate-500">Ringkasan statistik real-time SMAN 2 Situbondo</p>
            </div>

            <!-- Header Right Items -->
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Normal
                </span>

                <div class="h-6 w-px bg-slate-200 mx-1"></div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-800">{{ $currentAdmin->name ?? 'Administrator' }}</p>
                        <p class="text-[11px] text-slate-500">Role: {{ $currentAdmin?->role === 'super_admin' ? 'Super Admin' : ucwords(str_replace('_', ' ', $currentAdmin?->role ?? 'Admin')) }}</p>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-700 text-xs font-semibold rounded-lg transition-colors border border-slate-200">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Dashboard Canvas Content -->
        <main class="flex-1 p-6 md:p-8 space-y-8">

            {{-- Welcome Banner --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-xs relative overflow-hidden">
                <div class="relative z-10 max-w-2xl">
                    <span class="inline-block px-3 py-1 bg-slate-100 text-slate-900 text-xs font-bold rounded-full mb-3 border border-slate-200">
                        Selamat Datang Kembali 👋
                    </span>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900">
                        Halo, {{ $currentAdmin->name ?? 'Administrator' }}!
                    </h1>
                    <p class="text-slate-700 text-sm mt-2 leading-relaxed">
                        Berikut adalah ringkasan performa dan data operasional terkini dari sistem informasi SMAN 2 Situbondo.
                    </p>
                </div>
            </div>

            {{-- =====================================================================
                 BENTO GRID: 8 SUMMARY STAT CARDS (REAL-TIME DB COUNTS)
            ====================================================================== --}}
            <div>
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Ringkasan Statistik Data
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    
                    <!-- 1. Berita Card -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Berita Sekolah</span>
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-800 mb-2">{{ number_format($stats['news']['total']) }}</div>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="inline-flex items-center gap-1 font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                {{ $stats['news']['published'] }} Terbit
                            </span>
                            <span class="font-medium text-slate-500">
                                {{ $stats['news']['draft'] }} Draft
                            </span>
                        </div>
                    </div>

                    <!-- 2. Pengumuman Card -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengumuman</span>
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-800 mb-2">{{ number_format($stats['announcements']['total']) }}</div>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="inline-flex items-center gap-1 font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">
                                {{ $stats['announcements']['published'] }} Terbit
                            </span>
                            <span class="font-medium text-slate-500">
                                {{ $stats['announcements']['draft'] }} Draft
                            </span>
                        </div>
                    </div>

                    <!-- 3. Siswa Card -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Siswa</span>
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-800 mb-2">{{ number_format($stats['students']['total']) }}</div>
                        <p class="text-xs text-slate-500 font-medium">Terdaftar di Database Siswa</p>
                    </div>

                    <!-- 4. Pegawai Card -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Guru & Pegawai</span>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-800 mb-2">{{ number_format($stats['employees']['total']) }}</div>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                {{ $stats['employees']['active'] }} Aktif
                            </span>
                            <span class="font-medium text-slate-500">
                                {{ $stats['employees']['inactive'] }} Non-aktif
                            </span>
                        </div>
                    </div>

                    <!-- 5. Galeri Foto Card -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Galeri Foto</span>
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-800 mb-2">{{ number_format($stats['galleries']['total']) }}</div>
                        <p class="text-xs text-slate-500 font-medium">Item Foto Terupload</p>
                    </div>

                    <!-- 6. Video YouTube Card -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Video Dokumentasi</span>
                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-800 mb-2">{{ number_format($stats['videos']['total']) }}</div>
                        <p class="text-xs text-slate-500 font-medium">Link YouTube Terpasang</p>
                    </div>

                    <!-- 7. Pesan Contact Card -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pesan Masuk</span>
                            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-800 mb-2">{{ number_format($stats['contact_messages']['total']) }}</div>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">
                                {{ $stats['contact_messages']['unread'] }} Unread
                            </span>
                            <span class="font-medium text-slate-500">
                                {{ $stats['contact_messages']['read'] }} Read
                            </span>
                        </div>
                    </div>

                    <!-- 8. Dokumen SPMB Card -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Dokumen SPMB</span>
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-slate-800 mb-2">{{ number_format($stats['spmb']['document_total']) }}</div>
                        <p class="text-xs text-teal-600 font-semibold">Berkas Informasi Publik</p>
                    </div>

                </div>
            </div>

            {{-- =====================================================================
                 SECONDARY SECTION: SPMB STATUS & RECENT MESSAGES / QUICK ACTIONS
            ====================================================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Column (2 Cols): SPMB Status & Quick Actions -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- SPMB Info Status Panel -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 flex items-center justify-center font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-sm">Status & Periode SPMB (PPDB)</h3>
                                    <p class="text-xs text-slate-500">Ringkasan pendaftaran siswa baru yang aktif</p>
                                </div>
                            </div>
                            <a href="{{ url('/admin/spmb') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                Kelola SPMB &rarr;
                            </a>
                        </div>

                        <div class="p-6">
                            @if($stats['spmb']['info'])
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Periode Start -->
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                        <span class="text-xs text-slate-500 font-semibold block mb-1">Mulai Periode</span>
                                        <span class="text-base font-bold text-slate-800">
                                            {{ $stats['spmb']['info']->period_start ? $stats['spmb']['info']->period_start->format('d F Y') : 'Belum Ditentukan' }}
                                        </span>
                                    </div>

                                    <!-- Periode End -->
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                        <span class="text-xs text-slate-500 font-semibold block mb-1">Akhir Periode</span>
                                        <span class="text-base font-bold text-slate-800">
                                            {{ $stats['spmb']['info']->period_end ? $stats['spmb']['info']->period_end->format('d F Y') : 'Belum Ditentukan' }}
                                        </span>
                                    </div>

                                    <!-- Total Dokumen -->
                                    <div class="bg-teal-50/60 p-4 rounded-xl border border-teal-100">
                                        <span class="text-xs text-teal-700 font-semibold block mb-1">Lampiran Dokumen</span>
                                        <span class="text-base font-bold text-teal-900">
                                            {{ $stats['spmb']['document_total'] }} Dokumen Terupload
                                        </span>
                                    </div>
                                </div>

                                @if($stats['spmb']['info']->requirements_info)
                                    <div class="mt-4 p-4 rounded-xl bg-indigo-50/50 border border-indigo-100 text-xs text-indigo-900">
                                        <span class="font-bold block mb-1">Catatan Persyaratan Singkat:</span>
                                        <p class="text-slate-600 line-clamp-2">{{ Str::limit(strip_tags($stats['spmb']['info']->requirements_info), 180) }}</p>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-6 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada data periode SPMB aktif.</p>
                                    <a href="{{ url('/admin/spmb') }}" class="mt-2 inline-block text-xs text-indigo-600 font-bold hover:underline">Tambah Data SPMB Baru</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions Grid -->
                    <div>
                        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Pintas Aksi Cepat
                        </h3>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <!-- Shortcut Banner -->
                            <a href="{{ Route::has('admin.banners.index') ? route('admin.banners.index') : url('/admin/banners') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-indigo-400 hover:shadow-md transition-all group flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-800">Kelola Banner</span>
                            </a>

                            <!-- Shortcut Popup -->
                            <a href="{{ Route::has('admin.popups.index') ? route('admin.popups.index') : url('/admin/popups') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-amber-400 hover:shadow-md transition-all group flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-800">Kelola Pop-up</span>
                            </a>

                            <!-- Shortcut Berita -->
                            <a href="{{ url('/admin/news') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-blue-400 hover:shadow-md transition-all group flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-800">Tambah Berita</span>
                            </a>

                            <!-- Shortcut Siswa -->
                            <a href="{{ url('/admin/students') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all group flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-800">Kelola Siswa</span>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Right Column (1 Col): Recent Messages Feed -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Pesan Masuk Terbaru
                        </h3>
                        <a href="{{ url('/admin/contact') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                    </div>

                    <div class="p-6 flex-1 divide-y divide-slate-100">
                        @forelse($recentContactMessages as $message)
                            <div class="py-3 first:pt-0 last:pb-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-slate-800 truncate max-w-[160px]">{{ $message->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        {{ $message->created_at ? \Carbon\Carbon::parse($message->created_at)->diffForHumans() : '-' }}
                                    </span>
                                </div>
                                <p class="text-xs font-semibold text-slate-700 truncate">{{ $message->subject }}</p>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-0.5">{{ $message->message }}</p>
                                <div class="mt-1.5 flex items-center justify-between">
                                    <span class="text-[10px] text-slate-400">{{ $message->email }}</span>
                                    @if($message->status === 'unread')
                                        <span class="text-[10px] font-bold bg-rose-50 text-rose-600 px-1.5 py-0.5 rounded">Baru</span>
                                    @else
                                        <span class="text-[10px] text-slate-400">Dibaca</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-xs font-medium">Belum ada pesan masuk.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </main>

        <!-- Page Footer -->
        <footer class="mt-auto bg-white border-t border-slate-200 py-4 px-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} SMAN 2 Situbondo. Panel Administrasi Sistem. All rights reserved.
        </footer>
    </div>

</div>
@endsection