<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Perusahaan - JOB4DIS</title>
    <link rel="stylesheet" href="css/style.css">
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
        <div class="form-page-container">
            <div class="form-wrapper">
                <h2>Registrasi Akun Perusahaan</h2>
                <?php if (isset($_GET['error'])) { echo '<p class="message error">' . htmlspecialchars($_GET['error']) . '</p>'; } ?>
                <form action="process_register_company.php" method="post">
                    <div class="form-group">
                        <label for="company_name">Nama Perusahaan:</label>
                        <input type="text" id="company_name" name="company_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Perusahaan (untuk login):</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Daftar Sebagai Perusahaan</button>
                </form>
                <p class="form-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
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