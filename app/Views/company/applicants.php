<?= $this->extend('layout/dashboard_layout') ?>

<?= $this->section('dashboard_content') ?>
<div class="dashboard-header" style="margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
    <h2 style="margin: 0 0 5px 0; font-size: 1.4em; color: #222;">Daftar Pelamar Kerja Masuk</h2>
    <p style="color: #666; font-size: 0.9em;">Kelola berkas CV dan status lamaran para kandidat disabilitas.</p>
</div>

<?php if (!empty($applicants)): ?>
    <div style="display: flex; flex-direction: column; gap: 15px;">
        <?php foreach ($applicants as $app): ?>
            <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 22px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <img src="<?= base_url($app['profile_picture_path'] ?: 'images/placeholder_profile.png') ?>" alt="Foto" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 2px solid #e3f2fd;">
                        <div>
                            <h3 style="margin: 0 0 4px 0; font-size: 1.15em; color: #222;"><?= esc($app['nama_lengkap']) ?></h3>
                            <div style="font-size: 0.85em; color: #666;">
                                <span><i class="fas fa-briefcase"></i> Melamar Posisi: <strong><?= esc($app['job_title']) ?></strong></span> &bull; 
                                <span><i class="fas fa-universal-access"></i> Disabilitas: <strong style="color: #007bff;"><?= esc($app['jenis_disabilitas'] ?: '-') ?></strong></span>
                            </div>
                            <div style="font-size: 0.85em; color: #666; margin-top: 3px;">
                                <span><i class="fas fa-envelope"></i> <?= esc($app['email']) ?></span> &bull; 
                                <span><i class="fas fa-phone"></i> <?= esc($app['phone_number'] ?: '-') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Form -->
                    <form action="<?= base_url('company/update-status') ?>" method="POST" style="display: flex; align-items: center; gap: 8px;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
                        <select name="status" class="form-control" style="padding: 6px 12px; border-radius: 6px; font-size: 0.85em; border: 1px solid #ddd;">
                            <option value="pending" <?= ($app['status'] === 'pending') ? 'selected' : '' ?>>Menunggu Review</option>
                            <option value="interview" <?= ($app['status'] === 'interview') ? 'selected' : '' ?>>Jadwalkan Wawancara</option>
                            <option value="accepted" <?= ($app['status'] === 'accepted') ? 'selected' : '' ?>>Terima Bekerja</option>
                            <option value="rejected" <?= ($app['status'] === 'rejected') ? 'selected' : '' ?>>Tolak / Belum Sesuai</option>
                        </select>
                        <button type="submit" class="btn btn-outline btn-sm">Update</button>
                    </form>
                </div>

                <?php if (!empty($app['notes'])): ?>
                    <div style="background: #fcfcfc; padding: 12px 15px; border-radius: 8px; border: 1px solid #f0f0f0; margin-bottom: 12px; font-size: 0.88em; color: #555;">
                        <strong>Catatan / Pesan dari Pelamar:</strong>
                        <p style="margin: 4px 0 0 0;"><?= nl2br(esc($app['notes'])) ?></p>
                    </div>
                <?php endif; ?>

                <div style="display: flex; gap: 10px; align-items: center; border-top: 1px solid #f5f5f5; padding-top: 12px;">
                    <?php if (!empty($app['resume_path'])): ?>
                        <a href="<?= base_url($app['resume_path']) ?>" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fas fa-file-pdf"></i> Unduh / Buka Resume CV
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($app['cover_letter_path'])): ?>
                        <a href="<?= base_url($app['cover_letter_path']) ?>" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fas fa-file-alt"></i> Surat Lamaran
                        </a>
                    <?php endif; ?>
                    <span style="margin-left: auto; font-size: 0.8em; color: #888;">
                        <i class="far fa-clock"></i> Diajukan pada <?= date('d M Y, H:i', strtotime($app['created_at'])) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 50px 20px; background: #fcfcfc; border-radius: 12px; border: 1px dashed #ddd;">
        <i class="fas fa-user-friends" style="font-size: 3em; color: #ccc; margin-bottom: 15px;"></i>
        <h3 style="color: #444;">Belum Ada Pelamar yang Masuk</h3>
        <p style="color: #777;">Lowongan kerja yang Anda pasang belum menerima berkas lamaran dari pencari kerja.</p>
        <a href="<?= base_url('company/post-job') ?>" class="btn btn-secondary" style="margin-top: 10px;">Pasang Lowongan Baru</a>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
