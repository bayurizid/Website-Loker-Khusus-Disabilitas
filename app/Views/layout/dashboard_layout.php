<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container dashboard-container" style="display: flex; gap: 30px; margin: 40px auto; min-height: 60vh;">
    <!-- Sidebar Component -->
    <aside class="dashboard-sidebar" style="flex: 0 0 260px; background: #fff; border-radius: 12px; padding: 25px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); height: fit-content;">
        <div class="sidebar-profile" style="text-align: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
            <img src="<?= base_url(session()->get('profile_picture_path') ?: 'images/placeholder_profile.png') ?>" alt="Foto Profil" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; border: 3px solid #f0f4f8;">
            <h4 style="margin: 0; font-size: 1.1em; color: #333;"><?= esc(session()->get('nama_lengkap')) ?></h4>
            <span style="font-size: 0.85em; color: #777;"><?= (session()->get('role') === 'company') ? 'Perusahaan Mitra' : 'Pencari Kerja' ?></span>
        </div>

        <ul class="sidebar-nav" style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 10px;">
                <a href="<?= base_url('dashboard') ?>" class="<?= ($active_tab === 'overview') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 8px; color: #555; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-home" style="width: 20px;"></i> Ringkasan
                </a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="<?= base_url('dashboard/profile') ?>" class="<?= ($active_tab === 'profile') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 8px; color: #555; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-user-edit" style="width: 20px;"></i> Edit Profil
                </a>
            </li>
            <?php if (session()->get('role') === 'seeker'): ?>
                <li style="margin-bottom: 10px;">
                    <a href="<?= base_url('dashboard/history') ?>" class="<?= ($active_tab === 'history') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 8px; color: #555; text-decoration: none; font-weight: 500;">
                        <i class="fas fa-paper-plane" style="width: 20px;"></i> Riwayat Lamaran
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="<?= base_url('dashboard/saved') ?>" class="<?= ($active_tab === 'saved') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 8px; color: #555; text-decoration: none; font-weight: 500;">
                        <i class="fas fa-bookmark" style="width: 20px;"></i> Lowongan Disimpan
                    </a>
                </li>
            <?php else: ?>
                <li style="margin-bottom: 10px;">
                    <a href="<?= base_url('company/post-job') ?>" class="<?= ($active_tab === 'post_job') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 8px; color: #555; text-decoration: none; font-weight: 500;">
                        <i class="fas fa-plus-circle" style="width: 20px;"></i> Pasang Lowongan
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="<?= base_url('company/applicants') ?>" class="<?= ($active_tab === 'applicants') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 8px; color: #555; text-decoration: none; font-weight: 500;">
                        <i class="fas fa-users" style="width: 20px;"></i> Daftar Pelamar
                    </a>
                </li>
            <?php endif; ?>
            <li style="margin-top: 25px; padding-top: 15px; border-top: 1px solid #eee;">
                <a href="<?= base_url('auth/logout') ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 8px; color: #e53935; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-sign-out-alt" style="width: 20px;"></i> Keluar
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Dashboard Content -->
    <main class="dashboard-main" style="flex: 1; background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <?= $this->renderSection('dashboard_content') ?>
    </main>
</div>

<style>
    .sidebar-nav a.active {
        background: #007bff;
        color: #fff !important;
    }
    .sidebar-nav a:hover:not(.active) {
        background: #f4f7fa;
        color: #007bff;
    }
    @media (max-width: 768px) {
        .dashboard-container { flex-direction: column !important; }
        .dashboard-sidebar { flex: 1 1 100% !important; }
    }
</style>
<?= $this->endSection() ?>
