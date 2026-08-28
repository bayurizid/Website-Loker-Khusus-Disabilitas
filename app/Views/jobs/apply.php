<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 700px; margin: 40px auto;">
    <div class="breadcrumbs">
        <a href="<?= base_url('') ?>">Beranda</a> <i class="fas fa-chevron-right"></i>
        <a href="<?= base_url('jobs/detail/' . $job['id']) ?>"><?= esc($job['title']) ?></a> <i class="fas fa-chevron-right"></i>
        <span>Lamar Pekerjaan</span>
    </div>

    <div class="auth-card" style="background: #fff; border-radius: 12px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
            <img src="<?= base_url($job['company_logo_path']) ?>" alt="Logo <?= esc($job['company_name']) ?>" style="width: 60px; height: 60px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; padding: 3px;">
            <div>
                <h2 style="margin: 0; font-size: 1.3em; color: #333;"><?= esc($job['title']) ?></h2>
                <span style="color: #007bff; font-weight: 500;"><?= esc($job['company_name']) ?></span> &bull; <span style="color: #777; font-size: 0.9em;"><?= esc($job['location']) ?></span>
            </div>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert-box alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('jobs/process-apply/' . $job['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #444;">Nama Lengkap Pelamar</label>
                <input type="text" value="<?= esc($user['nama_lengkap']) ?>" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9;" readonly>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #444;">Email Pelamar</label>
                <input type="email" value="<?= esc($user['email']) ?>" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9;" readonly>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="resume" style="display: block; font-weight: 600; margin-bottom: 8px; color: #444;">
                    Upload Resume / CV <span style="color: red;">*</span>
                </label>
                <input type="file" id="resume" name="resume" class="form-control" accept=".pdf,.doc,.docx" required style="width: 100%; padding: 10px; border: 1px dashed #007bff; border-radius: 8px; background: #f8fbff;">
                <small style="color: #666; display: block; margin-top: 5px;">Format yang didukung: PDF, DOC, DOCX (Maksimal 5MB).</small>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="cover_letter" style="display: block; font-weight: 600; margin-bottom: 8px; color: #444;">
                    Upload Surat Lamaran / Cover Letter <span style="color: #777; font-weight: normal;">(Opsional)</span>
                </label>
                <input type="file" id="cover_letter" name="cover_letter" class="form-control" accept=".pdf,.doc,.docx" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                <small style="color: #666; display: block; margin-top: 5px;">Format yang didukung: PDF, DOC, DOCX (Maksimal 5MB).</small>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="notes" style="display: block; font-weight: 600; margin-bottom: 8px; color: #444;">
                    Pesan / Catatan Tambahan untuk HRD <span style="color: #777; font-weight: normal;">(Opsional)</span>
                </label>
                <textarea id="notes" name="notes" rows="4" class="form-control" placeholder="Tuliskan perkenalan singkat atau kebutuhan fasilitas aksesibilitas jika ada..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; resize: vertical;"><?= old('notes') ?></textarea>
            </div>

            <div style="display: flex; gap: 15px; align-items: center;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1em;">
                    <i class="fas fa-paper-plane"></i> Kirim Lamaran
                </button>
                <a href="<?= base_url('jobs/detail/' . $job['id']) ?>" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
