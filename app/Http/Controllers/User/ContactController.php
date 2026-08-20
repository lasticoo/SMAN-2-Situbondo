<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\NewContactMessageMail;
use App\Models\ColorSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Tampilkan Halaman Hubungi Kami / Kontak Publik SMAN 2 Situbondo (US-08)
     */
    public function index(Request $request)
    {
        // 1. Ambil Data Profil & Kontak Instansi dari Database
        $schoolProfile = DB::table('school_profile')->first();
        
        // 2. Ambil Pengaturan Warna Tema Dinamis
        $colorSetting = ColorSetting::first();

        // 3. Daftar Opsi Subjek Pesan Sesuai Spesifikasi Fitur
        $subjects = [
            'Kritik',
            'Saran',
            'Pengaduan',
            'Pertanyaan',
            'Masukan',
            'Keluhan',
            'Permintaan Informasi',
            'Aspirasi',
            'Laporan',
            'Konsultasi',
            'Umpan Balik',
            'Permohonan Bantuan',
            'Apresiasi',
            'Lainnya',
        ];

        return view('user.contact.index', compact(
            'schoolProfile',
            'colorSetting',
            'subjects'
        ));
    }

    /**
     * Simpan Pesan Kontak Baru & Kirim Email Notifikasi ke Admin (US-08 Store)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Formulir
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|min:5|max:3000',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email maksimal 150 karakter.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'subject.required' => 'Subjek pesan wajib dipilih.',
            'subject.max' => 'Subjek pesan maksimal 150 karakter.',
            'message.required' => 'Isi pesan wajib diisi.',
            'message.min' => 'Isi pesan minimal berisi 5 karakter.',
            'message.max' => 'Isi pesan maksimal 3000 karakter.',
        ]);

        // 2. Simpan ke Tabel contact_messages Menggunakan Query Builder
        $messageId = DB::table('contact_messages')->insertGetId([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
            'created_at' => Carbon::now(),
        ]);

        // 3. Kirim Email Notifikasi ke Admin
        try {
            $adminEmail = config('mail.admin_contact_receiver') ?: env('ADMIN_CONTACT_EMAIL', 'rizalwibowo716@gmail.com');
            
            $mailData = [
                'id' => $messageId,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'created_at' => Carbon::now(),
                'admin_url' => url('/admin/contact'),
            ];

            Mail::to($adminEmail)->send(new NewContactMessageMail($mailData));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email notifikasi kontak admin: ' . $e->getMessage());
        }

        // 4. Redirect Kembali dengan Notifikasi Sukses
        return redirect()->route('contact.index')->with(
            'success',
            'Pesan Anda berhasil dikirim! Tim SMAN 2 Situbondo akan segera menindaklanjuti pesan Anda.'
        );
    }
}
