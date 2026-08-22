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
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.spmb_info.index') }}"
                    class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors border border-slate-200"
                    title="Kembali ke Daftar Paket SPMB"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Kelola Dokumen SPMB</h2>
                    <p class="text-xs text-slate-500">
                        Paket Periode: <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($package->period_start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($package->period_end)->format('d M Y') }}</span>
                    </p>
                </div>
            </div>

            <!-- Header Right Items -->
            <div class="flex items-center gap-3">
                <button
                    onclick="openCreateModal()"
                    class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Dokumen SPMB
                </button>
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

            <!-- Paket Info Summary Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if($package->banner_url)
                        <img src="{{ Storage::url($package->banner_url) }}" alt="Banner SPMB" class="w-16 h-16 rounded-xl object-cover border border-slate-200 shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                            SPMB
                        </div>
                    @endif
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Paket SPMB (ID: {{ $package->id }})</h3>
                        <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">{{ $package->schedule_info }}</p>
                        <div class="flex items-center gap-3 mt-1.5 text-[11px] text-slate-400">
                            <span>Periode: <strong>{{ \Carbon\Carbon::parse($package->period_start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($package->period_end)->format('d/m/Y') }}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="text-right self-end md:self-auto">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-200">
                        {{ $documents->count() }} Dokumen Terlampir
                    </span>
                </div>
            </div>

            <!-- Documents Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-sm">Daftar Dokumen Paket Ini</h3>
                    <span class="text-xs text-slate-500">Total: <strong class="text-slate-800">{{ $documents->count() }}</strong> Dokumen</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200">
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Judul Dokumen</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">File</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th class="py-3.5 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($documents as $doc)
                                @php
                                    $extension = strtolower(pathinfo($doc->file_url, PATHINFO_EXTENSION));
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <!-- Judul Dokumen -->
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-800 text-xs flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $doc->title }}
                                        </div>
                                    </td>

                                    <!-- File URL Badge & Download Link -->
                                    <td class="py-4 px-6">
                                        <a
                                            href="{{ Storage::url($doc->file_url) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-700 transition-colors border border-slate-200"
                                            title="Buka / Unduh Dokumen"
                                        >
                                            <span class="uppercase text-[10px] font-extrabold px-1.5 py-0.5 rounded bg-slate-200 text-slate-600">
                                                {{ $extension }}
                                            </span>
                                            Lihat File
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </td>

                                    <!-- Tanggal Dibuat -->
                                    <td class="py-4 px-6">
                                        <span class="text-xs text-slate-500">
                                            {{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y H:i') }}
                                        </span>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button
                                                onclick="openEditModal({{ json_encode($doc) }})"
                                                class="p-1.5 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                title="Edit Dokumen"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <button
                                                onclick="openDeleteModal('{{ route('admin.spmb_info.documents.destroy', [$package->id, $doc->id]) }}', '{{ $doc->title }}')"
                                                class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Hapus Dokumen"
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
                                    <td colspan="4" class="py-12 text-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-500">Belum ada dokumen yang diunggah untuk paket ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- MODAL TAMBAH DOKUMEN --}}
<div id="modal-create" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Dokumen SPMB Baru
            </h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form action="{{ route('admin.spmb_info.documents.store', $package->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <!-- Judul Dokumen -->
            <div>
                <label for="create_title" class="block text-xs font-semibold text-slate-700 mb-1">Judul / Nama Dokumen <span class="text-rose-500">*</span></label>
                <input type="text" id="create_title" name="title" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5" placeholder="Contoh: Brosur SPMB 2026 / Form Pendaftaran Manual">
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">File Dokumen <span class="text-rose-500">*</span></label>
                <label for="create_file_url" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <span class="text-xs font-semibold text-slate-700">Pilih file dokumen</span>
                    <span class="text-[10px] text-slate-400">PDF, DOC, DOCX (Maks 10MB)</span>
                    <input type="file" id="create_file_url" name="file_url" accept=".pdf,.doc,.docx" required class="hidden" onchange="previewCreateDoc(this)">
                </label>
                <div id="create_file_display" class="hidden mt-2 p-2 rounded-lg bg-indigo-50 text-[11px] text-indigo-700 font-semibold truncate"></div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-xs">
                    Simpan Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT DOKUMEN --}}
<div id="modal-edit" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Dokumen SPMB
            </h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form id="form-edit" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- Judul Dokumen -->
            <div>
                <label for="edit_title" class="block text-xs font-semibold text-slate-700 mb-1">Judul / Nama Dokumen <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_title" name="title" required class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 p-2.5">
            </div>

            <!-- Ganti File Dokumen -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Ganti File Dokumen (Opsional)</label>
                <label for="edit_file_url" class="flex flex-col items-center justify-center p-3 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center">
                    <span class="text-xs font-semibold text-slate-700">Pilih file dokumen baru</span>
                    <span class="text-[10px] text-slate-400">PDF, DOC, DOCX (Maks 10MB)</span>
                    <input type="file" id="edit_file_url" name="file_url" accept=".pdf,.doc,.docx" class="hidden" onchange="previewEditDoc(this)">
                </label>
                <div id="edit_file_display" class="hidden mt-2 p-2 rounded-lg bg-indigo-50 text-[11px] text-indigo-700 font-semibold truncate"></div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-xs">
                    Perbarui Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HAPUS DOKUMEN --}}
<div id="modal-delete" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-sm w-full overflow-hidden p-6 text-center animate-in fade-in zoom-in duration-200">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="font-bold text-slate-800 text-sm mb-1">Konfirmasi Hapus Dokumen</h3>
        <p class="text-xs text-slate-500 mb-6">
            Apakah Anda yakin ingin menghapus dokumen <strong id="delete_doc_title" class="text-slate-800"></strong>? Tindakan ini akan menghapus file fisik dokumen dari storage.
        </p>

        <form id="form-delete" method="POST" class="flex justify-center gap-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Batal
            </button>
            <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-all shadow-xs">
                Ya, Hapus Dokumen
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

    function openEditModal(doc) {
        const form = document.getElementById('form-edit');
        form.action = `/admin/spmb-info/{{ $package->id }}/documents/${doc.id}`;

        document.getElementById('edit_title').value = doc.title;
        document.getElementById('modal-edit').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modal-edit').classList.add('hidden');
    }

    function openDeleteModal(actionUrl, title) {
        document.getElementById('form-delete').action = actionUrl;
        document.getElementById('delete_doc_title').textContent = title;
        document.getElementById('modal-delete').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('modal-delete').classList.add('hidden');
    }

    function previewCreateDoc(input) {
        const display = document.getElementById('create_file_display');
        if (input.files && input.files[0]) {
            display.textContent = `File terpilih: ${input.files[0].name}`;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }

    function previewEditDoc(input) {
        const display = document.getElementById('edit_file_display');
        if (input.files && input.files[0]) {
            display.textContent = `File baru terpilih: ${input.files[0].name}`;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }
</script>
@endsection
