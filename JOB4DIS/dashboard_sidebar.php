<?php
// Mendapatkan path halaman saat ini
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="dashboard-sidebar">
    <div class="profile-summary">
        <img src="<?php echo htmlspecialchars($_SESSION['user_profile_picture'] ?? 'images/placeholder_profile.png'); ?>" alt="Foto Profil" class="profile-pic">
        <h4><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></h4>
        <span class="profile-status-badge">
            STATUS: <?php echo ucfirst(htmlspecialchars($_SESSION['user_status'] ?? 'aktif')); ?>
        </span>
    </div>
    <nav class="dashboard-nav">
        <ul>
            <li><a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li><a href="application_history.php" class="<?php echo ($current_page == 'application_history.php') ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Riwayat Lamaran</a></li>
            <li><a href="edit_profile.php" class="<?php echo ($current_page == 'edit_profile.php') ? 'active' : ''; ?>"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
            <li><a href="saved_jobs.php" class="<?php echo ($current_page == 'saved_jobs.php') ? 'active' : ''; ?>"><i class="fas fa-bookmark"></i> Loker Disimpan</a></li>
            <hr>
            <li><a href="#"><i class="fas fa-question-circle"></i> Bantuan</a></li>
            <li><a href="home_user.php"><i class="fas fa-home"></i> Halaman Utama</a></li>
        </ul>
    </nav>
</aside>