<?php
session_start();
// Pastikan path ini benar sesuai struktur folder Anda
// Jika menggunakan config/db.php, ganti di sini
require_once 'config/db.php'; 

// Cek apakah pengguna sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=" . urlencode("Anda harus login untuk mengakses halaman ini!"));
    exit();
}

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];
$user_data = null;

// Query untuk mengambil semua data pengguna dari database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    }
    $stmt->close();
}

// Jika data pengguna tidak ditemukan, handle error (misalnya logout dan redirect)
if ($user_data === null) {
    header("Location: logout.php?error=" . urlencode("Gagal memuat data pengguna."));
    exit();
}

$nama_pengguna = htmlspecialchars($user_data['nama_lengkap'] ?? $user_data['username']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - JOB4DIS</title>
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
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="all_jobs.php">Cari Loker</a></li>
                    <li><a href="#tips-loker">Tips Loker</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <a href="dashboard.php" class="btn btn-primary-outline">Dashboard</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </header>

    <main class="dashboard-layout">
        <div class="container">
            <?php include 'dashboard_sidebar.php'; ?>

            <div class="dashboard-content">
                <h2>Selamat Datang, <?php echo $nama_pengguna; ?>!</h2>
                <hr>
                <p>Ini adalah halaman dashboard Anda. Dari sini, Anda dapat mengelola profil, melihat riwayat lamaran, dan menemukan peluang kerja baru.</p>
                
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-user-circle"></i> Informasi Pribadi</h3>
                        <a href="edit_profile.php" class="btn-edit-profile">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </a>
                    </div>
                    <div class="info-card-body">
                        <dl class="info-list">
                            <dt>Nama Lengkap</dt>
                            <dd><?php echo htmlspecialchars($user_data['nama_lengkap'] ?? '-'); ?></dd>
                            
                            <dt>Email</dt>
                            <dd><?php echo htmlspecialchars($user_data['email'] ?? '-'); ?></dd>

                            <dt>No. Telepon</dt>
                            <dd><?php echo htmlspecialchars($user_data['phone_number'] ?? 'Belum diisi'); ?></dd>

                            <dt>Jenis Kelamin</dt>
                            <dd><?php echo htmlspecialchars($user_data['jenis_kelamin'] ?? 'Belum diisi'); ?></dd>
                            
                            <dt>Jenis Disabilitas</dt>
                            <dd><?php echo htmlspecialchars($user_data['jenis_disabilitas'] ?? 'Belum diisi'); ?></dd>

                            <dt>Status Akun</dt>
                            <dd class="status-badge status-<?php echo strtolower($user_data['status']); ?>"><?php echo ucfirst(htmlspecialchars($user_data['status'])); ?></dd>

                            <dt>Media Sosial</dt>
                            <dd>
                                <div class="social-links">
                                    <?php if (!empty($user_data['instagram_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($user_data['instagram_url']); ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($user_data['twitter_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($user_data['twitter_url']); ?>" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($user_data['facebook_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($user_data['facebook_url']); ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                                    <?php endif; ?>
                                    <?php if (empty($user_data['instagram_url']) && empty($user_data['twitter_url']) && empty($user_data['facebook_url'])): ?>
                                        <span>Belum diisi</span>
                                    <?php endif; ?>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="dashboard-widgets">
                    <div class="widget">
                        <h3>Profil Anda</h3>
                        <p>Lengkapi profil Anda untuk meningkatkan peluang dilirik oleh perusahaan.</p>
                        <a href="edit_profile.php" class="btn btn-primary-outline">Lengkapi Profil</a>
                    </div>
                    <div class="widget">
                        <h3>Riwayat Lamaran</h3>
                        <p>Lihat status semua lamaran kerja yang telah Anda ajukan.</p>
                        <a href="application_history.php" class="btn btn-primary-outline">Lihat Riwayat</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>