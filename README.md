# JOB4DIS - Website Portal Lowongan Kerja Khusus Disabilitas (CodeIgniter 4)

JOB4DIS adalah platform portal lowongan kerja inklusif berbasis **CodeIgniter 4 (MVC)** yang dirancang untuk menghubungkan penyandang disabilitas dengan perusahaan-perusahaan yang ramah dan terbuka terhadap talenta disabilitas di Indonesia.

---

## 🚀 Cara Menjalankan Proyek

### 1. Prasyarat
- **PHP 8.1+** (disarankan PHP 8.2 / 8.3)
- **Composer**
- **MySQL / MariaDB** (via Laragon / XAMPP)

---

### 2. Langkah Cepat (Quick Start)

1. **Pastikan MySQL Aktif** (Klik **Start All** di Laragon / XAMPP).
2. **Setup Database & Migrasi CI4**:
   Jalankan perintah berikut di terminal root proyek:
   ```bash
   php spark migrate
   php spark db:seed MainSeeder
   ```
3. **Jalankan Web Server**:
   ```bash
   php spark serve
   ```
   Akses di browser: [http://localhost:8080](http://localhost:8080)

   *(Atau melalui Apache Laragon di: `http://localhost/Website-Loker-Khusus-Disabilitas/public/`)*

---

## 🔑 Akun Demo Siap Pakai

| Peran (Role) | Username / Email | Password | Keterangan |
|---|---|---|---|
| **Pencari Kerja** | `seeker_demo` / `budi@example.com` | `seeker123` | Profil pelamar disabilitas (Tuna Daksa) |
| **Perusahaan Mitra** | `company_demo` / `hr@bri.co.id` | `company123` | Dashboard kelola & pasang loker (PT BRI) |

---

## 🏗️ Struktur Arsitektur CI4
- `app/Controllers/` :
  - `Home.php` (Beranda & lowongan terpopuler)
  - `Jobs.php` (Filter multi-kategori, pencarian, detail loker, submit lamaran, AJAX bookmark)
  - `Auth.php` (Login, register seeker, register company, logout)
  - `Dashboard.php` (Ringkasan pelamar, edit profil, riwayat lamaran, bookmark)
  - `Company.php` (Landing perusahaan, pasang loker baru, manajemen pelamar)
- `app/Models/` : `UserModel`, `CompanyModel`, `JobModel`, `JobApplicationModel`, `SavedJobModel`, `TestimonialModel`.
- `app/Filters/` : `AuthFilter`, `CompanyFilter`.
- `app/Views/` : Template layout modular (`layout/main.php`, `layout/dashboard_layout.php`).
- `public/` : Aset publik (`css/`, `js/`, `images/`, `uploads/`).
- `app/Database/` : File migrasi resmi dan seeder.
