<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=" . urlencode("Anda harus login untuk melihat halaman ini!"));
    exit();
}

$user_id = $_SESSION['user_id'];
$saved_jobs_data = [];

// Query untuk mengambil semua loker yang disimpan oleh user
$sql = "SELECT j.id, j.title, j.company_name, j.company_logo_path, j.location, j.job_type, j.salary_range, j.category, j.created_at, j.education_level, j.experience_level
        FROM jobs j
        JOIN saved_jobs sj ON j.id = sj.job_id
        WHERE sj.user_id = ?
        ORDER BY sj.saved_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Proses data (sama seperti di index.php)
        $tags = [];
        if (!empty($row['job_type'])) $tags[] = htmlspecialchars($row['job_type']);
        if (!empty($row['education_level'])) $tags[] = htmlspecialchars($row['education_level']);
        if (!empty($row['experience_level'])) $tags[] = htmlspecialchars($row['experience_level']);
        $row['display_tags'] = $tags;
        
        $date_posted = new DateTime($row['created_at']);
        $now = new DateTime();
        $interval = $now->diff($date_posted);
        if ($interval->days >= 30) { $row['posted_ago'] = floor($interval->days / 30) . ' bulan lalu'; }
        elseif ($interval->days >= 7) { $row['posted_ago'] = floor($interval->days / 7) . ' minggu lalu'; }
        elseif ($interval->days > 0) { $row['posted_ago'] = $interval->days . ' hari lalu'; }
        else { $row['posted_ago'] = 'Hari ini'; }
        
        $row['company_logo_path'] = (!empty($row['company_logo_path']) && file_exists($row['company_logo_path'])) ? $row['company_logo_path'] : 'images/placeholder_logo.png';

        $saved_jobs_data[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loker Disimpan - JOB4DIS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="dashboard-page-header">
        <div class="container header-content">
            <div class="logo">
                <a href="home_user.php"><img src="images/logo.png" alt="JOB4DIS Logo"></a>
            </div>
            <div class="header-actions">
                <a href="dashboard.php" class="btn btn-primary-outline">Dashboard</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </header>

    <main class="dashboard-layout">
        <div class="container">
            <?php include 'dashboard_sidebar.php'; ?>

            <div class="dashboard-content">
                <h2>Loker yang Anda Simpan</h2>
                <hr>

                <?php if (empty($saved_jobs_data)): ?>
                    <p>Anda belum menyimpan lowongan apapun. Mulai <a href="all_jobs.php">cari loker</a> dan klik ikon hati untuk menyimpannya.</p>
                <?php else: ?>
                    <div class="popular-jobs-grid">
                        <?php foreach ($saved_jobs_data as $job): ?>
                            <a href="view_job.php?id=<?php echo $job['id']; ?>" class="job-card-popular">
                                <button type="button" class="job-card-favorite-btn active" aria-label="Simpan Lowongan" data-job-id="<?php echo $job['id']; ?>"><i class="fas fa-heart"></i></button>
                                <div class="job-card-popular-header">
                                    <img src="<?php echo htmlspecialchars($job['company_logo_path']); ?>" alt="Logo <?php echo htmlspecialchars($job['company_name']); ?>" class="company-logo-popular">
                                    <div class="job-card-company-info">
                                        <span class="company-name-popular"><?php echo htmlspecialchars($job['company_name']); ?></span>
                                        <h3 class="job-title-popular"><?php echo htmlspecialchars($job['title']); ?></h3>
                                    </div>
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
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>
</html>