@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-100">

    {{-- HEADER --}}
    <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center gap-4 sticky top-0 z-10 shadow-sm">
        <a
            href="{{ route('admin.banners.index') }}"
            class="p-2 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors duration-150"
            title="Kembali"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800">Tambah Banner Baru</h1>
            <p class="text-sm text-slate-500 mt-0.5">Isi form di bawah untuk menambahkan banner landing page.</p>
        </div>
    </header>

    <main class="p-6 max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form
                method="POST"
                action="{{ route('admin.banners.store') }}"
                enctype="multipart/form-data"
                id="form-banner-create"
            >
                @csrf

                <div class="p-6 space-y-6">

                    {{-- Judul --}}
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Judul Banner <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            maxlength="150"
                            placeholder="Contoh: Penerimaan Siswa Baru 2025"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-transparent transition-colors duration-150"
                        >
                        @error('title')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Deskripsi <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Deskripsi singkat untuk banner ini..."
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-transparent transition-colors duration-150 resize-none"
                        >{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Upload Gambar --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Gambar Banner <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-slate-400 mb-2">Format: JPG, JPEG, PNG, WebP. Maks. 2MB. Akan otomatis dikonversi ke WebP.</p>

                        {{-- Drop zone --}}
                        <label
                            for="image"
                            id="dropzone"
                            class="flex flex-col items-center justify-center gap-3 w-full h-40 border-2 border-dashed {{ $errors->has('image') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100 hover:border-slate-400' }} rounded-xl cursor-pointer transition-colors duration-150"
                        >
                            <div id="dropzone-placeholder" class="flex flex-col items-center gap-2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span class="text-sm font-medium text-slate-500">Klik atau seret gambar ke sini</span>
                            </div>
                            <img id="preview-img" src="" alt="Preview" class="hidden max-h-36 max-w-full rounded-lg object-contain">
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept="image/jpg,image/jpeg,image/png,image/webp"
                                class="hidden"
                                onchange="previewImage(this)"
                            >
                        </label>
                        @error('image')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Urutan & Status --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Urutan Tampil --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Urutan Tampil <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="sort_order"
                                name="sort_order"
                                value="{{ old('sort_order', $nextSortOrder) }}"
                                min="0"
                                class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('sort_order') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-transparent transition-colors duration-150"
                            >
                            <p class="mt-1 text-xs text-slate-400">Angka kecil tampil lebih dulu.</p>
                            @error('sort_order')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Aktif --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                            <div class="flex items-center gap-3 mt-1">
                                <button
                                    type="button"
                                    id="toggle-is-active"
                                    role="switch"
                                    aria-checked="true"
                                    onclick="toggleIsActive()"
                                    class="relative inline-flex items-center h-6 w-11 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-slate-400 bg-emerald-500"
                                >
                                    <span class="inline-block w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 translate-x-6"></span>
                                </button>
                                <span id="toggle-label" class="text-sm font-medium text-emerald-600">Aktif</span>
                                <input type="hidden" id="is_active" name="is_active" value="1">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer form --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a
                        href="{{ route('admin.banners.index') }}"
                        class="px-5 py-2 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors duration-150"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold transition-colors duration-150 shadow-sm"
                    >
                        Simpan Banner
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview-img').classList.remove('hidden');
            document.getElementById('dropzone-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleIsActive() {
    const btn   = document.getElementById('toggle-is-active');
    const label = document.getElementById('toggle-label');
    const input = document.getElementById('is_active');
    const dot   = btn.querySelector('span');

    if (input.value === '1') {
        input.value = '0';
        btn.classList.replace('bg-emerald-500', 'bg-slate-300');
        dot.classList.replace('translate-x-6', 'translate-x-1');
        label.textContent = 'Nonaktif';
        label.className = 'text-sm font-medium text-slate-400';
        btn.setAttribute('aria-checked', 'false');
    } else {
        input.value = '1';
        btn.classList.replace('bg-slate-300', 'bg-emerald-500');
        dot.classList.replace('translate-x-1', 'translate-x-6');
        label.textContent = 'Aktif';
        label.className = 'text-sm font-medium text-emerald-600';
        btn.setAttribute('aria-checked', 'true');
    }
}

// Drag & drop support
const dropzone = document.getElementById('dropzone');
['dragenter', 'dragover'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.add('border-slate-400', 'bg-slate-100'); }));
['dragleave', 'dragend'].forEach(e => dropzone.addEventListener(e, () => { dropzone.classList.remove('border-slate-400', 'bg-slate-100'); }));
dropzone.addEventListener('drop', function(ev) {
    ev.preventDefault();
    dropzone.classList.remove('border-slate-400', 'bg-slate-100');
    const dt = ev.dataTransfer;
    if (dt.files.length) {
        document.getElementById('image').files = dt.files;
        previewImage(document.getElementById('image'));
    }
});
</script>
@endsection
