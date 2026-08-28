-- =======================================================
-- Database SQL Dump for JOB4DIS (Website Loker Khusus Disabilitas)
-- =======================================================

CREATE DATABASE IF NOT EXISTS `job4dis_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `job4dis_db`;

-- --------------------------------------------------------
-- Struktur Tabel: `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_lengkap` VARCHAR(255) NOT NULL,
    `jenis_kelamin` VARCHAR(20) NULL,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `jenis_disabilitas` VARCHAR(100) NULL,
    `role` ENUM('seeker', 'company', 'admin') NOT NULL DEFAULT 'seeker',
    `phone_number` VARCHAR(50) NULL,
    `status` VARCHAR(50) DEFAULT 'aktif',
    `profile_picture_path` VARCHAR(255) NULL,
    `instagram_url` VARCHAR(255) NULL,
    `twitter_url` VARCHAR(255) NULL,
    `facebook_url` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Struktur Tabel: `companies`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `companies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `company_name` VARCHAR(255) NOT NULL,
    `company_logo_path` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Struktur Tabel: `jobs`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jobs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `company_name` VARCHAR(255) NOT NULL,
    `company_logo_path` VARCHAR(255) NULL,
    `location` VARCHAR(255) NULL,
    `category` VARCHAR(100) NULL,
    `job_type` VARCHAR(50) NULL,
    `salary_range` VARCHAR(100) NULL,
    `education_level` VARCHAR(100) NULL,
    `experience_level` VARCHAR(100) NULL,
    `suitable_disability_types` VARCHAR(255) NULL,
    `job_description` TEXT NULL,
    `responsibilities` TEXT NULL,
    `qualifications` TEXT NULL,
    `posted_by_user_id` INT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`posted_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Struktur Tabel: `saved_jobs`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saved_jobs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `job_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_user_job` (`user_id`, `job_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Struktur Tabel: `job_applications`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `job_applications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `job_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `status` VARCHAR(50) DEFAULT 'pending',
    `resume_path` VARCHAR(255) NULL,
    `cover_letter_path` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Struktur Tabel: `testimonials`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_name` VARCHAR(255) NOT NULL,
    `job_title` VARCHAR(255) NOT NULL,
    `testimonial_text` TEXT NOT NULL,
    `photo_path` VARCHAR(255) NULL,
    `is_featured` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Data Awal / Sampel Demo
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `nama_lengkap`, `jenis_kelamin`, `username`, `email`, `password_hash`, `jenis_disabilitas`, `role`, `phone_number`, `status`) VALUES
(1, 'PT Bank Rakyat Indonesia', NULL, 'company_demo', 'hr@bri.co.id', '$2y$10$wN9a/n8m5eZ7WbWjY8f8keXwM1zP1pG5H9dD9oR7S1V0K4T2U1X3W', NULL, 'company', NULL, 'aktif'),
(2, 'Budi Santoso', 'Laki-laki', 'seeker_demo', 'budi@example.com', '$2y$10$wN9a/n8m5eZ7WbWjY8f8keXwM1zP1pG5H9dD9oR7S1V0K4T2U1X3W', 'Daksa', 'seeker', '08123456789', 'aktif');

INSERT INTO `companies` (`id`, `user_id`, `company_name`, `company_logo_path`) VALUES
(1, 1, 'PT Bank Rakyat Indonesia', 'images/logo_bri.png');

INSERT INTO `jobs` (`id`, `title`, `company_name`, `company_logo_path`, `location`, `category`, `job_type`, `salary_range`, `education_level`, `experience_level`, `suitable_disability_types`, `job_description`, `responsibilities`, `qualifications`, `posted_by_user_id`, `is_active`) VALUES
(1, 'IT Support Specialist', 'PT Bank Rakyat Indonesia', 'images/logo_bri.png', 'Jakarta (Remote / WFH)', 'IT', 'Full Time', 'Rp 5.000.000 - Rp 7.000.000', 'D3 / S1', '1-2 Tahun', 'Rungu Wicara,Daksa', 'Bertanggung jawab dalam pemeliharaan sistem IT kantor secara berkala dan troubleshooting remote.', '- Membantu troubleshoot kendala user\n- Maintenance server dan jaringan kantor', '- Menguasai dasar networking dan OS\n- Mampu berkomunikasi tertulis dengan baik', 1, 1),
(2, 'Digital Marketing & Content Creator', 'PT Gojek Indonesia', 'images/logo_gojek.png', 'Bandung (Hybrid)', 'Pemasaran', 'Full Time', 'Rp 4.500.000 - Rp 6.500.000', 'SMA/SMK / S1', 'Fresh Graduate', 'Daksa,Rungu Wicara', 'Membuat konten kreatif untuk media sosial perusahaan dan mengelola kampanye iklan digital.', '- Merancang feed instagram & konten TikTok\n- Analisis performa medsos mingguan', '- Kreatif dan terbiasa dengan tools desain\n- Memahami copywriting dasar', 1, 1),
(3, 'Staf Administrasi & Data Entry', 'PT Bank Mandiri (Persero) Tbk', 'images/logo_mandiri.png', 'Surabaya (WFO / Hybrid)', 'Administrasi', 'Kontrak', 'Rp 4.000.000 - Rp 5.000.000', 'SMA / D3', 'Fresh Graduate', 'Netra,Daksa', 'Menginput data operasional harian dan merapikan arsip dokumen perusahaan.', '- Input invoice dan rekap data penjualan\n- Manajemen file digital kantor', '- Teliti dan rapi dalam bekerja\n- Menguasai Microsoft Excel / Google Sheets', 1, 1),
(4, 'Customer Service Online', 'PT Teleperformance Indonesia', 'images/logo_teleperformance.png', 'Yogyakarta (Remote)', 'Customer Service', 'Full Time', 'Rp 3.800.000 - Rp 4.800.000', 'SMA/SMK', 'Fresh Graduate', 'Daksa,Netra', 'Melayani keluhan dan pertanyaan pelanggan melalui chat dan email.', '- Menjawab tiket bantuan pengguna\n- Memberikan solusi ramah dan cepat', '- Komunikasi tulisan yang baik\n- Mampu mengetik dengan cepat', 1, 1);

INSERT INTO `testimonials` (`id`, `user_name`, `job_title`, `testimonial_text`, `photo_path`, `is_featured`) VALUES
(1, 'Siti Rahmawati', 'Data Entry & Admin di Bank Mandiri', 'Platform JOB4DIS sangat memudahkan saya yang bertuna daksa mendapatkan pekerjaan dengan lingkungan kerja yang inklusif dan suportif.', 'images/testimoni_rina.jpg', 1),
(2, 'Ahmad Fauzi', 'IT Support Specialist di BRI', 'Melalui JOB4DIS, proses rekrutmen berlangsung sangat transparan dan ramah disabilitas. Terima kasih!', 'images/testimoni_budi.jpg', 1),
(3, 'Fitri Handayani', 'Digital Marketing di Gojek', 'Sangat bersyukur ada portal lowongan yang benar-benar peduli dengan kesetaraan kerja.', 'images/testimoni_fitri.jpg', 1);
