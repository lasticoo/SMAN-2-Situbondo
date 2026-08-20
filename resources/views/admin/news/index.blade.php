@extends('layouts.admin')

@section('content')
@php
    $currentAdmin = Auth::guard('admin')->user();
@endphp

<div class="flex min-h-screen bg-slate-50 text-slate-800 font-sans">

    {{-- =====================================================================
         SIDEBAR NAVIGATION
    ====================================================================== --}}
    @include('partials.sidebar')

    {{-- =====================================================================
         MAIN CONTENT AREA
    ====================================================================== --}}
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">

        <!-- Top Header Bar -->
        <header class="h-16 shrink-0 bg-white border-b border-slate-200 px-6 md:px-8 flex items-center justify-between sticky top-0 z-20 shadow-xs">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Manajemen Berita</h2>
                <p class="text-xs text-slate-500">Kelola berita, liputan, dan artikel resmi portal SMAN 2 Situbondo.</p>
            </div>

            <!-- Action Button -->
            <div class="flex items-center gap-3">
                <button
                    onclick="openCreateModal()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Berita</span>
                </button>
            </div>
        </header>

        <!-- Main Body Canvas -->
        <main class="flex-1 p-6 md:p-8 space-y-6 overflow-y-auto">

            {{-- Flash Notification Alerts --}}
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1 shadow-xs">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Terdapat kesalahan pengisian form:</span>
                    </div>
                    <ul class="list-disc list-inside pl-2 space-y-0.5 text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Total Berita</p>
                        <h3 class="text-xl font-bold text-slate-800">{{ $stats['total'] ?? 0 }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Status Published</p>
                        <h3 class="text-xl font-bold text-slate-800">{{ $stats['published'] ?? 0 }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Status Draft</p>
                        <h3 class="text-xl font-bold text-slate-800">{{ $stats['draft'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            {{-- Filter and Search Control Bar --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <form action="{{ route('admin.news.index') }}" method="GET" class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3 items-center">
                    <!-- Search Input (5 Columns) -->
                    <div class="relative w-full sm:col-span-2 md:col-span-5">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari judul, ringkasan, atau konten berita..."
                            class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors placeholder:text-slate-400"
                        />
                    </div>

                    <!-- Category Filter (3 Columns) -->
                    <div class="w-full sm:col-span-1 md:col-span-3">
                        <select
                            name="category"
                            onchange="this.form.submit()"
                            class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        >
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter (2 Columns) -->
                    <div class="w-full sm:col-span-1 md:col-span-2">
                        <select
                            name="status"
                            onchange="this.form.submit()"
                            class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        >
                            <option value="">Semua Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <!-- Action Buttons (2 Columns) -->
                    <div class="w-full sm:col-span-2 md:col-span-2 flex items-center gap-2">
                        <button
                            type="submit"
                            class="flex-1 py-2 px-3 bg-slate-800 hover:bg-slate-900 active:bg-slate-950 text-white text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-2xs"
                            title="Terapkan Filter"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filter</span>
                        </button>

                        @if(request()->hasAny(['search', 'category', 'status']))
                            <a
                                href="{{ route('admin.news.index') }}"
                                class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl text-center transition-colors shrink-0"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table List of News --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[760px]">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3.5 px-5 w-6/12">Berita & Ringkasan</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Waktu Publikasi</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($news as $item)
                                @php
                                    $displayThumbnail = $item->thumbnail_url ? asset('storage/' . $item->thumbnail_url) : 'https://placehold.co/150x150/e2e8f0/475569?text=Berita';
                                    $pubDate = $item->published_at ? \Carbon\Carbon::parse($item->published_at) : null;
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors group">
                                    {{-- Title & Thumbnail & Content Preview --}}
                                    <td class="py-4 px-5">
                                        <div class="flex items-start gap-4">
                                            <img
                                                src="{{ $displayThumbnail }}"
                                                alt="{{ $item->title }}"
                                                class="w-16 h-16 rounded-xl object-cover border border-slate-200 shrink-0 bg-slate-100 mt-0.5"
                                            />
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-bold text-slate-900 text-sm leading-snug group-hover:text-indigo-600 transition-colors">
                                                    {{ $item->title }}
                                                </h4>
                                                <p class="text-slate-500 text-xs line-clamp-2 mt-1 leading-relaxed">
                                                    {{ $item->summary }}
                                                </p>
                                                <span class="text-[10px] text-slate-400 mt-1 block">
                                                    Oleh: {{ $item->author_name ?? 'Administrator' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kategori --}}
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/80">
                                            {{ $item->category ?? 'Umum' }}
                                        </span>
                                    </td>

                                    {{-- Waktu Publikasi --}}
                                    <td class="py-4 px-4 whitespace-nowrap text-slate-600">
                                        <div class="font-semibold text-slate-800">
                                            {{ $pubDate ? $pubDate->format('d M Y') : '-' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $pubDate ? $pubDate->format('H:i') . ' WIB' : '-' }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($item->status === 'published')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Draft
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button
                                                onclick="openEditModal({{ json_encode($item) }}, '{{ $displayThumbnail }}')"
                                                class="p-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 border border-transparent hover:border-indigo-100 transition-all"
                                                title="Edit Berita"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <button
                                                onclick="openDeleteModal('{{ route('admin.news.destroy', $item->id) }}', '{{ addslashes($item->title) }}')"
                                                class="p-2 rounded-xl text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all"
                                                title="Hapus Berita"
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
                                    <td colspan="5" class="py-12 text-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-500">Belum ada berita yang ditemukan.</p>
                                        <button onclick="openCreateModal()" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                                            Tambah Berita Baru
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($news->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $news->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>
</div>

{{-- =========================================================================
     MODAL CREATE BERITA
========================================================================== --}}
<div id="createModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all my-8">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Tambah Berita Baru</h3>
                <p class="text-[11px] text-slate-500">Semua field bertanda <span class="text-rose-500 font-bold">*</span> wajib diisi.</p>
            </div>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            {{-- Judul Berita --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Judul Berita <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    required
                    maxlength="200"
                    placeholder="Contoh: Tim Robotik SMAN 2 Situbondo Raih Juara 1 Tingkat Nasional"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select
                        name="category"
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    >
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $loop->first ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Status Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <select
                        name="status"
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    >
                        <option value="published" selected>Published (Langsung Tayang)</option>
                        <option value="draft">Draft (Simpan Sementara)</option>
                    </select>
                </div>
            </div>

            {{-- Waktu Publikasi & Thumbnail --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Waktu Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="datetime-local"
                        name="published_at"
                        required
                        value="{{ now()->format('Y-m-d\TH:i') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Thumbnail Berita (.webp / JPG / PNG) <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="file"
                        name="thumbnail"
                        accept="image/jpeg,image/png,image/webp"
                        required
                        class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors"
                    />
                </div>
            </div>

            {{-- Ringkasan --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Ringkasan Berita <span class="text-rose-500">*</span>
                </label>
                <textarea
                    name="summary"
                    rows="2"
                    required
                    maxlength="300"
                    placeholder="Tuliskan ringkasan singkat berita (maksimal 300 karakter)..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors resize-none"
                ></textarea>
            </div>

            {{-- Konten Berita --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Konten Berita Lengkap <span class="text-rose-500">*</span>
                </label>
                <textarea
                    name="content"
                    rows="5"
                    required
                    placeholder="Tuliskan seluruh rincian berita di sini..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors resize-y"
                ></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end gap-3">
                <button
                    type="button"
                    onclick="closeCreateModal()"
                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition-colors"
                >
                    Simpan Berita
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================================
     MODAL EDIT BERITA
========================================================================== --}}
<div id="editModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all my-8">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Edit Data Berita</h3>
                <p class="text-[11px] text-slate-500">Perbarui rincian berita di bawah ini.</p>
            </div>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- Judul Berita --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Judul Berita <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    id="edit_title"
                    name="title"
                    required
                    maxlength="200"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select
                        id="edit_category"
                        name="category"
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    >
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Status Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <select
                        id="edit_status"
                        name="status"
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    >
                        <option value="published">Published (Langsung Tayang)</option>
                        <option value="draft">Draft (Simpan Sementara)</option>
                    </select>
                </div>
            </div>

            {{-- Waktu Publikasi & Thumbnail --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Waktu Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="datetime-local"
                        id="edit_published_at"
                        name="published_at"
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Ganti Thumbnail (Opsional)
                    </label>
                    <input
                        type="file"
                        name="thumbnail"
                        accept="image/jpeg,image/png,image/webp"
                        class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors"
                    />
                    <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika ingin mempertahankan thumbnail lama.</p>
                </div>
            </div>

            {{-- Preview Thumbnail Saat Ini --}}
            <div id="edit_thumbnail_preview_wrapper" class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                <img id="edit_thumbnail_preview" src="" alt="Preview" class="w-12 h-12 rounded-lg object-cover border border-slate-200" />
                <div>
                    <p class="text-xs font-bold text-slate-700">Thumbnail Aktif Saat Ini</p>
                    <p class="text-[11px] text-slate-500">File tersimpan di sistem.</p>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Ringkasan Berita <span class="text-rose-500">*</span>
                </label>
                <textarea
                    id="edit_summary"
                    name="summary"
                    rows="2"
                    required
                    maxlength="300"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors resize-none"
                ></textarea>
            </div>

            {{-- Konten Berita --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Konten Berita Lengkap <span class="text-rose-500">*</span>
                </label>
                <textarea
                    id="edit_content"
                    name="content"
                    rows="5"
                    required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors resize-y"
                ></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end gap-3">
                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition-colors"
                >
                    Perbarui Berita
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================================
     MODAL DELETE CONFIRMATION
========================================================================== --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-100 p-6 text-center transform transition-all">
        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="text-base font-bold text-slate-900 mb-1">Konfirmasi Hapus Berita</h3>
        <p class="text-xs text-slate-500 mb-4">
            Apakah Anda yakin ingin menghapus berita <strong id="deleteTargetTitle" class="text-slate-800"></strong>? File thumbnail di penyimpanan juga akan dibersihkan.
        </p>

        <form id="deleteForm" method="POST" class="flex justify-center gap-3">
            @csrf
            @method('DELETE')

            <button
                type="button"
                onclick="closeDeleteModal()"
                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors"
            >
                Batal
            </button>
            <button
                type="submit"
                class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition-colors"
            >
                Hapus Sekarang
            </button>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(item, displayThumbnail) {
        const form = document.getElementById('editForm');
        form.action = `/admin/news/${item.id}`;

        document.getElementById('edit_title').value = item.title || '';
        document.getElementById('edit_category').value = item.category || 'Umum';
        document.getElementById('edit_status').value = item.status || 'published';
        document.getElementById('edit_summary').value = item.summary || '';
        document.getElementById('edit_content').value = item.content || '';

        if (item.published_at) {
            const dateObj = new Date(item.published_at);
            const isoStr = dateObj.toISOString().slice(0, 16);
            document.getElementById('edit_published_at').value = isoStr;
        }

        if (displayThumbnail) {
            document.getElementById('edit_thumbnail_preview').src = displayThumbnail;
            document.getElementById('edit_thumbnail_preview_wrapper').classList.remove('hidden');
        } else {
            document.getElementById('edit_thumbnail_preview_wrapper').classList.add('hidden');
        }

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function openDeleteModal(actionUrl, title) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteTargetTitle').innerText = title;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
