<?= $this->extend('layout/dashboard_layout') ?>

<?= $this->section('dashboard_content') ?>
<div class="dashboard-header" style="margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
    <h2 style="margin: 0 0 5px 0; font-size: 1.4em; color: #222;">Pengaturan & Edit Profil</h2>
    <p style="color: #666; font-size: 0.9em;">Perbarui data pribadi dan informasi kontak Anda.</p>
</div>

<form action="<?= base_url('dashboard/update-profile') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        <!-- Left Col: Avatar -->
        <div style="flex: 0 0 160px; text-align: center;">
            <img src="<?= base_url($user['profile_picture_path'] ?: 'images/placeholder_profile.png') ?>" alt="Foto Profil" style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid #f0f4f8; margin-bottom: 15px;">
            <label for="profile_picture" class="btn btn-outline btn-sm" style="display: inline-block; cursor: pointer; font-size: 0.85em;">
                <i class="fas fa-camera"></i> Ganti Foto
            </label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" style="display: none;" onchange="document.getElementById('fileNameSpan').innerText = this.files[0] ? this.files[0].name : '';">
            <span id="fileNameSpan" style="display: block; font-size: 0.75em; color: #666; margin-top: 5px;"></span>
        </div>

        <!-- Right Col: Fields -->
        <div style="flex: 1; min-width: 280px;">
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Nama Lengkap / Nama Perusahaan</label>
                <input type="text" name="nama_lengkap" value="<?= esc($user['nama_lengkap']) ?>" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 18px;">
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Alamat Email</label>
                    <input type="email" value="<?= esc($user['email']) ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9;" readonly>
                </div>
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Nomor WhatsApp / HP</label>
                    <input type="text" name="phone_number" value="<?= esc($user['phone_number']) ?>" class="form-control" placeholder="Contoh: 08123456789" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Status Kesiapan Kerja</label>
                <select name="status" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="aktif" <?= ($user['status'] === 'aktif') ? 'selected' : '' ?>>Aktif Mencari Kerja</option>
                    <option value="pasif" <?= ($user['status'] === 'pasif') ? 'selected' : '' ?>>Terbuka untuk Peluang (Pasif)</option>
                    <option value="tidak_aktif" <?= ($user['status'] === 'tidak_aktif') ? 'selected' : '' ?>>Sedang Tidak Mencari Kerja</option>
                </select>
            </div>

            <?php if ($user['role'] === 'company' && $company): ?>
                <div style="margin-top: 25px; padding-top: 15px; border-top: 1px solid #eee;">
                    <h4 style="margin: 0 0 15px 0; color: #333;">Informasi Profil Perusahaan</h4>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Industri Perusahaan</label>
                        <input type="text" name="company_industry" value="<?= esc($company['company_industry']) ?>" class="form-control" placeholder="Misal: Perbankan, Teknologi, Manufaktur" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Tentang Perusahaan</label>
                        <textarea name="company_description" rows="3" class="form-control" placeholder="Deskripsi profil perusahaan..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"><?= esc($company['company_description']) ?></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Alamat Kantor</label>
                        <textarea name="company_address" rows="2" class="form-control" placeholder="Alamat lengkap..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"><?= esc($company['company_address']) ?></textarea>
                    </div>
                </div>
            <?php endif; ?>

            <div style="margin-top: 25px; padding-top: 15px; border-top: 1px solid #eee;">
                <h4 style="margin: 0 0 15px 0; color: #333;">Media Sosial (Opsional)</h4>
                
                <div class="form-group" style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <i class="fab fa-instagram" style="font-size: 1.4em; color: #e1306c; width: 25px;"></i>
                    <input type="text" name="instagram_url" value="<?= esc($user['instagram_url']) ?>" class="form-control" placeholder="Username / URL Instagram" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div class="form-group" style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <i class="fab fa-twitter" style="font-size: 1.4em; color: #1da1f2; width: 25px;"></i>
                    <input type="text" name="twitter_url" value="<?= esc($user['twitter_url']) ?>" class="form-control" placeholder="Username / URL Twitter" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div class="form-group" style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <i class="fab fa-facebook" style="font-size: 1.4em; color: #4267b2; width: 25px;"></i>
                    <input type="text" name="facebook_url" value="<?= esc($user['facebook_url']) ?>" class="form-control" placeholder="Username / URL Facebook" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-primary" style="padding: 11px 28px;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
