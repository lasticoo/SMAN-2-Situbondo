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
                <h2 class="text-lg font-bold text-slate-800">Manajemen Akun Admin</h2>
                <p class="text-xs text-slate-500">Kelola daftar pengelola, peran (role), dan status akses sistem SMAN 2 Situbondo.</p>
            </div>

            <!-- Header Action Button -->
            <div class="flex items-center gap-3">
                <button
                    onclick="openCreateModal()"
                    class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all shadow-sm"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Admin</span>
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

            @if(session('error') || $errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('error') ?? 'Terjadi kesalahan saat memproses data:' }}</span>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @if($errors->any())
                        <ul class="list-disc list-inside pl-7 text-[11px] space-y-0.5 text-rose-700">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            {{-- Summary Stats & Filter Controls --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row gap-4 justify-between items-center">
                {{-- Role Filter Pills --}}
                <div class="flex flex-wrap gap-2 w-full md:w-auto">
                    <a
                        href="{{ route('admin.admin_accounts.index') }}"
                        class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ empty(request('role')) ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Semua ({{ $stats['total'] ?? 0 }})
                    </a>
                    <a
                        href="{{ route('admin.admin_accounts.index', ['role' => 'super_admin', 'search' => request('search')]) }}"
                        class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ request('role') == 'super_admin' ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Super Admin ({{ $stats['super_admin'] ?? 0 }})
                    </a>
                    <a
                        href="{{ route('admin.admin_accounts.index', ['role' => 'admin', 'search' => request('search')]) }}"
                        class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ request('role') == 'admin' ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Admin ({{ $stats['admin'] ?? 0 }})
                    </a>
                </div>

                {{-- Search Bar Form --}}
                <form action="{{ route('admin.admin_accounts.index') }}" method="GET" class="w-full md:w-72">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama atau email admin..."
                            class="w-full pl-9 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors placeholder:text-slate-400"
                        />
                    </div>
                </form>
            </div>

            {{-- Table List of Admin Accounts --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[760px]">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3.5 px-6">Admin</th>
                                <th class="py-3.5 px-6">Email</th>
                                <th class="py-3.5 px-6">Role</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($admins as $adm)
                                @php
                                    $isSelf = ($adm->id === $currentAdmin->id);
                                    $isActive = (bool) $adm->is_active;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors group {{ !$isActive ? 'opacity-60 bg-slate-50/50' : '' }}">
                                    {{-- Admin Profile & Name --}}
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <img
                                                src="{{ $adm->display_avatar_url }}"
                                                alt="{{ $adm->name }}"
                                                class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-2xs shrink-0 {{ !$isActive ? 'grayscale' : '' }}"
                                            />
                                            <div>
                                                <p class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                                                    <span>{{ $adm->name }}</span>
                                                    @if($isSelf)
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-indigo-100 text-indigo-700">Saya</span>
                                                    @endif
                                                </p>
                                                <p class="text-[11px] text-slate-400">
                                                    Dibuat {{ $adm->created_at ? $adm->created_at->format('d M Y') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Email --}}
                                    <td class="py-4 px-6 whitespace-nowrap font-medium text-slate-700">
                                        {{ $adm->email }}
                                    </td>

                                    {{-- Role --}}
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if($adm->role === 'super_admin')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                Super Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                Admin
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if($isActive)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            {{-- Edit Button --}}
                                            <button
                                                onclick="openEditModal({{ json_encode($adm) }})"
                                                class="p-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 border border-transparent hover:border-indigo-100 transition-all"
                                                title="Edit Akun Admin"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 012.828 0L20.586 7.586a2 2 0 010 2.828L11.828 19H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            {{-- Toggle Active Button --}}
                                            @if(!$isSelf)
                                                <form action="{{ route('admin.admin_accounts.toggleActive', $adm->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        type="submit"
                                                        class="p-2 rounded-xl text-slate-600 hover:text-amber-600 hover:bg-amber-50 border border-transparent hover:border-amber-100 transition-all"
                                                        title="{{ $isActive ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                                    >
                                                        @if($isActive)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Delete Button --}}
                                            @if(!$isSelf)
                                                <button
                                                    onclick="openDeleteModal('{{ route('admin.admin_accounts.destroy', $adm->id) }}', '{{ addslashes($adm->name) }}')"
                                                    class="p-2 rounded-xl text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all"
                                                    title="Hapus Akun Admin"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-500">Belum ada akun admin yang ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($admins->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $admins->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>
</div>

{{-- =========================================================================
     MODAL CREATE ADMIN
========================================================================== --}}
<div id="modalCreateAdmin" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all my-8">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm">Tambah Admin Baru</h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.admin_accounts.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
            @csrf

            {{-- Avatar Upload Preview --}}
            <div class="flex flex-col items-center gap-2 mb-2">
                <div class="w-20 h-20 rounded-full bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center text-slate-400 cursor-pointer hover:border-indigo-500 hover:bg-slate-50 transition-colors relative overflow-hidden group">
                    <img id="createAvatarPreview" src="" class="hidden w-full h-full object-cover rounded-full" />
                    <span id="createAvatarIcon" class="flex flex-col items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    <input type="file" name="avatar" accept="image/*" onchange="previewImage(this, 'createAvatarPreview', 'createAvatarIcon')" class="absolute inset-0 opacity-0 cursor-pointer" />
                </div>
                <p class="text-[11px] text-slate-400 font-medium">Unggah Foto Avatar (Opsional, JPG/PNG/WEBP max 5MB)</p>
            </div>

            {{-- Full Name --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    name="name"
                    required
                    placeholder="Masukkan nama lengkap admin"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>

            {{-- Email --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Email <span class="text-rose-500">*</span></label>
                <input
                    type="email"
                    name="email"
                    required
                    placeholder="nama@smada.sch.id"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>

            {{-- Role Dropdown --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                <select
                    name="role"
                    required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors font-medium text-slate-800"
                >
                    <option value="super_admin" selected>Super Admin</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            {{-- Password --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                <input
                    type="password"
                    name="password"
                    required
                    placeholder="••••••••"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
                <p class="text-[10px] text-slate-400 mt-0.5">Minimal 8 karakter.</p>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Konfirmasi Password <span class="text-rose-500">*</span></label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    placeholder="••••••••"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>

            {{-- Form Footer --}}
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <button
                    type="button"
                    onclick="closeCreateModal()"
                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition-colors"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-sm transition-colors"
                >
                    Simpan Admin
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================================
     MODAL EDIT ADMIN
========================================================================== --}}
<div id="modalEditAdmin" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all my-8">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm">Edit Data Akun Admin</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
            @csrf
            @method('PUT')

            {{-- Avatar Upload Preview --}}
            <div class="flex flex-col items-center gap-2 mb-2">
                <div class="w-20 h-20 rounded-full bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center text-slate-400 cursor-pointer hover:border-indigo-500 hover:bg-slate-50 transition-colors relative overflow-hidden group">
                    <img id="editAvatarPreview" src="" class="w-full h-full object-cover rounded-full" />
                    <span id="editAvatarIcon" class="hidden flex flex-col items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                    </span>
                    <input type="file" name="avatar" accept="image/*" onchange="previewImage(this, 'editAvatarPreview', 'editAvatarIcon')" class="absolute inset-0 opacity-0 cursor-pointer" />
                </div>
                <p class="text-[11px] text-slate-400 font-medium">Ganti Foto Avatar (Opsional, JPG/PNG/WEBP max 5MB)</p>
            </div>

            {{-- Full Name --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="edit_name"
                    name="name"
                    required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>

            {{-- Email --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Email <span class="text-rose-500">*</span></label>
                <input
                    type="email"
                    id="edit_email"
                    name="email"
                    required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>

            {{-- Role Dropdown --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                <select
                    id="edit_role"
                    name="role"
                    required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors font-medium text-slate-800"
                >
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            {{-- Password (Optional) --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Password Baru <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
                <p class="text-[10px] text-slate-400 mt-0.5">Kosongkan jika tidak ingin mengubah password lama.</p>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block font-bold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="••••••••"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>

            {{-- Active Status Checkbox --}}
            <div id="editActiveContainer" class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input
                        type="checkbox"
                        id="edit_is_active"
                        name="is_active"
                        value="1"
                        class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500"
                    />
                    <span class="font-bold text-slate-700 text-xs">Akun Berstatus Aktif</span>
                </label>
            </div>

            {{-- Form Footer --}}
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition-colors"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-sm transition-colors"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================================
     MODAL DELETE CONFIRMATION
========================================================================== --}}
<div id="modalDeleteAdmin" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-100 p-6 text-center transform transition-all">
        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="text-base font-bold text-slate-900 mb-1">Konfirmasi Hapus Akun Admin</h3>
        <p class="text-xs text-slate-500 mb-4">
            Apakah Anda yakin ingin menghapus akun admin <strong id="deleteTargetName" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan.
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
    const currentAdminId = {{ $currentAdmin->id }};

    function openCreateModal() {
        document.getElementById('modalCreateAdmin').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('modalCreateAdmin').classList.add('hidden');
    }

    function openEditModal(data) {
        document.getElementById('editForm').action = `/admin/admin-accounts/${data.id}`;
        document.getElementById('edit_name').value = data.name || '';
        document.getElementById('edit_email').value = data.email || '';
        document.getElementById('edit_role').value = data.role || 'super_admin';
        document.getElementById('edit_is_active').checked = !!data.is_active;

        const previewImg = document.getElementById('editAvatarPreview');
        const previewIcon = document.getElementById('editAvatarIcon');

        if (data.display_avatar_url) {
            previewImg.src = data.display_avatar_url;
            previewImg.classList.remove('hidden');
            previewIcon.classList.add('hidden');
        } else {
            previewImg.src = '';
            previewImg.classList.add('hidden');
            previewIcon.classList.remove('hidden');
        }

        // Self protection for status checkbox
        const activeContainer = document.getElementById('editActiveContainer');
        if (data.id === currentAdminId) {
            activeContainer.classList.add('hidden');
        } else {
            activeContainer.classList.remove('hidden');
        }

        document.getElementById('modalEditAdmin').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditAdmin').classList.add('hidden');
    }

    function openDeleteModal(actionUrl, name) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteTargetName').innerText = name;
        document.getElementById('modalDeleteAdmin').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('modalDeleteAdmin').classList.add('hidden');
    }

    function previewImage(input, previewId, iconId) {
        const preview = document.getElementById(previewId);
        const icon = document.getElementById(iconId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (icon) icon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
