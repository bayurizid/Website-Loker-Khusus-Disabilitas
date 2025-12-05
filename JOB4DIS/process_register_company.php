<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = trim($_POST['company_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'company';

    if (empty($company_name) || empty($email) || empty($password)) {
        header("Location: register_company.php?error=" . urlencode("Semua field wajib diisi!"));
        exit();
    }
    if ($password !== $confirm_password) {
        header("Location: register_company.php?error=" . urlencode("Password tidak cocok!"));
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    mysqli_begin_transaction($conn);

    try {
        $sql_user = "INSERT INTO users (username, email, password_hash, nama_lengkap, role) VALUES (?, ?, ?, ?, ?)";
        $stmt_user = mysqli_prepare($conn, $sql_user);
        $username = $email;
        mysqli_stmt_bind_param($stmt_user, "sssss", $username, $email, $password_hash, $company_name, $role);
        mysqli_stmt_execute($stmt_user);

        $user_id = mysqli_insert_id($conn);
        if ($user_id == 0) {
            throw new Exception("Gagal membuat akun pengguna.");
        }

        $sql_company = "INSERT INTO companies (user_id, company_name) VALUES (?, ?)";
        $stmt_company = mysqli_prepare($conn, $sql_company);
        mysqli_stmt_bind_param($stmt_company, "is", $user_id, $company_name);
        mysqli_stmt_execute($stmt_company);
        
        mysqli_commit($conn);

        header("Location: login.php?success=" . urlencode("Registrasi perusahaan berhasil! Silakan login."));
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        if(mysqli_errno($conn) == 1062) {
            header("Location: register_company.php?error=" . urlencode("Email sudah terdaftar. Silakan gunakan email lain."));
        } else {
            header("Location: register_company.php?error=" . urlencode("Terjadi kesalahan: " . $e->getMessage()));
        }
        exit();
    }

} else {
    header("Location: register_company.php");
    exit();
}