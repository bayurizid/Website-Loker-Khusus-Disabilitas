<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 650px; margin: 40px auto;">
    <div class="auth-card" style="background: #fff; border-radius: 16px; padding: 40px 30px; box-shadow: 0 4px 25px rgba(0,0,0,0.07);">
        <div style="text-align: center; margin-bottom: 25px;">
            <h1 style="font-size: 1.6em; color: #222; margin-bottom: 8px;">Daftar Akun Pencari Kerja</h1>
            <p style="color: #666; font-size: 0.95em;">Buat profil inklusif Anda dan raih peluang karir impian</p>
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

        <form action="<?= base_url('auth/process-register') ?>" method="POST" id="seekerRegisterForm">
            <?= csrf_field() ?>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="nama_lengkap" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" class="form-control" placeholder="Contoh: Budi Santoso" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="jenis_kelamin" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Jenis Kelamin <span style="color: red;">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki" <?= (old('jenis_kelamin') === 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= (old('jenis_kelamin') === 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="username" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Username <span style="color: red;">*</span></label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" class="form-control" placeholder="Contoh: budi_santoso" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="email" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Alamat Email <span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" class="form-control" placeholder="Contoh: budi@gmail.com" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="password" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Kata Sandi <span style="color: red;">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div class="form-group">
                    <label for="confirm_password" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Konfirmasi Sandi <span style="color: red;">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Ulangi kata sandi" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
            </div>

            <!-- Disability Category Checkboxes -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 600; margin-bottom: 4px; color: #444;">Jenis Disabilitas <span style="color: red;">*</span></label>
                <p style="font-size: 0.85em; color: #777; margin-bottom: 12px;">Pilih satu atau lebih kategori disabilitas yang Anda miliki:</p>

                <div class="disability-options-container" id="disability-options">
                    <?php 
                        $oldDis = old('disabilitas') ?: [];
                        if (!is_array($oldDis)) $oldDis = explode(',', $oldDis);
                        $disList = [
                            'Mental'       => 'Mental.png',
                            'Grahita'      => 'Grahita.png',
                            'Netra'        => 'Netra.png',
                            'Rungu Wicara' => 'Rungu Wicara.png',
                            'Daksa'        => 'Daksa.png',
                        ];
                    ?>
                    <?php foreach ($disList as $name => $img): ?>
                        <label class="disability-option-label">
                            <input type="checkbox" name="disabilitas[]" value="<?= $name ?>" <?= in_array($name, $oldDis) ? 'checked' : '' ?>>
                            <img src="<?= base_url('images/' . $img) ?>" alt="Ikon <?= $name ?>">
                            <span><?= $name ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="width: 100%; padding: 13px; font-size: 1em; font-weight: 600; border-radius: 8px;">
                Daftar Sekarang
            </button>
        </form>

        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 0.9em; color: #666;">
            Sudah punya akun? <a href="<?= base_url('auth/login') ?>" style="color: #007bff; font-weight: 600; text-decoration: none;">Masuk di sini</a> &bull;
            Daftar sebagai <a href="<?= base_url('auth/register-company') ?>" style="color: #ff9800; font-weight: 600; text-decoration: none;">Perusahaan</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
