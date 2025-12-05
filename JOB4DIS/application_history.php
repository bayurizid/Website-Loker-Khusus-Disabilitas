<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=" . urlencode("Anda harus login!"));
    exit();
}

$user_id = $_SESSION['user_id'];
$applications = [];

$sql = "SELECT j.id as job_id, j.title, j.company_name, ja.application_date, ja.status 
        FROM job_applications ja
        JOIN jobs j ON ja.job_id = j.id
        WHERE ja.user_id = ?
        ORDER BY ja.application_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result) {
    while($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Lamaran - JOB4DIS</title>
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
                <h2>Riwayat Lamaran Kerja</h2>
                
                <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="message success">
                    <p>✓ Lamaran Anda telah berhasil dikirim dan sedang ditinjau!</p>
                </div>
                <?php endif; ?>

                <hr>
                
                <div class="history-table-container">
                    <?php if (empty($applications)): ?>
                        <p>Anda belum pernah melamar pekerjaan apapun. <a href="all_jobs.php">Cari lowongan sekarang!</a></p>
                    <?php else: ?>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Posisi yang Dilamar</th>
                                    <th>Tanggal Melamar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td>
                                        <a href="view_job.php?id=<?php echo $app['job_id']; ?>" style="font-weight: bold; color: var(--primary-color);">
                                            <?php echo htmlspecialchars($app['title']); ?>
                                        </a>
                                        <br>
                                        <small><?php echo htmlspecialchars($app['company_name']); ?></small>
                                    </td>
                                    <td><?php echo date('d F Y, H:i', strtotime($app['application_date'])); ?></td>
                                    <td><span class="status-badge status-<?php echo strtolower(htmlspecialchars($app['status'])); ?>"><?php echo htmlspecialchars($app['status']); ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>