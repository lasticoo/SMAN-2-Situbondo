# Alur Kontribusi & Pengerjaan Fitur (Sprint Workflow)

Project ini dikembangkan oleh 2 Developer dengan alur kerja **Agile Scrum + Daily Sprint**.

## Konvensi Branching
Setiap pengerjaan fitur/perbaikan wajib menggunakan branch terpisah dari branch `develop`:
- **Role Admin**: `feature/admin-<nama-fitur>` (contoh: `feature/admin-banner`)
- **Role User**: `feature/user-<nama-fitur>` (contoh: `feature/user-landing`)
- **Perbaikan Bug**: `fix/<deskripsi-singkat>`
- **Tugas Maintenance**: `chore/<deskripsi-singkat>`

## Langkah Pengerjaan Fitur
1. **Ambil Issue** dari GitHub Projects Board (kolom *Sprint Ready*).
2. **Switch ke branch `develop` & pull terbaru**:
   ```bash
   git checkout develop
   git pull origin develop
   ```
3. **Buat branch fitur baru**:
   ```bash
   git checkout -b feature/admin-banner
   ```
4. **Kerjakan fitur & lakukan testing mandiri**:
   - Jalankan `php artisan migrate:fresh --seed` jika ada penyesuaian schema.
   - Pastikan tidak ada credential / `.env` yang ter-commit.
5. **Commit & Push ke GitHub**:
   ```bash
   git add .
   git commit -m "feat(admin): implementasi CRUD banner AD-01"
   git push origin feature/admin-banner
   ```
6. **Buka Pull Request (PR)** ke branch `develop` (isi sesuai `pull_request_template.md`).
7. **Code Review**: Rekan tim me-review PR, memberikan feedback/revisi.
8. **Merge ke `develop`**: Setelah disetujui, merge PR dan update status issue di board ke *Done*.
9. **Testing Bersama**: Lakukan testing integrasi bersama di branch `develop`.
