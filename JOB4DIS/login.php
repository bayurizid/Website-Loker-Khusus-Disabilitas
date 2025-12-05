<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (($_SESSION['role'] ?? '') === 'company') {
        header("Location: company_landing.php");
    } else {
        header("Location: home_user.php");
    }
    exit();
}
$redirect_url = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JOB4DIS</title>
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
                    <li><a href="register_company.php">Pasang Loker</a></li>
                    <li><a href="all_jobs.php">Cari Loker</a></li>
                    <li><a href="#">Tips Loker</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="form-page-container">
            <div class="form-wrapper">
                <h2>Login ke Akun Anda</h2>
                <?php
                if (isset($_GET['error'])) { echo '<p class="message error">' . htmlspecialchars($_GET['error']) . '</p>'; }
                if (isset($_GET['success'])) { echo '<p class="message success">' . htmlspecialchars($_GET['success']) . '</p>'; }
                if (isset($_GET['loggedout'])) { echo '<p class="message success">Anda berhasil logout.</p>'; }
                ?>
                <form action="process_login.php" method="post">
                    <?php if (!empty($redirect_url)): ?>
                        <input type="hidden" name="redirect" value="<?php echo $redirect_url; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="username_email">Username atau Email:</label>
                        <input type="text" id="username_email" name="username_email" required value="<?php echo isset($_SESSION['form_login_data']['username_email']) ? htmlspecialchars($_SESSION['form_login_data']['username_email']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p class="form-link">Belum punya akun? <a href="register_choice.php">Registrasi di sini</a></p>
                </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> JOB4DIS. Dibuat dengan cinta untuk inklusivitas.</p>
        </div>
    </footer>
    <?php unset($_SESSION['form_login_data']); ?>
</body>
</html>