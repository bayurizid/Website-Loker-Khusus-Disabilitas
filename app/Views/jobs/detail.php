<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
if (!function_exists('renderSmartSectionContent')) {
    function renderSmartSectionContent($text) {
        $text = trim($text ?? '');
        if (empty($text)) return '';
        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $text));
        $isList = false;
        $html = '';
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (empty($trimmed)) continue;
            
            if (preg_match('/^[-*•]\s*(.*)$/u', $trimmed, $matches)) {
                if (!$isList) {
                    $html .= '<ul class="modern-bullet-list">';
                    $isList = true;
                }
                $html .= '<li><i class="fas fa-check-circle bullet-icon"></i><span>' . esc($matches[1]) . '</span></li>';
            } else {
                if ($isList) {
                    $html .= '</ul>';
                    $isList = false;
                }
                $html .= '<p class="content-paragraph">' . esc($trimmed) . '</p>';
            }
        }
        if ($isList) {
            $html .= '</ul>';
        }
        return $html;
    }
}
?>

<main class="job-detail-page" style="padding: 30px 0 60px 0; background-color: #f8fafc;">
    <div class="container">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs" style="margin-bottom: 20px; font-size: 0.9em; color: #64748b;">
            <a href="<?= base_url('') ?>" style="color: #007bff; text-decoration: none;">Beranda</a> 
            <i class="fas fa-chevron-right" style="font-size: 0.75em; margin: 0 8px; color: #cbd5e1;"></i>
            <a href="<?= base_url('jobs?kategori=' . urlencode($job['category'])) ?>" style="color: #007bff; text-decoration: none;"><?= esc($job['category']) ?></a> 
            <i class="fas fa-chevron-right" style="font-size: 0.75em; margin: 0 8px; color: #cbd5e1;"></i>
            <span style="color: #1e293b; font-weight: 500;"><?= esc($job['title']) ?></span>
        </div>

        <div class="job-detail-layout">
            <!-- Main Content Area -->
            <div class="job-detail-main">
                <!-- Header Info -->
                <div class="job-detail-header">
                    <div>
                        <h1><?= esc($job['title']) ?></h1>
                        <div class="job-company-info-inline">
                            <a href="#company-profile" class="company-name-link">
                                <?= esc($job['company_name']) ?>
                            </a>
                            <span class="posted-time">
                                <i class="far fa-clock" style="margin-right: 4px;"></i> Dipasang <?= esc($job['posted_ago']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="job-actions">
                        <button type="button" class="job-card-favorite-btn btn-icon" data-job-id="<?= $job['id'] ?>" aria-label="Simpan Lowongan">
                            <i class="<?= $is_saved ? 'fas' : 'far' ?> fa-heart" style="<?= $is_saved ? 'color: #ef4444;' : '' ?>"></i>
                        </button>
                        
                        <?php if ($has_applied): ?>
                            <span class="btn btn-secondary" style="background: #10b981; border: none; cursor: default; padding: 12px 24px; border-radius: 10px;">
                                <i class="fas fa-check-circle"></i> Sudah Dilamar
                            </span>
                        <?php elseif (session()->get('logged_in')): ?>
                            <?php if (session()->get('role') === 'seeker'): ?>
                                <a href="<?= base_url('jobs/apply/' . $job['id']) ?>" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Lamar Pekerjaan
                                </a>
                            <?php else: ?>
                                <span class="badge-status badge-pending" style="padding: 10px 18px; font-size: 0.9em;">Akun Perusahaan</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?= base_url('auth/login?redirect=' . urlencode(base_url('jobs/apply/' . $job['id']))) ?>" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Login untuk Lamar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Modern Summary Info Tiles Grid -->
                <div class="job-summary-grid-modern">
                    <div class="summary-tile">
                        <div class="tile-icon tile-blue"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="tile-content">
                            <span class="tile-label">Lokasi</span>
                            <span class="tile-value"><?= esc($job['location']) ?></span>
                        </div>
                    </div>
                    <div class="summary-tile">
                        <div class="tile-icon tile-orange"><i class="fas fa-briefcase"></i></div>
                        <div class="tile-content">
                            <span class="tile-label">Tipe Pekerjaan</span>
                            <span class="tile-value"><?= esc($job['job_type']) ?></span>
                        </div>
                    </div>
                    <div class="summary-tile">
                        <div class="tile-icon tile-green"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="tile-content">
                            <span class="tile-label">Kisaran Gaji</span>
                            <span class="tile-value highlight-salary"><?= esc($job['salary_range'] ?: 'Negosiasi') ?></span>
                        </div>
                    </div>
                    <div class="summary-tile">
                        <div class="tile-icon tile-purple"><i class="fas fa-user-clock"></i></div>
                        <div class="tile-content">
                            <span class="tile-label">Pengalaman</span>
                            <span class="tile-value"><?= esc($job['experience_level'] ?: 'Fresh Graduate') ?></span>
                        </div>
                    </div>
                    <div class="summary-tile">
                        <div class="tile-icon tile-cyan"><i class="fas fa-graduation-cap"></i></div>
                        <div class="tile-content">
                            <span class="tile-label">Pendidikan</span>
                            <span class="tile-value"><?= esc($job['education_level'] ?: '-') ?></span>
                        </div>
                    </div>
                    <div class="summary-tile">
                        <div class="tile-icon tile-pink"><i class="fas fa-tags"></i></div>
                        <div class="tile-content">
                            <span class="tile-label">Kategori</span>
                            <span class="tile-value"><?= esc($job['category']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Disability Spotlight Box -->
                <?php if (!empty($job['suitable_disability_types'])): ?>
                    <div class="disability-spotlight-box">
                        <div class="disability-spotlight-title">
                            <i class="fas fa-universal-access"></i> Terbuka Khusus Disabilitas:
                        </div>
                        <div class="job-disability-tags" style="margin-bottom: 0;">
                            <?php 
                            $disabilityTypes = explode(',', $job['suitable_disability_types']);
                            foreach ($disabilityTypes as $type): 
                                $type = trim($type);
                                if (empty($type)) continue;
                                $iconFile = \App\Models\JobModel::getDisabilityIcon($type);
                            ?>
                                <span class="disability-tag" style="font-size: 0.9em; padding: 7px 14px; background: #fff; border: 1px solid #86efac; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                                    <img src="<?= base_url('images/' . $iconFile) ?>" alt="Ikon <?= esc($type) ?>">
                                    <?= esc($type) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Deskripsi Pekerjaan -->
                <?php if (!empty($job['job_description'])): ?>
                    <div class="detail-section-card">
                        <div class="section-title-wrapper">
                            <div class="section-title-icon"><i class="fas fa-file-alt"></i></div>
                            <h3 class="section-title">Deskripsi Pekerjaan</h3>
                        </div>
                        <div class="section-body">
                            <?= renderSmartSectionContent($job['job_description']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tanggung Jawab Pekerjaan -->
                <?php if (!empty($job['responsibilities'])): ?>
                    <div class="detail-section-card">
                        <div class="section-title-wrapper">
                            <div class="section-title-icon" style="background: #ffedd5; color: #ea580c;"><i class="fas fa-tasks"></i></div>
                            <h3 class="section-title">Tanggung Jawab Pekerjaan</h3>
                        </div>
                        <div class="section-body">
                            <?= renderSmartSectionContent($job['responsibilities']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Kualifikasi Pelamar -->
                <?php if (!empty($job['qualifications'])): ?>
                    <div class="detail-section-card">
                        <div class="section-title-wrapper">
                            <div class="section-title-icon" style="background: #f3e8ff; color: #9333ea;"><i class="fas fa-user-check"></i></div>
                            <h3 class="section-title">Kualifikasi Pelamar</h3>
                        </div>
                        <div class="section-body">
                            <?= renderSmartSectionContent($job['qualifications']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Keahlian yang Dibutuhkan -->
                <?php if (!empty($job['skills'])): ?>
                    <div class="detail-section-card">
                        <div class="section-title-wrapper">
                            <div class="section-title-icon" style="background: #dcfce7; color: #16a34a;"><i class="fas fa-tools"></i></div>
                            <h3 class="section-title">Keahlian yang Dibutuhkan</h3>
                        </div>
                        <div class="section-body">
                            <?= renderSmartSectionContent($job['skills']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Waktu / Jadwal Kerja -->
                <?php if (!empty($job['work_schedule'])): ?>
                    <div class="detail-section-card">
                        <div class="section-title-wrapper">
                            <div class="section-title-icon" style="background: #fce7f3; color: #db2777;"><i class="fas fa-calendar-alt"></i></div>
                            <h3 class="section-title">Waktu / Jadwal Kerja</h3>
                        </div>
                        <div class="section-body">
                            <p class="content-paragraph"><i class="far fa-clock" style="color: #0284c7; margin-right: 6px;"></i> <?= esc($job['work_schedule']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar Perusahaan -->
            <aside class="job-detail-sidebar">
                <div class="company-profile-card" id="company-profile">
                    <img src="<?= base_url($job['company_logo_path']) ?>" alt="Logo <?= esc($job['company_name']) ?>" class="company-logo-detail">
                    <h4><?= esc($job['company_name']) ?></h4>
                    
                    <div style="margin: 15px 0 20px 0; text-align: left; display: flex; flex-direction: column; gap: 8px;">
                        <?php if (!empty($company_detail['company_industry'])): ?>
                            <div class="company-meta-item" style="justify-content: flex-start;">
                                <i class="fas fa-industry" style="color: #007bff; width: 20px;"></i> 
                                <span><?= esc($company_detail['company_industry']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($company_detail['company_size'])): ?>
                            <div class="company-meta-item" style="justify-content: flex-start;">
                                <i class="fas fa-users" style="color: #10b981; width: 20px;"></i> 
                                <span><?= esc($company_detail['company_size']) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="company-meta-item" style="justify-content: flex-start;">
                            <i class="fas fa-map-marker-alt" style="color: #ef4444; width: 20px;"></i> 
                            <span><?= esc($job['location']) ?></span>
                        </div>
                    </div>

                    <?php if (!empty($company_detail['company_description'])): ?>
                        <div style="padding-top: 15px; border-top: 1px solid #f1f5f9; text-align: left; font-size: 0.88em; color: #64748b; line-height: 1.6;">
                            <strong style="color: #334155; display: block; margin-bottom: 5px;">Tentang Perusahaan:</strong>
                            <p style="margin: 0;"><?= nl2br(esc($company_detail['company_description'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 25px;">
                        <a href="<?= base_url('jobs?keyword=' . urlencode($job['company_name'])) ?>" class="btn btn-outline" style="font-size: 0.88em; width: 100%; border-radius: 10px; padding: 10px 15px; text-decoration: none; display: block; text-align: center;">
                            <i class="fas fa-search"></i> Lowongan Lainnya
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
