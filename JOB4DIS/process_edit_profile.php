<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: edit_profile.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$nama_lengkap = trim($_POST['nama_lengkap']);
$phone_number = trim($_POST['phone_number']);
$status = in_array($_POST['status'], ['aktif', 'pasif', 'nonaktif']) ? $_POST['status'] : 'aktif';
$instagram_url = trim($_POST['instagram_url']);
$twitter_url = trim($_POST['twitter_url']);
$facebook_url = trim($_POST['facebook_url']);

if (empty($nama_lengkap) || empty($phone_number)) {
    header("Location: edit_profile.php?error=" . urlencode("Nama Lengkap dan No. Telepon wajib diisi!"));
    exit();
}

$sql_update_fields = [];
$params = [];
$types = '';

$sql_update_fields[] = "nama_lengkap = ?";
$params[] = $nama_lengkap;
$types .= 's';

$sql_update_fields[] = "phone_number = ?";
$params[] = $phone_number;
$types .= 's';

$sql_update_fields[] = "status = ?";
$params[] = $status;
$types .= 's';

$sql_update_fields[] = "instagram_url = ?";
$params[] = (!empty($instagram_url) && filter_var($instagram_url, FILTER_VALIDATE_URL)) ? $instagram_url : null;
$types .= 's';

$sql_update_fields[] = "twitter_url = ?";
$params[] = (!empty($twitter_url) && filter_var($twitter_url, FILTER_VALIDATE_URL)) ? $twitter_url : null;
$types .= 's';

$sql_update_fields[] = "facebook_url = ?";
$params[] = (!empty($facebook_url) && filter_var($facebook_url, FILTER_VALIDATE_URL)) ? $facebook_url : null;
$types .= 's';

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['profile_picture'];
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2 MB

    if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
        $upload_dir = 'uploads/profile_pictures/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = $user_id . '_' . time() . '.' . $file_extension;
        $destination = $upload_dir . $new_filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $sql_update_fields[] = "profile_picture_path = ?";
            $params[] = $destination;
            $types .= 's';
        } else {
            header("Location: edit_profile.php?error=" . urlencode("Gagal memindahkan file yang diupload."));
            exit();
        }
    } else {
        header("Location: edit_profile.php?error=" . urlencode("File tidak valid! Pastikan format (JPG/PNG) dan ukuran (maks 2MB) sesuai."));
        exit();
    }
}

if (!empty($sql_update_fields)) {
    $sql = "UPDATE users SET " . implode(", ", $sql_update_fields) . " WHERE id = ?";
    $types .= 'i';
    $params[] = $user_id;

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        header("Location: edit_profile.php?success=" . urlencode("Profil berhasil diperbarui!"));
    } else {
        header("Location: edit_profile.php?error=" . urlencode("Gagal memperbarui profil."));
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: edit_profile.php");
}

mysqli_close($conn);
exit();
?>