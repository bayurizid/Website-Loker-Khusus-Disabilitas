<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'company') {
    header("Location: login.php?error=Akses ditolak");
    exit();
}

$all_applicants = [];
// Ambil semua pelamar (aktif dan pasif)
$sql_all = "SELECT id, nama_lengkap, profile_picture_path, status FROM users WHERE role = 'seeker' AND status IN ('aktif', 'pasif') ORDER BY created_at DESC";
$result_all = mysqli_query($conn, $sql_all);
if ($result_all) {
    while ($row = mysqli_fetch_assoc($result_all)) {
        $row['profile_picture_path'] = (!empty($row['profile_picture_path']) && file_exists($row['profile_picture_path'])) ? $row['profile_picture_path'] : 'images/placeholder_profile.png';
        $all_applicants[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Kandidat - JOB4DIS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        </header>
    <main class="all-jobs-page">
        <div class="container">
            <div class="page-header" style="text-align: center; margin-bottom: 30px; padding-top: 20px;">
                <h1>Telusuri Semua Kandidat</h1>
                <p>Temukan talenta yang paling sesuai dengan kebutuhan perusahaan Anda.</p>
            </div>
            <?php if (empty($all_applicants)): ?>
                <p style="text-align:center;">Belum ada kandidat yang terdaftar di platform.</p>
            <?php else: ?>
                <div class="applicant-grid">
                    <?php foreach ($all_applicants as $applicant): ?>
                        <div class="applicant-card">
                            <?php if ($applicant['status'] == 'pasif'): ?>
                                <span class="status-badge-corner">Pasif</span>
                            <?php endif; ?>
                            <img src="<?php echo htmlspecialchars($applicant['profile_picture_path']); ?>" alt="Foto profil">
                            <h3><?php echo htmlspecialchars($applicant['nama_lengkap']); ?></h3>
                            <a href="view_applicant_profile.php?id=<?php echo $applicant['id']; ?>" class="btn btn-primary-outline btn-sm">Lihat Profil</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        </footer>
</body>
</html>