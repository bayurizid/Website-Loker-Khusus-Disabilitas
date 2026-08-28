<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-container">
        <div class="hero-text">
            <h1>Cari Kerja <span class="highlight">#MakinMudah</span><br>Dengan JOB4DIS</h1>
            <form class="search-form-hero" action="<?= base_url('jobs') ?>" method="GET">
                <div class="search-input-group">
                    <input type="text" name="keyword" placeholder="Masukkan kata kunci (mis: admin, marketing)" aria-label="Kata Kunci Pekerjaan">
                    <button type="submit" class="search-button-hero" aria-label="Cari">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="search-filters-hero">
                    <!-- Lokasi Custom Select -->
                    <div class="custom-select-wrapper filter-dropdown" style="min-width: 190px; background-color: #fff; border: 1px solid var(--border-color); border-radius: 6px; padding: 2px 10px;">
                        <input type="hidden" name="lokasi" value="">
                        <div class="custom-select-trigger" style="padding: 10px 0;">
                            <span class="selected-text">Semua Lokasi</span>
                            <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 0.75em; color: #888;"></i>
                        </div>
                        <div class="custom-select-options">
                            <div class="custom-option selected" data-value="" data-label="Semua Lokasi">
                                <span>Semua Lokasi</span>
                            </div>
                            <div class="custom-option" data-value="Jakarta" data-label="Jakarta">
                                <span>Jakarta (Jabodetabek)</span>
                            </div>
                            <div class="custom-option" data-value="Bandung" data-label="Bandung">
                                <span>Bandung</span>
                            </div>
                            <div class="custom-option" data-value="Surabaya" data-label="Surabaya">
                                <span>Surabaya</span>
                            </div>
                            <div class="custom-option" data-value="Yogyakarta" data-label="Yogyakarta">
                                <span>Yogyakarta</span>
                            </div>
                            <div class="custom-option" data-value="Remote" data-label="Remote">
                                <span>Remote / WFH</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori Custom Select -->
                    <div class="custom-select-wrapper filter-dropdown" style="min-width: 190px; background-color: #fff; border: 1px solid var(--border-color); border-radius: 6px; padding: 2px 10px;">
                        <input type="hidden" name="kategori" value="">
                        <div class="custom-select-trigger" style="padding: 10px 0;">
                            <span class="selected-text">Semua Kategori</span>
                            <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 0.75em; color: #888;"></i>
                        </div>
                        <div class="custom-select-options">
                            <div class="custom-option selected" data-value="" data-label="Semua Kategori">
                                <i class="fas fa-th-large" style="color: #007bff; width: 20px; text-align: center;"></i>
                                <span>Semua Kategori</span>
                            </div>
                            <div class="custom-option" data-value="IT" data-label="IT">
                                <i class="fas fa-laptop-code" style="color: #6f42c1; width: 20px; text-align: center;"></i>
                                <span>IT & Komputer</span>
                            </div>
                            <div class="custom-option" data-value="Administrasi" data-label="Administrasi">
                                <i class="fas fa-file-signature" style="color: #17a2b8; width: 20px; text-align: center;"></i>
                                <span>Administrasi</span>
                            </div>
                            <div class="custom-option" data-value="Customer Service" data-label="Customer Service">
                                <i class="fas fa-headset" style="color: #e83e8c; width: 20px; text-align: center;"></i>
                                <span>Customer Service</span>
                            </div>
                            <div class="custom-option" data-value="Desain Grafis" data-label="Desain Grafis">
                                <i class="fas fa-palette" style="color: #fd7e14; width: 20px; text-align: center;"></i>
                                <span>Desain Grafis</span>
                            </div>
                            <div class="custom-option" data-value="Pemasaran" data-label="Pemasaran">
                                <i class="fas fa-bullhorn" style="color: #20c997; width: 20px; text-align: center;"></i>
                                <span>Pemasaran</span>
                            </div>
                            <div class="custom-option" data-value="Akuntansi" data-label="Akuntansi">
                                <i class="fas fa-calculator" style="color: #28a745; width: 20px; text-align: center;"></i>
                                <span>Akuntansi & Keuangan</span>
                            </div>
                            <div class="custom-option" data-value="Pendidikan" data-label="Pendidikan">
                                <i class="fas fa-graduation-cap" style="color: #007bff; width: 20px; text-align: center;"></i>
                                <span>Pendidikan</span>
                            </div>
                            <div class="custom-option" data-value="Perbankan" data-label="Perbankan">
                                <i class="fas fa-university" style="color: #343a40; width: 20px; text-align: center;"></i>
                                <span>Perbankan</span>
                            </div>
                        </div>
                    </div>
                    <div class="custom-select-wrapper filter-dropdown" style="min-width: 210px; background-color: #fff; border: 1px solid var(--border-color); border-radius: 6px; padding: 2px 10px;">
                        <input type="hidden" name="disabilitas" value="">
                        <div class="custom-select-trigger" style="padding: 10px 0;">
                            <span class="selected-text">Semua Disabilitas</span>
                            <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 0.75em; color: #888;"></i>
                        </div>
                        <div class="custom-select-options">
                            <div class="custom-option selected" data-value="" data-label="Semua Disabilitas" data-icon="">
                                <i class="fas fa-check-circle" style="color: #007bff; width: 22px; text-align: center;"></i>
                                <span>Semua Disabilitas</span>
                            </div>
                            <div class="custom-option" data-value="Daksa" data-label="Daksa" data-icon="<?= base_url('images/Daksa.png') ?>">
                                <img src="<?= base_url('images/Daksa.png') ?>" alt="Daksa">
                                <span>Daksa (Fisik)</span>
                            </div>
                            <div class="custom-option" data-value="Rungu Wicara" data-label="Rungu Wicara" data-icon="<?= base_url('images/Rungu Wicara.png') ?>">
                                <img src="<?= base_url('images/Rungu Wicara.png') ?>" alt="Rungu Wicara">
                                <span>Rungu Wicara</span>
                            </div>
                            <div class="custom-option" data-value="Netra" data-label="Netra" data-icon="<?= base_url('images/Netra.png') ?>">
                                <img src="<?= base_url('images/Netra.png') ?>" alt="Netra">
                                <span>Netra (Penglihatan)</span>
                            </div>
                            <div class="custom-option" data-value="Grahita" data-label="Grahita" data-icon="<?= base_url('images/Grahita.png') ?>">
                                <img src="<?= base_url('images/Grahita.png') ?>" alt="Grahita">
                                <span>Grahita (Intelektual)</span>
                            </div>
                            <div class="custom-option" data-value="Mental" data-label="Mental" data-icon="<?= base_url('images/Mental.png') ?>">
                                <img src="<?= base_url('images/Mental.png') ?>" alt="Mental">
                                <span>Mental (Psikososial)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="popular-searches-hero">
                <span>Paling sering dicari:</span>
                <?php foreach ($popular_searches as $index => $search) : ?>
                    <a href="<?= base_url('jobs?keyword=' . urlencode($search)) ?>"><?= esc($search) ?></a><?= ($index < count($popular_searches) - 1) ? ', ' : '' ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="hero-image-container">
            <img src="<?= base_url('images/hero_image.png') ?>" alt="Dua orang sedang mencari kerja dengan laptop" width="436" height="436">
        </div>
    </div>
</section>

<!-- Popular Jobs Section -->
<section class="popular-jobs-section" id="cari-loker">
    <div class="container">
        <h2>Lowongan Kerja Terpopuler</h2>
        <div class="job-category-filters">
            <button class="filter-btn active" data-filter="semua">Semua</button>
            <button class="filter-btn" data-filter="Akuntansi">Akuntansi</button>
            <button class="filter-btn" data-filter="Perbankan">Perbankan</button>
            <button class="filter-btn" data-filter="Administrasi">Administrasi</button>
            <button class="filter-btn" data-filter="Customer Service">Customer Service</button>
            <div class="filter-dropdown-wrapper">
                <button class="filter-btn filter-dropdown-toggle" id="filterLainnyaBtn">Lainnya <i class="fas fa-chevron-down"></i></button>
                <div class="filter-dropdown-content" id="filterLainnyaDropdown">
                    <a href="<?= base_url('jobs?kategori=IT') ?>" class="filter-option">IT & Komputer</a>
                    <a href="<?= base_url('jobs?kategori=Pemasaran') ?>" class="filter-option">Pemasaran & Sales</a>
                    <a href="<?= base_url('jobs?kategori=Pendidikan') ?>" class="filter-option">Pendidikan</a>
                    <a href="<?= base_url('jobs?kategori=Kesehatan') ?>" class="filter-option">Kesehatan</a>
                </div>
            </div>
        </div>

        <div class="popular-jobs-grid">
            <?php if (!empty($popular_jobs)) : ?>
                <?php foreach ($popular_jobs as $job) : ?>
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
            <?php else : ?>
                <p style="text-align:center; padding: 20px; grid-column: 1 / -1;">Belum ada lowongan populer saat ini.</p>
            <?php endif; ?>
        </div>
        <p style="text-align: center; margin-top: 30px;">
            <a href="<?= base_url('jobs') ?>" class="btn btn-secondary">Lihat Semua Lowongan</a>
        </p>
    </div>
</section>

<!-- Testimonials Section -->
<?php if (!empty($testimonials)): ?>
<section class="testimonial-section">
    <div class="container">
        <h2>4.2 Milyar+ pelamar mendapat kerja berkat JOB4DIS</h2>
        <div class="testimonial-slider-container">
            <div class="testimonial-track">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="testimonial-card">
                        <p class="testimonial-text">"<?= esc($testimonial['testimonial_text']) ?>"</p>
                        <div class="testimonial-user">
                            <img src="<?= base_url($testimonial['photo_path'] ?: 'images/placeholder_profile.png') ?>" alt="Foto <?= esc($testimonial['user_name']) ?>" class="testimonial-photo">
                            <div class="testimonial-user-info">
                                <span class="testimonial-name"><?= esc($testimonial['user_name']) ?></span>
                                <span class="testimonial-job-title"><?= esc($testimonial['job_title']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Partners Section -->
<section class="partners-section">
    <div class="container">
        <h2>Dipercaya 2000+ perusahaan besar di Indonesia</h2>
        <div class="logo-slider-container">
            <div class="logo-track">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <div class="logo-item">
                        <img src="<?= base_url('images/partners/' . $i . '.png') ?>" alt="Logo Mitra Perusahaan">
                    </div>
                <?php endfor; ?>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <div class="logo-item">
                        <img src="<?= base_url('images/partners/' . $i . '.png') ?>" alt="Logo Mitra Perusahaan">
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
