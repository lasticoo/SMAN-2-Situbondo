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
                <h2 class="text-lg font-bold text-slate-800">Pesan Masuk (Contact)</h2>
                <p class="text-xs text-slate-500">Kelola pesan, pertanyaan, dan tanggapan dari pengunjung portal SMAN 2 Situbondo.</p>
            </div>

            <!-- Action Button -->
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.contact.exportCsv') }}"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Download Rekap CSV</span>
                </a>
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

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 012-2V7a2 2 0 01-2-2H5a2 2 0 01-2 2v10a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Total Pesan</p>
                        <h3 class="text-xl font-bold text-slate-800">{{ $stats['total'] ?? 0 }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Belum Dibaca (Unread)</p>
                        <h3 class="text-xl font-bold text-rose-600">{{ $stats['unread'] ?? 0 }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Sudah Dibaca (Read)</p>
                        <h3 class="text-xl font-bold text-slate-800">{{ $stats['read'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            {{-- Filter and Search Control Bar --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <form action="{{ route('admin.contact.index') }}" method="GET" class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3 items-center">
                    <!-- Search Input (7 Columns) -->
                    <div class="relative w-full sm:col-span-2 md:col-span-7">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama, email, telepon, subjek, atau isi pesan..."
                            class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors placeholder:text-slate-400"
                        />
                    </div>

                    <!-- Status Filter (3 Columns) -->
                    <div class="w-full sm:col-span-1 md:col-span-3">
                        <select
                            name="status"
                            onchange="this.form.submit()"
                            class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        >
                            <option value="">Semua Status</option>
                            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread (Belum Dibaca)</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read (Sudah Dibaca)</option>
                        </select>
                    </div>

                    <!-- Action Buttons (2 Columns) -->
                    <div class="w-full sm:col-span-1 md:col-span-2 flex items-center gap-2">
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

                        @if(request()->hasAny(['search', 'status']))
                            <a
                                href="{{ route('admin.contact.index') }}"
                                class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl text-center transition-colors shrink-0"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table List of Messages --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[760px]">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3.5 px-5 w-3/12">Pengirim</th>
                                <th class="py-3.5 px-4 w-5/12">Subjek & Pesan</th>
                                <th class="py-3.5 px-4">Waktu Masuk</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($messages as $msg)
                                @php
                                    $isUnread = ($msg->status === 'unread');
                                    $createdDate = $msg->created_at ? \Carbon\Carbon::parse($msg->created_at) : null;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors group {{ $isUnread ? 'bg-indigo-50/30' : '' }}">
                                    {{-- Pengirim --}}
                                    <td class="py-4 px-5">
                                        <div class="flex items-start gap-3">
                                            <div class="w-9 h-9 rounded-xl {{ $isUnread ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600 font-semibold' }} flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($msg->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-bold text-slate-900 text-xs truncate flex items-center gap-1.5">
                                                    <span>{{ $msg->name }}</span>
                                                    @if($isUnread)
                                                        <span class="w-2 h-2 rounded-full bg-rose-500 inline-block shrink-0" title="Pesan Belum Dibaca"></span>
                                                    @endif
                                                </h4>
                                                <p class="text-[11px] text-slate-500 truncate">{{ $msg->email }}</p>
                                                @if($msg->phone)
                                                    <p class="text-[10px] text-slate-400 truncate">{{ $msg->phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Subjek & Pesan Preview --}}
                                    <td class="py-4 px-4">
                                        <div class="min-w-0">
                                            <h5 class="{{ $isUnread ? 'font-bold text-slate-900' : 'font-semibold text-slate-800' }} text-xs line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                                {{ $msg->subject }}
                                            </h5>
                                            <p class="text-slate-500 text-[11px] line-clamp-2 mt-0.5 leading-relaxed">
                                                {{ $msg->message }}
                                            </p>
                                        </div>
                                    </td>

                                    {{-- Waktu Masuk --}}
                                    <td class="py-4 px-4 whitespace-nowrap text-slate-600">
                                        <div class="font-semibold text-slate-800">
                                            {{ $createdDate ? $createdDate->format('d M Y') : '-' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $createdDate ? $createdDate->format('H:i') . ' WIB' : '-' }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($isUnread)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Unread
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Read
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            {{-- Detail Button --}}
                                            <button
                                                onclick="openDetailModal({{ $msg->id }})"
                                                class="p-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 border border-transparent hover:border-indigo-100 transition-all"
                                                title="Lihat Detail Pesan"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            {{-- Toggle Status Form Button --}}
                                            <form action="{{ route('admin.contact.toggleStatus', $msg->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    class="p-2 rounded-xl text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-100 transition-all"
                                                    title="{{ $isUnread ? 'Tandai Sudah Dibaca' : 'Tandai Belum Dibaca' }}"
                                                >
                                                    @if($isUnread)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4"/>
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            {{-- Delete Button --}}
                                            <button
                                                onclick="openDeleteModal('{{ route('admin.contact.destroy', $msg->id) }}', '{{ addslashes($msg->name) }}', '{{ addslashes($msg->subject) }}')"
                                                class="p-2 rounded-xl text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all"
                                                title="Hapus Pesan"
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
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-500">Belum ada pesan masuk yang ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($messages->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $messages->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>
</div>

{{-- =========================================================================
     MODAL DETAIL PESAN
========================================================================== --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all my-8">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 012-2V7a2 2 0 01-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Detail Pesan Masuk</h3>
                    <p class="text-[11px] text-slate-500">Informasi lengkap pesan pengirim</p>
                </div>
            </div>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 space-y-5">
            {{-- Sender Info Box --}}
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Nama Pengirim</span>
                    <p id="detail_name" class="font-bold text-slate-800 text-sm">-</p>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Waktu Masuk</span>
                    <p id="detail_time" class="font-semibold text-slate-700">-</p>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Email Pengirim</span>
                    <a id="detail_email_link" href="#" class="font-semibold text-indigo-600 hover:underline flex items-center gap-1">
                        <span id="detail_email">-</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Nomor Telepon</span>
                    <p id="detail_phone" class="font-semibold text-slate-700">-</p>
                </div>
            </div>

            {{-- Subject --}}
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Subjek Pesan</span>
                <div id="detail_subject" class="p-3 bg-white border border-slate-200 rounded-xl font-bold text-slate-800 text-xs">
                    -
                </div>
            </div>

            {{-- Message Content --}}
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Isi Pesan Lengkap</span>
                <div id="detail_message" class="p-4 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 leading-relaxed whitespace-pre-line min-h-[120px] max-h-[260px] overflow-y-auto">
                    -
                </div>
            </div>

            {{-- Footer Action Buttons --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <form id="detailToggleForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors flex items-center gap-1.5"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Ubah Status Baca</span>
                    </button>
                </form>

                <div class="flex items-center gap-2">
                    <a
                        id="detail_reply_email"
                        href="#"
                        target="_blank"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition-colors flex items-center gap-1.5"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Balas Email</span>
                    </a>
                </div>
            </div>
        </div>
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

        <h3 class="text-base font-bold text-slate-900 mb-1">Konfirmasi Hapus Pesan</h3>
        <p class="text-xs text-slate-500 mb-4">
            Apakah Anda yakin ingin menghapus pesan dari <strong id="deleteTargetSender" class="text-slate-800"></strong> dengan subjek <em id="deleteTargetSubject" class="text-slate-700"></em>?
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
    function openDetailModal(id) {
        fetch(`/admin/contact/${id}`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const data = res.data;
                    document.getElementById('detail_name').innerText = data.name || '-';
                    document.getElementById('detail_email').innerText = data.email || '-';
                    document.getElementById('detail_email_link').href = `mailto:${data.email}`;
                    document.getElementById('detail_reply_email').href = `mailto:${data.email}?subject=Re: ${encodeURIComponent(data.subject || '')}`;
                    document.getElementById('detail_phone').innerText = data.phone || '-';
                    document.getElementById('detail_subject').innerText = data.subject || '-';
                    document.getElementById('detail_message').innerText = data.message || '-';

                    if (data.created_at) {
                        const dateObj = new Date(data.created_at);
                        document.getElementById('detail_time').innerText = dateObj.toLocaleString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        }) + ' WIB';
                    }

                    document.getElementById('detailToggleForm').action = `/admin/contact/${data.id}/toggle-status`;
                    document.getElementById('detailModal').classList.remove('hidden');
                } else {
                    alert('Gagal mengambil data pesan.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        // Reload page to reflect auto-read status if needed
        window.location.reload();
    }

    function openDeleteModal(actionUrl, senderName, subject) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteTargetSender').innerText = senderName;
        document.getElementById('deleteTargetSubject').innerText = `"${subject}"`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
