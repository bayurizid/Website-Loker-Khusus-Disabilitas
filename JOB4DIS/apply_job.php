<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'seeker') {
    header("Location: login.php?error=" . urlencode("Anda harus login sebagai pencari kerja untuk melamar!"));
    exit();
}

if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    header("Location: all_jobs.php");
    exit();
}

$job_id = (int)$_GET['job_id'];
$user_id = $_SESSION['user_id'];

$job_detail = null;
$sql_job = "SELECT title, company_name FROM jobs WHERE id = ? AND is_active = TRUE";
$stmt_job = $conn->prepare($sql_job);
$stmt_job->bind_param("i", $job_id);
$stmt_job->execute();
$result_job = $stmt_job->get_result();
if ($result_job->num_rows > 0) {
    $job_detail = $result_job->fetch_assoc();
} else {
    header("Location: all_jobs.php?error=" . urlencode("Lowongan tidak ditemukan atau sudah tidak aktif."));
    exit();
}
$stmt_job->close();

$user_data = null;
$sql_user = "SELECT nama_lengkap, email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
} else {
     header("Location: logout.php?error=" . urlencode("Gagal memuat data pengguna."));
    exit();
}
$stmt_user->close();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamar Pekerjaan: <?php echo htmlspecialchars($job_detail['title']); ?> - JOB4DIS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="dashboard-page-header">
        <div class="container header-content">
            <div class="logo">
                <a href="home_user.php"><img src="images/logo.png" alt="JOB4DIS Logo"></a>
            </div>
            <div class="header-actions">
                <a href="dashboard.php" class="btn btn-primary-outline">Dashboard</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </header>

    <main class="dashboard-layout">
        <div class="container">
            <div class="dashboard-content">
                <h2>Formulir Lamaran Kerja</h2>
                <p>Anda akan melamar untuk posisi <strong><?php echo htmlspecialchars($job_detail['title']); ?></strong> di <strong><?php echo htmlspecialchars($job_detail['company_name']); ?></strong>.</p>
                <hr>
                
                <?php if(isset($_GET['error'])): ?>
                    <p class="message error"><?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>

                <form action="process_application.php" method="POST" enctype="multipart/form-data" class="profile-form">
                    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                    
                    <div class="form-section">
                        <h4>Informasi Anda</h4>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" value="<?php echo htmlspecialchars($user_data['nama_lengkap']); ?>" readonly>
                        </div>
                         <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Dokumen Lamaran</h4>
                        <div class="form-group">
                            <label for="resume">Unggah CV/Resume Anda (Format: PDF, maks: 2MB) <span class="required">*</span></label>
                            <input type="file" id="resume" name="resume" accept=".pdf" required>
                        </div>

                        <div class="form-group">
                            <label for="cover_letter_file">Unggah Surat Lamaran (Opsional)</label>
                            <input type="file" id="cover_letter_file" name="cover_letter_file" accept=".pdf,.doc,.docx">
                            <small>Anda bisa mengunggah file PDF, DOC, atau DOCX. Maks: 2MB.</small>
                        </div>
                        </div>

                    <div class="form-actions" style="text-align: right;">
                        <a href="view_job.php?id=<?php echo $job_id; ?>" class="btn btn-secondary-outline" style="margin-right: 10px;">Batal</a>
                        <button type="submit" class="btn btn-primary">Kirim Lamaran</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>