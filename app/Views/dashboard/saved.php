<?= $this->extend('layout/dashboard_layout') ?>

<?= $this->section('dashboard_content') ?>
<div class="dashboard-header" style="margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
    <h2 style="margin: 0 0 5px 0; font-size: 1.4em; color: #222;">Lowongan Kerja Disimpan</h2>
    <p style="color: #666; font-size: 0.9em;">Daftar lowongan kerja yang telah Anda simpan / favoritkan.</p>
</div>

<?php if (!empty($saved_jobs)): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        <?php foreach ($saved_jobs as $job): ?>
            <div class="job-card-popular" style="position: relative; text-decoration: none; color: inherit; display: block; border-radius: 12px; background: #fff; padding: 20px; border: 1px solid #eee; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <button type="button" class="job-card-favorite-btn" aria-label="Hapus dari Simpanan" data-job-id="<?= $job['id'] ?>" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.2em; cursor: pointer;">
                    <i class="fas fa-heart" style="color: #e74c3c;"></i>
                </button>
                <div class="job-card-popular-header" style="display: flex; gap: 12px; align-items: center; margin-bottom: 12px;">
                    <img src="<?= base_url($job['company_logo_path']) ?>" alt="Logo" class="company-logo-popular">
                    <div>
                        <span class="company-name-popular" style="font-size: 0.85em; color: #777; display: block;"><?= esc($job['company_name']) ?></span>
                        <h3 class="job-title-popular" style="font-size: 1em; margin: 0; color: #333;"><?= esc($job['title']) ?></h3>
                    </div>
                </div>

                <p style="font-size: 0.85em; color: #666; margin-bottom: 10px;">
                    <i class="fas fa-map-marker-alt"></i> <?= esc($job['location']) ?>
                </p>

                <?php if (!empty($job['suitable_disability_types'])): ?>
                    <div class="job-disability-tags" style="margin-bottom: 12px;">
                        <?php 
                        $disabilityTypes = explode(',', $job['suitable_disability_types']);
                        foreach ($disabilityTypes as $type): 
                            $type = trim($type);
                            if (empty($type)) continue;
                            $iconFile = \App\Models\JobModel::getDisabilityIcon($type);
                        ?>
                            <span class="disability-tag">
                                <img src="<?= base_url('images/' . $iconFile) ?>" alt="Ikon <?= esc($type) ?>">
                                <?= esc($type) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div style="font-size: 0.9em; font-weight: 600; color: #2e7d32; margin-bottom: 15px;">
                    <i class="fas fa-dollar-sign"></i> <?= esc($job['salary_range'] ?: 'Negosiasi') ?>
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="<?= base_url('jobs/detail/' . $job['id']) ?>" class="btn btn-primary btn-sm" style="flex: 1; text-align: center; text-decoration: none;">Lihat Detail</a>
                    <a href="<?= base_url('jobs/apply/' . $job['id']) ?>" class="btn btn-outline btn-sm" style="flex: 1; text-align: center; text-decoration: none;">Lamar</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 50px 20px; background: #fcfcfc; border-radius: 12px; border: 1px dashed #ddd;">
        <i class="far fa-heart" style="font-size: 3em; color: #ccc; margin-bottom: 15px;"></i>
        <h3 style="color: #444;">Belum Ada Lowongan Disimpan</h3>
        <p style="color: #777;">Simpan lowongan yang menarik minat Anda dengan menekan tombol hati saat mencari kerja.</p>
        <a href="<?= base_url('jobs') ?>" class="btn btn-primary" style="margin-top: 15px;">Cari Lowongan</a>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
