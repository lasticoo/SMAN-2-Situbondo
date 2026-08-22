@extends('layouts.admin')

@section('content')
@php
    $currentAdmin = Auth::guard('admin')->user();
@endphp

<div class="flex min-h-screen bg-slate-50 text-slate-800 font-sans">

    {{-- SIDEBAR NAVIGATION --}}
    @include('partials.sidebar')

    {{-- MAIN CONTENT AREA --}}
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">

        <!-- Top Header Bar -->
        <header class="h-16 shrink-0 bg-white border-b border-slate-200 px-6 md:px-8 flex items-center justify-between sticky top-0 z-20 shadow-xs">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Manajemen Paket & Informasi SPMB</h2>
                <p class="text-xs text-slate-500">Kelola jalur pendaftaran, banner, jadwal, persyaratan, dan dokumen SPMB.</p>
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
                    <button
                        onclick="openCreateModal()"
                        class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs ml-1"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Paket SPMB
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

        <!-- Main Content -->
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

            <!-- Grid List Paket SPMB -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($packages as $package)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col transition-all hover:shadow-md">
                        <!-- Banner Image Header -->
                        <div class="relative h-44 bg-slate-100 border-b border-slate-100 overflow-hidden">
                            @if($package->banner_url)
                                <img src="{{ Storage::url($package->banner_url) }}" alt="Banner SPMB" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                    <span class="text-xs font-semibold">Tidak ada banner</span>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-xs text-indigo-700 border border-indigo-200 px-2.5 py-1 rounded-full text-[11px] font-bold flex items-center gap-1 shadow-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ $package->documents_count }} Dokumen
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <!-- Periode Badge -->
                                <div class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-semibold text-slate-700">
                                        {{ \Carbon\Carbon::parse($package->period_start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($package->period_end)->format('d M Y') }}
                                    </span>
                                </div>

                                <!-- Informasi Jadwal Snippet -->
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Informasi Jadwal</p>
                                    <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed font-medium">
                                        {{ $package->schedule_info }}
                                    </p>
                                </div>

                                <!-- Informasi Persyaratan Snippet -->
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Persyaratan</p>
                                    <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed font-medium">
                                        {{ $package->requirements_info }}
                                    </p>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                <a
                                    href="{{ route('admin.spmb_info.documents.index', $package->id) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition-all shadow-xs"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                    </svg>
                                    Kelola Dokumen
                                </a>

                                <div class="flex items-center gap-1">
                                    <button
                                        onclick="openEditModal({{ json_encode($package) }})"
                                        class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors border border-slate-200 hover:border-indigo-200"
                                        title="Edit Paket SPMB"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <button
                                        onclick="openDeleteModal('{{ route('admin.spmb_info.destroy', $package->id) }}', '{{ \Carbon\Carbon::parse($package->period_start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($package->period_end)->format('d M Y') }}')"
                                        class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors border border-slate-200 hover:border-rose-200"
                                        title="Hapus Paket SPMB"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200 p-8 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-sm font-bold text-slate-700 mb-1">Belum Ada Paket SPMB</h3>
                        <p class="text-xs text-slate-500 mb-4">Tambahkan jalur/paket SPMB baru untuk menampilkan informasi pendaftaran ke calon murid.</p>
                        <button onclick="openCreateModal()" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Paket Pertama
                        </button>
                    </div>
                @endforelse
            </div>

        </main>
    </div>
</div>

{{-- MODAL TAMBAH PAKET SPMB --}}
<div id="modal-create" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-xl w-full overflow-hidden animate-in fade-in zoom-in duration-200 max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Paket / Jalur SPMB Baru
            </h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form action="{{ route('admin.spmb_info.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 overflow-y-auto">
            @csrf

            <!-- Upload Banner -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Banner SPMB (Wajib, Kompresi WebP) <span class="text-rose-500">*</span></label>
                <label for="create_banner_url" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-xs font-semibold text-slate-700">Pilih gambar banner</span>
                    <span class="text-[10px] text-slate-400">JPG, PNG, WebP (Maks 2MB)</span>
                    <input type="file" id="create_banner_url" name="banner_url" accept="image/jpeg,image/png,image/jpg,image/webp" required class="hidden" onchange="previewCreateBanner(this)">
                </label>
                <div id="create_banner_display" class="hidden mt-2 p-2 rounded-lg bg-indigo-50 text-[11px] text-indigo-700 font-semibold truncate"></div>
            </div>

            <!-- Tanggal Periode Mulai & Selesai -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="create_period_start" class="block text-xs font-semibold text-slate-700 mb-1">Periode Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" id="create_period_start" name="period_start" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
                </div>
                <div>
                    <label for="create_period_end" class="block text-xs font-semibold text-slate-700 mb-1">Periode Berakhir <span class="text-rose-500">*</span></label>
                    <input type="date" id="create_period_end" name="period_end" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
                </div>
            </div>

            <!-- Informasi Jadwal -->
            <div>
                <label for="create_schedule_info" class="block text-xs font-semibold text-slate-700 mb-1">Informasi Jadwal <span class="text-rose-500">*</span></label>
                <textarea id="create_schedule_info" name="schedule_info" rows="3" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Contoh: Pendaftaran dibuka tanggal 1 - 15 Mei 2026 via online. Seleksi berkas 16-18 Mei."></textarea>
            </div>

            <!-- Informasi Persyaratan -->
            <div>
                <label for="create_requirements_info" class="block text-xs font-semibold text-slate-700 mb-1">Informasi Persyaratan <span class="text-rose-500">*</span></label>
                <textarea id="create_requirements_info" name="requirements_info" rows="3" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Contoh: 1. Ijazah/SKL SMP Sederajat, 2. Kartu Keluarga, 3. Pas foto 3x4 berwarna (2 lembar)."></textarea>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100 shrink-0">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-xs">
                    Simpan Paket SPMB
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT PAKET SPMB --}}
<div id="modal-edit" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-xl w-full overflow-hidden animate-in fade-in zoom-in duration-200 max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Paket / Jalur SPMB
            </h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form id="form-edit" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 overflow-y-auto">
            @csrf
            @method('PUT')

            <!-- Upload Banner -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Ganti Banner SPMB (Opsional)</label>
                <div id="edit_banner_current" class="mb-2 hidden">
                    <p class="text-[10px] text-slate-500 font-medium mb-1">Banner saat ini:</p>
                    <img id="edit_banner_preview" src="" alt="Banner SPMB" class="w-full h-24 object-cover rounded-xl border border-slate-200 shadow-xs">
                </div>

                <label for="edit_banner_url" class="flex flex-col items-center justify-center p-3 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center">
                    <span class="text-xs font-semibold text-slate-700">Pilih banner baru</span>
                    <span class="text-[10px] text-slate-400">JPG, PNG, WebP (Maks 2MB)</span>
                    <input type="file" id="edit_banner_url" name="banner_url" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewEditBanner(this)">
                </label>
                <div id="edit_banner_display" class="hidden mt-2 p-2 rounded-lg bg-indigo-50 text-[11px] text-indigo-700 font-semibold truncate"></div>
            </div>

            <!-- Tanggal Periode Mulai & Selesai -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit_period_start" class="block text-xs font-semibold text-slate-700 mb-1">Periode Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" id="edit_period_start" name="period_start" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
                </div>
                <div>
                    <label for="edit_period_end" class="block text-xs font-semibold text-slate-700 mb-1">Periode Berakhir <span class="text-rose-500">*</span></label>
                    <input type="date" id="edit_period_end" name="period_end" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
                </div>
            </div>

            <!-- Informasi Jadwal -->
            <div>
                <label for="edit_schedule_info" class="block text-xs font-semibold text-slate-700 mb-1">Informasi Jadwal <span class="text-rose-500">*</span></label>
                <textarea id="edit_schedule_info" name="schedule_info" rows="3" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5"></textarea>
            </div>

            <!-- Informasi Persyaratan -->
            <div>
                <label for="edit_requirements_info" class="block text-xs font-semibold text-slate-700 mb-1">Informasi Persyaratan <span class="text-rose-500">*</span></label>
                <textarea id="edit_requirements_info" name="requirements_info" rows="3" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5"></textarea>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100 shrink-0">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-xs">
                    Perbarui Paket SPMB
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HAPUS PAKET SPMB --}}
<div id="modal-delete" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-sm w-full overflow-hidden p-6 text-center animate-in fade-in zoom-in duration-200">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="font-bold text-slate-800 text-sm mb-1">Konfirmasi Hapus Paket SPMB</h3>
        <p class="text-xs text-slate-500 mb-3">
            Apakah Anda yakin ingin menghapus Paket SPMB periode <strong id="delete_package_period" class="text-slate-800"></strong>?
        </p>

        <!-- Warning Card -->
        <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-left mb-6 text-[11px] text-rose-700 flex items-start gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><strong>Peringatan Hapus Berantai:</strong> Seluruh dokumen yang menempel pada paket ini beserta file fisiknya di storage akan ikut terhapus secara permanen.</span>
        </div>

        <form id="form-delete" method="POST" class="flex justify-center gap-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Batal
            </button>
            <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-all shadow-xs">
                Ya, Hapus Semua Data
            </button>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modal-create').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('modal-create').classList.add('hidden');
    }

    function openEditModal(package) {
        const form = document.getElementById('form-edit');
        form.action = `/admin/spmb-info/${package.id}`;

        document.getElementById('edit_period_start').value = package.period_start;
        document.getElementById('edit_period_end').value = package.period_end;
        document.getElementById('edit_schedule_info').value = package.schedule_info;
        document.getElementById('edit_requirements_info').value = package.requirements_info;

        const bannerContainer = document.getElementById('edit_banner_current');
        const bannerPreview = document.getElementById('edit_banner_preview');
        if (package.banner_url) {
            bannerPreview.src = `/storage/${package.banner_url}`;
            bannerContainer.classList.remove('hidden');
        } else {
            bannerContainer.classList.add('hidden');
        }

        document.getElementById('modal-edit').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modal-edit').classList.add('hidden');
    }

    function openDeleteModal(actionUrl, periodText) {
        document.getElementById('form-delete').action = actionUrl;
        document.getElementById('delete_package_period').textContent = periodText;
        document.getElementById('modal-delete').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('modal-delete').classList.add('hidden');
    }

    function previewCreateBanner(input) {
        const display = document.getElementById('create_banner_display');
        if (input.files && input.files[0]) {
            display.textContent = `File terpilih: ${input.files[0].name}`;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }

    function previewEditBanner(input) {
        const display = document.getElementById('edit_banner_display');
        if (input.files && input.files[0]) {
            display.textContent = `Banner baru terpilih: ${input.files[0].name}`;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }
</script>
@endsection
