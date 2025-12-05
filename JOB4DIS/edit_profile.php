<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=" . urlencode("Anda harus login!"));
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT nama_lengkap, email, phone_number, status, profile_picture_path, instagram_url, twitter_url, facebook_url FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);


$_SESSION['user_profile_picture'] = $user_data['profile_picture_path'];
$_SESSION['user_status'] = $user_data['status'];

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - JOB4DIS</title>
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
            <?php include 'dashboard_sidebar.php'; ?>
            <div class="dashboard-content">
                <h2>Edit Profile</h2>
                <hr>
                
                <?php if (isset($_GET['error'])): ?>
                    <p class="message error"><?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>
                <?php if (isset($_GET['success'])): ?>
                    <p class="message success"><?php echo htmlspecialchars($_GET['success']); ?></p>
                <?php endif; ?>

                <form action="process_edit_profile.php" method="POST" enctype="multipart/form-data" class="profile-form">
                    
                    <div class="form-section">
                        <h4>Personal Information</h4>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" required value="<?php echo htmlspecialchars($user_data['nama_lengkap'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" readonly disabled value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>">
                            <small>Email tidak dapat diubah.</small>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">No Telepon / WA <span class="required">*</span></label>
                            <input type="tel" id="phone_number" name="phone_number" required value="<?php echo htmlspecialchars($user_data['phone_number'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Status <span class="required">*</span></label>
                            <div class="radio-group">
                                <label><input type="radio" name="status" value="aktif" <?php echo (($user_data['status'] ?? 'aktif') == 'aktif') ? 'checked' : ''; ?>> Saya aktif mencari kerja dan terbuka untuk mendapatkan tawaran kerja (Aktif)</label>
                                <label><input type="radio" name="status" value="pasif" <?php echo (($user_data['status'] ?? '') == 'pasif') ? 'checked' : ''; ?>> Saya tidak mencari kerja, tetapi tertarik untuk mendapatkan tawaran kerja (Pasif)</label>
                                <label><input type="radio" name="status" value="nonaktif" <?php echo (($user_data['status'] ?? '') == 'nonaktif') ? 'checked' : ''; ?>> Saya tidak mencari kerja dan tidak tertarik dengan tawaran kerja (Nonaktif)</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Upload Foto Profile</h4>
                        <div class="form-group">
                            <label for="profile_picture">Maksimal ukuran file: 2MB. File: .jpg, .jpeg, .png</label>
                            <input type="file" id="profile_picture" name="profile_picture" accept=".jpg, .jpeg, .png">
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Social Networks (Opsional)</h4>
                        <div class="form-group">
                            <label for="instagram_url">Instagram</label>
                            <input type="url" id="instagram_url" name="instagram_url" placeholder="https://www.instagram.com/username" value="<?php echo htmlspecialchars($user_data['instagram_url'] ?? ''); ?>">
                        </div>
                         <div class="form-group">
                            <label for="twitter_url">Twitter</label>
                            <input type="url" id="twitter_url" name="twitter_url" placeholder="https://twitter.com/username" value="<?php echo htmlspecialchars($user_data['twitter_url'] ?? ''); ?>">
                        </div>
                         <div class="form-group">
                            <label for="facebook_url">Facebook</label>
                            <input type="url" id="facebook_url" name="facebook_url" placeholder="https://www.facebook.com/username" value="<?php echo htmlspecialchars($user_data['facebook_url'] ?? ''); ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-secondary">Update Profile</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>