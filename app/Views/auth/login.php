<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 480px; margin: 50px auto;">
    <div class="auth-card" style="background: #fff; border-radius: 16px; padding: 40px 30px; box-shadow: 0 4px 25px rgba(0,0,0,0.07);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="font-size: 1.6em; color: #222; margin-bottom: 8px;">Masuk ke JOB4DIS</h1>
            <p style="color: #666; font-size: 0.95em;">Silakan masukkan akun Anda untuk melanjutkan</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-box alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/process-login') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="redirect_url" value="<?= esc($redirect_url) ?>">

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="username_email" style="display: block; font-weight: 600; margin-bottom: 8px; color: #444;">Username atau Email</label>
                <input type="text" id="username_email" name="username_email" value="<?= old('username_email') ?>" class="form-control" placeholder="Contoh: budi_santoso atau user@email.com" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95em;">
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="password" style="display: block; font-weight: 600; margin-bottom: 8px; color: #444;">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan kata sandi Anda" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95em;">
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="width: 100%; padding: 13px; font-size: 1em; font-weight: 600; border-radius: 8px;">
                Masuk Sekarang
            </button>
        </form>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 0.9em; color: #666;">
            Belum punya akun? <a href="<?= base_url('auth/choice') ?>" style="color: #007bff; font-weight: 600; text-decoration: none;">Daftar di sini</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
