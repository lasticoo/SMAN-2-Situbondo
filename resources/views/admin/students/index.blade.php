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
                <h2 class="text-lg font-bold text-slate-800">Data Siswa</h2>
                <p class="text-xs text-slate-500">Kelola data seluruh siswa aktif, nonaktif, dan mutasi SMAN 2 Situbondo.</p>
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

                    <!-- Impor Data Button -->
                    <button
                        onclick="openImportModal()"
                        class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 text-xs font-bold px-3.5 py-2 rounded-xl transition-all border border-slate-200 shadow-xs"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Impor Data
                    </button>

                    <!-- Tambah Siswa Button -->
                    <button
                        onclick="openCreateModal()"
                        class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Siswa
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

            <!-- Warning Alert Banner -->
            @if(session('warning'))
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('warning') }}</span>
                </div>
            @endif

            <!-- Global Error Summary Alert -->
            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-sm mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Terdapat kesalahan pengisian form/upload file. Silakan periksa kembali:</span>
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
                    <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto flex-1 max-w-2xl">
                        <!-- Search Box -->
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
                                placeholder="Cari NISN, nama, atau kelas..."
                                class="w-full h-10 pl-10 pr-4 rounded-xl border border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 bg-white placeholder-slate-400 text-slate-700 shadow-xs"
                            />
                        </div>

                        <!-- Class Filter -->
                        <select
                            name="class_filter"
                            onchange="this.form.submit()"
                            class="h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 bg-white text-slate-700 font-medium shadow-xs min-w-[130px]"
                        >
                            <option value="all" {{ request('class_filter') == 'all' || !request('class_filter') ? 'selected' : '' }}>Semua Kelas</option>
                            <option value="10" {{ request('class_filter') == '10' ? 'selected' : '' }}>Kelas X (10)</option>
                            <option value="11" {{ request('class_filter') == '11' ? 'selected' : '' }}>Kelas XI (11)</option>
                            <option value="12" {{ request('class_filter') == '12' ? 'selected' : '' }}>Kelas XII (12)</option>
                        </select>

                        <!-- Public Status Filter -->
                        <select
                            name="status_filter"
                            onchange="this.form.submit()"
                            class="h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 bg-white text-slate-700 font-medium shadow-xs min-w-[130px]"
                        >
                            <option value="all" {{ request('status_filter') == 'all' || !request('status_filter') ? 'selected' : '' }}>Semua Status</option>
                            <option value="public" {{ request('status_filter') == 'public' || request('status_filter') === '1' ? 'selected' : '' }}>Publik (Tampil)</option>
                            <option value="private" {{ request('status_filter') == 'private' || request('status_filter') === '0' ? 'selected' : '' }}>Private (Sembunyi)</option>
                        </select>

                        @if(request('search') || (request('class_filter') && request('class_filter') !== 'all') || (request('status_filter') && request('status_filter') !== 'all'))
                            <a href="{{ route('admin.students.index') }}" title="Reset Filter" class="h-10 w-10 shrink-0 flex items-center justify-center text-slate-500 hover:text-rose-600 bg-white hover:bg-rose-50 rounded-xl transition-colors border border-slate-200 hover:border-rose-200 shadow-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        @endif
                    </form>

                    <div class="text-xs text-slate-500 font-medium self-end sm:self-auto">
                        Total: <span class="font-bold text-slate-800">{{ $students->total() }}</span> Siswa
                    </div>
                </div>

                <!-- Table (NO PHOTO COLUMN PER SPECIFICATION) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200">
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36">NISN</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-32">Kelas</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Informasi Tambahan</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36">Status Publikasi</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($students as $student)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <!-- NISN -->
                                    <td class="py-4 px-6 font-mono text-xs font-semibold text-slate-800">
                                        <span class="bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                            {{ $student->nisn }}
                                        </span>
                                    </td>

                                    <!-- Nama Lengkap -->
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-indigo-950 text-xs">{{ $student->name }}</div>
                                    </td>

                                    <!-- Kelas -->
                                    <td class="py-4 px-6">
                                        <span class="text-xs font-semibold text-slate-700 bg-indigo-50/60 text-indigo-800 px-2.5 py-0.5 rounded-md border border-indigo-100">
                                            {{ $student->class }}
                                        </span>
                                    </td>

                                    <!-- Extra Info -->
                                    <td class="py-4 px-6">
                                        @if($student->extra_info)
                                            <span class="text-xs text-slate-600 truncate max-w-xs block" title="{{ $student->extra_info }}">
                                                {{ $student->extra_info }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        @endif
                                    </td>

                                    <!-- Status Publikasi (Toggle Button) -->
                                    <td class="py-4 px-6">
                                        <form action="{{ route('admin.students.togglePublic', $student) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition-all border {{ $student->is_public ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}" title="Klik untuk mengubah status publikasi">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $student->is_public ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                {{ $student->is_public ? 'Publik' : 'Private' }}
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button
                                                onclick="openEditModal({{ json_encode($student) }})"
                                                class="p-1.5 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                title="Edit Siswa"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <button
                                                onclick="openDeleteModal('{{ route('admin.students.destroy', $student) }}', '{{ $student->name }}')"
                                                class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Hapus Siswa"
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
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-500">Tidak ada data siswa yang ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Styled Pagination Bar -->
                <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 font-medium">
                        Menampilkan <span class="font-bold text-slate-800">{{ $students->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $students->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $students->total() }}</span> siswa
                    </p>

                    @if($students->hasPages())
                        @php
                            $currentPage  = $students->currentPage();
                            $lastPage     = $students->lastPage();
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
                                <a href="{{ $students->url($prevBlockPage) }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-xs font-semibold flex items-center justify-center transition-all shadow-xs" title="Blok Halaman Sebelumnya">
                                    ‹
                                </a>
                            @else
                                <span class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 text-slate-300 text-xs font-semibold flex items-center justify-center cursor-not-allowed">
                                    ‹
                                </span>
                            @endif

                            {{-- Page Buttons (Blok 4 Halaman) --}}
                            @foreach ($students->getUrlRange($startPage, $endPage) as $page => $url)
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
                                <a href="{{ $students->url($nextBlockPage) }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-xs font-semibold flex items-center justify-center transition-all shadow-xs" title="Blok Halaman Selanjutnya">
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
     MODAL TAMBAH SISWA MANUAL
====================================================================== --}}
<div id="modal-create" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Tambah Data Siswa
            </h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form action="{{ route('admin.students.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- NISN -->
            <div>
                <label for="create_nisn" class="block text-xs font-semibold text-slate-700 mb-1">NISN Siswa <span class="text-rose-500">*</span></label>
                <input type="text" id="create_nisn" name="nisn" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5 font-mono" placeholder="Contoh: 0051234567">
            </div>

            <!-- Nama -->
            <div>
                <label for="create_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                <input type="text" id="create_name" name="name" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Contoh: Ahmad Ridwan">
            </div>

            <!-- Kelas -->
            <div>
                <label for="create_class_select" class="block text-xs font-semibold text-slate-700 mb-1">Kelas <span class="text-rose-500">*</span></label>
                <select id="create_class_select" onchange="handleClassSelectChange('create')" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5 bg-white mb-2">
                    <option value="">-- Pilih Kelas --</option>
                    <option value="X-1">X-1</option>
                    <option value="X-2">X-2</option>
                    <option value="X-3">X-3</option>
                    <option value="X-4">X-4</option>
                    <option value="XI IPA 1">XI IPA 1</option>
                    <option value="XI IPA 2">XI IPA 2</option>
                    <option value="XI IPS 1">XI IPS 1</option>
                    <option value="XI IPS 2">XI IPS 2</option>
                    <option value="XII IPA 1">XII IPA 1</option>
                    <option value="XII IPA 2">XII IPA 2</option>
                    <option value="XII IPS 1">XII IPS 1</option>
                    <option value="XII IPS 2">XII IPS 2</option>
                    <option value="XII Bahasa">XII Bahasa</option>
                    <option value="custom">-- Ketik Kelas Manual --</option>
                </select>
                <input type="text" id="create_class_custom" oninput="handleCustomClassInput('create')" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5 hidden" placeholder="Masukkan nama kelas manual (misal: XII MIPA 3)">
                <input type="hidden" id="create_class" name="class" required>
            </div>

            <!-- Extra Info -->
            <div>
                <label for="create_extra_info" class="block text-xs font-semibold text-slate-700 mb-1">Informasi Tambahan (Opsional)</label>
                <textarea id="create_extra_info" name="extra_info" rows="2" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Contoh: Wali Kelas / Catatan Prestasi"></textarea>
            </div>

            <!-- Status Publikasi -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="create_is_public" name="is_public" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                <label for="create_is_public" class="text-xs font-semibold text-slate-700">Status Publikasi (Tampilkan di Website User)</label>
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
     MODAL EDIT SISWA MANUAL
====================================================================== --}}
<div id="modal-edit" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Data Siswa
            </h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form id="form-edit" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- NISN -->
            <div>
                <label for="edit_nisn" class="block text-xs font-semibold text-slate-700 mb-1">NISN Siswa <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_nisn" name="nisn" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5 font-mono">
            </div>

            <!-- Nama -->
            <div>
                <label for="edit_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_name" name="name" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
            </div>

            <!-- Kelas -->
            <div>
                <label for="edit_class_select" class="block text-xs font-semibold text-slate-700 mb-1">Kelas <span class="text-rose-500">*</span></label>
                <select id="edit_class_select" onchange="handleClassSelectChange('edit')" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5 bg-white mb-2">
                    <option value="">-- Pilih Kelas --</option>
                    <option value="X-1">X-1</option>
                    <option value="X-2">X-2</option>
                    <option value="X-3">X-3</option>
                    <option value="X-4">X-4</option>
                    <option value="XI IPA 1">XI IPA 1</option>
                    <option value="XI IPA 2">XI IPA 2</option>
                    <option value="XI IPS 1">XI IPS 1</option>
                    <option value="XI IPS 2">XI IPS 2</option>
                    <option value="XII IPA 1">XII IPA 1</option>
                    <option value="XII IPA 2">XII IPA 2</option>
                    <option value="XII IPS 1">XII IPS 1</option>
                    <option value="XII IPS 2">XII IPS 2</option>
                    <option value="XII Bahasa">XII Bahasa</option>
                    <option value="custom">-- Ketik Kelas Manual --</option>
                </select>
                <input type="text" id="edit_class_custom" oninput="handleCustomClassInput('edit')" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5 hidden" placeholder="Masukkan nama kelas manual (misal: XII MIPA 3)">
                <input type="hidden" id="edit_class" name="class" required>
            </div>

            <!-- Extra Info -->
            <div>
                <label for="edit_extra_info" class="block text-xs font-semibold text-slate-700 mb-1">Informasi Tambahan (Opsional)</label>
                <textarea id="edit_extra_info" name="extra_info" rows="2" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5"></textarea>
            </div>

            <!-- Status Publikasi -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="edit_is_public" name="is_public" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                <label for="edit_is_public" class="text-xs font-semibold text-slate-700">Status Publikasi (Tampilkan di Website User)</label>
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
     MODAL HAPUS SISWA
====================================================================== --}}
<div id="modal-delete" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-sm w-full overflow-hidden p-6 text-center animate-in fade-in zoom-in duration-200">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="font-bold text-slate-800 text-sm mb-1">Konfirmasi Hapus Siswa</h3>
        <p class="text-xs text-slate-500 mb-6">
            Apakah Anda yakin ingin menghapus data siswa <strong id="delete_student_name" class="text-slate-800"></strong>? Data yang dihapus tidak dapat dikembalikan.
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

{{-- =====================================================================
     MODAL IMPOR DATA EXCEL & RIWAYAT BATCHES
====================================================================== --}}
<div id="modal-import" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Impor Data Siswa via Excel
            </h3>
            <button onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <div class="p-6 space-y-6 max-h-[80vh] overflow-y-auto custom-scrollbar">

            <!-- Download Template Banner -->
            <div class="p-4 rounded-xl bg-indigo-50/70 border border-indigo-100 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                        XLS
                    </div>
                    <div>
                        <p class="text-xs font-bold text-indigo-950">Unduh Format Template Excel</p>
                        <p class="text-[11px] text-indigo-700">Pastikan susunan kolom sesuai dengan template (NISN, Nama, Kelas, Info, Status).</p>
                    </div>
                </div>
                <a href="{{ route('admin.students.downloadTemplate') }}" class="h-9 px-3.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center gap-1.5 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Template
                </a>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih File Excel / CSV (.xlsx, .xls, .csv)</label>
                    <label for="excel_file" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-xs font-semibold text-slate-700">Klik untuk memilih file excel</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">Format file: .xlsx, .xls, atau .csv (Maksimal 10MB)</span>
                        <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required class="hidden" onchange="previewImportFile(this)">
                    </label>
                    <div id="import_file_display" class="hidden mt-2 p-2.5 rounded-lg bg-indigo-50 text-xs text-indigo-700 font-semibold truncate"></div>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-xs">
                        Proses Import Excel
                    </button>
                </div>
            </form>

            <!-- Riwayat Import Batches -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-800 mb-3 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat Import Terakhir (Log import_batches)
                </h4>

                <div class="border border-slate-200 rounded-xl overflow-hidden text-xs">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3 font-semibold text-slate-600">File & Admin</th>
                                <th class="py-2.5 px-3 font-semibold text-slate-600">Total</th>
                                <th class="py-2.5 px-3 font-semibold text-slate-600">Sukses</th>
                                <th class="py-2.5 px-3 font-semibold text-slate-600">Gagal</th>
                                <th class="py-2.5 px-3 font-semibold text-slate-600">Status</th>
                                <th class="py-2.5 px-3 font-semibold text-slate-600 text-right">Log</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($importBatches as $batch)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="py-2.5 px-3">
                                        <div class="font-bold text-slate-800 truncate max-w-[150px]">{{ $batch->file_name }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $batch->created_at ? $batch->created_at->format('d M Y H:i') : '-' }} • {{ $batch->uploader->name ?? 'Admin' }}</div>
                                    </td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-700">{{ $batch->total_rows }}</td>
                                    <td class="py-2.5 px-3 text-emerald-600 font-bold">{{ $batch->success_rows }}</td>
                                    <td class="py-2.5 px-3 text-rose-600 font-bold">{{ $batch->failed_rows }}</td>
                                    <td class="py-2.5 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $batch->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ strtoupper($batch->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        @if($batch->error_log)
                                            <button onclick="showErrorLog({{ json_encode($batch->error_log) }})" class="text-[10px] text-indigo-600 hover:text-indigo-800 font-bold underline">
                                                Lihat Error
                                            </button>
                                        @else
                                            <span class="text-[10px] text-slate-400 italic">Clean</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400 italic">Belum ada riwayat import data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Error Log Viewer --}}
<div id="modal-error-log" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden p-6 animate-in fade-in zoom-in duration-200">
        <h4 class="font-bold text-slate-800 text-sm mb-2">Catatan Error Import (failed_rows log)</h4>
        <div class="bg-slate-900 text-rose-300 font-mono text-[11px] p-3 rounded-xl max-h-60 overflow-y-auto whitespace-pre-wrap mb-4 custom-scrollbar" id="error_log_content"></div>
        <div class="flex justify-end">
            <button type="button" onclick="closeErrorLogModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function handleClassSelectChange(prefix) {
        const select = document.getElementById(`${prefix}_class_select`);
        const customInput = document.getElementById(`${prefix}_class_custom`);
        const hiddenInput = document.getElementById(`${prefix}_class`);

        if (select.value === 'custom') {
            customInput.classList.remove('hidden');
            customInput.focus();
            hiddenInput.value = customInput.value;
        } else {
            customInput.classList.add('hidden');
            hiddenInput.value = select.value;
        }
    }

    function handleCustomClassInput(prefix) {
        const customInput = document.getElementById(`${prefix}_class_custom`);
        const hiddenInput = document.getElementById(`${prefix}_class`);
        hiddenInput.value = customInput.value;
    }

    function setClassValueForEdit(className) {
        const select = document.getElementById('edit_class_select');
        const customInput = document.getElementById('edit_class_custom');
        const hiddenInput = document.getElementById('edit_class');

        hiddenInput.value = className;

        let matched = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === className) {
                select.selectedIndex = i;
                matched = true;
                break;
            }
        }

        if (!matched) {
            select.value = 'custom';
            customInput.value = className;
            customInput.classList.remove('hidden');
        } else {
            customInput.classList.add('hidden');
            customInput.value = '';
        }
    }

    function openCreateModal() {
        document.getElementById('create_class_select').selectedIndex = 0;
        document.getElementById('create_class_custom').classList.add('hidden');
        document.getElementById('create_class_custom').value = '';
        document.getElementById('create_class').value = '';
        document.getElementById('modal-create').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('modal-create').classList.add('hidden');
    }

    function openEditModal(student) {
        const form = document.getElementById('form-edit');
        form.action = `/admin/students/${student.id}`;

        document.getElementById('edit_nisn').value = student.nisn;
        document.getElementById('edit_name').value = student.name;
        document.getElementById('edit_extra_info').value = student.extra_info || '';
        document.getElementById('edit_is_public').checked = Boolean(student.is_public);

        setClassValueForEdit(student.class);

        document.getElementById('modal-edit').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modal-edit').classList.add('hidden');
    }

    function openDeleteModal(actionUrl, name) {
        document.getElementById('form-delete').action = actionUrl;
        document.getElementById('delete_student_name').textContent = name;
        document.getElementById('modal-delete').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('modal-delete').classList.add('hidden');
    }

    function openImportModal() {
        document.getElementById('modal-import').classList.remove('hidden');
    }
    function closeImportModal() {
        document.getElementById('modal-import').classList.add('hidden');
    }

    function previewImportFile(input) {
        const display = document.getElementById('import_file_display');
        if (input.files && input.files[0]) {
            display.textContent = `File terpilih: ${input.files[0].name} (${Math.round(input.files[0].size / 1024)} KB)`;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }

    function showErrorLog(log) {
        document.getElementById('error_log_content').textContent = log;
        document.getElementById('modal-error-log').classList.remove('hidden');
    }
    function closeErrorLogModal() {
        document.getElementById('modal-error-log').classList.add('hidden');
    }
</script>
@endsection
