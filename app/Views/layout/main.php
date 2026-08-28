<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'JOB4DIS - Portal Lowongan Kerja Inklusif') ?></title>
    
    <!-- CSS Stylesheets with Cache-Buster -->
    <link rel="stylesheet" href="<?= base_url('css/style.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= base_url('css/slider.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= base_url('css/info-footer.css?v=' . time()) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Meta CSRF for AJAX -->
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-hash" content="<?= csrf_hash() ?>">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .alert-box {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .alert-danger { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .alert-info { background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; }
        .badge-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }
        .badge-pending { background: #fff3e0; color: #e65100; }
        .badge-interview { background: #e3f2fd; color: #0d47a1; }
        .badge-accepted { background: #e8f5e9; color: #1b5e20; }
        .badge-rejected { background: #ffebee; color: #b71c1c; }
        .job-card-popular-header { display: flex; align-items: center; gap: 15px; }
        .company-logo-popular { width: 50px; height: 50px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; padding: 3px; background: #fff; }

        /* Custom Dropdown Styling Guard */
        .custom-select-wrapper {
            position: relative;
            cursor: pointer;
            user-select: none;
        }
        .custom-select-trigger {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 10px 12px 10px 0;
            font-size: 0.95em;
            color: #333;
        }
        .custom-select-trigger .selected-text {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .custom-select-trigger .select-opt-icon {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }
        .custom-select-options {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            z-index: 9999;
            display: none;
            max-height: 280px;
            overflow-y: auto;
        }
        .custom-select-options.open {
            display: block !important;
        }
        .custom-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            font-size: 0.9em;
            color: #333;
            transition: background 0.15s ease;
            cursor: pointer;
        }
        .custom-option:hover {
            background: #f0f7ff;
            color: #007bff;
        }
        .custom-option.selected {
            background: #e3f2fd;
            font-weight: 600;
            color: #007bff;
        }
        .custom-option img {
            width: 22px;
            height: 22px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="<?= base_url('') ?>">
                    <img src="<?= base_url('images/logo.png') ?>" alt="JOB4DIS Logo">
                </a>
            </div>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="<?= base_url('jobs') ?>">Cari Loker</a></li>
                    <?php if (session()->get('logged_in') && session()->get('role') === 'company'): ?>
                        <li><a href="<?= base_url('company/post-job') ?>">Pasang Loker</a></li>
                        <li><a href="<?= base_url('company/applicants') ?>">Daftar Pelamar</a></li>
                    <?php else: ?>
                        <li><a href="<?= base_url('company') ?>">Untuk Perusahaan</a></li>
                    <?php endif; ?>
                    <li><a href="<?= base_url('#tentang-kami') ?>">Tentang Kami</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <?php if (session()->get('logged_in')) : ?>
                    <span class="welcome-user">Halo, <strong><?= esc(session()->get('nama_lengkap')) ?></strong></span>
                    <a href="<?= base_url('dashboard') ?>" class="auth-links">Dashboard</a>
                    <span class="auth-separator">/</span>
                    <a href="<?= base_url('auth/logout') ?>" class="auth-links">Keluar</a>
                <?php else : ?>
                    <a href="<?= base_url('auth/choice') ?>" class="auth-links">Registrasi</a>
                    <span class="auth-separator">/</span>
                    <a href="<?= base_url('auth/login') ?>" class="auth-links">Masuk</a>
                    <a href="<?= base_url('auth/register-company') ?>" class="btn btn-secondary btn-sm" style="margin-left: 8px;">Pasang Loker</a>
                <?php endif; ?>
            </div>
            <button class="mobile-nav-toggle" aria-label="Toggle Navigation Menu" aria-expanded="false" aria-controls="mainNav">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Flash Message Container -->
    <div class="container" style="margin-top: 15px;">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-box alert-success"><i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-box alert-danger"><i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert-box alert-info"><i class="fas fa-info-circle"></i> <?= session()->getFlashdata('info') ?></div>
        <?php endif; ?>
    </div>

    <!-- Main Content Section -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Info & Footer -->
    <section class="info-section" id="tentang-kami">
        <div class="container">
            <div class="info-content">
                <div class="info-about">
                    <img src="<?= base_url('images/logo.png') ?>" alt="JOB4DIS Logo" class="info-logo">
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
                            <img src="<?= base_url('images/gov/kemnaker.png') ?>" alt="Logo KEMNAKER">
                        </a>
                        <a href="https://kemensos.go.id/" target="_blank" rel="noopener noreferrer" class="gov-logo-item">
                            <img src="<?= base_url('images/gov/kemensos.png') ?>" alt="Logo Kementerian Sosial RI">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; <?= date("Y") ?> JOB4DIS. Dibuat dengan cinta untuk inklusivitas Indonesia.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?= base_url('js/script.js?v=' . time()) ?>"></script>
    <script>
        // Global handler for AJAX Bookmark toggle
        document.querySelectorAll('.job-card-favorite-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const jobId = this.getAttribute('data-job-id');
                const heartIcon = this.querySelector('i');

                const csrfName = document.querySelector('meta[name="csrf-token-name"]').getAttribute('content');
                const csrfHash = document.querySelector('meta[name="csrf-hash"]').getAttribute('content');

                const formData = new FormData();
                formData.append('job_id', jobId);
                formData.append(csrfName, csrfHash);

                fetch('<?= base_url('jobs/toggle-save') ?>', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        if (data.action === 'saved') {
                            heartIcon.classList.remove('far');
                            heartIcon.classList.add('fas');
                            heartIcon.style.color = '#e74c3c';
                        } else {
                            heartIcon.classList.remove('fas');
                            heartIcon.classList.add('far');
                            heartIcon.style.color = '';
                        }
                    } else if (res.status === 401 || data.message) {
                        alert(data.message);
                        if (data.status === 'error' && data.message.includes('login')) {
                            window.location.href = '<?= base_url('auth/login') ?>';
                        }
                    }
                })
                .catch(err => console.error(err));
            });
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
