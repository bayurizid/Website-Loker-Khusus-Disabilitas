<?php
session_start();
require_once 'config/db.php'; 

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$lokasi = isset($_GET['lokasi']) ? trim($_GET['lokasi']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$disabilitas = isset($_GET['disabilitas']) ? trim($_GET['disabilitas']) : '';

$search_results_data = [];
$search_message = "";

$sql_base = "FROM jobs WHERE is_active = TRUE";
$conditions = [];
$params = [];
$types = '';

if (!empty($keyword)) {
    $conditions[] = "(title LIKE ? OR company_name LIKE ? OR job_description LIKE ?)";
    $keyword_param = "%" . $keyword . "%";
    $params[] = $keyword_param;
    $params[] = $keyword_param;
    $params[] = $keyword_param;
    $types .= "sss";
}

if (!empty($lokasi)) {
    $conditions[] = "location = ?";
    $params[] = $lokasi;
    $types .= "s";
}

if (!empty($kategori)) {
    $conditions[] = "category = ?";
    $params[] = $kategori;
    $types .= "s";
}

if (!empty($disabilitas)) {
    $conditions[] = "FIND_IN_SET(?, suitable_disability_types)";
    $params[] = $disabilitas;
    $types .= "s";
}

if (!empty($conditions)) {
    $sql_base .= " AND " . implode(" AND ", $conditions);
}

$sql_base .= " ORDER BY created_at DESC"; 

$sql_count = "SELECT COUNT(*) as total " . $sql_base;
$stmt_count = $conn->prepare($sql_count);
if ($stmt_count && !empty($types)) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$total_results = $stmt_count->get_result()->fetch_assoc()['total'];
$stmt_count->close();


$sql_final = "SELECT id, title, company_name, company_logo_path, location, job_type, salary_range, category, created_at, education_level, experience_level, suitable_disability_types " . $sql_base;

$stmt_final = $conn->prepare($sql_final);
if ($stmt_final) {
    if (!empty($types)) {
        $stmt_final->bind_param($types, ...$params);
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
            
            $date_posted = new DateTime($row['created_at']);
            $now = new DateTime();
            $interval = $now->diff($date_posted);
            if ($interval->days >= 30) { $row['posted_ago'] = floor($interval->days / 30) . ' bulan lalu'; } 
            elseif ($interval->days >= 7) { $row['posted_ago'] = floor($interval->days / 7) . ' minggu lalu'; } 
            elseif ($interval->days > 1) { $row['posted_ago'] = $interval->days . ' hari lalu'; } 
            elseif ($interval->h > 0) { $row['posted_ago'] = $interval->h . ' jam lalu'; } 
            else { $row['posted_ago'] = 'Baru saja'; }
            
            $row['company_logo_path'] = (!empty($row['company_logo_path']) && file_exists($row['company_logo_path'])) ? $row['company_logo_path'] : 'images/placeholder_logo.png';
            $search_results_data[] = $row;
        }
    } else {
        $search_message = "Tidak ada lowongan yang cocok dengan kriteria pencarian Anda.";
    }
    $stmt_final->close();
} else {
    $search_message = "Terjadi kesalahan dalam mempersiapkan pencarian.";
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian Lowongan - JOB4DIS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        </header>

    <main class="all-jobs-page">
        <div class="container">
            <div class="page-header" style="text-align: left; margin-bottom: 20px;">
                <h1>Hasil Pencarian</h1>
                <p>
                    Menampilkan <?php echo $total_results; ?> hasil untuk:
                    <?php 
                    $criteria = [];
                    if (!empty($keyword)) $criteria[] = "Kata Kunci '<strong>" . htmlspecialchars($keyword) . "</strong>'";
                    if (!empty($lokasi)) $criteria[] = "Lokasi '<strong>" . htmlspecialchars($lokasi) . "</strong>'";
                    if (!empty($kategori)) $criteria[] = "Kategori '<strong>" . htmlspecialchars($kategori) . "</strong>'";
                    if (!empty($disabilitas)) $criteria[] = "Disabilitas '<strong>" . htmlspecialchars($disabilitas) . "</strong>'";
                    
                    if (empty($criteria)) {
                        echo "Semua lowongan.";
                    } else {
                        echo implode(", ", $criteria) . ".";
                    }
                    ?>
                </p>
            </div>

            <?php if (!empty($search_results_data)) : ?>
                <div class="popular-jobs-grid">
                    <?php foreach ($search_results_data as $job) : ?>
                        <a href="view_job.php?id=<?php echo $job['id']; ?>" class="job-card-popular">
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
            <?php else : ?>
                <div class="no-results-message" style="text-align: center; padding: 40px 20px;">
                    <p><?php echo $search_message; ?></p>
                    <p style="margin-top: 15px;">Coba ubah kata kunci atau filter pencarian Anda, atau <a href="index.php" class="btn btn-secondary">kembali ke halaman utama</a>.</p>
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