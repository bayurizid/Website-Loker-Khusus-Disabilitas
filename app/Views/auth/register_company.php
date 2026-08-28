<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 580px; margin: 40px auto;">
    <div class="auth-card" style="background: #fff; border-radius: 16px; padding: 40px 30px; box-shadow: 0 4px 25px rgba(0,0,0,0.07);">
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="width: 70px; height: 70px; margin: 0 auto 15px; background: #fff3e0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-building" style="font-size: 1.8em; color: #ff9800;"></i>
            </div>
            <h1 style="font-size: 1.6em; color: #222; margin-bottom: 8px;">Daftar Akun Perusahaan Mitra</h1>
            <p style="color: #666; font-size: 0.95em;">Buka pintu inklusivitas dan rekrut talenta terbaik Indonesia</p>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert-box alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/process-register-company') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="company_name" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Nama Perusahaan / Organisasi <span style="color: red;">*</span></label>
                <input type="text" id="company_name" name="company_name" value="<?= old('company_name') ?>" class="form-control" placeholder="Contoh: PT Bank Mandiri (Persero) Tbk" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="username" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Username Akun <span style="color: red;">*</span></label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" class="form-control" placeholder="Contoh: bank_mandiri_hrd" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="email" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Email Resmi / HRD <span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" class="form-control" placeholder="Contoh: recruitment@perusahaan.co.id" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
                <div class="form-group">
                    <label for="password" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Kata Sandi <span style="color: red;">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div class="form-group">
                    <label for="confirm_password" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Konfirmasi Sandi <span style="color: red;">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Ulangi kata sandi" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
            </div>

            <button type="submit" class="btn btn-secondary btn-block" style="width: 100%; padding: 13px; font-size: 1em; font-weight: 600; border-radius: 8px;">
                Daftarkan Perusahaan
            </button>
        </form>

        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 0.9em; color: #666;">
            Sudah punya akun perusahaan? <a href="<?= base_url('auth/login') ?>" style="color: #ff9800; font-weight: 600; text-decoration: none;">Masuk di sini</a> &bull;
            Daftar sebagai <a href="<?= base_url('auth/register') ?>" style="color: #007bff; font-weight: 600; text-decoration: none;">Pencari Kerja</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
