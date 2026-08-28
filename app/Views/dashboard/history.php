<?= $this->extend('layout/dashboard_layout') ?>

<?= $this->section('dashboard_content') ?>
<div class="dashboard-header" style="margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
    <h2 style="margin: 0 0 5px 0; font-size: 1.4em; color: #222;">Riwayat Lamaran Pekerjaan</h2>
    <p style="color: #666; font-size: 0.9em;">Pantau status dan perkembangan lamaran pekerjaan yang telah Anda kirimkan.</p>
</div>

<?php if (!empty($applications)): ?>
    <div style="display: flex; flex-direction: column; gap: 15px;">
        <?php foreach ($applications as $app): ?>
            <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="<?= base_url($app['company_logo_path'] ?: 'images/placeholder_logo.png') ?>" alt="Logo" style="width: 55px; height: 55px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; background: #fff; padding: 3px;">
                    <div>
                        <h3 style="margin: 0 0 4px 0; font-size: 1.1em; color: #333;">
                            <a href="<?= base_url('jobs/detail/' . $app['job_id']) ?>" style="color: inherit; text-decoration: none;"><?= esc($app['job_title']) ?></a>
                        </h3>
                        <div style="font-size: 0.85em; color: #666;">
                            <span style="font-weight: 500; color: #007bff;"><?= esc($app['company_name']) ?></span> &bull; 
                            <span><i class="fas fa-map-marker-alt"></i> <?= esc($app['location']) ?></span> &bull; 
                            <span><i class="far fa-calendar-alt"></i> Dilamar pada <?= date('d M Y, H:i', strtotime($app['created_at'])) ?></span>
                        </div>
                        <?php if (!empty($app['resume_path'])): ?>
                            <div style="margin-top: 8px;">
                                <a href="<?= base_url($app['resume_path']) ?>" target="_blank" style="font-size: 0.8em; color: #555; text-decoration: none; background: #f0f4f8; padding: 3px 8px; border-radius: 4px;">
                                    <i class="fas fa-file-pdf"></i> Lihat File CV Terkirim
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="text-align: right;">
                    <?php 
                        $statusClass = 'badge-pending';
                        $statusText  = 'Menunggu Review';
                        if ($app['status'] === 'interview') { $statusClass = 'badge-interview'; $statusText = 'Tahap Wawancara'; }
                        elseif ($app['status'] === 'accepted') { $statusClass = 'badge-accepted'; $statusText = 'Diterima Bekerja'; }
                        elseif ($app['status'] === 'rejected') { $statusClass = 'badge-rejected'; $statusText = 'Belum Lolos'; }
                    ?>
                    <span class="badge-status <?= $statusClass ?>" style="font-size: 0.9em; padding: 6px 14px;"><?= $statusText ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 50px 20px; background: #fcfcfc; border-radius: 12px; border: 1px dashed #ddd;">
        <i class="fas fa-folder-open" style="font-size: 3em; color: #ccc; margin-bottom: 15px;"></i>
        <h3 style="color: #444;">Belum Ada Riwayat Lamaran</h3>
        <p style="color: #777;">Anda belum melamar pekerjaan apa pun. Mulai jelajahi ribuan peluang karir inklusif sekarang!</p>
        <a href="<?= base_url('jobs') ?>" class="btn btn-primary" style="margin-top: 15px;">Jelajahi Lowongan Kerja</a>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
