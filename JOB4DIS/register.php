<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if ($_SESSION['role'] == 'company') {
        header("Location: dashboard_company.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}
$form_data = $_SESSION['form_data'] ?? [];
$disabilitas_data = $form_data['disabilitas'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pencari Kerja - JOB4DIS</title>
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
            <div class="form-wrapper form-wrapper-wide">
                <h2>Buat Akun Pencari Kerja</h2>
                <?php
                if (isset($_GET['error'])) {
                    echo '<p class="message error">' . htmlspecialchars($_GET['error']) . '</p>';
                }
                ?>
                <form action="process_register.php" method="post" id="registerForm">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap:</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required value="<?php echo htmlspecialchars($form_data['nama_lengkap'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin:</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="Laki-laki" required <?php echo (($form_data['jenis_kelamin'] ?? '') === 'Laki-laki') ? 'checked' : ''; ?>> Laki-laki
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="Perempuan" required <?php echo (($form_data['jenis_kelamin'] ?? '') === 'Perempuan') ? 'checked' : ''; ?>> Perempuan
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Jenis Disabilitas yang Dialami:</label>
                        <p class="form-instruction">Pilih minimal satu jenis disabilitas.</p>
                        <div class="disability-options-container" id="disability-options">
                            <label class="disability-option-label">
                                <input type="checkbox" name="disabilitas[]" value="Mental" <?php echo in_array('Mental', $disabilitas_data) ? 'checked' : ''; ?>>
                                <img src="images/Mental.png" alt="Ikon Disabilitas Mental">
                                <span>Mental</span>
                            </label>
                             <label class="disability-option-label">
                                <input type="checkbox" name="disabilitas[]" value="Grahita" <?php echo in_array('Grahita', $disabilitas_data) ? 'checked' : ''; ?>>
                                <img src="images/Grahita.png" alt="Ikon Disabilitas Grahita">
                                <span>Grahita</span>
                            </label>
                             <label class="disability-option-label">
                                <input type="checkbox" name="disabilitas[]" value="Netra" <?php echo in_array('Netra', $disabilitas_data) ? 'checked' : ''; ?>>
                                <img src="images/Netra.png" alt="Ikon Disabilitas Netra">
                                <span>Netra</span>
                            </label>
                             <label class="disability-option-label">
                                <input type="checkbox" name="disabilitas[]" value="Rungu Wicara" <?php echo in_array('Rungu Wicara', $disabilitas_data) ? 'checked' : ''; ?>>
                                <img src="images/Rungu Wicara.png" alt="Ikon Disabilitas Rungu Wicara">
                                <span>Rungu Wicara</span>
                            </label>
                             <label class="disability-option-label">
                                <input type="checkbox" name="disabilitas[]" value="Daksa" <?php echo in_array('Daksa', $disabilitas_data) ? 'checked' : ''; ?>>
                                <img src="images/Daksa.png" alt="Ikon Disabilitas Daksa">
                                <span>Daksa</span>
                            </label>
                        </div>
                        <p id="disability-error" class="message error" style="display:none; margin-top: 10px;">Anda harus memilih minimal satu jenis disabilitas.</p>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrasi</button>
                </form>
                <p class="form-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </div>
    </main>

    <footer style="position: relative; bottom: 0; width: 100%;">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> JOB4DIS.</p>
        </div>
    </footer>
    <?php unset($_SESSION['form_data']); ?>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            const checkboxes = document.querySelectorAll('input[name="disabilitas[]"]');
            let isChecked = false;
            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    isChecked = true;
                    break;
                }
            }

            if (!isChecked) {
                event.preventDefault(); // Mencegah form untuk submit
                document.getElementById('disability-error').style.display = 'block';
            } else {
                document.getElementById('disability-error').style.display = 'none';
            }
        });
    </script>
</body>
</html>