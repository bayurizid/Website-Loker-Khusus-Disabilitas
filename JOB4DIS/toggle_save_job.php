<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk menyimpan lowongan.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['job_id']) || !is_numeric($data['job_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID lowongan tidak valid.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$job_id = (int)$data['job_id'];

$sql_check = "SELECT * FROM saved_jobs WHERE user_id = ? AND job_id = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $job_id);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) > 0) {
    $sql_delete = "DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete);
    mysqli_stmt_bind_param($stmt_delete, "ii", $user_id, $job_id);
    if (mysqli_stmt_execute($stmt_delete)) {
        echo json_encode(['status' => 'success', 'action' => 'removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus lowongan.']);
    }
    mysqli_stmt_close($stmt_delete);
} else {
    $sql_insert = "INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $job_id);
    if (mysqli_stmt_execute($stmt_insert)) {
        echo json_encode(['status' => 'success', 'action' => 'saved']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan lowongan.']);
    }
    mysqli_stmt_close($stmt_insert);
}

mysqli_stmt_close($stmt_check);
mysqli_close($conn);
?>