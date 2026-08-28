<?= $this->extend('layout/dashboard_layout') ?>

<?= $this->section('dashboard_content') ?>
<div class="dashboard-header" style="margin-bottom: 25px;">
    <h2 style="margin: 0 0 5px 0; font-size: 1.5em; color: #222;">Selamat Datang, <?= esc($user['nama_lengkap']) ?>! 👋</h2>
    <p style="color: #666; font-size: 0.95em;">Berikut adalah ringkasan aktivitas akun Anda di JOB4DIS.</p>
</div>

<!-- Stats Widgets -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 35px;">
    <?php if ($user['role'] === 'seeker'): ?>
        <div style="background: #e3f2fd; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: #2196f3; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5em;">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.6em; color: #0d47a1;"><?= $applied_count ?></h3>
                <span style="color: #555; font-size: 0.85em;">Lamaran Dikirim</span>
            </div>
        </div>

        <div style="background: #fff3e0; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: #ff9800; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5em;">
                <i class="fas fa-bookmark"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.6em; color: #e65100;"><?= $saved_count ?></h3>
                <span style="color: #555; font-size: 0.85em;">Lowongan Tersimpan</span>
            </div>
        </div>

        <div style="background: #e8f5e9; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: #4caf50; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5em;">
                <i class="fas fa-universal-access"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.1em; color: #1b5e20;"><?= esc($user['jenis_disabilitas'] ?: 'Belum diset') ?></h3>
                <span style="color: #555; font-size: 0.85em;">Kategori Disabilitas</span>
            </div>
        </div>
    <?php else: ?>
        <div style="background: #fff3e0; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: #ff9800; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5em;">
                <i class="fas fa-briefcase"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.1em; color: #e65100;">Perusahaan Mitra</h3>
                <span style="color: #555; font-size: 0.85em;">Status Akun Aktif</span>
            </div>
        </div>
        <div style="background: #e8f5e9; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: #4caf50; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5em;">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div>
                <a href="<?= base_url('company/post-job') ?>" style="text-decoration: none; color: #1b5e20; font-weight: 600;">+ Pasang Loker Baru</a>
                <span style="display: block; color: #555; font-size: 0.85em;">Publikasikan lowongan</span>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Recent Applications Section -->
<?php if ($user['role'] === 'seeker'): ?>
    <div style="margin-top: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 1.2em; color: #333;">Lamaran Terakhir Anda</h3>
            <a href="<?= base_url('dashboard/history') ?>" style="color: #007bff; font-size: 0.9em; text-decoration: none; font-weight: 500;">Lihat Semua &rarr;</a>
        </div>

        <?php if (!empty($recent_applications)): ?>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php foreach ($recent_applications as $app): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #fcfcfc; border: 1px solid #eee; border-radius: 10px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="<?= base_url($app['company_logo_path'] ?: 'images/placeholder_logo.png') ?>" alt="Logo" style="width: 45px; height: 45px; object-fit: contain; border-radius: 6px; border: 1px solid #eee; background: #fff;">
                            <div>
                                <h4 style="margin: 0 0 3px 0; font-size: 1em; color: #333;"><?= esc($app['job_title']) ?></h4>
                                <span style="font-size: 0.85em; color: #666;"><?= esc($app['company_name']) ?> &bull; <?= esc($app['location']) ?></span>
                            </div>
                        </div>
                        <div>
                            <?php 
                                $statusClass = 'badge-pending';
                                $statusText  = 'Menunggu Review';
                                if ($app['status'] === 'interview') { $statusClass = 'badge-interview'; $statusText = 'Tahap Wawancara'; }
                                elseif ($app['status'] === 'accepted') { $statusClass = 'badge-accepted'; $statusText = 'Diterima'; }
                                elseif ($app['status'] === 'rejected') { $statusClass = 'badge-rejected'; $statusText = 'Belum Lolos'; }
                            ?>
                            <span class="badge-status <?= $statusClass ?>"><?= $statusText ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 30px; background: #fcfcfc; border-radius: 10px; border: 1px dashed #ddd;">
                <p style="color: #777; margin-bottom: 12px;">Anda belum mengajukan lamaran pekerjaan apa pun.</p>
                <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm">Mulai Cari Lowongan</a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
