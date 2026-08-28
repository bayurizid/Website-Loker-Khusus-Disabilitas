# JOB4DIS - Website Portal Lowongan Kerja Khusus Disabilitas

JOB4DIS adalah platform portal lowongan kerja inklusif yang dirancang untuk menghubungkan penyandang disabilitas dengan perusahaan-perusahaan yang ramah disabilitas di Indonesia.

---

## 🚀 Cara Menjalankan Proyek (Plug & Play)

Proyek ini telah dikonfigurasi dengan fitur **Auto-Setup & Auto-Migration**. Anda tidak perlu melakukan konfigurasi database yang rumit.

### 1. Prasyarat
- **Laragon** / **XAMPP** (PHP 7.4 - 8.x dan MySQL)
- Web Browser

---

### 2. Langkah Menjalankan
1. **Clone repository** ini ke folder `www` (Laragon) atau `htdocs` (XAMPP):
   ```bash
   git clone https://github.com/bayurizid/Website-Loker-Khusus-Disabilitas.git
   ```
2. **Start Laragon / XAMPP**:
   - Pastikan service **Apache/Nginx** dan **MySQL** dalam status **Running (Start All)**.
3. **Buka di Browser**:
   - Akses: [http://localhost/Website-Loker-Khusus-Disabilitas/JOB4DIS/](http://localhost/Website-Loker-Khusus-Disabilitas/JOB4DIS/)
   - ✨ **Database `job4dis_db`, seluruh tabel, dan data demo awal akan dibuat secara otomatis saat halaman pertama kali dibuka!**

---

### 3. Opsi Migrasi Manual (Jika Diperlukan)
Jika Anda ingin me-reset atau menjalankan migrasi secara manual:
- **Via Terminal**:
  ```bash
  cd JOB4DIS
  php migrate.php
  ```
- **Via Browser**:
  Buka: `http://localhost/Website-Loker-Khusus-Disabilitas/JOB4DIS/migrate.php`
- **Via Import phpMyAdmin**:
  Import file `JOB4DIS/database.sql` ke phpMyAdmin.

---

## 🔑 Akun Demo Siap Pakai

| Peran (Role) | Username | Password | Keterangan |
|---|---|---|---|
| **Pencari Kerja** | `seeker_demo` | `seeker123` | Profil pelamar disabilitas |
| **Perusahaan** | `company_demo` | `company123` | Dashboard pasang lowongan |

---

## 📂 Struktur Direktori Utama
- `JOB4DIS/config/db.php` : Konfigurasi koneksi dan auto-migration engine.
- `JOB4DIS/database.sql` : Skema dan data awal database.
- `JOB4DIS/migrate.php` : Skrip migrasi mandiri.
- `JOB4DIS/images/` : Aset ikon disabilitas (`Daksa.png`, `Netra.png`, dsb), logo mitra, dan gambar testimoni.
- `JOB4DIS/uploads/` : Folder penyimpanan file resume & cover letter pelamar.
