<?php
/**
 * Database Connection & Auto-Setup Configuration for JOB4DIS
 */

if (!defined('DB_SERVER')) define('DB_SERVER', '127.0.0.1');
if (!defined('DB_USERNAME')) define('DB_USERNAME', 'root');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', '');
if (!defined('DB_NAME')) define('DB_NAME', 'job4dis_db');

// 1. Hubungkan ke MySQL Server
$conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if ($conn->connect_error) {
    die("Koneksi MySQL Gagal: " . $conn->connect_error . ". Pastikan service MySQL di Laragon / XAMPP sudah berjalan (Start All).");
}

// 2. Buat Database Otomatis jika belum ada (Plug & Play)
$conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db(DB_NAME);
$conn->set_charset("utf8mb4");

// 3. Auto-Migrate jika tabel belum ada (saat pertama kali clone/dijalankan)
$check_tables = $conn->query("SHOW TABLES LIKE 'users'");
if (!$check_tables || $check_tables->num_rows === 0) {
    $migrate_file = __DIR__ . '/../migrate.php';
    if (file_exists($migrate_file)) {
        require_once $migrate_file;
        if (function_exists('run_job4dis_migration')) {
            run_job4dis_migration($conn, true); // Jalankan migrasi senyap di background
        }
    }
}
?>