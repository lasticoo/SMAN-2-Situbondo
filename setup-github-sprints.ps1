# PowerShell Script: Create Milestones & Issues for SMADA Sprint Board

$repo = "lasticoo/SMAN-2-Situbondo"
$gh = if (Test-Path "C:\Program Files\GitHub CLI\gh.exe") { "C:\Program Files\GitHub CLI\gh.exe" } else { "gh" }

Write-Host "Creating Milestones via GitHub API..." -ForegroundColor Green
& $gh api "repos/$repo/milestones" -f title="Sprint 1 - Setup & Core Content (Minggu 1)" -f description="US-01..04, AD-01..05, AD-13, AD-15"
& $gh api "repos/$repo/milestones" -f title="Sprint 2 - Modul Informasi & Media (Minggu 2)" -f description="US-05..08, AD-06..10"
& $gh api "repos/$repo/milestones" -f title="Sprint 3 - Modul Spesifik & Integrasi (Minggu 3)" -f description="US-09..10, AD-11..12, AD-14, Testing & Prep Hosting"
& $gh api "repos/$repo/milestones" -f title="Sprint 4 - Deployment & Finalisasi (Minggu 4)" -f description="Deploy Hosting, Online Testing, & Release"

Write-Host "Creating Issues for Sprint 1..." -ForegroundColor Green
& $gh issue create --repo $repo --title "[AD-01] Manajemen Banner Web User" --body "Admin dapat mengelola banner aktif di Beranda User" --label "role:admin" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"
& $gh issue create --repo $repo --title "[AD-02] Manajemen Pop-Up Event" --body "Admin dapat mengelola pop-up event dengan rentang tanggal" --label "role:admin" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"
& $gh issue create --repo $repo --title "[AD-03] Manajemen Profile Sekolah" --body "Admin dapat mengubah visi, misi, sejarah, & struktur sekolah" --label "role:admin" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"
& $gh issue create --repo $repo --title "[AD-04] Manajemen Pegawai & Guru" --body "Admin dapat mengelola data guru & staf sekolah" --label "role:admin" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"
& $gh issue create --repo $repo --title "[AD-05] Manajemen Siswa" --body "Admin dapat mengelola data siswa & status publikasi" --label "role:admin" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"
& $gh issue create --repo $repo --title "[AD-13] Manajemen Akun Admin" --body "Admin dapat mengelola daftar akun admin & kredensial" --label "role:admin" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"
& $gh issue create --repo $repo --title "[AD-15] Pengaturan Kustomisasi Warna Tema" --body "Admin dapat mengubah primary & secondary color tema" --label "role:admin" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"

& $gh issue create --repo $repo --title "[US-01] Landing Page Publik" --body "User dapat melihat banner, pop-up, & berita di Beranda" --label "role:user" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"
& $gh issue create --repo $repo --title "[US-02] Profil Sekolah" --body "User dapat melihat visi, misi, sejarah, & struktur sekolah" --label "role:user" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"
& $gh issue create --repo $repo --title "[US-03] Civitas Akademik (Data Pegawai & Guru)" --body "User dapat melihat daftar guru & staf aktif" --label "role:user" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"
& $gh issue create --repo $repo --title "[US-04] Data Siswa Publik" --body "User dapat melihat data siswa yang dipublikasikan" --label "role:user" --milestone "Sprint 1 - Setup & Core Content (Minggu 1)"

Write-Host "Creating Issues for Sprint 2..." -ForegroundColor Green
& $gh issue create --repo $repo --title "[AD-06] Manajemen Pengumuman" --body "Admin dapat membuat pengumuman (draft/published)" --label "role:admin" --milestone "Sprint 2 - Modul Informasi & Media (Minggu 2)"
& $gh issue create --repo $repo --title "[AD-07] Manajemen Galeri Foto" --body "Admin dapat mengelola foto kegiatan sekolah" --label "role:admin" --milestone "Sprint 2 - Modul Informasi & Media (Minggu 2)"
& $gh issue create --repo $repo --title "[AD-08] Manajemen Video YouTube" --body "Admin dapat mengunggah URL video YouTube sekolah" --label "role:admin" --milestone "Sprint 2 - Modul Informasi & Media (Minggu 2)"
& $gh issue create --repo $repo --title "[AD-09] Manajemen Berita" --body "Admin dapat membuat & mempublikasikan artikel berita" --label "role:admin" --milestone "Sprint 2 - Modul Informasi & Media (Minggu 2)"
& $gh issue create --repo $repo --title "[AD-10] Manajemen Pesan Contact Masuk" --body "Admin dapat membaca & mengelola pesan masuk dari publik" --label "role:admin" --milestone "Sprint 2 - Modul Informasi & Media (Minggu 2)"

& $gh issue create --repo $repo --title "[US-05] Halaman Pengumuman Publik" --body "User dapat membaca pengumuman sekolah" --label "role:user" --milestone "Sprint 2 - Modul Informasi & Media (Minggu 2)"
& $gh issue create --repo $repo --title "[US-06] Halaman Media (Galeri Foto & Video)" --body "User dapat melihat galeri foto & memutar video YouTube" --label "role:user" --milestone "Sprint 2 - Modul Informasi & Media (Minggu 2)"
& $gh issue create --repo $repo --title "[US-07] Halaman Berita Publik" --body "User dapat membaca artikel berita sekolah" --label "role:user" --milestone "Sprint 2 - Modul Informasi & Media (Minggu 2)"
& $gh issue create --repo $repo --title "[US-08] Halaman Contact / Form Pesan" --body "User dapat mengirimkan pesan ke pihak sekolah" --label "role:user" --milestone "Sprint 2 - Modul Informasi & Media (Minggu 2)"

Write-Host "Creating Issues for Sprint 3..." -ForegroundColor Green
& $gh issue create --repo $repo --title "[AD-11] Manajemen SPMB / PPDB & Dokumen" --body "Admin dapat mengelola informasi & dokumen unduhan PPDB" --label "role:admin" --milestone "Sprint 3 - Modul Spesifik & Integrasi (Minggu 3)"
& $gh issue create --repo $repo --title "[AD-12] Manajemen Siklus / Kelulusan & SKL" --body "Admin dapat mengunggah Excel kelulusan, preset SKL, & publikasi" --label "role:admin" --milestone "Sprint 3 - Modul Spesifik & Integrasi (Minggu 3)"
& $gh issue create --repo $repo --title "[AD-14] Dashboard Admin & Ringkasan Fitur" --body "Admin dapat melihat ringkasan statistik data aplikasi" --label "role:admin" --milestone "Sprint 3 - Modul Spesifik & Integrasi (Minggu 3)"

& $gh issue create --repo $repo --title "[US-09] Halaman SPMB / PPDB Publik" --body "User dapat melihat informasi & mengunduh dokumen PPDB" --label "role:user" --milestone "Sprint 3 - Modul Spesifik & Integrasi (Minggu 3)"
& $gh issue create --repo $repo --title "[US-10] Siklus (Cek Status Kelulusan & SKL Online)" --body "User dapat memasukkan NISN untuk melihat kelulusan & unduh SKL" --label "role:user" --milestone "Sprint 3 - Modul Spesifik & Integrasi (Minggu 3)"

Write-Host "All Issues & Milestones Populated Successfully!" -ForegroundColor Green
