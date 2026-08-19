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
                <h2 class="text-lg font-bold text-slate-800">Manajemen Profil Sekolah</h2>
                <p class="text-xs text-slate-500">Kelola informasi dasar, visi, misi, sejarah, dan struktur organisasi institusi.</p>
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
        <main class="flex-1 p-6 md:p-8 overflow-y-auto max-w-7xl">

            <!-- Success Alert Banner -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Global Error Summary Alert -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
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

            <!-- Profile Form -->
            <form action="{{ route('admin.school_profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Action Bar -->
                <div class="mb-6 flex items-center justify-between flex-wrap gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600"></div>
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Form Pengeditan Profil Single-Page</span>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.school_profile.edit') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            Batal / Reset
                        </a>
                        <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-md shadow-indigo-600/20 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <!-- Left Column: Informational Text Areas -->
                    <div class="lg:col-span-8 space-y-6">

                        <!-- Card: Informasi Tentang Sekolah (about_us) -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-sm">Tentang Sekolah</h3>
                                    <p class="text-[11px] text-slate-500">Ringkasan profil dan gambaran umum SMAN 2 Situbondo.</p>
                                </div>
                            </div>
                            <div class="p-6">
                                <label for="about_us" class="block font-semibold text-xs text-slate-700 mb-2">Informasi Tentang Sekolah <span class="text-rose-500">*</span></label>
                                <textarea id="about_us" name="about_us" rows="4" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 p-3.5 transition-all @error('about_us') border-rose-300 bg-rose-50/20 @enderror" placeholder="Tuliskan gambaran umum dan informasi tentang sekolah...">{{ old('about_us', $schoolProfile->about_us) }}</textarea>
                                @error('about_us')
                                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Bento Grid: Visi & Misi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Card: Visi -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col">
                                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-slate-800 text-sm">Visi Sekolah</h3>
                                </div>
                                <div class="p-6 flex-1 flex flex-col">
                                    <label for="vision" class="block font-semibold text-xs text-slate-700 mb-2">Visi <span class="text-rose-500">*</span></label>
                                    <textarea id="vision" name="vision" rows="6" class="w-full flex-1 rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 p-3.5 transition-all @error('vision') border-rose-300 bg-rose-50/20 @enderror" placeholder="Tuliskan visi sekolah di sini...">{{ old('vision', $schoolProfile->vision) }}</textarea>
                                    @error('vision')
                                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Card: Misi -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col">
                                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-slate-800 text-sm">Misi Sekolah</h3>
                                </div>
                                <div class="p-6 flex-1 flex flex-col">
                                    <label for="mission" class="block font-semibold text-xs text-slate-700 mb-2">Misi <span class="text-rose-500">*</span></label>
                                    <textarea id="mission" name="mission" rows="6" class="w-full flex-1 rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 p-3.5 transition-all @error('mission') border-rose-300 bg-rose-50/20 @enderror" placeholder="Tuliskan poin-poin misi sekolah...">{{ old('mission', $schoolProfile->mission) }}</textarea>
                                    @error('mission')
                                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <!-- Card: Tujuan Sekolah (goals) -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm">Tujuan Sekolah</h3>
                            </div>
                            <div class="p-6">
                                <label for="goals" class="block font-semibold text-xs text-slate-700 mb-2">Tujuan <span class="text-rose-500">*</span></label>
                                <textarea id="goals" name="goals" rows="4" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 p-3.5 transition-all @error('goals') border-rose-300 bg-rose-50/20 @enderror" placeholder="Tuliskan tujuan strategis sekolah...">{{ old('goals', $schoolProfile->goals) }}</textarea>
                                @error('goals')
                                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Card: Sejarah Singkat (history) -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm">Sejarah Singkat</h3>
                            </div>
                            <div class="p-6">
                                <label for="history" class="block font-semibold text-xs text-slate-700 mb-2">Sejarah Berdirinya Sekolah <span class="text-rose-500">*</span></label>
                                <textarea id="history" name="history" rows="6" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 p-3.5 transition-all @error('history') border-rose-300 bg-rose-50/20 @enderror" placeholder="Ceritakan riwayat dan sejarah perjalanan SMAN 2 Situbondo...">{{ old('history', $schoolProfile->history) }}</textarea>
                                @error('history')
                                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Media Visual & Gambar Struktur Organisasi -->
                    <div class="lg:col-span-4 space-y-6">

                        <!-- Card: Gambar Struktur Organisasi -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-slate-800 text-sm">Struktur Organisasi</h3>
                                </div>
                            </div>

                            <div class="p-6 space-y-4">
                                <div>
                                    <label class="block font-semibold text-xs text-slate-700 mb-2">Gambar Bagan Struktur</label>

                                    <!-- Current Image Preview -->
                                    <div class="mb-4">
                                        <p class="text-[11px] text-slate-500 font-medium mb-1.5">Gambar Saat Ini:</p>
                                        @if($schoolProfile->structure_image_url)
                                            <div class="relative rounded-xl border border-slate-200 overflow-hidden bg-slate-100 group">
                                                <img src="{{ Storage::url($schoolProfile->structure_image_url) }}" alt="Struktur Organisasi SMAN 2 Situbondo" class="w-full h-48 object-contain p-2 bg-white">
                                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                                    <a href="{{ Storage::url($schoolProfile->structure_image_url) }}" target="_blank" class="px-3 py-1.5 text-xs font-bold text-white bg-slate-900/80 hover:bg-slate-900 rounded-lg flex items-center gap-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                        </svg>
                                                        Lihat Penuh
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="p-6 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="text-xs font-semibold text-slate-500">Belum ada gambar struktur</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Upload Input Dropzone -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-semibold text-slate-700">Unggah Gambar Struktur Organisasi</label>
                                        
                                        <div class="relative">
                                            <label for="structure_image" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50 hover:bg-indigo-50/30 hover:border-indigo-400 transition-all cursor-pointer text-center group">
                                                <div class="w-12 h-12 mb-3 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-100 transition-all shadow-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 transition-colors mb-1">
                                                    Klik untuk memilih file gambar
                                                </p>
                                                <p class="text-[11px] text-slate-400">
                                                    PNG, JPG, JPEG, WebP (Maksimal 2MB)
                                                </p>
                                                <input type="file" id="structure_image" name="structure_image" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewStructureFileName(this)">
                                            </label>

                                            <!-- File Name Indicator -->
                                            <div id="file_name_display" class="hidden mt-3 p-3 rounded-xl bg-indigo-50/70 border border-indigo-100 text-xs font-medium text-indigo-800 flex items-center justify-between">
                                                <div class="flex items-center gap-2 overflow-hidden">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                    </svg>
                                                    <span id="file_name_text" class="truncate font-semibold"></span>
                                                </div>
                                                <button type="button" onclick="clearStructureFile()" class="text-indigo-400 hover:text-indigo-700 ml-2 font-bold text-sm" title="Hapus file">✕</button>
                                            </div>
                                        </div>

                                        @error('structure_image')
                                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card: Terakhir Diperbarui Info -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Terakhir Diperbarui</p>
                                <p class="text-xs font-semibold text-slate-700">
                                    {{ $schoolProfile->updated_at ? $schoolProfile->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum pernah' }}
                                </p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>

                    </div>

                </div>

            </form>

        </main>
    </div>
</div>

<script>
    function previewStructureFileName(input) {
        const display = document.getElementById('file_name_display');
        const text = document.getElementById('file_name_text');
        if (input.files && input.files[0]) {
            text.textContent = input.files[0].name;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }

    function clearStructureFile() {
        const input = document.getElementById('structure_image');
        const display = document.getElementById('file_name_display');
        if (input) input.value = '';
        if (display) display.classList.add('hidden');
    }
</script>
@endsection

