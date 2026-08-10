# SOP & TANDUAN WORKFLOW TEKNIS TIM DEVELOPER (SMAN 2 SITUBONDO)

Dokumen ini berisi panduan teknis taktis lengkap untuk **2 Developer** dengan **2 Role** (Admin & User).

---

## 📘 BAGIAN I: SOP TEKNIS DEVELOPER (Dari Sebelum Coding s/d Push & Cleanup)

### Skenario: Developer Admin ingin mengerjakan Task `AD-01` (Manajemen Banner).

---

### Tahap 1: Persiapan Sebelum Coding (Sync Local & Buat Branch)

1. Buka terminal di VS Code / Laragon (`c:\laragon\www\smada`).
2. Pindah ke branch `develop` dan ambil update kode terbaru dari GitHub:
   ```bash
   git checkout develop
   git pull origin develop
   ```
3. Buat branch fitur baru sesuai konvensi penamaan:
   - **Format Role Admin**: `feature/admin-<nama-fitur>`
   - **Format Role User**: `feature/user-<nama-fitur>`
   
   *Contoh untuk AD-01*:
   ```bash
   git checkout -b feature/admin-banner
   ```
4. Verifikasi bahwa Anda berada di branch yang benar:
   ```bash
   git branch
   # Output harus menunjukkan * feature/admin-banner
   ```

---

### Tahap 2: Proses Pengerjaan Koding & Testing Lokal

1. Buka editor dan kerjakan file pada area folder role Anda:
   - **Controller**: `app/Http/Controllers/Admin/BannerController.php`
   - **Route**: `routes/admin.php`
   - **Views**: `resources/views/admin/banner/`
2. Jalankan migration dan seeder jika ada perubahan schema database:
   ```bash
   php artisan migrate:fresh --seed
   ```
3. Jalankan server lokal untuk melakukan tes mandiri:
   ```bash
   # Terminal 1: Vite bundler
   npm run dev

   # Terminal 2: Server Laravel
   php artisan serve
   ```
4. Buka browser di `http://127.0.0.1:8000/admin/login` dan pastikan seluruh fitur CRUD berjalan tanpa error.

---

### Tahap 3: Commit & Push ke GitHub

1. Hentikan server atau buka terminal baru, lalu cek status file yang diubah:
   ```bash
   git status
   ```
2. Tambahkan seluruh perubahan file:
   ```bash
   git add .
   ```
3. Buat commit dengan format pesan yang rapi:
   ```bash
   git commit -m "feat(admin): menyelesaikan CRUD manajemen banner AD-01"
   ```
4. Upload (push) branch fitur lokal ke GitHub:
   ```bash
   git push -u origin feature/admin-banner
   ```

---

### Tahap 4: Membuat Pull Request (PR) di Browser GitHub

1. Buka browser: [https://github.com/lasticoo/SMAN-2-Situbondo](https://github.com/lasticoo/SMAN-2-Situbondo)
2. Klik tombol hijau **"Compare & pull request"** yang muncul di atas.
3. **KONFIGURASI PR**:
   - **base**: `develop` *(Wajib! Jangan pilih main)*
   - **compare**: `feature/admin-banner`
4. Isi judul PR: `[AD-01] Implementasi Fitur Manajemen Banner Admin`.
5. Centang checklist pada deskripsi PR yang muncul otomatis.
6. Pada kolom **Reviewers** (sebelah kanan), pilih akun rekan tim Anda.
7. Klik tombol **"Create pull request"**.

---

### Tahap 5: Penanganan Revisi (Jika Ada Masukan dari Reviewer)

Jika reviewer meminta revisi (misal: "tambahkan error handling jika file bukan gambar"):
1. Tetap berada di branch `feature/admin-banner` pada laptop Anda.
2. Edit kodenya di VS Code.
3. Commit dan push perbaikan:
   ```bash
   git add .
   git commit -m "fix(admin): menambahkan validasi tipe file gambar banner"
   git push origin feature/admin-banner
   ```
4. Halaman PR di browser GitHub akan ter-update secara otomatis.

---

### Tahap 6: Cleanup Setelah PR Di-Merge

Setelah reviewer menekan tombol **Merge Pull Request** di GitHub (status PR menjadi *Merged* / ungu):
1. Kembalikan terminal Anda ke branch `develop`:
   ```bash
   git checkout develop
   ```
2. Tarik kode terbaru yang sudah menyatu dari GitHub:
   ```bash
   git pull origin develop
   ```
3. Hapus branch fitur lokal yang tugasnya sudah selesai:
   ```bash
   git branch -d feature/admin-banner
   ```

---
---

## 📕 BAGIAN II: SOP TEKNIS REVIEWER & INTEGRASI 2 ROLE (Review, Joint Testing, & Merge)

### Skenario: Reviewer (Developer User) me-review PR Developer Admin (`AD-01`), lalu menggabungkannya dengan fitur User (`US-01` / Landing Page) hingga rilis ke `main`.

---

### Tahap 1: Code Review di Website GitHub

1. **Reviewer** membuka email notifikasi atau masuk ke repository GitHub:
   [https://github.com/lasticoo/SMAN-2-Situbondo/pulls](https://github.com/lasticoo/SMAN-2-Situbondo/pulls)
2. Klik judul Pull Request yang diajukan (misal: `[AD-01] Implementasi Fitur Manajemen Banner Admin`).
3. Klik tab **Files changed** untuk melihat perbandingan baris kode:
   - 🟩 Warna hijau = kode baru ditambahkan.
   - 🟥 Warna merah = kode lama dihapus.
4. **Pemeriksaan Keamanan & Standar**:
   - Pastikan tidak ada file `.env` atau credential yang terikut.
   - Pastikan tidak ada perubahan tak sengaja di file shared (`routes/web.php`, `config/auth.php`).
   - Pastikan variabel dan logika Eloquent sudah rapi.
5. **Pemberian Keputusan Review**:
   - Klik tombol **Review changes** di kanan atas.
   - Pilih **Approve** (jika kode sudah bagus) atau **Request changes** (jika butuh revisi).
   - Klik **Submit review**.
6. **Eksekusi Merge**:
   - Klik tombol hijau **"Merge pull request"** &rarr; **"Confirm merge"**.

---

### Tahap 2: Penyatuan 2 Role di Branch `develop` Local

Misalkan `AD-01` (Admin Banner) dan `US-01` (User Landing Page) sama-sama sudah di-merge ke `develop` di GitHub.

Kedua developer membuka terminal laptop masing-masing:
```bash
git checkout develop
git pull origin develop
```
*Hasil*: Branch `develop` di laptop kedua developer kini memiliki **KODE ADMIN + KODE USER YANG SUDAH MENYATU!**

---

### Tahap 3: Joint Integration Testing (Uji Coba Bersama 2 Role)

Kedua pengembang duduk bersama (atau share screen) untuk memverifikasi keterhubungan 2 role:

1. **Jalankan Database Fresh & Server**:
   ```bash
   php artisan migrate:fresh --seed
   php artisan serve
   ```
2. **Uji Alur Admin (Input Data)**:
   - Login ke `http://127.0.0.1:8000/admin/login`.
   - Masuk ke menu **Banner**, buat 1 banner baru (Judul: "Selamat Datang di SMADA", Upload Gambar, Status: **Aktif**).
   - Klik Simpan.
3. **Uji Alur User (Konsumsi Data)**:
   - Buka tab browser baru ke `http://127.0.0.1:8000/`.
   - **Verifikasi**: Pastikan banner "Selamat Datang di SMADA" yang di-input Admin **langsung tampil di Beranda User**.
4. **Uji Skenario Non-Aktif**:
   - Di Admin Panel, ganti status banner menjadi **Draft / Non-Aktif**.
   - Refresh halaman Beranda User.
   - **Verifikasi**: Pastikan banner **hilang** dari tampilan User.

---

### Tahap 4: Production Release (`develop` &rarr; `main`)

Setelah seluruh fitur dalam Sprint tersebut lulus pengujian bersama di branch `develop`:

1. Buka GitHub: [https://github.com/lasticoo/SMAN-2-Situbondo/pulls](https://github.com/lasticoo/SMAN-2-Situbondo/pulls)
2. Klik **New Pull Request**:
   - **base**: `main`
   - **compare**: `develop`
3. Judul: `Release Sprint 1 - Core Auth, Admin Banner & User Landing Page`.
4. Klik **Create pull request**.
5. Kedua developer melakukan final check, lalu klik **Merge pull request**.
6. **Selesai!** Branch `main` resmi berisi versi rilis produksi aplikasi yang stabil dan teruji.
