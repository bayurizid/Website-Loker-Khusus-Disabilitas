<?php
session_start();
require_once 'config/db.php'; 

$saved_job_ids = [];
if (isset($_SESSION['logged_in']) && ($_SESSION['role'] ?? '') === 'seeker') {
    $user_id = $_SESSION['user_id'];
    $sql_saved = "SELECT job_id FROM saved_jobs WHERE user_id = ?";
    $stmt_saved = $conn->prepare($sql_saved);
    if ($stmt_saved) {
        $stmt_saved->bind_param("i", $user_id);
        $stmt_saved->execute();
        $result_saved = $stmt_saved->get_result();
        while($row_saved = $result_saved->fetch_assoc()) {
            $saved_job_ids[] = $row_saved['job_id'];
        }
        $stmt_saved->close();
    }
}

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location = isset($_GET['lokasi']) ? trim($_GET['lokasi']) : '';
$category = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$disability_filter = isset($_GET['disabilitas']) ? trim($_GET['disabilitas']) : '';

$jobs_per_page = 9;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $jobs_per_page;

$sql_base = "FROM jobs WHERE is_active = TRUE";
$where_clauses = [];
$params = [];
$types = '';

if (!empty($keyword)) {
    $where_clauses[] = "(title LIKE ? OR company_name LIKE ?)";
    $keyword_param = "%" . $keyword . "%";
    $params[] = $keyword_param;
    $params[] = $keyword_param;
    $types .= 'ss';
}
if (!empty($location)) {
    $where_clauses[] = "location = ?";
    $params[] = $location;
    $types .= 's';
}
if (!empty($category)) {
    $where_clauses[] = "category = ?";
    $params[] = $category;
    $types .= 's';
}
if (!empty($disability_filter)) {
    $where_clauses[] = "FIND_IN_SET(?, suitable_disability_types)";
    $params[] = $disability_filter;
    $types .= 's';
}


if (!empty($where_clauses)) {
    $sql_base .= " AND " . implode(" AND ", $where_clauses);
}

$total_jobs_sql = "SELECT COUNT(*) as total " . $sql_base;
$stmt_total = $conn->prepare($total_jobs_sql);
if ($stmt_total && !empty($types)) {
    $stmt_total->bind_param($types, ...$params);
}
$stmt_total->execute();
$total_jobs_result = $stmt_total->get_result();
$total_jobs = $total_jobs_result->fetch_assoc()['total'];
$total_pages = ceil($total_jobs / $jobs_per_page);
$stmt_total->close();

$all_jobs_data = [];
$page_message = "";


$sql_final = "SELECT id, title, company_name, company_logo_path, location, job_type, salary_range, category, created_at, education_level, experience_level, suitable_disability_types " . $sql_base . " ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params_final = $params;
$params_final[] = $jobs_per_page;
$params_final[] = $offset;
$types_final = $types . 'ii';

$stmt_final = $conn->prepare($sql_final);
if ($stmt_final) {
    if (!empty($types)) {
        $stmt_final->bind_param($types_final, ...$params_final);
    } else {
        $stmt_final->bind_param('ii', $jobs_per_page, $offset);
    }
    
    $stmt_final->execute();
    $result = $stmt_final->get_result();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tags = [];
            if (!empty($row['job_type'])) $tags[] = htmlspecialchars($row['job_type']);
            if (!empty($row['education_level'])) $tags[] = htmlspecialchars($row['education_level']);
            if (!empty($row['experience_level'])) $tags[] = htmlspecialchars($row['experience_level']);
            $row['display_tags'] = $tags;
            $row['company_logo_path'] = (!empty($row['company_logo_path']) && file_exists($row['company_logo_path'])) ? $row['company_logo_path'] : 'images/placeholder_logo.png';
            $date_posted = new DateTime($row['created_at']);
            $now = new DateTime();
            $interval = $now->diff($date_posted);
            if ($interval->days >= 30) { $row['posted_ago'] = floor($interval->days / 30) . ' bulan lalu'; } 
            elseif ($interval->days >= 7) { $row['posted_ago'] = floor($interval->days / 7) . ' minggu lalu'; } 
            elseif ($interval->days > 0) { $row['posted_ago'] = $interval->days . ' hari lalu'; } 
            else { $row['posted_ago'] = 'Hari ini'; }
            $all_jobs_data[] = $row;
        }
    } else {
        $page_message = "Tidak ada lowongan yang cocok dengan kriteria pencarian Anda.";
    }
    $stmt_final->close();
} else {
    $page_message = "Terjadi kesalahan dalam mengambil data.";
}

$pagination_query_string = http_build_query(['keyword' => $keyword, 'lokasi' => $location, 'kategori' => $category, 'disabilitas' => $disability_filter]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Lowongan Kerja - JOB4DIS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="<?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'seeker') ? 'home_user.php' : 'index.php'; ?>">
                    <img src="images/logo.png" alt="JOB4DIS Logo">
                </a>
            </div>
            <nav class="main-nav" id="mainNav">
                <ul>
                     <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'company') : ?>
                        <li><a href="post_job.php">Pasang Loker</a></li>
                        <li><a href="all_applicants.php">Cari Pelamar</a></li>
                    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'seeker') : ?>
                        <li><a href="all_jobs.php" class="active">Cari Loker</a></li>
                        <li><a href="#">Tips Loker</a></li>
                    <?php else : ?>
                        <li><a href="register_company.php">Pasang Loker</a></li>
                        <li><a href="all_jobs.php" class="active">Cari Loker</a></li>
                        <li><a href="#">Tips Loker</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="header-actions">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) : ?>
                    <span class="welcome-user">Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</span>
                    <a href="<?php echo $_SESSION['role'] == 'company' ? 'dashboard_company.php' : 'dashboard.php'; ?>" class="auth-links">Dashboard</a>
                    <span class="auth-separator">/</span>
                    <a href="logout.php" class="auth-links">Logout</a>
                <?php else : ?>
                    <a href="register_choice.php" class="auth-links">Registrasi</a>
                    <span class="auth-separator">/</span>
                    <a href="login.php" class="auth-links">Masuk</a>
                    <a href="register_company.php" class="btn btn-secondary btn-sm">Untuk Perusahaan</a>
                <?php endif; ?>
            </div>
            <button class="mobile-nav-toggle" aria-label="Toggle Navigation Menu" aria-expanded="false" aria-controls="mainNav">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <main class="all-jobs-page">
        <div class="container">
            <div class="breadcrumbs">
                <a href="<?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'seeker') ? 'home_user.php' : 'index.php'; ?>">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <span>Cari Lowongan Pekerjaan</span>
            </div>

            <div class="search-panel-all-jobs">
                <form action="all_jobs.php" method="GET" class="search-form-all-jobs">
                    <div class="search-field-group">
                        <i class="fas fa-search"></i>
                        <input type="text" name="keyword" placeholder="Masukkan kata kunci" value="<?php echo htmlspecialchars($keyword); ?>">
                    </div>
                    <div class="search-field-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="lokasi" aria-label="Cari Lokasi">
                             <option value="">Semua Lokasi</option>
                             <option value="Jakarta Pusat" <?php echo ($location == 'Jakarta Pusat') ? 'selected' : ''; ?>>Jakarta Pusat</option>
                             <option value="Bandung" <?php echo ($location == 'Bandung') ? 'selected' : ''; ?>>Bandung</option>
                             <option value="Surabaya" <?php echo ($location == 'Surabaya') ? 'selected' : ''; ?>>Surabaya</option>
                             <option value="Yogyakarta" <?php echo ($location == 'Yogyakarta') ? 'selected' : ''; ?>>Yogyakarta</option>
                             <option value="Remote" <?php echo ($location == 'Remote') ? 'selected' : ''; ?>>Remote</option>
                        </select>
                    </div>
                    <div class="search-field-group">
                        <i class="fas fa-briefcase"></i>
                        <select name="kategori" aria-label="Cari Kategori">
                             <option value="">Semua Kategori</option>
                             <option value="Akuntansi" <?php echo ($category == 'Akuntansi') ? 'selected' : ''; ?>>Akuntansi</option>
                             <option value="Administrasi" <?php echo ($category == 'Administrasi') ? 'selected' : ''; ?>>Administrasi</option>
                             <option value="Customer Service" <?php echo ($category == 'Customer Service') ? 'selected' : ''; ?>>Customer Service</option>
                             <option value="Desain Grafis" <?php echo ($category == 'Desain Grafis') ? 'selected' : ''; ?>>Desain Grafis</option>
                             <option value="IT" <?php echo ($category == 'IT') ? 'selected' : ''; ?>>IT</option>
                             <option value="Kesehatan" <?php echo ($category == 'Kesehatan') ? 'selected' : ''; ?>>Kesehatan</option>
                             <option value="Pemasaran" <?php echo ($category == 'Pemasaran') ? 'selected' : ''; ?>>Pemasaran</option>
                             <option value="Perbankan / Jasa Finansial" <?php echo ($category == 'Perbankan / Jasa Finansial') ? 'selected' : ''; ?>>Perbankan</option>
                        </select>
                    </div>
                    <div class="search-field-group">
                        <i class="fas fa-universal-access"></i>
                        <select name="disabilitas" aria-label="Cari Berdasarkan Disabilitas">
                            <option value="">Semua Disabilitas</option>
                            <option value="Mental" <?php echo ($disability_filter == 'Mental') ? 'selected' : ''; ?>>Mental</option>
                            <option value="Grahita" <?php echo ($disability_filter == 'Grahita') ? 'selected' : ''; ?>>Grahita</option>
                            <option value="Netra" <?php echo ($disability_filter == 'Netra') ? 'selected' : ''; ?>>Netra</option>
                            <option value="Rungu Wicara" <?php echo ($disability_filter == 'Rungu Wicara') ? 'selected' : ''; ?>>Rungu Wicara</option>
                            <option value="Daksa" <?php echo ($disability_filter == 'Daksa') ? 'selected' : ''; ?>>Daksa</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
            
            <div class="page-header" style="text-align: left; margin-bottom: 20px;">
                <h2>Hasil Pencarian</h2>
                <p>Menampilkan <?php echo $total_jobs; ?> lowongan.</p>
            </div>

            <div class="popular-jobs-grid">
                 <?php if (!empty($all_jobs_data)): ?>
                    <?php foreach ($all_jobs_data as $job) : ?>
                        <?php
                        $is_saved = in_array($job['id'], $saved_job_ids);
                        $heart_class = $is_saved ? 'fas' : 'far';
                        $active_class = $is_saved ? 'active' : '';
                        ?>
                        <a href="view_job.php?id=<?php echo $job['id']; ?>" class="job-card-popular">
                            <button type="button" class="job-card-favorite-btn <?php echo $active_class; ?>" data-job-id="<?php echo $job['id']; ?>"><i class="<?php echo $heart_class; ?> fa-heart"></i></button>
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
                                    $disability_icon_map = [
                                        'Mental' => 'Mental.png',
                                        'Tuna Mental' => 'Mental.png',
                                        'Grahita' => 'Grahita.png',
                                        'Tuna Grahita' => 'Grahita.png',
                                        'Netra' => 'Netra.png',
                                        'Tuna Netra' => 'Netra.png',
                                        'Tuna Netra Parsial' => 'Netra.png',
                                        'Rungu Wicara' => 'Rungu Wicara.png',
                                        'Rungu' => 'Rungu Wicara.png',
                                        'Tuna Rungu' => 'Rungu Wicara.png',
                                        'Daksa' => 'Daksa.png',
                                        'Tuna Daksa' => 'Daksa.png'
                                    ];
                                    $disability_types = explode(',', $job['suitable_disability_types']);
                                    foreach ($disability_types as $type): 
                                        $type = trim($type);
                                        if (empty($type)) continue;
                                        $icon_filename = $disability_icon_map[$type] ?? ($type . '.png');
                                    ?>
                                        <span class="disability-tag">
                                            <img src="images/<?php echo htmlspecialchars($icon_filename); ?>" alt="Ikon <?php echo htmlspecialchars($type); ?>">
                                            <?php echo htmlspecialchars($type); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="job-tags-popular"><?php foreach ($job['display_tags'] as $tag) : ?><span><?php echo $tag; ?></span><?php endforeach; ?></div>
                            <div class="job-salary-popular"><i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($job['salary_range'] ?? 'Negosiasi'); ?></div>
                            <div class="job-posted-time-popular"><?php echo htmlspecialchars($job['posted_ago']); ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-results-message" style="grid-column: 1 / -1; text-align: center; padding: 40px 20px;">
                        <p><?php echo htmlspecialchars($page_message); ?></p>
                        <p style="margin-top:15px;"><a href="all_jobs.php" class="btn btn-secondary">Tampilkan Semua Lowongan</a></p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1) : ?>
            <div class="pagination">
                <?php if ($current_page > 1) : ?>
                    <a href="all_jobs.php?page=<?php echo $current_page - 1; ?>&amp;<?php echo $pagination_query_string; ?>" class="page-link">&laquo; Sebelumnya</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <a href="all_jobs.php?page=<?php echo $i; ?>&amp;<?php echo $pagination_query_string; ?>" class="page-link <?php if ($i == $current_page) echo 'current'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($current_page < $total_pages) : ?>
                    <a href="all_jobs.php?page=<?php echo $current_page + 1; ?>&amp;<?php echo $pagination_query_string; ?>" class="page-link">Berikutnya &raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> JOB4DIS. Dibuat dengan cinta untuk inklusivitas.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>