# Website SMAN 2 Situbondo ("smada")

Aplikasi Website Sekolah SMAN 2 Situbondo dikembangkan menggunakan Laravel 12 dengan 2 role terpisah: **Admin** (panel manajemen konten) dan **User** (halaman publik / guest).

## Tech Stack
- **Framework**: Laravel 12 (PHP 8.3)
- **Database**: MySQL (`db_smada`)
- **Frontend / Templating**: Blade + Tailwind CSS (Vite)
- **Authentication**: Laravel Breeze + Custom Admin Guard (`role:admin`)

## Role System
- **Admin**: Akses panel manajemen via `/admin` (terautentikasi).
- **User**: Pengunjung publik tanpa login untuk melihat profil, berita, pengumuman, media, SPMB/PPDB, dan cek status kelulusan (Siklus/SKL).

## Setup Lokal
1. Clone repository:
   ```bash
   git clone https://github.com/lasticoo/SMAN-2-Situbondo.git
   cd SMAN-2-Situbondo
   ```
2. Install dependency PHP & Node:
   ```bash
   composer install
   npm install
   ```
3. Salin `.env.example` dan jalankan key generate:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Konfigurasi kredensial database di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306 (atau 3307 pada Laragon)
   DB_DATABASE=db_smada
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Jalankan migration dan seeder data awal:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Kredensial Admin Default*: `admin@smada.sch.id` / `password123`
6. Jalankan bundler asset Vite dan server lokal:
   ```bash
   npm run dev
   php artisan serve
   ```

## Alur Kontribusi
Lihat [CONTRIBUTING.md](./CONTRIBUTING.md) untuk panduan branching dan Pull Request.
