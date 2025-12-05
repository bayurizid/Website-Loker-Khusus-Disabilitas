<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: " . ($_SESSION['role'] == 'company' ? 'dashboard_company.php' : 'dashboard.php'));
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jenis Akun - JOB4DIS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="index.php"><img src="images/logo.png" alt="JOB4DIS Logo"></a>
            </div>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="post_job.php">Pasang Loker</a></li>
                    <li><a href="all_jobs.php">Cari Loker</a></li>
                    <li><a href="#">Tips Loker</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container choice-page-container">
            <h1>Buat Akun Baru</h1>
            <p>Pilih jenis akun yang sesuai dengan kebutuhan Anda.</p>
            <div class="choice-wrapper">
                <a href="register.php" class="choice-card">
                    <i class="fas fa-user-tie choice-icon"></i>
                    <h3>Saya Pencari Kerja</h3>
                    <p>Buat profil, unggah CV, dan lamar pekerjaan impian Anda dengan mudah. Temukan peluang karir terbaik di sini.</p>
                    <div class="btn btn-primary">Daftar Sebagai Pelamar</div>
                </a>
                <a href="register_company.php" class="choice-card">
                    <i class="fas fa-building choice-icon"></i>
                    <h3>Saya Perusahaan</h3>
                    <p>Pasang iklan lowongan kerja, kelola lamaran, dan temukan kandidat paling potensial untuk perusahaan Anda.</p>
                    <div class="btn btn-secondary">Daftar Sebagai Perusahaan</div>
                </a>
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> JOB4DIS.</p>
        </div>
    </footer>
</body>
</html>