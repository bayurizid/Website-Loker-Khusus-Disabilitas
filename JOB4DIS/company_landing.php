<?php
session_start();
// Pastikan hanya user dengan role 'company' yang bisa akses dan sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['role'] ?? '') !== 'company') {
    header("Location: login.php?error=Akses ditolak.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan - JOB4DIS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/company_landing_style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="company_landing.php">
                    <img src="images/logo.png" alt="JOB4DIS Logo">
                </a>
            </div>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="company_landing.php">Pasang Loker</a></li>
                    <li><a href="company_landing.php">Cari Loker</a></li>
                    <li><a href="#">Tips Loker</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <span class="welcome-user">Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']); ?>!</span>
                <span class="auth-separator">/</span>
                <a href="logout.php" class="auth-links">Logout</a>
            </div>
            <button class="mobile-nav-toggle" aria-label="Toggle Navigation Menu" aria-expanded="false" aria-controls="mainNav">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <main>
        <div class="landing-page-container">
            <div class="landing-message-box">
                <h2>Selamat Datang di JOB4DIS</h2>
                <p>
                    Terima kasih telah melakukan registrasi sebagai perusahaan.
                </p>
                <p>
                    Saat ini, sistem masih dalam tahap prototype dan fitur khusus untuk perusahaan belum tersedia di versi demo. Untuk kebutuhan demo, akses hanya terbatas pada fitur untuk Pelamar.
                </p>
                <p>
                    Apabila Anda ingin mencoba versi lengkap dari sistem ini, silakan hubungi pengembang untuk mendapatkan akses penuh.
                </p>
                
                <div class="contact-info">
                    <img src="images/logo_four_company.png" alt="Four Company Logo" class="developer-logo">
                    <h3 class="developer-name">FOUR COMPANY</h3>
                    <ul class="contact-details-list">
                        <li><i class="fas fa-phone"></i> 123-456-7890</li>
                        <li><i class="fas fa-globe"></i> <a href="http://www.FourCpny.com" target="_blank">www.FourCpny.com</a></li>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:FourCmpny@gmail.com">FourCmpny@gmail.com</a></li>
                    </ul>
                </div>

                <a href="logout.php" class="btn btn-secondary mt-1">Logout dan Kembali</a>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> JOB4DIS. Dibuat dengan cinta untuk inklusivitas.</p>
        </div>
    </footer>
    
    <script src="js/script.js"></script>
</body>
</html>