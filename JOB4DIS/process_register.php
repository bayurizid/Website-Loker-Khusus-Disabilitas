<?php
session_start();
require_once 'config/db.php'; 

function redirect_with_error($error_message, $form_data) {
    $_SESSION['form_data'] = $form_data;
    header("Location: register.php?error=" . urlencode($error_message));
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $disabilitas = $_POST['disabilitas'] ?? [];
    $role = 'seeker';
   
    $form_data = $_POST;

    if (empty($nama_lengkap) || empty($jenis_kelamin) || empty($username) || empty($email) || empty($password) || empty($disabilitas)) {
        redirect_with_error("Semua field wajib diisi, termasuk jenis disabilitas.", $form_data);
    }

    if ($password !== $confirm_password) {
        redirect_with_error("Konfirmasi password tidak cocok.", $form_data);
    }

    if (strlen($password) < 6) {
        redirect_with_error("Password minimal harus 6 karakter.", $form_data);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_error("Format email tidak valid.", $form_data);
    }

    $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $stmt_check->store_result();
    
    if ($stmt_check->num_rows > 0) {
        $stmt_check->close();
        redirect_with_error("Username atau Email sudah terdaftar. Silakan gunakan yang lain.", $form_data);
    }
    $stmt_check->close();

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $disabilitas_string = implode(',', $disabilitas);

    $sql_insert = "INSERT INTO users (nama_lengkap, jenis_kelamin, username, email, password_hash, jenis_disabilitas, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if ($stmt_insert === false) {
        redirect_with_error("Terjadi kesalahan pada server. Coba lagi nanti.", $form_data);
    }
    
    $stmt_insert->bind_param("sssssss", $nama_lengkap, $jenis_kelamin, $username, $email, $password_hash, $disabilitas_string, $role);

    if ($stmt_insert->execute()) {
        unset($_SESSION['form_data']);
        header("Location: login.php?success=" . urlencode("Registrasi berhasil! Silakan login."));
        exit();
    } else {
        redirect_with_error("Registrasi gagal. Silakan coba lagi.", $form_data);
    }

    $stmt_insert->close();
    $conn->close();

} else {
    header("Location: register.php");
    exit();
}
?>