<?php
session_start();
require_once 'config/db.php';

$popular_searches = ["Administrasi", "Guru", "Driver", "IT Support", "Accounting Finance", "Content Creator", "Fresh Graduate", "Penjualan", "Konstruksi Bangunan", "Digital Marketing", "Desain Grafis", "Komunikasi Pemasaran"];

$popular_jobs_data = [];
$sql_jobs = "SELECT id, title, company_name, company_logo_path, location, job_type, salary_range, category, created_at, education_level, experience_level, suitable_disability_types
             FROM jobs 
             WHERE is_active = TRUE 
             ORDER BY created_at DESC LIMIT 6";
$result_jobs = mysqli_query($conn, $sql_jobs);

if ($result_jobs && mysqli_num_rows($result_jobs) > 0) {
    while ($row = mysqli_fetch_assoc($result_jobs)) {
        $tags = [];
        if (!empty($row['job_type'])) $tags[] = htmlspecialchars($row['job_type']);
        if (!empty($row['education_level'])) $tags[] = htmlspecialchars($row['education_level']);
        if (!empty($row['experience_level'])) $tags[] = htmlspecialchars($row['experience_level']);
        $row['display_tags'] = $tags;
        
        $date_posted = new DateTime($row['created_at']);
        $now = new DateTime();
        $interval = $now->diff($date_posted);
        if ($interval->days >= 30) {
            $row['posted_ago'] = floor($interval->days / 30) . ' bulan lalu';
        } elseif ($interval->days >= 7) {
            $row['posted_ago'] = floor($interval->days / 7) . ' minggu lalu';
        } elseif ($interval->days > 1) {
            $row['posted_ago'] = $interval->days . ' hari lalu';
        } elseif ($interval->h > 0) {
            $row['posted_ago'] = $interval->h . ' jam lalu';
        } elseif ($interval->i > 0) {
            $row['posted_ago'] = $interval->i . ' menit lalu';
        } else {
            $row['posted_ago'] = 'Baru saja';
        }
        
        $row['company_logo_path'] = (!empty($row['company_logo_path']) && file_exists($row['company_logo_path'])) ? $row['company_logo_path'] : 'images/placeholder_logo.png';

        $popular_jobs_data[] = $row;
    }
}

$testimonials_data = [];
$sql_testimonials = "SELECT user_name, job_title, testimonial_text, photo_path 
                     FROM testimonials 
                     WHERE is_featured = TRUE 
                     ORDER BY created_at DESC";
$result_testimonials = mysqli_query($conn, $sql_testimonials);
if ($result_testimonials && mysqli_num_rows($result_testimonials) > 0) {
    while ($row = mysqli_fetch_assoc($result_testimonials)) {
        $row['photo_path'] = (!empty($row['photo_path']) && file_exists($row['photo_path'])) ? $row['photo_path'] : 'images/placeholder_profile.png';
        $testimonials_data[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB4DIS - Portal Lowongan Kerja Inklusif</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/slider.css">
    <link rel="stylesheet" href="css/info-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="JOB4DIS Logo">
                </a>
            </div>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="register_company.php">Pasang Loker</a></li>
                    <li><a href="all_jobs.php">Cari Loker</a></li>
                    <li><a href="#tips-loker">Tips Loker</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) : ?>
                    <span class="welcome-user">Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']); ?>!</span>
                    <a href="dashboard.php" class="auth-links">Dashboard</a>
                    <span class="auth-separator">/</span>
                    <a href="logout.php" class="auth-links">Logout</a>
                <?php else : ?>
                    <a href="register_choice.php" class="auth-links">Registrasi</a>
                    <span class="auth-separator">/</span>
                    <a href="login.php" class="auth-links">Masuk</a>
                <?php endif; ?>
                <a href="register_company.php" class="btn btn-secondary btn-sm">Untuk Perusahaan</a>
            </div>
            <button class="mobile-nav-toggle" aria-label="Toggle Navigation Menu" aria-expanded="false" aria-controls="mainNav">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <main>
        <section class="hero-section">
            <div class="container hero-container">
                <div class="hero-text">
                    <h1>Cari Kerja <span class="highlight">#makinmudah</span><br>Pake JOB4DIS</h1>
                    <form class="search-form-hero" action="all_jobs.php" method="GET">
                        <div class="search-input-group">
                            <input type="text" name="keyword" placeholder="Masukkan kata kunci (mis: admin, marketing)" aria-label="Kata Kunci Pekerjaan">
                            <button type="submit" class="search-button-hero" aria-label="Cari">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div class="search-filters-hero">
                            <div class="filter-dropdown">
                                <select name="lokasi" aria-label="Cari Lokasi">
                                    <option value="">Semua Lokasi</option>
                                    <option value="Jakarta Pusat">Jakarta Pusat</option>
                                    <option value="Bandung">Bandung</option>
                                    <option value="Surabaya">Surabaya</option>
                                    <option value="Yogyakarta">Yogyakarta</option>
                                    <option value="Remote">Remote</option>
                                </select>
                            </div>
                            <div class="filter-dropdown">
                                <select name="kategori" aria-label="Cari Kategori">
                                    <option value="">Semua Kategori</option>
                                    <option value="Akuntansi">Akuntansi</option>
                                    <option value="Perbankan / Jasa Finansial">Perbankan / Jasa Finansial</option>
                                    <option value="Administrasi">Administrasi</option>
                                    <option value="Customer Service">Customer Service</option>
                                    <option value="IT">IT</option>
                                    <option value="Pemasaran">Pemasaran</option>
                                    <option value="Pendidikan">Pendidikan</option>
                                    <option value="Kesehatan">Kesehatan</option>
                                    <option value="Desain Grafis">Desain Grafis</option>
                                </select>
                            </div>
                            <div class="filter-dropdown">
                                <select name="disabilitas" aria-label="Cari Berdasarkan Disabilitas">
                                    <option value="">Semua Disabilitas</option>
                                    <option value="Mental">Mental</option>
                                    <option value="Grahita">Grahita</option>
                                    <option value="Netra">Netra</option>
                                    <option value="Rungu Wicara">Rungu Wicara</option>
                                    <option value="Daksa">Daksa</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="popular-searches-hero">
                        <span>Paling sering dicari:</span>
                        <?php foreach ($popular_searches as $index => $search) : ?>
                            <a href="all_jobs.php?keyword=<?php echo urlencode($search); ?>"><?php echo htmlspecialchars($search); ?></a><?php if ($index < count($popular_searches) -1 ) echo ', '; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="hero-image-container">
                    <img src="images/hero_image.png" alt="Dua orang sedang mencari kerja dengan laptop" width="436" height="436">
                </div>
            </div>
        </section>

        <section class="popular-jobs-section" id="cari-loker">
            <div class="container">
                <h2>Lowongan Kerja Terpopuler</h2>
                
                <div class="job-category-filters">
                    <button class="filter-btn active" data-filter="semua">Semua</button>
                    <button class="filter-btn" data-filter="Akuntansi">Akuntansi</button>
                    <button class="filter-btn" data-filter="Perbankan / Jasa Finansial">Perbankan</button>
                    <button class="filter-btn" data-filter="Administrasi">Administrasi</button>
                    <button class="filter-btn" data-filter="Customer Service">Customer Service</button>
                    <div class="filter-dropdown-wrapper">
                        <button class="filter-btn filter-dropdown-toggle" id="filterLainnyaBtn">Lainnya <i class="fas fa-chevron-down"></i></button>
                        <div class="filter-dropdown-content" id="filterLainnyaDropdown">
                            <a href="#" class="filter-option" data-filter="IT">IT & Komputer</a>
                            <a href="#" class="filter-option" data-filter="Pemasaran">Pemasaran & Sales</a>
                            <a href="#" class="filter-option" data-filter="Pendidikan">Pendidikan</a>
                            <a href="#" class="filter-option" data-filter="Kesehatan">Kesehatan</a>
                        </div>
                    </div>
                </div>
                <div class="popular-jobs-grid">
                    <?php if (!empty($popular_jobs_data)) : ?>
                        <?php foreach ($popular_jobs_data as $job) : ?>
                            <a href="view_job.php?id=<?php echo $job['id']; ?>" class="job-card-popular" data-category="<?php echo htmlspecialchars($job['category']); ?>">
                                <button type="button" class="job-card-favorite-btn" aria-label="Simpan Lowongan" data-job-id="<?php echo $job['id']; ?>"><i class="far fa-heart"></i></button>
                                <div class="job-card-popular-header">
                                    <img src="<?php echo htmlspecialchars($job['company_logo_path']); ?>" alt="Logo <?php echo htmlspecialchars($job['company_name']); ?>" class="company-logo-popular">
                                    <div class="job-card-company-info">
                                        <span class="company-name-popular"><?php echo htmlspecialchars($job['company_name']); ?></span>
                                        <h3 class="job-title-popular"><?php echo htmlspecialchars($job['title']); ?></h3>
                                    </div>
                                </div>
                                <p class="job-location-popular"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></p>

                                <?php if (!empty($job['suitable_disability_types'])): ?>
                                    <div class="job-disability-tags">
                                        <?php 
                                        $disability_types = explode(',', $job['suitable_disability_types']);
                                        foreach ($disability_types as $type): 
                                            $type = trim($type);
                                            $icon_filename = $type . '.png';
                                        ?>
                                            <span class="disability-tag">
                                                <img src="images/<?php echo htmlspecialchars($icon_filename); ?>" alt="Ikon <?php echo htmlspecialchars($type); ?>">
                                                <?php echo htmlspecialchars($type); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="job-tags-popular">
                                    <?php foreach ($job['display_tags'] as $tag) : ?>
                                        <span><?php echo $tag; ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <div class="job-salary-popular">
                                    <i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($job['salary_range'] ?? 'Negosiasi'); ?>
                                </div>
                                <div class="job-posted-time-popular">
                                    <?php echo htmlspecialchars($job['posted_ago']); ?>
                                </div>
                            </a> 
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p style="text-align:center; padding: 20px; grid-column: 1 / -1;">Belum ada lowongan populer saat ini.</p>
                    <?php endif; ?>
                </div>
                <p style="text-align: center; margin-top: 30px;"><a href="all_jobs.php" class="btn btn-secondary">Lihat Semua Lowongan</a></p>
            </div>
        </section>
        
        <?php if (!empty($testimonials_data)): ?>
        <section class="testimonial-section">
            <div class="container">
                <h2>4.2 Milyar+ pelamar mendapat kerja berkat JOB4DIS</h2>
                <div class="testimonial-slider-container">
                    <div class="testimonial-track">
                        <?php foreach ($testimonials_data as $testimonial): ?>
                            <div class="testimonial-card">
                                <p class="testimonial-text">"<?php echo htmlspecialchars($testimonial['testimonial_text']); ?>"</p>
                                <div class="testimonial-user">
                                    <img src="<?php echo htmlspecialchars($testimonial['photo_path']); ?>" alt="Foto <?php echo htmlspecialchars($testimonial['user_name']); ?>" class="testimonial-photo">
                                    <div class="testimonial-user-info">
                                        <span class="testimonial-name"><?php echo htmlspecialchars($testimonial['user_name']); ?></span>
                                        <span class="testimonial-job-title"><?php echo htmlspecialchars($testimonial['job_title']); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <section class="partners-section">
            <div class="container">
                <h2>Dipercaya 2000+ perusahaan besar di Indonesia</h2>
                <div class="logo-slider-container">
                    <div class="logo-track">
                        <?php 
                        $partner_logos = [
                            'images/partners/1.png',
                            'images/partners/2.png',
                            'images/partners/3.png',
                            'images/partners/4.png',
                            'images/partners/5.png',
                            'images/partners/6.png',
                            'images/partners/7.png',
                            'images/partners/8.png',
                            'images/partners/9.png',
                            'images/partners/10.png',
                        ];

                        $logos_to_display = array_merge($partner_logos, $partner_logos);

                        foreach ($logos_to_display as $logo_path): 
                        ?>
                            <div class="logo-item">
                                <img src="<?php echo htmlspecialchars($logo_path); ?>" alt="Logo Mitra Perusahaan">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        </main>

    <section class="info-section">
        <div class="container">
            <div class="info-content">
                <div class="info-about">
                    <img src="images/logo.png" alt="JOB4DIS Logo" class="info-logo">
                    <p class="info-description">
                        JOB4DIS adalah Situs Lowongan Kerja (Job Portal) yang fokus di bidang rekrutmen inklusif untuk mempermudah pencarian kerja dan perekrutan karyawan penyandang disabilitas di Indonesia.
                    </p>
                    <div class="info-socials">
                        <a href="#" aria-label="Kunjungi Twitter kami"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Kunjungi Instagram kami"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Kunjungi LinkedIn kami"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="info-gov-partners">
                    <h4>Didukung Oleh:</h4>
                    <div class="gov-logos">
                        <a href="https://kemnaker.go.id/" target="_blank" rel="noopener noreferrer" class="gov-logo-item">
                            <img src="images/gov/kemnaker.png" alt="Logo KEMNAKER">
                        </a>
                        <a href="https://kemensos.go.id/" target="_blank" rel="noopener noreferrer" class="gov-logo-item">
                            <img src="images/gov/kemensos.png" alt="Logo Kementerian Sosial RI">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> JOB4DIS. Dibuat dengan cinta untuk inklusivitas.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>