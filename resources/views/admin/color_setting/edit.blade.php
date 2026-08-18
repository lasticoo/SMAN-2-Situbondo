@extends('layouts.admin')

@section('content')
@php
    $currentAdmin = Auth::guard('admin')->user();
    $defaultPrimary = config('theme.primary', '#001C4D');
    $defaultSecondary = config('theme.secondary', '#5C5F60');

    $primaryVal = old('primary_color', $colorSetting->primary_color ?? $defaultPrimary);
    $secondaryVal = old('secondary_color', $colorSetting->secondary_color ?? $defaultSecondary);
@endphp

<style>
    .color-picker-wrapper input[type="color"] {
        -webkit-appearance: none;
        border: none;
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        cursor: pointer;
        padding: 0;
        overflow: hidden;
    }
    .color-picker-wrapper input[type="color"]::-webkit-color-swatch-wrapper {
        padding: 0;
    }
    .color-picker-wrapper input[type="color"]::-webkit-color-swatch {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 0 0 1px rgba(0,0,0,0.1) inset;
    }
</style>

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
                <h2 class="text-lg font-bold text-slate-800">Pengaturan Tampilan & Warna</h2>
                <p class="text-xs text-slate-500">Kustomisasi palet warna utama untuk menyesuaikan identitas visual SMAN 2 Situbondo.</p>
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
                </div>
            </div>
        </header>

        <!-- Main Content Canvas -->
        <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto space-y-6">

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

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Column: Form Kustomisasi Warna -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                        
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                            <h3 class="font-bold text-slate-800 text-sm">Kustomisasi Warna Utama</h3>
                        </div>

                        <form action="{{ route('admin.color_settings.update') }}" method="POST" class="p-6 space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Primary Color Card -->
                            <div class="flex flex-col sm:flex-row sm:items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <div class="color-picker-wrapper shrink-0">
                                    <input
                                        id="primary_color_picker"
                                        type="color"
                                        value="{{ strtoupper($primaryVal) }}"
                                        oninput="syncFromPicker('primary')"
                                    />
                                </div>
                                <div class="flex-1">
                                    <label for="primary_color_text" class="block font-bold text-slate-800 text-xs mb-1">Warna Primer (Primary Color)</label>
                                    <p class="text-[11px] text-slate-500 mb-3">Digunakan untuk elemen utama seperti header, tombol utama, dan navigasi aktif.</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-500">HEX</span>
                                        <input
                                            id="primary_color_text"
                                            name="primary_color"
                                            type="text"
                                            required
                                            maxlength="50"
                                            value="{{ strtoupper($primaryVal) }}"
                                            class="h-9 px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 w-36 uppercase shadow-xs"
                                            oninput="syncFromText('primary')"
                                        />
                                    </div>
                                    @error('primary_color')
                                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Secondary Color Card -->
                            <div class="flex flex-col sm:flex-row sm:items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <div class="color-picker-wrapper shrink-0">
                                    <input
                                        id="secondary_color_picker"
                                        type="color"
                                        value="{{ strtoupper($secondaryVal) }}"
                                        oninput="syncFromPicker('secondary')"
                                    />
                                </div>
                                <div class="flex-1">
                                    <label for="secondary_color_text" class="block font-bold text-slate-800 text-xs mb-1">Warna Sekunder (Secondary Color)</label>
                                    <p class="text-[11px] text-slate-500 mb-3">Digunakan untuk teks sekunder, batas elemen, dan tombol aksi minor.</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-500">HEX</span>
                                        <input
                                            id="secondary_color_text"
                                            name="secondary_color"
                                            type="text"
                                            required
                                            maxlength="50"
                                            value="{{ strtoupper($secondaryVal) }}"
                                            class="h-9 px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 w-36 uppercase shadow-xs"
                                            oninput="syncFromText('secondary')"
                                        />
                                    </div>
                                    @error('secondary_color')
                                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form Footer Buttons -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                                <button
                                    type="button"
                                    onclick="resetToDefault()"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-colors"
                                >
                                    Atur Ulang ke Default
                                </button>
                                <button
                                    type="submit"
                                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all shadow-xs flex items-center gap-2"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Info & Live Preview -->
                <div class="lg:col-span-5 space-y-6">

                    <!-- Information Card -->
                    <div class="p-5 rounded-2xl bg-indigo-50/70 border border-indigo-100 flex items-start gap-3 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="font-bold text-indigo-950 text-xs mb-1">Pengaruh Perubahan Warna</h4>
                            <p class="text-[11px] text-indigo-900/80 leading-relaxed">
                                Menyimpan perubahan warna akan secara otomatis memperbarui variabel CSS di seluruh sistem secara global.
                            </p>
                        </div>
                    </div>

                    <!-- CSS Root Variables Code Card -->
                    <div class="bg-[#1e1e1e] rounded-2xl shadow-xs border border-slate-800 overflow-hidden">
                        <div class="px-4 py-3 bg-[#2d2d2d] border-b border-[#404040] flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                                <span class="font-mono text-xs text-[#d4d4d4] font-semibold">Variabel CSS Root</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-mono">Live Preview</span>
                        </div>
                        <div class="p-4 overflow-x-auto">
                            <pre class="font-mono text-xs leading-relaxed text-[#d4d4d4]"><code><span class="text-[#569cd6]">:root</span> {
  <span class="text-[#9cdcfe]">--primary-color</span>: <span id="code-primary" class="text-[#ce9178]">{{ strtoupper($primaryVal) }}</span>;
  <span class="text-[#9cdcfe]">--secondary-color</span>: <span id="code-secondary" class="text-[#ce9178]">{{ strtoupper($secondaryVal) }}</span>;
}</code></pre>
                        </div>
                    </div>

                    <!-- Live Component Preview Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                            <h4 class="font-bold text-slate-800 text-xs">Pratinjau Komponen Live</h4>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Primary Button Preview -->
                            <button
                                id="preview-primary-btn"
                                type="button"
                                style="background-color: {{ $primaryVal }}; color: #ffffff;"
                                class="w-full py-2.5 font-bold text-xs rounded-xl shadow-xs transition-all"
                            >
                                Tombol Utama (Primary)
                            </button>

                            <!-- Tags Preview -->
                            <div class="flex flex-wrap gap-2">
                                <span
                                    id="preview-primary-tag"
                                    style="background-color: {{ $primaryVal }}15; color: {{ $primaryVal }}; border-color: {{ $primaryVal }}30;"
                                    class="px-3 py-1 rounded-full text-[11px] font-bold border"
                                >
                                    Tag Status Aktif
                                </span>
                                <span
                                    id="preview-secondary-tag"
                                    style="background-color: {{ $secondaryVal }}15; color: {{ $secondaryVal }}; border-color: {{ $secondaryVal }}30;"
                                    class="px-3 py-1 rounded-full text-[11px] font-bold border"
                                >
                                    Label Sekunder
                                </span>
                            </div>

                            <!-- Notification Accent Box -->
                            <div
                                id="preview-notification-box"
                                style="border-left-color: {{ $primaryVal }}; background-color: {{ $primaryVal }}08;"
                                class="p-3.5 border-l-4 rounded-r-xl"
                            >
                                <p class="text-xs font-semibold text-slate-700">Pesan notifikasi sistem dengan warna aksen primer.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>
</div>

<script>
    const DEFAULT_PRIMARY = "{{ $defaultPrimary }}";
    const DEFAULT_SECONDARY = "{{ $defaultSecondary }}";

    function isValidHex(hex) {
        return /^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/.test(hex);
    }

    function syncFromPicker(type) {
        const picker = document.getElementById(`${type}_color_picker`);
        const textInput = document.getElementById(`${type}_color_text`);
        textInput.value = picker.value.toUpperCase();
        updateLivePreview();
    }

    function syncFromText(type) {
        const picker = document.getElementById(`${type}_color_picker`);
        const textInput = document.getElementById(`${type}_color_text`);
        let val = textInput.value.trim();

        if (val.length > 0 && !val.startsWith('#')) {
            val = '#' + val;
            textInput.value = val.toUpperCase();
        }

        if (isValidHex(val)) {
            if (val.length === 4) {
                // Expand 3-digit hex (#FFF -> #FFFFFF) for color picker input
                val = '#' + val[1] + val[1] + val[2] + val[2] + val[3] + val[3];
            }
            picker.value = val;
            updateLivePreview();
        }
    }

    function updateLivePreview() {
        const primary = document.getElementById('primary_color_text').value || DEFAULT_PRIMARY;
        const secondary = document.getElementById('secondary_color_text').value || DEFAULT_SECONDARY;

        // Update Code snippet
        document.getElementById('code-primary').textContent = primary.toUpperCase();
        document.getElementById('code-secondary').textContent = secondary.toUpperCase();

        // Update Live components
        const btn = document.getElementById('preview-primary-btn');
        if (btn) btn.style.backgroundColor = primary;

        const pTag = document.getElementById('preview-primary-tag');
        if (pTag) {
            pTag.style.backgroundColor = primary + '15';
            pTag.style.color = primary;
            pTag.style.borderColor = primary + '30';
        }

        const sTag = document.getElementById('preview-secondary-tag');
        if (sTag) {
            sTag.style.backgroundColor = secondary + '15';
            sTag.style.color = secondary;
            sTag.style.borderColor = secondary + '30';
        }

        const noteBox = document.getElementById('preview-notification-box');
        if (noteBox) {
            noteBox.style.borderLeftColor = primary;
            noteBox.style.backgroundColor = primary + '08';
        }
    }

    function resetToDefault() {
        document.getElementById('primary_color_picker').value = DEFAULT_PRIMARY;
        document.getElementById('primary_color_text').value = DEFAULT_PRIMARY;

        document.getElementById('secondary_color_picker').value = DEFAULT_SECONDARY;
        document.getElementById('secondary_color_text').value = DEFAULT_SECONDARY;

        updateLivePreview();
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateLivePreview();
    });
</script>
@endsection
