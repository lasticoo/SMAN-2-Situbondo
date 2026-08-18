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
                <h2 class="text-lg font-bold text-slate-800">Manajemen Pegawai</h2>
                <p class="text-xs text-slate-500">Kelola data staf dan tenaga kependidikan SMAN 2 Situbondo.</p>
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
                        onclick="openCreateModal()"
                        class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs ml-1"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Pegawai
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

            <!-- Data Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">

                <!-- Toolbar (Search & Filter) -->
                <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <form action="{{ route('admin.employees.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto flex-1 max-w-xl">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari nama, NIP, atau jabatan..."
                                class="w-full h-10 pl-10 pr-4 rounded-xl border border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 bg-white placeholder-slate-400 text-slate-700 shadow-xs"
                            />
                        </div>

                        <select
                            name="position_filter"
                            onchange="this.form.submit()"
                            class="h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 bg-white text-slate-700 font-medium shadow-xs"
                        >
                            <option value="all" {{ request('position_filter') == 'all' || !request('position_filter') ? 'selected' : '' }}>Semua Jabatan</option>
                            <option value="Guru" {{ request('position_filter') == 'Guru' ? 'selected' : '' }}>Guru & Pengajar</option>
                            <option value="Kepala Sekolah" {{ request('position_filter') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                            <option value="Staf Administrasi" {{ request('position_filter') == 'Staf Administrasi' ? 'selected' : '' }}>Staf Administrasi</option>
                        </select>

                        @if(request('search') || (request('position_filter') && request('position_filter') !== 'all'))
                            <a href="{{ route('admin.employees.index') }}" title="Reset Filter" class="h-10 w-10 shrink-0 flex items-center justify-center text-slate-500 hover:text-rose-600 bg-white hover:bg-rose-50 rounded-xl transition-colors border border-slate-200 hover:border-rose-200 shadow-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        @endif
                    </form>

                    <div class="text-xs text-slate-500 font-medium self-end sm:self-auto">
                        Total: <span class="font-bold text-slate-800">{{ $employees->total() }}</span> Pegawai
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200">
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Foto</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama & Catatan</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">NIP</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jabatan</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($employees as $employee)
                                @php
                                    $initials = collect(explode(' ', $employee->name))
                                        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                        ->take(2)
                                        ->implode('');
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <!-- Foto -->
                                    <td class="py-4 px-6">
                                        @if($employee->photo_url)
                                            <img src="{{ Storage::url($employee->photo_url) }}" alt="{{ $employee->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shadow-xs">
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center border border-indigo-200">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Nama & Extra Info -->
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-800 text-xs">{{ $employee->name }}</div>
                                        @if($employee->extra_info)
                                            <div class="text-[11px] text-slate-500 truncate max-w-xs" title="{{ $employee->extra_info }}">
                                                {{ $employee->extra_info }}
                                            </div>
                                        @else
                                            <div class="text-[11px] text-slate-400 italic">-</div>
                                        @endif
                                    </td>

                                    <!-- NIP -->
                                    <td class="py-4 px-6">
                                        <span class="font-mono text-xs text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                            {{ $employee->nip }}
                                        </span>
                                    </td>

                                    <!-- Jabatan -->
                                    <td class="py-4 px-6">
                                        <div class="text-xs font-semibold text-slate-800">{{ $employee->position }}</div>
                                    </td>

                                    <!-- Status (Toggle Switch) -->
                                    <td class="py-4 px-6">
                                        <form action="{{ route('admin.employees.toggleActive', $employee) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition-all border {{ $employee->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}" title="Klik untuk mengubah status">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $employee->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button
                                                onclick="openEditModal({{ json_encode($employee) }})"
                                                class="p-1.5 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                title="Edit Pegawai"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <button
                                                onclick="openDeleteModal('{{ route('admin.employees.destroy', $employee) }}', '{{ $employee->name }}')"
                                                class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Hapus Pegawai"
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
                                    <td colspan="6" class="py-12 text-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-500">Tidak ada data pegawai yang ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Styled Pagination Bar -->
                <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 font-medium">
                        Menampilkan <span class="font-bold text-slate-800">{{ $employees->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $employees->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $employees->total() }}</span> pegawai
                    </p>

                    @if($employees->hasPages())
                        @php
                            $currentPage  = $employees->currentPage();
                            $lastPage     = $employees->lastPage();
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
                            {{-- Previous Block Link (<) --}}
                            @if ($hasPrevBlock)
                                <a href="{{ $employees->url($prevBlockPage) }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-xs font-semibold flex items-center justify-center transition-all shadow-xs" title="Blok Halaman Sebelumnya">
                                    ‹
                                </a>
                            @else
                                <span class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 text-slate-300 text-xs font-semibold flex items-center justify-center cursor-not-allowed">
                                    ‹
                                </span>
                            @endif

                            {{-- Page Buttons (Blok 4 Halaman) --}}
                            @foreach ($employees->getUrlRange($startPage, $endPage) as $page => $url)
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

                            {{-- Next Block Link (>) --}}
                            @if ($hasNextBlock)
                                <a href="{{ $employees->url($nextBlockPage) }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-xs font-semibold flex items-center justify-center transition-all shadow-xs" title="Blok Halaman Selanjutnya">
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
     MODAL TAMBAH PEGAWAI
====================================================================== --}}
<div id="modal-create" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Tambah Data Pegawai
            </h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <!-- Nama -->
            <div>
                <label for="create_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                <input type="text" id="create_name" name="name" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Contoh: Siti Aminah, S.Pd">
            </div>

            <!-- NIP -->
            <div>
                <label for="create_nip" class="block text-xs font-semibold text-slate-700 mb-1">NIP (Nomor Pokok Pegawai) <span class="text-rose-500">*</span></label>
                <input type="text" id="create_nip" name="nip" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5 font-mono" placeholder="Contoh: 198507232010012004">
            </div>

            <!-- Position -->
            <div>
                <label for="create_position" class="block text-xs font-semibold text-slate-700 mb-1">Jabatan / Posisi <span class="text-rose-500">*</span></label>
                <input type="text" id="create_position" name="position" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Contoh: Guru Matematika / Staf Administrasi">
            </div>

            <!-- Extra Info -->
            <div>
                <label for="create_extra_info" class="block text-xs font-semibold text-slate-700 mb-1">Informasi Tambahan / Kontak (Opsional)</label>
                <textarea id="create_extra_info" name="extra_info" rows="2" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Contoh: email@sekolah.id atau Wali Kelas X-1"></textarea>
            </div>

            <!-- Upload Photo Dropzone -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Foto Pegawai (Opsional, Kompresi WebP)</label>
                <label for="create_photo" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-xs font-semibold text-slate-700">Pilih foto pegawai</span>
                    <span class="text-[10px] text-slate-400">JPG, PNG, WebP (Maks 2MB)</span>
                    <input type="file" id="create_photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewCreatePhoto(this)">
                </label>
                <div id="create_photo_display" class="hidden mt-2 p-2 rounded-lg bg-indigo-50 text-[11px] text-indigo-700 font-semibold truncate"></div>
            </div>

            <!-- Is Active Switch -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="create_is_active" name="is_active" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                <label for="create_is_active" class="text-xs font-semibold text-slate-700">Status Aktif (Tampilkan di Website User)</label>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-xs">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =====================================================================
     MODAL EDIT PEGAWAI
====================================================================== --}}
<div id="modal-edit" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Data Pegawai
            </h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form id="form-edit" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label for="edit_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_name" name="name" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
            </div>

            <!-- NIP -->
            <div>
                <label for="edit_nip" class="block text-xs font-semibold text-slate-700 mb-1">NIP (Nomor Pokok Pegawai) <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_nip" name="nip" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5 font-mono">
            </div>

            <!-- Position -->
            <div>
                <label for="edit_position" class="block text-xs font-semibold text-slate-700 mb-1">Jabatan / Posisi <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_position" name="position" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
            </div>

            <!-- Extra Info -->
            <div>
                <label for="edit_extra_info" class="block text-xs font-semibold text-slate-700 mb-1">Informasi Tambahan / Kontak (Opsional)</label>
                <textarea id="edit_extra_info" name="extra_info" rows="2" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5"></textarea>
            </div>

            <!-- Upload Photo Dropzone -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Ganti Foto (Opsional)</label>
                <div id="edit_photo_current" class="mb-2 hidden">
                    <p class="text-[10px] text-slate-500 font-medium mb-1">Foto saat ini:</p>
                    <img id="edit_photo_preview" src="" alt="Foto Pegawai" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-xs">
                </div>

                <label for="edit_photo" class="flex flex-col items-center justify-center p-3 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center">
                    <span class="text-xs font-semibold text-slate-700">Pilih foto baru</span>
                    <span class="text-[10px] text-slate-400">JPG, PNG, WebP (Maks 2MB)</span>
                    <input type="file" id="edit_photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewEditPhoto(this)">
                </label>
                <div id="edit_photo_display" class="hidden mt-2 p-2 rounded-lg bg-indigo-50 text-[11px] text-indigo-700 font-semibold truncate"></div>
            </div>

            <!-- Is Active Switch -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                <label for="edit_is_active" class="text-xs font-semibold text-slate-700">Status Aktif (Tampilkan di Website User)</label>
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
     MODAL HAPUS PEGAWAI
====================================================================== --}}
<div id="modal-delete" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-sm w-full overflow-hidden p-6 text-center animate-in fade-in zoom-in duration-200">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="font-bold text-slate-800 text-sm mb-1">Konfirmasi Hapus Pegawai</h3>
        <p class="text-xs text-slate-500 mb-6">
            Apakah Anda yakin ingin menghapus data pegawai <strong id="delete_employee_name" class="text-slate-800"></strong>? Tindakan ini akan menghapus foto dan data permanen.
        </p>

        <form id="form-delete" method="POST" class="flex justify-center gap-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Batal
            </button>
            <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-all shadow-xs">
                Ya, Hapus Data
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

    function openEditModal(employee) {
        const form = document.getElementById('form-edit');
        form.action = `/admin/employees/${employee.id}`;

        document.getElementById('edit_name').value = employee.name;
        document.getElementById('edit_nip').value = employee.nip;
        document.getElementById('edit_position').value = employee.position;
        document.getElementById('edit_extra_info').value = employee.extra_info || '';
        document.getElementById('edit_is_active').checked = Boolean(employee.is_active);

        const photoContainer = document.getElementById('edit_photo_current');
        const photoPreview = document.getElementById('edit_photo_preview');
        if (employee.photo_url) {
            photoPreview.src = `/storage/${employee.photo_url}`;
            photoContainer.classList.remove('hidden');
        } else {
            photoContainer.classList.add('hidden');
        }

        document.getElementById('modal-edit').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modal-edit').classList.add('hidden');
    }

    function openDeleteModal(actionUrl, name) {
        document.getElementById('form-delete').action = actionUrl;
        document.getElementById('delete_employee_name').textContent = name;
        document.getElementById('modal-delete').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('modal-delete').classList.add('hidden');
    }

    function previewCreatePhoto(input) {
        const display = document.getElementById('create_photo_display');
        if (input.files && input.files[0]) {
            display.textContent = `File terpilih: ${input.files[0].name}`;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }

    function previewEditPhoto(input) {
        const display = document.getElementById('edit_photo_display');
        if (input.files && input.files[0]) {
            display.textContent = `File baru terpilih: ${input.files[0].name}`;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }
</script>
@endsection
