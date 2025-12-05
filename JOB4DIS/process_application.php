<?php
session_start();
require_once 'config/db.php';

// Fungsi untuk redirect dengan pesan error
function redirect_with_error($job_id, $error_message) {
    header("Location: apply_job.php?job_id=" . $job_id . "&error=" . urlencode($error_message));
    exit();
}

// 1. Validasi Awal: Request Method dan Login
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'seeker') {
    header("Location: login.php?error=" . urlencode("Sesi tidak valid. Silakan login kembali."));
    exit();
}

// 2. Ambil dan Validasi Data dari Form
$user_id = $_SESSION['user_id'];
$job_id = filter_input(INPUT_POST, 'job_id', FILTER_VALIDATE_INT);

if (!$job_id) {
    header("Location: all_jobs.php?error=" . urlencode("Lowongan tidak valid."));
    exit();
}

// 3. Keamanan: Cek apakah pengguna sudah pernah melamar pekerjaan ini
$sql_check = "SELECT id FROM job_applications WHERE user_id = ? AND job_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $job_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows > 0) {
    redirect_with_error($job_id, "Anda sudah pernah melamar untuk posisi ini sebelumnya.");
}
$stmt_check->close();

// 4. Proses Upload File CV (Wajib)
$resume_path = null;
if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['resume'];
    $allowed_types = ['application/pdf'];
    if (!in_array($file['type'], $allowed_types)) {
        redirect_with_error($job_id, "Format CV harus PDF.");
    }
    if ($file['size'] > 2 * 1024 * 1024) { // Maks 2MB
        redirect_with_error($job_id, "Ukuran file CV tidak boleh lebih dari 2MB.");
    }
    
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_name = "resume_" . $user_id . "_" . $job_id . "_" . time() . "." . $file_extension;
    $target_dir = "uploads/resumes/";
    $resume_path = $target_dir . $unique_name;

    if (!move_uploaded_file($file['tmp_name'], $resume_path)) {
        redirect_with_error($job_id, "Gagal mengunggah CV. Silakan coba lagi.");
    }
} else {
    redirect_with_error($job_id, "CV wajib diunggah.");
}

// 5. Proses Upload Surat Lamaran (Opsional)
$cover_letter_path = null;
if (isset($_FILES['cover_letter_file']) && $_FILES['cover_letter_file']['error'] == UPLOAD_ERR_OK) {
    $file_cl = $_FILES['cover_letter_file'];
    $allowed_types_cl = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!in_array($file_cl['type'], $allowed_types_cl)) {
        redirect_with_error($job_id, "Format Surat Lamaran harus PDF, DOC, atau DOCX.");
    }
    if ($file_cl['size'] > 2 * 1024 * 1024) { // Maks 2MB
        redirect_with_error($job_id, "Ukuran file Surat Lamaran tidak boleh lebih dari 2MB.");
    }

    $file_cl_extension = pathinfo($file_cl['name'], PATHINFO_EXTENSION);
    $unique_name_cl = "coverletter_" . $user_id . "_" . $job_id . "_" . time() . "." . $file_cl_extension;
    $target_dir_cl = "uploads/cover_letters/";
    $cover_letter_path = $target_dir_cl . $unique_name_cl;

    if (!move_uploaded_file($file_cl['tmp_name'], $cover_letter_path)) {
        redirect_with_error($job_id, "Gagal mengunggah Surat Lamaran. Silakan coba lagi.");
    }
}

$status = 'Pending';
$sql_insert = "INSERT INTO job_applications (job_id, user_id, status, resume_path, cover_letter_path) VALUES (?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("iisss", $job_id, $user_id, $status, $resume_path, $cover_letter_path);

if ($stmt_insert->execute()) {
    header("Location: application_history.php?success=1");
    exit();
} else {
    redirect_with_error($job_id, "Terjadi kesalahan pada database. Lamaran tidak terkirim.");
}

$stmt_insert->close();
$conn->close();

?>