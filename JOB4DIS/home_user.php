<?php
// JOB4DIS/index.php
session_start();
require_once 'db_connect.php';

$popular_searches = ["Administrasi", "Guru", "Driver", "IT Support", "Accounting Finance", "Content Creator", "Fresh Graduate", "Penjualan", "Konstruksi Bangunan", "Digital Marketing", "Desain Grafis", "Komunikasi Pemasaran"];

$popular_jobs_data = [];
$sql_jobs = "SELECT id, title, company_name, company_logo_path, location, job_type, salary_range, category, created_at, education_level, experience_level 
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB4DIS - Portal Lowongan Kerja Inklusif</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="home_user.php">
                    <img src="images/logo.png" alt="JOB4DIS Logo">
                </a>
            </div>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="all_jobs.php">Cari Loker</a></li>
                    <li><a href="#tips-loker">Tips Loker</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <a href="#bantuan" class="help-icon" aria-label="Bantuan"><i class="fas fa-question-circle"></i></a>
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
                                    <option value="jakarta">Jakarta</option>
                                    <option value="bandung">Bandung</option>
                                    <option value="surabaya">Surabaya</option>
                                    <option value="yogyakarta">Yogyakarta</option>
                                    <option value="remote">Remote</option>
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
                    <div class="hero-image-overlay">
                        <span class="loker-count">100rb ++</span>
                        <span class="loker-tersedia">Loker Tersedia</span>
                    </div>
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

                <?php if (!empty($popular_jobs_data)) : ?>
                    <div class="popular-jobs-grid">
                        <?php foreach ($popular_jobs_data as $job) : ?>
                            <a href="view_job.php?id=<?php echo $job['id']; ?>" class="job-card-popular" data-category="<?php echo htmlspecialchars($job['category']); ?>">
                                <button type="button" class="job-card-favorite-btn" aria-label="Simpan Lowongan" data-job-id="<?php echo $job['id']; ?>"><i class="far fa-heart"></i></button>
                                <div class="job-card-popular-header">
                                    <img src="<?php echo htmlspecialchars($job['company_logo_path']); ?>" alt="Logo <?php echo htmlspecialchars($job['company_name']); ?>" class="company-logo-popular">
                                    <div class="job-card-company-info">
                                        <span class="company-name-popular"><?php echo htmlspecialchars($job['company_name']); ?></span>
                                        <h3 class="job-title-popular"><?php echo htmlspecialchars($job['title']); ?></h3> </div>
                                </div>
                                <p class="job-location-popular"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></p>
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
                            </a> <?php endforeach; ?>
                    </div>
                    <p style="text-align: center; margin-top: 30px;"><a href="all_jobs.php" class="btn btn-secondary">Lihat Semua Lowongan</a></p>
                <?php else : ?>
                    <p style="text-align:center; padding: 20px;">Belum ada lowongan populer saat ini.</p>
                <?php endif; ?>
            </div>
        </section>
        
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> JOB4DIS. Dibuat dengan cinta untuk inklusivitas.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>