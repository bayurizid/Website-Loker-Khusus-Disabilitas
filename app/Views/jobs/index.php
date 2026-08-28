<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<section class="all-jobs-page">
    <div class="container">
        <div class="breadcrumbs">
            <a href="<?= base_url('') ?>">Beranda</a> <i class="fas fa-chevron-right"></i>
            <span>Semua Lowongan Kerja</span>
        </div>

        <h1 class="page-title">Cari dan Temukan Karir Impian Anda</h1>
        <p class="page-subtitle">Temukan ribuan peluang kerja inklusif yang ramah dan terbuka bagi penyandang disabilitas di seluruh Indonesia.</p>

        <!-- Search Panel -->
        <div class="search-panel-all-jobs">
            <form action="<?= base_url('jobs') ?>" method="GET" class="search-form-all-jobs">
                <div class="search-field-group">
                    <i class="fas fa-search"></i>
                    <input type="text" name="keyword" placeholder="Masukkan kata kunci" value="<?= esc($keyword) ?>">
                </div>
                <!-- Custom Lokasi Dropdown -->
                <div class="custom-select-wrapper search-field-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="hidden" name="lokasi" value="<?= esc($location) ?>">
                    <div class="custom-select-trigger">
                        <span class="selected-text">
                            <?php if (!empty($location)): ?>
                                <?= esc($location) ?>
                            <?php else: ?>
                                Semua Lokasi
                            <?php endif; ?>
                        </span>
                        <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 0.75em; color: #888; padding: 0;"></i>
                    </div>
                    <div class="custom-select-options">
                        <div class="custom-option <?= empty($location) ? 'selected' : '' ?>" data-value="" data-label="Semua Lokasi">
                            <span>Semua Lokasi</span>
                        </div>
                        <div class="custom-option <?= ($location === 'Jakarta' || $location === 'Jakarta Pusat') ? 'selected' : '' ?>" data-value="Jakarta" data-label="Jakarta">
                            <span>Jakarta (Jabodetabek)</span>
                        </div>
                        <div class="custom-option <?= ($location === 'Bandung') ? 'selected' : '' ?>" data-value="Bandung" data-label="Bandung">
                            <span>Bandung</span>
                        </div>
                        <div class="custom-option <?= ($location === 'Surabaya') ? 'selected' : '' ?>" data-value="Surabaya" data-label="Surabaya">
                            <span>Surabaya</span>
                        </div>
                        <div class="custom-option <?= ($location === 'Yogyakarta') ? 'selected' : '' ?>" data-value="Yogyakarta" data-label="Yogyakarta">
                            <span>Yogyakarta</span>
                        </div>
                        <div class="custom-option <?= ($location === 'Remote') ? 'selected' : '' ?>" data-value="Remote" data-label="Remote">
                            <span>Remote / WFH</span>
                        </div>
                    </div>
                </div>

                <!-- Custom Kategori Dropdown -->
                <div class="custom-select-wrapper search-field-group">
                    <i class="fas fa-briefcase"></i>
                    <input type="hidden" name="kategori" value="<?= esc($category) ?>">
                    <div class="custom-select-trigger">
                        <span class="selected-text">
                            <?php if (!empty($category)): ?>
                                <?= esc($category) ?>
                            <?php else: ?>
                                Semua Kategori
                            <?php endif; ?>
                        </span>
                        <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 0.75em; color: #888; padding: 0;"></i>
                    </div>
                    <div class="custom-select-options">
                        <div class="custom-option <?= empty($category) ? 'selected' : '' ?>" data-value="" data-label="Semua Kategori">
                            <i class="fas fa-th-large" style="color: #007bff; width: 20px; text-align: center;"></i>
                            <span>Semua Kategori</span>
                        </div>
                        <div class="custom-option <?= ($category === 'IT') ? 'selected' : '' ?>" data-value="IT" data-label="IT">
                            <i class="fas fa-laptop-code" style="color: #6f42c1; width: 20px; text-align: center;"></i>
                            <span>IT & Komputer</span>
                        </div>
                        <div class="custom-option <?= ($category === 'Administrasi') ? 'selected' : '' ?>" data-value="Administrasi" data-label="Administrasi">
                            <i class="fas fa-file-signature" style="color: #17a2b8; width: 20px; text-align: center;"></i>
                            <span>Administrasi</span>
                        </div>
                        <div class="custom-option <?= ($category === 'Customer Service') ? 'selected' : '' ?>" data-value="Customer Service" data-label="Customer Service">
                            <i class="fas fa-headset" style="color: #e83e8c; width: 20px; text-align: center;"></i>
                            <span>Customer Service</span>
                        </div>
                        <div class="custom-option <?= ($category === 'Desain Grafis') ? 'selected' : '' ?>" data-value="Desain Grafis" data-label="Desain Grafis">
                            <i class="fas fa-palette" style="color: #fd7e14; width: 20px; text-align: center;"></i>
                            <span>Desain Grafis</span>
                        </div>
                        <div class="custom-option <?= ($category === 'Pemasaran') ? 'selected' : '' ?>" data-value="Pemasaran" data-label="Pemasaran">
                            <i class="fas fa-bullhorn" style="color: #20c997; width: 20px; text-align: center;"></i>
                            <span>Pemasaran</span>
                        </div>
                        <div class="custom-option <?= ($category === 'Akuntansi') ? 'selected' : '' ?>" data-value="Akuntansi" data-label="Akuntansi">
                            <i class="fas fa-calculator" style="color: #28a745; width: 20px; text-align: center;"></i>
                            <span>Akuntansi & Keuangan</span>
                        </div>
                        <div class="custom-option <?= ($category === 'Pendidikan') ? 'selected' : '' ?>" data-value="Pendidikan" data-label="Pendidikan">
                            <i class="fas fa-graduation-cap" style="color: #007bff; width: 20px; text-align: center;"></i>
                            <span>Pendidikan</span>
                        </div>
                        <div class="custom-option <?= ($category === 'Perbankan' || $category === 'Perbankan / Jasa Finansial') ? 'selected' : '' ?>" data-value="Perbankan" data-label="Perbankan">
                            <i class="fas fa-university" style="color: #343a40; width: 20px; text-align: center;"></i>
                            <span>Perbankan</span>
                        </div>
                    </div>
                </div>
                <!-- Custom Disability Selector with Icons -->
                <div class="custom-select-wrapper search-field-group" id="disabilitySelectWrapper">
                    <i class="fas fa-universal-access"></i>
                    <input type="hidden" name="disabilitas" id="disabilityHiddenInput" value="<?= esc($disability) ?>">
                    <div class="custom-select-trigger" id="disabilitySelectTrigger">
                        <span class="selected-text" id="disabilitySelectedText">
                            <?php if (!empty($disability)): ?>
                                <img src="<?= base_url('images/' . \App\Models\JobModel::getDisabilityIcon($disability)) ?>" class="select-opt-icon" alt="">
                                <?= esc($disability) ?>
                            <?php else: ?>
                                Semua Disabilitas
                            <?php endif; ?>
                        </span>
                        <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 0.75em; color: #888; padding: 0;"></i>
                    </div>
                    <div class="custom-select-options" id="disabilitySelectOptions">
                        <div class="custom-option <?= empty($disability) ? 'selected' : '' ?>" data-value="" data-label="Semua Disabilitas" data-icon="">
                            <i class="fas fa-check-circle" style="color: #007bff; width: 22px; text-align: center;"></i>
                            <span>Semua Disabilitas</span>
                        </div>
                        <div class="custom-option <?= ($disability === 'Daksa') ? 'selected' : '' ?>" data-value="Daksa" data-label="Daksa" data-icon="<?= base_url('images/Daksa.png') ?>">
                            <img src="<?= base_url('images/Daksa.png') ?>" alt="Daksa">
                            <span>Daksa (Fisik)</span>
                        </div>
                        <div class="custom-option <?= ($disability === 'Rungu Wicara') ? 'selected' : '' ?>" data-value="Rungu Wicara" data-label="Rungu Wicara" data-icon="<?= base_url('images/Rungu Wicara.png') ?>">
                            <img src="<?= base_url('images/Rungu Wicara.png') ?>" alt="Rungu Wicara">
                            <span>Rungu Wicara</span>
                        </div>
                        <div class="custom-option <?= ($disability === 'Netra') ? 'selected' : '' ?>" data-value="Netra" data-label="Netra" data-icon="<?= base_url('images/Netra.png') ?>">
                            <img src="<?= base_url('images/Netra.png') ?>" alt="Netra">
                            <span>Netra (Penglihatan)</span>
                        </div>
                        <div class="custom-option <?= ($disability === 'Grahita') ? 'selected' : '' ?>" data-value="Grahita" data-label="Grahita" data-icon="<?= base_url('images/Grahita.png') ?>">
                            <img src="<?= base_url('images/Grahita.png') ?>" alt="Grahita">
                            <span>Grahita (Intelektual)</span>
                        </div>
                        <div class="custom-option <?= ($disability === 'Mental') ? 'selected' : '' ?>" data-value="Mental" data-label="Mental" data-icon="<?= base_url('images/Mental.png') ?>">
                            <img src="<?= base_url('images/Mental.png') ?>" alt="Mental">
                            <span>Mental (Psikososial)</span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
                <?php if (!empty($keyword) || !empty($location) || !empty($category) || !empty($disability)): ?>
                    <a href="<?= base_url('jobs') ?>" class="btn btn-outline" style="border: 1px solid #ccc; padding: 12px 18px; border-radius: 6px; text-decoration: none; color: #555;">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="results-header" style="margin: 25px 0; display: flex; justify-content: space-between; align-items: center;">
            <p style="color: #666; margin: 0;">Menampilkan <strong><?= count($jobs) ?></strong> dari total <strong><?= $total_results ?></strong> lowongan</p>
        </div>

        <!-- Jobs Grid -->
        <div class="popular-jobs-grid">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <?php 
                        $isSaved = in_array($job['id'], $saved_job_ids ?? []);
                        $heartClass = $isSaved ? 'fas' : 'far';
                        $heartStyle = $isSaved ? 'color: #e74c3c;' : '';
                    ?>
                    <a href="<?= base_url('jobs/detail/' . $job['id']) ?>" class="job-card-popular" data-category="<?= esc($job['category']) ?>">
                        <button type="button" class="job-card-favorite-btn" aria-label="Simpan Lowongan" data-job-id="<?= $job['id'] ?>">
                            <i class="<?= $heartClass ?> fa-heart" style="<?= $heartStyle ?>"></i>
                        </button>
                        <div class="job-card-popular-header">
                            <img src="<?= base_url($job['company_logo_path']) ?>" alt="Logo <?= esc($job['company_name']) ?>" class="company-logo-popular">
                            <div class="job-card-company-info">
                                <span class="company-name-popular"><?= esc($job['company_name']) ?></span>
                                <h3 class="job-title-popular"><?= esc($job['title']) ?></h3>
                            </div>
                        </div>
                        <p class="job-location-popular"><i class="fas fa-map-marker-alt"></i> <?= esc($job['location']) ?></p>

                        <?php if (!empty($job['suitable_disability_types'])): ?>
                            <div class="job-disability-tags">
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

                        <div class="job-tags-popular">
                            <?php foreach ($job['display_tags'] as $tag) : ?>
                                <span><?= esc($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="job-salary-popular">
                            <i class="fas fa-dollar-sign"></i> <?= esc($job['salary_range'] ?: 'Negosiasi') ?>
                        </div>
                        <div class="job-posted-time-popular">
                            <?= esc($job['posted_ago']) ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results-message" style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px; border: 1px dashed #ddd;">
                    <i class="fas fa-search" style="font-size: 3em; color: #ccc; margin-bottom: 15px;"></i>
                    <h3>Tidak ada lowongan yang sesuai kriteria</h3>
                    <p style="color: #777;">Coba ubah kata kunci pencarian atau reset filter untuk melihat lowongan lainnya.</p>
                    <a href="<?= base_url('jobs') ?>" class="btn btn-primary" style="margin-top: 15px;">Tampilkan Semua Lowongan</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($pager->getPageCount('jobs') > 1): ?>
            <div class="pagination-container" style="margin: 40px 0; text-align: center;">
                <?= $pager->links('jobs', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
