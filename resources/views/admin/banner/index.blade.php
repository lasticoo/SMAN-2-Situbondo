@extends('layouts.admin')

@section('content')
@php
    $currentAdmin = Auth::guard('admin')->user();
@endphp

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
                <h2 class="text-lg font-bold text-slate-800">Manajemen Landing Page</h2>
                <p class="text-xs text-slate-500">Kelola banner slider landing page dan pop-up event</p>
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
                        <p class="text-xs font-bold text-slate-800">{{ $currentAdmin->name ?? 'Administrator SMAN 2 Situbondo' }}</p>
                        <p class="text-[11px] text-slate-500">Role: Admin</p>
                    </div>
                    <button
                        id="btn-tambah-baru"
                        onclick="document.getElementById('modal-pilih-tambah').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs ml-1"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Baru
                    </button>
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

            {{-- =====================================================================
                 FLASH MESSAGES
            ====================================================================== --}}
            @if(session('success'))
            <div id="flash-success" class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
                <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-600 hover:text-emerald-800 font-bold">✕</button>
            </div>
            @endif

            @if(session('error'))
            <div id="flash-error" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                </svg>
                {{ session('error') }}
                <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-red-600 hover:text-red-800 font-bold">✕</button>
            </div>
            @endif

            {{-- =====================================================================
                 MAIN CONTENT GRID: Banner Landing Page (kiri) + Pop-up Event (kanan)
            ====================================================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">

                {{-- ----------------------------------------------------------------
                     KOLOM KIRI — Banner Landing Page (3/5)
                ----------------------------------------------------------------- --}}
                <section class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-800 text-sm">Banner Landing Page</h2>
                                <p class="text-xs text-slate-500">Daftar banner slider yang ditampilkan di halaman utama</p>
                            </div>
                        </div>
                        <a
                            href="{{ route('admin.banners.create') }}"
                            class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Banner
                        </a>
                    </div>

                    {{-- Tabel banner --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Gambar</th>
                                    <th class="px-4 py-3 text-left">Judul &amp; Deskripsi</th>
                                    <th class="px-4 py-3 text-center">Urutan</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($banners as $banner)
                                <tr class="hover:bg-slate-50 transition-colors duration-100 group">
                                    {{-- Thumbnail --}}
                                    <td class="px-4 py-3">
                                        @if($banner->display_image_url)
                                        <img
                                            src="{{ $banner->display_image_url }}"
                                            alt="{{ $banner->title }}"
                                            class="w-20 h-12 object-cover rounded-lg border border-slate-200 bg-slate-100 shadow-xs"
                                        >
                                        @else
                                        <div class="w-20 h-12 bg-slate-100 rounded-lg border border-slate-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        @endif
                                    </td>

                                    {{-- Judul & Deskripsi --}}
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-slate-800 leading-tight text-xs">{{ $banner->title }}</p>
                                        @if($banner->description)
                                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-1 leading-relaxed">{{ $banner->description }}</p>
                                        @endif
                                    </td>

                                    {{-- Urutan --}}
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 bg-slate-100 text-slate-700 text-xs font-bold rounded-full">
                                            {{ $banner->sort_order }}
                                        </span>
                                    </td>

                                    {{-- Status toggle --}}
                                    <td class="px-4 py-3 text-center">
                                        <form
                                            id="toggle-banner-{{ $banner->id }}"
                                            method="POST"
                                            action="{{ route('admin.banners.toggleActive', $banner) }}"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-150 {{ $banner->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}"
                                                title="Klik untuk mengubah status"
                                            >
                                                <span class="w-1.5 h-1.5 rounded-full {{ $banner->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a
                                                href="{{ route('admin.banners.edit', $banner) }}"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                                                title="Edit"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            <button
                                                type="button"
                                                onclick="confirmDelete('{{ route('admin.banners.destroy', $banner) }}', 'Banner: {{ $banner->title }}')"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                                title="Hapus"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-sm font-medium">Belum ada banner terupload.</p>
                                        <a href="{{ route('admin.banners.create') }}" class="inline-block mt-2 text-xs font-bold text-indigo-600 hover:underline">+ Tambah Banner Pertama</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- ----------------------------------------------------------------
                     KOLOM KANAN — Pop-up Event (2/5)
                ----------------------------------------------------------------- --}}
                <section class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800 text-sm">Pop-up Event</h2>
                            <p class="text-xs text-slate-500">Kelola pop-up event promosi &amp; pengumuman</p>
                        </div>
                        <span class="ml-auto text-xs font-bold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full border border-amber-100">{{ $popups->count() }} popup</span>
                    </div>

                    <div class="p-4 flex flex-col gap-3 max-h-[600px] overflow-y-auto">
                        @forelse($popups as $popup)
                        @php
                            $now       = now()->toDateString();
                            $inPeriod  = (! $popup->start_date || $popup->start_date->toDateString() <= $now)
                                      && (! $popup->end_date   || $popup->end_date->toDateString()   >= $now);
                            $isLive    = $popup->is_active && $inPeriod;
                        @endphp
                        <div class="border border-slate-200 rounded-xl overflow-hidden bg-white hover:shadow-md transition-shadow duration-150">
                            <div class="px-4 pt-3 pb-1 flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm leading-tight">{{ $popup->title }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        @if($popup->start_date || $popup->end_date)
                                            {{ $popup->start_date ? $popup->start_date->format('d M Y') : '—' }}
                                            &rarr;
                                            {{ $popup->end_date ? $popup->end_date->format('d M Y') : '—' }}
                                        @else
                                            Tanpa periode
                                        @endif
                                    </p>
                                </div>
                                @if($isLive)
                                    <span class="flex-shrink-0 inline-flex items-center gap-1 text-xs font-semibold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Live
                                    </span>
                                @elseif($popup->is_active && !$inPeriod)
                                    <span class="flex-shrink-0 text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Terjadwal</span>
                                @else
                                    <span class="flex-shrink-0 text-xs font-semibold bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">Draft</span>
                                @endif
                            </div>

                            @if($popup->display_image_url)
                            <div class="mx-4 mb-2 rounded-lg overflow-hidden bg-slate-100 border border-slate-100">
                                <img
                                    src="{{ $popup->display_image_url }}"
                                    alt="{{ $popup->title }}"
                                    class="w-full h-28 object-cover"
                                >
                            </div>
                            @else
                            <div class="mx-4 mb-2 rounded-lg bg-slate-50 border border-dashed border-slate-200 h-20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif

                            <div class="px-4 pb-3 flex items-center justify-between">
                                <form method="POST" action="{{ route('admin.popups.toggleActive', $popup) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="text-xs font-semibold px-2.5 py-1 rounded-md transition-colors {{ $popup->is_active ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}"
                                    >
                                        {{ $popup->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <div class="flex items-center gap-1">
                                    <a
                                        href="{{ route('admin.popups.edit', $popup) }}"
                                        class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors"
                                        title="Edit"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button
                                        type="button"
                                        onclick="confirmDelete('{{ route('admin.popups.destroy', $popup) }}', '{{ $popup->title }}')"
                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                        title="Hapus"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center flex flex-col items-center gap-2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <p class="text-sm font-medium">Belum ada pop-up</p>
                        </div>
                        @endforelse

                        <a
                            href="{{ route('admin.popups.create') }}"
                            class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-slate-200 rounded-xl py-5 text-slate-400 hover:text-slate-600 hover:border-slate-400 hover:bg-slate-50 transition-colors duration-150 group"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-300 group-hover:text-slate-500 transition-colors duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="text-sm font-medium">Tambah Pop-up</span>
                        </a>
                    </div>
                </section>
            </div>

        </main>

        <!-- Page Footer -->
        <footer class="mt-auto bg-white border-t border-slate-200 py-4 px-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} SMAN 2 Situbondo. Panel Administrasi Sistem. All rights reserved.
        </footer>
    </div>

</div>

{{-- =========================================================================
     MODAL — Pilih Tambah Banner atau Pop-up Event
========================================================================== --}}
<div
    id="modal-pilih-tambah"
    class="hidden fixed inset-0 z-50 flex items-center justify-center"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-pilih-title"
>
    <div
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="document.getElementById('modal-pilih-tambah').classList.add('hidden')"
    ></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 animate-in fade-in zoom-in duration-200">
        <h3 id="modal-pilih-title" class="text-lg font-bold text-slate-800 mb-1">Tambah Konten Baru</h3>
        <p class="text-sm text-slate-500 mb-5">Pilih jenis konten yang ingin ditambahkan.</p>

        <div class="grid grid-cols-2 gap-3">
            <a
                href="{{ route('admin.banners.create') }}"
                class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-slate-200 hover:border-indigo-600 hover:bg-indigo-50/50 transition-all duration-150 group"
            >
                <div class="w-12 h-12 bg-indigo-50 group-hover:bg-indigo-100 rounded-xl flex items-center justify-center transition-colors duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-800">Banner</span>
            </a>

            <a
                href="{{ route('admin.popups.create') }}"
                class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-slate-200 hover:border-amber-600 hover:bg-amber-50/50 transition-all duration-150 group"
            >
                <div class="w-12 h-12 bg-amber-50 group-hover:bg-amber-100 rounded-xl flex items-center justify-center transition-colors duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-800">Pop-up Event</span>
            </a>
        </div>

        <button
            onclick="document.getElementById('modal-pilih-tambah').classList.add('hidden')"
            class="mt-4 w-full text-xs font-semibold text-slate-500 hover:text-slate-700 py-2 rounded-lg hover:bg-slate-50 transition-colors duration-150"
        >
            Batal
        </button>
    </div>
</div>

{{-- =========================================================================
     MODAL KONFIRMASI HAPUS
========================================================================== --}}
<div
    id="modal-hapus"
    class="hidden fixed inset-0 z-50 flex items-center justify-center"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-hapus-title"
>
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                </svg>
            </div>
            <div>
                <h3 id="modal-hapus-title" class="font-bold text-slate-800">Hapus Konten?</h3>
                <p class="text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        <p class="text-sm text-slate-600 mb-5">
            Yakin ingin menghapus <strong id="modal-hapus-nama" class="text-slate-800"></strong>?
            File gambar terkait juga akan dihapus permanen dari server.
        </p>
        <div class="flex gap-3">
            <button
                onclick="closeDeleteModal()"
                class="flex-1 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors duration-150"
            >
                Batal
            </button>
            <form id="form-hapus" method="POST">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="flex-1 py-2 px-6 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors duration-150"
                >
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(url, nama) {
    document.getElementById('form-hapus').action = url;
    document.getElementById('modal-hapus-nama').textContent = nama;
    document.getElementById('modal-hapus').classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('modal-hapus').classList.add('hidden');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('modal-hapus').classList.add('hidden');
        document.getElementById('modal-pilih-tambah').classList.add('hidden');
    }
});
</script>
@endsection
