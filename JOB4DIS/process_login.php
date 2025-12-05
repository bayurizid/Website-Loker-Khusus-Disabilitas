<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, email, password_hash, nama_lengkap, profile_picture_path, status, role FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username_email, $username_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['logged_in'] = true;
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_profile_picture'] = $user['profile_picture_path'];
            $_SESSION['user_status'] = $user['status'];
            
            if ($user['role'] == 'company') {
                header("Location: company_landing.php");
            } else { 
                header("Location: home_user.php");
            }
            exit();
        } else {
            header("Location: login.php?error=" . urlencode("Password salah!"));
            exit();
        }
    } else {
        header("Location: login.php?error=" . urlencode("Username atau Email tidak ditemukan!"));
        exit();
    }
}
?>