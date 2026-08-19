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
                <h2 class="text-lg font-bold text-slate-800">Manajemen Media</h2>
                <p class="text-xs text-slate-500">Kelola galeri foto dan tautan video YouTube.</p>
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

                    <a href="{{ url('/admin/videos') }}" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Tambah Video
                    </a>

                    <button
                        onclick="openCreateModal()"
                        class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Upload Foto
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Canvas -->
        <main class="flex-1 p-6 md:p-8 space-y-6 max-w-7xl">

            <!-- Success Alert Banner -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Global Error Summary Alert -->
            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-sm mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Terdapat kesalahan pengisian form. Silakan periksa kembali:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Navigation Tabs -->
            <div class="border-b border-slate-200 flex gap-6">
                <a href="{{ route('admin.galleries.index') }}" class="pb-3 border-b-2 border-indigo-600 font-bold text-sm text-indigo-600 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Galeri Foto
                </a>
                <a href="{{ url('/admin/videos') }}" class="pb-3 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Video YouTube
                </a>
            </div>

            <!-- Content Card (Grid Galeri) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                
                <!-- Toolbar & Filter -->
                <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Daftar Foto Galeri
                    </h3>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <!-- Search Form -->
                        <form action="{{ route('admin.galleries.index') }}" method="GET" class="relative flex-1 sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari kegiatan..."
                                class="w-full h-9 pl-9 pr-3 rounded-xl border border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 bg-white text-slate-700 placeholder-slate-400 shadow-xs"
                            />
                        </form>

                        <span class="text-xs text-slate-500 font-medium">
                            Total: <span class="font-bold text-slate-800">{{ $galleries->total() }}</span> Foto
                        </span>
                    </div>
                </div>

                <!-- Grid Photo Cards -->
                <div class="p-6">
                    @if($galleries->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($galleries as $gallery)
                                <div class="group relative bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs hover:shadow-md hover:border-indigo-200 transition-all duration-200 flex flex-col">
                                    <!-- Photo Thumbnail Container -->
                                    <div class="aspect-video relative overflow-hidden bg-slate-100 shrink-0">
                                        @if($gallery->photo_url)
                                            <img
                                                src="{{ $gallery->display_photo_url }}"
                                                alt="{{ $gallery->activity_name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Hover Action Overlay -->
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-xs">
                                            <!-- Preview Button -->
                                            <button
                                                onclick="openPreviewModal('{{ $gallery->display_photo_url }}', '{{ addslashes($gallery->activity_name) }}')"
                                                class="w-8 h-8 rounded-full bg-white text-indigo-600 flex items-center justify-center hover:bg-indigo-50 transition-colors shadow-sm"
                                                title="Lihat Foto Full"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            <!-- Edit Button -->
                                            <button
                                                onclick="openEditModal({{ json_encode($gallery) }})"
                                                class="w-8 h-8 rounded-full bg-white text-slate-700 flex items-center justify-center hover:bg-slate-100 transition-colors shadow-sm"
                                                title="Edit Data Galeri"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <!-- Delete Button -->
                                            <button
                                                onclick="openDeleteModal('{{ route('admin.galleries.destroy', $gallery) }}', '{{ addslashes($gallery->activity_name) }}')"
                                                class="w-8 h-8 rounded-full bg-white text-rose-600 flex items-center justify-center hover:bg-rose-50 transition-colors shadow-sm"
                                                title="Hapus Galeri"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Card Info -->
                                    <div class="p-3.5 flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-start justify-between gap-2 mb-1">
                                                <h4 class="font-bold text-slate-800 text-xs line-clamp-1" title="{{ $gallery->activity_name }}">
                                                    {{ $gallery->activity_name }}
                                                </h4>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold shrink-0 border border-indigo-100">
                                                    #{{ $gallery->sort_order }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $gallery->activity_date ? $gallery->activity_date->format('d M Y') : '-' }}
                                            </p>
                                        </div>

                                        @if($gallery->creator)
                                            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                                                <span>Oleh: {{ $gallery->creator->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs font-semibold text-slate-500">Belum ada foto galeri yang diunggah.</p>
                            <button onclick="openCreateModal()" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                                Upload Foto Sekarang
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Custom Pagination Bar -->
                <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 font-medium">
                        Menampilkan <span class="font-bold text-slate-800">{{ $galleries->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $galleries->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $galleries->total() }}</span> foto
                    </p>

                    @if($galleries->hasPages())
                        @php
                            $currentPage  = $galleries->currentPage();
                            $lastPage     = $galleries->lastPage();
                            $chunkSize    = 4;

                            $currentBlock = (int) ceil($currentPage / $chunkSize);
                            $startPage    = ($currentBlock - 1) * $chunkSize + 1;
                            $endPage      = min($lastPage, $currentBlock * $chunkSize);

                            $prevBlockPage = max(1, $startPage - $chunkSize);
                            $nextBlockPage = min($lastPage, $endPage + 1);

                            $hasPrevBlock  = $startPage > 1;
                            $hasNextBlock  = $endPage < $lastPage;
                        @endphp

                        <nav class="flex items-center gap-1.5" aria-label="Pagination">
                            @if ($hasPrevBlock)
                                <a href="{{ $galleries->url($prevBlockPage) }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-xs font-semibold flex items-center justify-center transition-all shadow-xs" title="Blok Halaman Sebelumnya">
                                    ‹
                                </a>
                            @else
                                <span class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 text-slate-300 text-xs font-semibold flex items-center justify-center cursor-not-allowed">
                                    ‹
                                </span>
                            @endif

                            @foreach ($galleries->getUrlRange($startPage, $endPage) as $page => $url)
                                @if ($page == $currentPage)
                                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shadow-xs">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-xs font-semibold flex items-center justify-center transition-all shadow-xs">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($hasNextBlock)
                                <a href="{{ $galleries->url($nextBlockPage) }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-xs font-semibold flex items-center justify-center transition-all shadow-xs" title="Blok Halaman Selanjutnya">
                                    ›
                                </a>
                            @else
                                <span class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 text-slate-300 text-xs font-semibold flex items-center justify-center cursor-not-allowed">
                                    ›
                                </span>
                            @endif
                        </nav>
                    @endif
                </div>

            </div>

        </main>
    </div>
</div>

{{-- =====================================================================
     MODAL TAMBAH GALERI (UPLOAD FOTO)
====================================================================== --}}
<div id="modal-create" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Upload Foto Galeri Baru
            </h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <!-- Upload Photo Dropzone -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Foto Kegiatan <span class="text-rose-500">*</span></label>
                <label for="create_photo" class="flex flex-col items-center justify-center p-5 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-xs font-semibold text-slate-700">Pilih foto galeri</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">JPG, PNG, WebP (Maks 2MB, Otomatis dikonversi ke WebP)</span>
                    <input type="file" id="create_photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" required class="hidden" onchange="previewCreatePhoto(this)">
                </label>
                <div id="create_photo_display" class="hidden mt-2 p-2.5 rounded-xl bg-indigo-50 border border-indigo-100 text-xs text-indigo-700 font-semibold truncate flex items-center justify-between">
                    <span id="create_photo_filename"></span>
                    <span class="text-[10px] text-indigo-500">Siap diunggah</span>
                </div>
            </div>

            <!-- Nama Kegiatan -->
            <div>
                <label for="create_activity_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                <input type="text" id="create_activity_name" name="activity_name" required maxlength="150" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Contoh: Upacara Hari Kemerdekaan">
            </div>

            <!-- Tanggal Kegiatan -->
            <div>
                <label for="create_activity_date" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Kegiatan <span class="text-rose-500">*</span></label>
                <input type="date" id="create_activity_date" name="activity_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
            </div>

            <!-- Urutan Tampil (Sort Order) -->
            <div>
                <label for="create_sort_order" class="block text-xs font-semibold text-slate-700 mb-1">Urutan Tampil (Sort Order)</label>
                <input type="number" id="create_sort_order" name="sort_order" min="0" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Kosongkan untuk otomatis urutan terakhir">
                <p class="text-[10px] text-slate-400 mt-1">Jika dikosongkan, urutan akan terisi otomatis (urutan terakhir + 1).</p>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-xs">
                    Simpan & Kompres Foto
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =====================================================================
     MODAL EDIT GALERI
====================================================================== --}}
<div id="modal-edit" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Data Galeri Foto
            </h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form id="form-edit" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- Preview Foto Saat Ini & Upload Foto Baru -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Foto Galeri</label>
                <div id="edit_photo_current" class="mb-2 hidden flex items-center gap-3 p-2 rounded-xl bg-slate-50 border border-slate-200">
                    <img id="edit_photo_preview" src="" alt="Foto Galeri" class="w-16 h-12 rounded-lg object-cover border border-slate-200 shadow-xs">
                    <div>
                        <p class="text-xs font-semibold text-slate-700">Foto saat ini</p>
                        <p class="text-[10px] text-slate-400">Pilih foto baru jika ingin mengganti</p>
                    </div>
                </div>

                <label for="edit_photo" class="flex flex-col items-center justify-center p-3 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center">
                    <span class="text-xs font-semibold text-slate-700">Pilih foto baru (Opsional)</span>
                    <span class="text-[10px] text-slate-400">JPG, PNG, WebP (Maks 2MB)</span>
                    <input type="file" id="edit_photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewEditPhoto(this)">
                </label>
                <div id="edit_photo_display" class="hidden mt-2 p-2 rounded-lg bg-indigo-50 text-[11px] text-indigo-700 font-semibold truncate"></div>
            </div>

            <!-- Nama Kegiatan -->
            <div>
                <label for="edit_activity_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_activity_name" name="activity_name" required maxlength="150" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
            </div>

            <!-- Tanggal Kegiatan -->
            <div>
                <label for="edit_activity_date" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Kegiatan <span class="text-rose-500">*</span></label>
                <input type="date" id="edit_activity_date" name="activity_date" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
            </div>

            <!-- Urutan Tampil -->
            <div>
                <label for="edit_sort_order" class="block text-xs font-semibold text-slate-700 mb-1">Urutan Tampil (Sort Order) <span class="text-rose-500">*</span></label>
                <input type="number" id="edit_sort_order" name="sort_order" required min="0" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-xs">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =====================================================================
     MODAL HAPUS GALERI
====================================================================== --}}
<div id="modal-delete" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-sm w-full overflow-hidden p-6 text-center animate-in fade-in zoom-in duration-200">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="font-bold text-slate-800 text-sm mb-1">Konfirmasi Hapus Foto Galeri</h3>
        <p class="text-xs text-slate-500 mb-6">
            Apakah Anda yakin ingin menghapus foto galeri <strong id="delete_activity_name" class="text-slate-800"></strong>? File foto WebP dan catatan kompresi akan dihapus permanen dari storage.
        </p>

        <form id="form-delete" method="POST" class="flex justify-center gap-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Batal
            </button>
            <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-all shadow-xs">
                Ya, Hapus Galeri
            </button>
        </form>
    </div>
</div>

{{-- =====================================================================
     MODAL PREVIEW FOTO FULL
====================================================================== --}}
<div id="modal-preview" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 hidden" onclick="closePreviewModal()">
    <div class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center justify-center" onclick="event.stopPropagation()">
        <button onclick="closePreviewModal()" class="absolute -top-10 right-0 text-white hover:text-slate-300 font-bold text-lg">✕ Tutup</button>
        <img id="preview_image_full" src="" alt="Preview Foto Full" class="max-w-full max-h-[80vh] rounded-xl object-contain shadow-2xl border border-slate-800">
        <p id="preview_image_title" class="text-white text-sm font-semibold mt-3 text-center bg-slate-900/60 px-4 py-1.5 rounded-full border border-slate-700"></p>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modal-create').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('modal-create').classList.add('hidden');
    }

    function openEditModal(gallery) {
        const form = document.getElementById('form-edit');
        form.action = `/admin/galleries/${gallery.id}`;

        document.getElementById('edit_activity_name').value = gallery.activity_name;
        
        if (gallery.activity_date) {
            const dateObj = new Date(gallery.activity_date);
            const formattedDate = dateObj.toISOString().split('T')[0];
            document.getElementById('edit_activity_date').value = formattedDate;
        }

        document.getElementById('edit_sort_order').value = gallery.sort_order;

        if (gallery.photo_url) {
            const previewImg = document.getElementById('edit_photo_preview');
            const displayUrl = gallery.photo_url.startsWith('http') || gallery.photo_url.startsWith('/storage') || gallery.photo_url.startsWith('storage')
                ? gallery.photo_url
                : `/storage/${gallery.photo_url}`;
            previewImg.src = displayUrl;
            document.getElementById('edit_photo_current').classList.remove('hidden');
        } else {
            document.getElementById('edit_photo_current').classList.add('hidden');
        }

        document.getElementById('modal-edit').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modal-edit').classList.add('hidden');
    }

    function openDeleteModal(actionUrl, activityName) {
        document.getElementById('form-delete').action = actionUrl;
        document.getElementById('delete_activity_name').textContent = activityName;
        document.getElementById('modal-delete').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('modal-delete').classList.add('hidden');
    }

    function openPreviewModal(photoUrl, title) {
        document.getElementById('preview_image_full').src = photoUrl;
        document.getElementById('preview_image_title').textContent = title;
        document.getElementById('modal-preview').classList.remove('hidden');
    }

    function closePreviewModal() {
        document.getElementById('modal-preview').classList.add('hidden');
    }

    function previewCreatePhoto(input) {
        if (input.files && input.files[0]) {
            const filename = input.files[0].name;
            document.getElementById('create_photo_filename').textContent = filename;
            document.getElementById('create_photo_display').classList.remove('hidden');
        }
    }

    function previewEditPhoto(input) {
        if (input.files && input.files[0]) {
            const display = document.getElementById('edit_photo_display');
            display.textContent = 'Foto baru terpilih: ' + input.files[0].name;
            display.classList.remove('hidden');
        }
    }
</script>
@endsection
