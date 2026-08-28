<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="company-landing-page">
    <!-- Hero Banner -->
    <section style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #fff; padding: 70px 0; text-align: center;">
        <div class="container" style="max-width: 850px;">
            <h1 style="font-size: 2.5em; margin-bottom: 15px; font-weight: 700;">Bangun Tempat Kerja Inklusif Bersama JOB4DIS</h1>
            <p style="font-size: 1.15em; line-height: 1.6; opacity: 0.95; margin-bottom: 35px;">
                Dapatkan akses langsung ke ribuan talenta penyandang disabilitas yang berkompeten, berdedikasi tinggi, dan siap memberikan dampak positif bagi perusahaan Anda.
            </p>
            <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                <?php if (session()->get('logged_in') && session()->get('role') === 'company'): ?>
                    <a href="<?= base_url('company/post-job') ?>" class="btn btn-secondary" style="padding: 14px 35px; font-size: 1.05em;">Pasang Lowongan Sekarang</a>
                <?php else: ?>
                    <a href="<?= base_url('auth/register-company') ?>" class="btn btn-secondary" style="padding: 14px 35px; font-size: 1.05em;">Daftarkan Perusahaan</a>
                    <a href="<?= base_url('auth/login') ?>" class="btn btn-outline" style="padding: 14px 35px; font-size: 1.05em; color: #fff; border-color: #fff;">Masuk Akun Perusahaan</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Key Benefits -->
    <section style="padding: 60px 0; background: #f8fafc;">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 45px; font-size: 1.8em; color: #222;">Mengapa Merekrut Melalui JOB4DIS?</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <div style="background: #fff; padding: 30px 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); text-align: center;">
                    <div style="width: 65px; height: 65px; background: #e3f2fd; color: #007bff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8em; margin: 0 auto 20px;">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 style="font-size: 1.2em; color: #333; margin-bottom: 10px;">Talenta Beragam & Terlatih</h3>
                    <p style="font-size: 0.9em; color: #666; line-height: 1.6;">Database kandidat disabilitas dari berbagai disiplin ilmu, mulai dari IT, Administrasi, Desain, hingga Manajemen.</p>
                </div>

                <div style="background: #fff; padding: 30px 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); text-align: center;">
                    <div style="width: 65px; height: 65px; background: #e8f5e9; color: #2e7d32; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8em; margin: 0 auto 20px;">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3 style="font-size: 1.2em; color: #333; margin-bottom: 10px;">Dukungan Regulasi UU No. 8/2016</h3>
                    <p style="font-size: 0.9em; color: #666; line-height: 1.6;">Membantu perusahaan Anda memenuhi kuota penyerapan tenaga kerja disabilitas sesuai regulasi ketenagakerjaan resmi Indonesia.</p>
                </div>

                <div style="background: #fff; padding: 30px 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); text-align: center;">
                    <div style="width: 65px; height: 65px; background: #fff3e0; color: #e65100; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8em; margin: 0 auto 20px;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 style="font-size: 1.2em; color: #333; margin-bottom: 10px;">Dashboard Pengelolaan Mudah</h3>
                    <p style="font-size: 0.9em; color: #666; line-height: 1.6;">Pasang lowongan, kelola CV pelamar, dan atur jadwal wawancara langsung dari satu dashboard intuitif.</p>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
