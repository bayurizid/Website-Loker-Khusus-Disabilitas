<?= $this->extend('layout/dashboard_layout') ?>

<?= $this->section('dashboard_content') ?>
<div class="dashboard-header" style="margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
    <h2 style="margin: 0 0 5px 0; font-size: 1.4em; color: #222;">Pasang Lowongan Kerja Baru</h2>
    <p style="color: #666; font-size: 0.9em;">Publikasikan lowongan kerja inklusif dan temukan kandidat disabilitas terbaik.</p>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert-box alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= base_url('company/save-job') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group" style="margin-bottom: 18px;">
        <label for="title" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Judul Posisi / Pekerjaan <span style="color: red;">*</span></label>
        <input type="text" id="title" name="title" value="<?= old('title') ?>" class="form-control" placeholder="Contoh: IT Support Specialist, Staff Administrasi" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 18px;">
        <div class="form-group">
            <label for="location" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Lokasi Penempatan <span style="color: red;">*</span></label>
            <input type="text" id="location" name="location" value="<?= old('location') ?>" class="form-control" placeholder="Contoh: Jakarta (Remote / WFH) atau Surabaya (Hybrid)" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
        </div>
        <div class="form-group">
            <label for="category" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Kategori Pekerjaan <span style="color: red;">*</span></label>
            <select id="category" name="category" class="form-control" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
                <option value="">-- Pilih Kategori --</option>
                <option value="IT" <?= (old('category') === 'IT') ? 'selected' : '' ?>>IT & Komputer</option>
                <option value="Administrasi" <?= (old('category') === 'Administrasi') ? 'selected' : '' ?>>Administrasi</option>
                <option value="Customer Service" <?= (old('category') === 'Customer Service') ? 'selected' : '' ?>>Customer Service</option>
                <option value="Pemasaran" <?= (old('category') === 'Pemasaran') ? 'selected' : '' ?>>Pemasaran & Media Sosial</option>
                <option value="Akuntansi" <?= (old('category') === 'Akuntansi') ? 'selected' : '' ?>>Akuntansi & Keuangan</option>
                <option value="Desain Grafis" <?= (old('category') === 'Desain Grafis') ? 'selected' : '' ?>>Desain Grafis & Multimedia</option>
                <option value="Pendidikan" <?= (old('category') === 'Pendidikan') ? 'selected' : '' ?>>Pendidikan & Pengajaran</option>
            </select>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 18px;">
        <div class="form-group">
            <label for="job_type" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Tipe Pekerjaan <span style="color: red;">*</span></label>
            <select id="job_type" name="job_type" class="form-control" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
                <option value="Full Time" <?= (old('job_type') === 'Full Time') ? 'selected' : '' ?>>Full Time</option>
                <option value="Part Time" <?= (old('job_type') === 'Part Time') ? 'selected' : '' ?>>Part Time</option>
                <option value="Kontrak" <?= (old('job_type') === 'Kontrak') ? 'selected' : '' ?>>Kontrak</option>
                <option value="Magang / Internship" <?= (old('job_type') === 'Magang / Internship') ? 'selected' : '' ?>>Magang / Internship</option>
                <option value="Freelance" <?= (old('job_type') === 'Freelance') ? 'selected' : '' ?>>Freelance</option>
            </select>
        </div>
        <div class="form-group">
            <label for="salary_range" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Kisaran Gaji / Bulan <span style="color: red;">*</span></label>
            <input type="text" id="salary_range" name="salary_range" value="<?= old('salary_range') ?>" class="form-control" placeholder="Contoh: Rp 4.500.000 - Rp 6.000.000" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 18px;">
        <div class="form-group">
            <label for="education_level" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Minimal Pendidikan <span style="color: red;">*</span></label>
            <input type="text" id="education_level" name="education_level" value="<?= old('education_level') ?>" class="form-control" placeholder="Contoh: SMA/SMK / D3 / S1" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
        </div>
        <div class="form-group">
            <label for="experience_level" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Pengalaman Kerja <span style="color: red;">*</span></label>
            <input type="text" id="experience_level" name="experience_level" value="<?= old('experience_level') ?>" class="form-control" placeholder="Contoh: Fresh Graduate atau 1-2 Tahun" required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
        </div>
    </div>

    <!-- Disability Types Checkbox -->
    <div class="form-group" style="margin-bottom: 25px;">
        <label style="display: block; font-weight: 600; margin-bottom: 4px; color: #444;">Kategori Disabilitas yang Sesuai <span style="color: red;">*</span></label>
        <p style="font-size: 0.85em; color: #777; margin-bottom: 12px;">Pilih ragam disabilitas yang memenuhi syarat untuk posisi ini:</p>

        <div class="disability-options-container" id="disability-options">
            <?php 
                $oldDis = old('disabilitas') ?: [];
                if (!is_array($oldDis)) $oldDis = explode(',', $oldDis);
                $disList = [
                    'Mental'       => 'Mental.png',
                    'Grahita'      => 'Grahita.png',
                    'Netra'        => 'Netra.png',
                    'Rungu Wicara' => 'Rungu Wicara.png',
                    'Daksa'        => 'Daksa.png',
                ];
            ?>
            <?php foreach ($disList as $name => $img): ?>
                <label class="disability-option-label">
                    <input type="checkbox" name="disabilitas[]" value="<?= $name ?>" <?= in_array($name, $oldDis) ? 'checked' : '' ?>>
                    <img src="<?= base_url('images/' . $img) ?>" alt="Ikon <?= $name ?>">
                    <span><?= $name ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group" style="margin-bottom: 20px;">
        <label for="job_description" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Deskripsi Pekerjaan <span style="color: red;">*</span></label>
        <textarea id="job_description" name="job_description" rows="4" class="form-control" placeholder="Jelaskan gambaran umum pekerjaan ini..." required style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;"><?= old('job_description') ?></textarea>
    </div>

    <div class="form-group" style="margin-bottom: 20px;">
        <label for="responsibilities" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Tanggung Jawab Pekerjaan</label>
        <textarea id="responsibilities" name="responsibilities" rows="4" class="form-control" placeholder="Gunakan tanda strip (-) untuk daftar poin responsibilitas..." style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;"><?= old('responsibilities') ?></textarea>
    </div>

    <div class="form-group" style="margin-bottom: 20px;">
        <label for="qualifications" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Kualifikasi Pelamar</label>
        <textarea id="qualifications" name="qualifications" rows="4" class="form-control" placeholder="Kualifikasi yang diharapkan..." style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;"><?= old('qualifications') ?></textarea>
    </div>

    <div class="form-group" style="margin-bottom: 25px;">
        <label for="work_schedule" style="display: block; font-weight: 600; margin-bottom: 6px; color: #444;">Jadwal Kerja (Opsional)</label>
        <input type="text" id="work_schedule" name="work_schedule" value="<?= old('work_schedule') ?>" class="form-control" placeholder="Contoh: Senin - Jumat, 08:30 - 17:30 WIB" style="width: 100%; padding: 11px; border: 1px solid #ddd; border-radius: 8px;">
    </div>

    <button type="submit" class="btn btn-secondary" style="padding: 13px 35px; font-size: 1em;">
        <i class="fas fa-paper-plane"></i> Publikasikan Lowongan
    </button>
</form>
<?= $this->endSection() ?>
