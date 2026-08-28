<?php
/**
 * Database Migration & Seeder Script for JOB4DIS
 * Dapat dijalankan via:
 * 1. Otomatis saat web pertama kali dibuka (Auto-Migrate)
 * 2. CLI: php migrate.php
 * 3. Browser: http://localhost/Website-Loker-Khusus-Disabilitas/JOB4DIS/migrate.php
 */

function run_job4dis_migration($conn, $silent = false) {
    $is_cli = (php_sapi_name() === 'cli');
    $log = function($msg) use ($silent, $is_cli) {
        if ($silent) return;
        if ($is_cli) {
            echo $msg . PHP_EOL;
        } else {
            echo "<p style='font-family:sans-serif; margin: 4px 0;'>$msg</p>";
        }
    };

    $log("=== Memulai Migrasi Database JOB4DIS ===");

    // 1. Buat Tabel-tabel
    $tables = [
        "users" => "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama_lengkap VARCHAR(255) NOT NULL,
            jenis_kelamin VARCHAR(20) NULL,
            username VARCHAR(100) NOT NULL UNIQUE,
            email VARCHAR(150) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            jenis_disabilitas VARCHAR(100) NULL,
            role ENUM('seeker', 'company', 'admin') NOT NULL DEFAULT 'seeker',
            phone_number VARCHAR(50) NULL,
            status VARCHAR(50) DEFAULT 'aktif',
            profile_picture_path VARCHAR(255) NULL,
            instagram_url VARCHAR(255) NULL,
            twitter_url VARCHAR(255) NULL,
            facebook_url VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "companies" => "CREATE TABLE IF NOT EXISTS companies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            company_name VARCHAR(255) NOT NULL,
            company_logo_path VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "jobs" => "CREATE TABLE IF NOT EXISTS jobs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            company_name VARCHAR(255) NOT NULL,
            company_logo_path VARCHAR(255) NULL,
            location VARCHAR(255) NULL,
            category VARCHAR(100) NULL,
            job_type VARCHAR(50) NULL,
            salary_range VARCHAR(100) NULL,
            education_level VARCHAR(100) NULL,
            experience_level VARCHAR(100) NULL,
            suitable_disability_types VARCHAR(255) NULL,
            job_description TEXT NULL,
            responsibilities TEXT NULL,
            qualifications TEXT NULL,
            posted_by_user_id INT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (posted_by_user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "saved_jobs" => "CREATE TABLE IF NOT EXISTS saved_jobs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            job_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user_job (user_id, job_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "job_applications" => "CREATE TABLE IF NOT EXISTS job_applications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            job_id INT NOT NULL,
            user_id INT NOT NULL,
            status VARCHAR(50) DEFAULT 'pending',
            resume_path VARCHAR(255) NULL,
            cover_letter_path VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "testimonials" => "CREATE TABLE IF NOT EXISTS testimonials (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_name VARCHAR(255) NOT NULL,
            job_title VARCHAR(255) NOT NULL,
            testimonial_text TEXT NOT NULL,
            photo_path VARCHAR(255) NULL,
            is_featured TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    ];

    foreach ($tables as $name => $sql) {
        if ($conn->query($sql) === TRUE) {
            $log("✅ Tabel '$name' berhasil dibuat / sudah ada.");
        } else {
            $log("❌ Gagal membuat tabel '$name': " . $conn->error);
        }
    }

    // 2. Masukkan Data Sampel / Demo (jika tabel users masih kosong)
    $res = $conn->query("SELECT COUNT(*) as cnt FROM users");
    $row = $res ? $res->fetch_assoc() : ['cnt' => 0];

    if ($row['cnt'] == 0) {
        $log("⚙️ Mengisi data sampel...");
        
        // Sample Company User
        $pass_company = password_hash('company123', PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (nama_lengkap, username, email, password_hash, role) 
                      VALUES ('PT Bank Rakyat Indonesia', 'company_demo', 'hr@bri.co.id', '$pass_company', 'company')");
        $company_user_id = $conn->insert_id;

        if ($company_user_id) {
            $conn->query("INSERT INTO companies (user_id, company_name, company_logo_path) 
                          VALUES ($company_user_id, 'PT Bank Rakyat Indonesia', 'images/logo_bri.png')");
        }

        // Sample Seeker User
        $pass_seeker = password_hash('seeker123', PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (nama_lengkap, jenis_kelamin, username, email, password_hash, jenis_disabilitas, role, phone_number, status) 
                      VALUES ('Budi Santoso', 'Laki-laki', 'seeker_demo', 'budi@example.com', '$pass_seeker', 'Daksa', 'seeker', '08123456789', 'aktif')");
        $seeker_user_id = $conn->insert_id;

        // Sample Jobs with real company logos & disability icons
        $conn->query("INSERT INTO jobs (title, company_name, company_logo_path, location, category, job_type, salary_range, education_level, experience_level, suitable_disability_types, job_description, responsibilities, qualifications, posted_by_user_id) 
                      VALUES 
                      ('IT Support Specialist', 'PT Bank Rakyat Indonesia', 'images/logo_bri.png', 'Jakarta (Remote / WFH)', 'IT', 'Full Time', 'Rp 5.000.000 - Rp 7.000.000', 'D3 / S1', '1-2 Tahun', 'Rungu Wicara,Daksa', 'Bertanggung jawab dalam pemeliharaan sistem IT kantor secara berkala dan troubleshooting remote.', '- Membantu troubleshoot kendala user\n- Maintenance server dan jaringan kantor', '- Menguasai dasar networking dan OS\n- Mampu berkomunikasi tertulis dengan baik', $company_user_id),
                      ('Digital Marketing & Content Creator', 'PT Gojek Indonesia', 'images/logo_gojek.png', 'Bandung (Hybrid)', 'Pemasaran', 'Full Time', 'Rp 4.500.000 - Rp 6.500.000', 'SMA/SMK / S1', 'Fresh Graduate', 'Daksa,Rungu Wicara', 'Membuat konten kreatif untuk media sosial perusahaan dan mengelola kampanye iklan digital.', '- Merancang feed instagram & konten TikTok\n- Analisis performa medsos mingguan', '- Kreatif dan terbiasa dengan tools desain\n- Memahami copywriting dasar', $company_user_id),
                      ('Staf Administrasi & Data Entry', 'PT Bank Mandiri (Persero) Tbk', 'images/logo_mandiri.png', 'Surabaya (WFO / Hybrid)', 'Administrasi', 'Kontrak', 'Rp 4.000.000 - Rp 5.000.000', 'SMA / D3', 'Fresh Graduate', 'Netra,Daksa', 'Menginput data operasional harian dan merapikan arsip dokumen perusahaan.', '- Input invoice dan rekap data penjualan\n- Manajemen file digital kantor', '- Teliti dan rapi dalam bekerja\n- Menguasai Microsoft Excel / Google Sheets', $company_user_id),
                      ('Customer Service Online', 'PT Teleperformance Indonesia', 'images/logo_teleperformance.png', 'Yogyakarta (Remote)', 'Customer Service', 'Full Time', 'Rp 3.800.000 - Rp 4.800.000', 'SMA/SMK', 'Fresh Graduate', 'Daksa,Netra', 'Melayani keluhan dan pertanyaan pelanggan melalui chat dan email.', '- Menjawab tiket bantuan pengguna\n- Memberikan solusi ramah dan cepat', '- Komunikasi tulisan yang baik\n- Mampu mengetik dengan cepat', $company_user_id)");

        $log("✅ Data demo awal berhasil ditambahkan!");
        $log("   📌 Akun Pencari Kerja: `seeker_demo` / Password: `seeker123`");
        $log("   📌 Akun Perusahaan: `company_demo` / Password: `company123`");
    }

    // 3. Check Testimonials Seeder
    $res_testi = $conn->query("SELECT COUNT(*) as cnt FROM testimonials");
    $row_testi = $res_testi ? $res_testi->fetch_assoc() : ['cnt' => 0];

    if ($row_testi['cnt'] == 0) {
        $conn->query("INSERT INTO testimonials (user_name, job_title, testimonial_text, photo_path, is_featured) 
                      VALUES 
                      ('Siti Rahmawati', 'Data Entry & Admin di Bank Mandiri', 'Platform JOB4DIS sangat memudahkan saya yang bertuna daksa mendapatkan pekerjaan dengan lingkungan kerja yang inklusif dan suportif.', 'images/testimoni_rina.jpg', 1),
                      ('Ahmad Fauzi', 'IT Support Specialist di BRI', 'Melalui JOB4DIS, proses rekrutmen berlangsung sangat transparan dan ramah disabilitas. Terima kasih!', 'images/testimoni_budi.jpg', 1),
                      ('Fitri Handayani', 'Digital Marketing di Gojek', 'Sangat bersyukur ada portal lowongan yang benar-benar peduli dengan kesetaraan kerja.', 'images/testimoni_fitri.jpg', 1)");
        $log("✅ Data testimoni demo berhasil ditambahkan!");
    }

    $log("=== 🎉 Migrasi Database Selesai dengan Sukses! ===");
}

// Jika file ini dipanggil langsung (CLI atau Direct Browser Request)
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'job4dis_db';

    $conn = @new mysqli($db_host, $db_user, $db_pass);
    if ($conn->connect_error) {
        echo "❌ Gagal terhubung ke MySQL: " . $conn->connect_error;
        exit(1);
    }
    $conn->query("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->select_db($db_name);
    $conn->set_charset("utf8mb4");

    run_job4dis_migration($conn, false);
    $conn->close();
}
?>
