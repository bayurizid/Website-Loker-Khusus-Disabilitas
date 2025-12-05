<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'company') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    $sql_company = "SELECT company_name, company_logo_path FROM companies WHERE user_id = ?";
    $stmt_company = mysqli_prepare($conn, $sql_company);
    mysqli_stmt_bind_param($stmt_company, "i", $user_id);
    mysqli_stmt_execute($stmt_company);
    $result_company = mysqli_stmt_get_result($stmt_company);
    $company = mysqli_fetch_assoc($result_company);
    $company_name = $company['company_name'];
    $company_logo = $company['company_logo_path'];
    mysqli_stmt_close($stmt_company);

    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $category = trim($_POST['category']);
    $job_description = trim($_POST['job_description']);
    $responsibilities = trim($_POST['responsibilities']);
    $qualifications = trim($_POST['qualifications']);

    if (empty($title) || empty($location) || empty($job_description)) {
        header("Location: post_job.php?error=Judul, Lokasi, dan Deskripsi wajib diisi.");
        exit();
    }

    $sql_insert = "INSERT INTO jobs (title, company_name, company_logo_path, location, category, job_description, responsibilities, qualifications, posted_by_user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssssssssi", $title, $company_name, $company_logo, $location, $category, $job_description, $responsibilities, $qualifications, $user_id);

    if (mysqli_stmt_execute($stmt_insert)) {
        header("Location: dashboard_company.php?success=Lowongan baru berhasil dipublikasikan!");
    } else {
        header("Location: post_job.php?error=Gagal mempublikasikan lowongan. Silakan coba lagi.");
    }
    mysqli_stmt_close($stmt_insert);
    mysqli_close($conn);
    exit();
}