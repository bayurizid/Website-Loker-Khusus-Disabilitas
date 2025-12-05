<?php
session_start();

require_once 'config/db.php'; 

$home_url = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) ? 'home_user.php' : 'index.php';

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

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . $home_url);
    exit();
}

$job_id = (int)$_GET['id'];
$job_detail = null;

$sql = "SELECT * FROM jobs WHERE id = ? AND is_active = TRUE";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $job_detail = $result->fetch_assoc();
} else {
    echo "Lowongan tidak ditemukan.";
    exit();
}
$stmt->close();

function nl2list($string) {
    $items = explode("\n", trim($string));
    $list_html = '<ul>';
    foreach ($items as $item) {
        if (!empty(trim($item))) {
            $list_html .= '<li>' . htmlspecialchars(trim($item)) . '</li>';
        }
    }
    $list_html .= '</ul>';
    return $list_html;
}

$date_posted = new DateTime($job_detail['created_at']);
$now = new DateTime();
$interval = $now->diff($date_posted);
if ($interval->days >= 30) { $posted_ago = floor($interval->days / 30) . ' bulan lalu'; } 
elseif ($interval->days >= 7) { $posted_ago = floor($interval->days / 7) . ' minggu lalu'; } 
elseif ($interval->days > 1) { $posted_ago = $interval->days . ' hari lalu'; } 
elseif ($interval->h > 0) { $posted_ago = $interval->h . ' jam lalu'; } 
elseif ($interval->i > 0) { $posted_ago = $interval->i . ' menit lalu'; } 
else { $posted_ago = 'Baru saja'; }
$job_detail['company_logo_path'] = (!empty($job_detail['company_logo_path']) && file_exists($job_detail['company_logo_path'])) ? $job_detail['company_logo_path'] : 'images/placeholder_logo.png';

$is_saved = in_array($job_detail['id'], $saved_job_ids);
$heart_class = $is_saved ? 'fas' : 'far'; 
$active_class = $is_saved ? 'active' : '';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job_detail['title']); ?> - JOB4DIS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="<?php echo $home_url; ?>"><img src="images/logo.png" alt="JOB4DIS Logo" style="height: 40px;"></a>
            </div>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="<?php echo $home_url; ?>#cari-loker">Cari Loker</a></li>
                    <li><a href="<?php echo $home_url; ?>#tips-loker">Tips Loker</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                 <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) : ?>
                    <span class="welcome-user">Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']); ?>!</span>
                    <a href="dashboard.php" class="auth-links">Dashboard</a> <span class="auth-separator">/</span>
                    <a href="logout.php" class="auth-links">Logout</a>
                <?php else : ?>
                    <a href="register.php" class="auth-links">Registrasi</a> <span class="auth-separator">/</span>
                    <a href="login.php" class="auth-links">Masuk</a>
                <?php endif; ?>
            </div>
            <button class="mobile-nav-toggle" aria-label="Toggle Navigation Menu" aria-expanded="false" aria-controls="mainNav">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <main class="job-detail-page">
        <div class="container">
            <div class="breadcrumbs">
                 <a href="<?php echo $home_url; ?>">Beranda</a> <i class="fas fa-chevron-right"></i>
                <a href="all_jobs.php?kategori=<?php echo urlencode($job_detail['category']); ?>"><?php echo htmlspecialchars($job_detail['category']); ?></a> <i class="fas fa-chevron-right"></i>
                <span><?php echo htmlspecialchars($job_detail['title']); ?></span>
            </div>

            <div class="job-detail-layout">
                <div class="job-detail-main">
                    <div class="job-detail-header">
                        <h1><?php echo htmlspecialchars($job_detail['title']); ?></h1>
                        <div class="job-actions">
                            <button type="button" class="job-card-favorite-btn btn-icon <?php echo $active_class; ?>" data-job-id="<?php echo $job_detail['id']; ?>" aria-label="Simpan Lowongan">
                                <i class="<?php echo $heart_class; ?> fa-heart"></i>
                            </button>
                            <?php
                            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                                echo '<a href="apply_job.php?job_id=' . $job_detail['id'] . '" class="btn btn-primary">Lamar Pekerjaan</a>';
                            } else {
                                $current_page_url = "view_job.php?id=" . $job_detail['id'];
                                echo '<a href="login.php?redirect=' . urlencode($current_page_url) . '" class="btn btn-primary">Login untuk Lamar</a>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="job-company-info-inline">
                        <a href="#company-profile" class="company-name-link"><?php echo htmlspecialchars($job_detail['company_name']); ?></a>
                        <span class="posted-time"><i class="far fa-clock"></i> <?php echo $posted_ago; ?></span>
                    </div>

                    <div class="job-summary-grid">
                        <div><i class="fas fa-map-marker-alt"></i> <strong>Lokasi:</strong><br><?php echo htmlspecialchars($job_detail['location']); ?></div>
                        <div><i class="fas fa-briefcase"></i> <strong>Tipe Pekerjaan:</strong><br><?php echo htmlspecialchars($job_detail['job_type']); ?></div>
                        <div><i class="fas fa-layer-group"></i> <strong>Level Pekerjaan:</strong><br><?php echo htmlspecialchars($job_detail['level_pekerjaan'] ?? '-'); ?></div>
                        <div><i class="fas fa-cogs"></i> <strong>Fungsi:</strong><br><?php echo htmlspecialchars($job_detail['category']); ?></div> <div><i class="fas fa-graduation-cap"></i> <strong>Pendidikan:</strong><br><?php echo htmlspecialchars($job_detail['education_level'] ?? '-'); ?></div>
                        <div><i class="fas fa-dollar-sign"></i> <strong>Gaji:</strong><br><?php echo htmlspecialchars($job_detail['salary_range'] ?? 'Negosiasi'); ?></div>
                    </div>

                    <?php if (!empty($job_detail['company_description'])): ?>
                    <div class="job-section" id="company-profile">
                        <h3>Tentang Perusahaan</h3>
                        <p><?php echo nl2br(htmlspecialchars($job_detail['company_description'])); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($job_detail['job_description'])): ?>
                    <div class="job-section">
                        <h3>Deskripsi Pekerjaan</h3>
                        <p><?php echo nl2br(htmlspecialchars($job_detail['job_description'])); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($job_detail['responsibilities'])): ?>
                    <div class="job-section">
                        <h3>Tanggung Jawab Pekerjaan</h3>
                        <?php echo nl2list($job_detail['responsibilities']); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($job_detail['skills'])): ?>
                    <div class="job-section">
                        <h3>Keahlian</h3>
                        <?php echo nl2list($job_detail['skills']); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($job_detail['qualifications'])): ?>
                    <div class="job-section">
                        <h3>Kualifikasi</h3>
                        <?php echo nl2list($job_detail['qualifications']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($job_detail['work_schedule'])): ?>
                    <div class="job-section">
                        <h3>Waktu Bekerja</h3>
                        <p><?php echo htmlspecialchars($job_detail['work_schedule']); ?></p>
                    </div>
                    <?php endif; ?>
                     <?php if (!empty($job_detail['experience_level'])): ?>
                    <div class="job-section">
                        <h3>Pengalaman Kerja</h3>
                        <p><?php echo htmlspecialchars($job_detail['experience_level']); ?></p>
                    </div>
                    <?php endif; ?>

                </div>
                <aside class="job-detail-sidebar">
                    <div class="company-profile-card">
                        <img src="<?php echo htmlspecialchars($job_detail['company_logo_path']); ?>" alt="Logo <?php echo htmlspecialchars($job_detail['company_name']); ?>" class="company-logo-detail">
                        <h4><?php echo htmlspecialchars($job_detail['company_name']); ?></h4>
                        <?php if (!empty($job_detail['company_industry'])): ?>
                            <p><i class="fas fa-industry"></i> <?php echo htmlspecialchars($job_detail['company_industry']); ?></p>
                        <?php endif; ?>
                         <?php if (!empty($job_detail['location'])): ?> <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job_detail['location']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($job_detail['company_size'])): ?>
                            <p><i class="fas fa-users"></i> <?php echo htmlspecialchars($job_detail['company_size']); ?></p>
                        <?php endif; ?>
                         <?php if (!empty($job_detail['company_address'])): ?>
                            <p style="font-size: 0.9em; color: #555;"><?php echo nl2br(htmlspecialchars($job_detail['company_address'])); ?></p>
                        <?php endif; ?>
                        </div>
                </aside>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> JOB4DIS.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>