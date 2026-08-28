<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 800px; margin: 60px auto; text-align: center;">
    <h1 style="font-size: 2em; color: #222; margin-bottom: 10px;">Bergabung dengan JOB4DIS</h1>
    <p style="color: #666; margin-bottom: 40px;">Pilih jenis akun yang sesuai dengan kebutuhan Anda untuk memulai.</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px;">
        <!-- Seeker Card -->
        <a href="<?= base_url('auth/register') ?>" style="display: block; text-decoration: none; color: inherit; background: #fff; border-radius: 16px; padding: 40px 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.06); border: 2px solid transparent; transition: all 0.3s ease;" onmouseover="this.style.borderColor='#007bff'; this.style.transform='translateY(-5px)';" onmouseout="this.style.borderColor='transparent'; this.style.transform='translateY(0)';">
            <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: #e3f2fd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-graduate" style="font-size: 2.2em; color: #007bff;"></i>
            </div>
            <h2 style="font-size: 1.3em; margin-bottom: 10px; color: #333;">Saya Pencari Kerja</h2>
            <p style="font-size: 0.9em; color: #666; line-height: 1.5; margin-bottom: 25px;">Temukan lowongan kerja inklusif yang ramah disabilitas dan lamar karir impian Anda.</p>
            <span class="btn btn-primary btn-block" style="width: 100%;">Daftar Sebagai Pelamar</span>
        </a>

        <!-- Company Card -->
        <a href="<?= base_url('auth/register-company') ?>" style="display: block; text-decoration: none; color: inherit; background: #fff; border-radius: 16px; padding: 40px 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.06); border: 2px solid transparent; transition: all 0.3s ease;" onmouseover="this.style.borderColor='#ff9800'; this.style.transform='translateY(-5px)';" onmouseout="this.style.borderColor='transparent'; this.style.transform='translateY(0)';">
            <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: #fff3e0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-building" style="font-size: 2.2em; color: #ff9800;"></i>
            </div>
            <h2 style="font-size: 1.3em; margin-bottom: 10px; color: #333;">Saya Perusahaan / HRD</h2>
            <p style="font-size: 0.9em; color: #666; line-height: 1.5; margin-bottom: 25px;">Pasang lowongan kerja dan rekrut talenta disabilitas berbakat serta berdedikasi tinggi.</p>
            <span class="btn btn-secondary btn-block" style="width: 100%;">Daftar Sebagai Perusahaan</span>
        </a>
    </div>

    <p style="margin-top: 35px; color: #666;">
        Sudah memiliki akun? <a href="<?= base_url('auth/login') ?>" style="color: #007bff; font-weight: 600; text-decoration: none;">Masuk di sini</a>
    </p>
</div>
<?= $this->endSection() ?>
