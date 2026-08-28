<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Users
        $userModel = new \App\Models\UserModel();
        $companyModel = new \App\Models\CompanyModel();
        $jobModel = new \App\Models\JobModel();
        $testimonialModel = new \App\Models\TestimonialModel();

        // Check if users already exist
        if ($userModel->countAllResults() == 0) {
            // Company user
            $companyUserId = $userModel->insert([
                'nama_lengkap'         => 'PT Bank Rakyat Indonesia',
                'username'             => 'company_demo',
                'email'                => 'hr@bri.co.id',
                'password_hash'        => password_hash('company123', PASSWORD_DEFAULT),
                'role'                 => 'company',
                'status'               => 'aktif',
                'profile_picture_path' => 'images/logo_bri.png',
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ]);

            // Company profile
            $companyModel->insert([
                'user_id'             => $companyUserId,
                'company_name'        => 'PT Bank Rakyat Indonesia',
                'company_logo_path'   => 'images/logo_bri.png',
                'company_description' => 'Bank Rakyat Indonesia adalah salah satu bank komersial terbesar di Indonesia yang berkomitmen terhadap kesempatan kerja inklusif dan ramah disabilitas.',
                'company_industry'    => 'Perbankan / Finansial',
                'company_size'        => '10.000+ Karyawan',
                'company_address'     => 'Jl. Jenderal Sudirman Kav. 44-46, Jakarta Pusat',
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ]);

            // Seeker user
            $seekerUserId = $userModel->insert([
                'nama_lengkap'         => 'Budi Santoso',
                'jenis_kelamin'        => 'Laki-laki',
                'username'             => 'seeker_demo',
                'email'                => 'budi@example.com',
                'password_hash'        => password_hash('seeker123', PASSWORD_DEFAULT),
                'jenis_disabilitas'    => 'Daksa',
                'role'                 => 'seeker',
                'phone_number'         => '08123456789',
                'status'               => 'aktif',
                'profile_picture_path' => 'images/testimoni_budi.jpg',
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ]);

            // Jobs
            $jobs = [
                [
                    'title'                     => 'IT Support Specialist',
                    'company_name'              => 'PT Bank Rakyat Indonesia',
                    'company_logo_path'         => 'images/logo_bri.png',
                    'location'                  => 'Jakarta (Remote / WFH)',
                    'category'                  => 'IT',
                    'job_type'                  => 'Full Time',
                    'salary_range'              => 'Rp 5.000.000 - Rp 7.000.000',
                    'education_level'           => 'D3 / S1',
                    'experience_level'          => '1-2 Tahun',
                    'suitable_disability_types' => 'Rungu Wicara,Daksa',
                    'job_description'           => 'Bertanggung jawab dalam pemeliharaan sistem IT kantor secara berkala, konfigurasi perangkat, dan troubleshooting jarak jauh.',
                    'responsibilities'          => "- Membantu troubleshoot kendala user secara remote\n- Melakukan maintenance server & jaringan kantor\n- Dokumentasi tiket penanganan IT",
                    'qualifications'            => "- Memahami dasar networking (TCP/IP, DNS) dan OS Windows/Linux\n- Mampu berkomunikasi tertulis secara jelas\n- Terbiasa menggunakan ticketing tools",
                    'work_schedule'             => 'Senin - Jumat, 08:30 - 17:30 WIB',
                    'skills'                    => "- Troubleshooting IT\n- Remote Desktop\n- Network Configuration",
                    'posted_by_user_id'         => $companyUserId,
                    'is_active'                 => 1,
                    'created_at'                => date('Y-m-d H:i:s'),
                    'updated_at'                => date('Y-m-d H:i:s'),
                ],
                [
                    'title'                     => 'Digital Marketing & Content Creator',
                    'company_name'              => 'PT Gojek Indonesia',
                    'company_logo_path'         => 'images/logo_gojek.png',
                    'location'                  => 'Bandung (Hybrid)',
                    'category'                  => 'Pemasaran',
                    'job_type'                  => 'Full Time',
                    'salary_range'              => 'Rp 4.500.000 - Rp 6.500.000',
                    'education_level'           => 'SMA/SMK / S1',
                    'experience_level'          => 'Fresh Graduate',
                    'suitable_disability_types' => 'Daksa,Rungu Wicara',
                    'job_description'           => 'Merancang konten kreatif untuk media sosial perusahaan dan mengelola kampanye iklan digital yang menarik.',
                    'responsibilities'          => "- Membuat editorial plan konten bulanan\n- Merancang feed Instagram, LinkedIn & video TikTok\n- Analisis performa medsos secara berkala",
                    'qualifications'            => "- Kreatif, up-to-date dengan tren digital\n- Menguasai Canva / Figma / Adobe Photoshop\n- Memahami dasar copywriting persuasif",
                    'work_schedule'             => 'Fleksibel Hybrid (2 Hari WFO, 3 Hari WFH)',
                    'skills'                    => "- Copywriting\n- Content Creation\n- Social Media Analytics",
                    'posted_by_user_id'         => $companyUserId,
                    'is_active'                 => 1,
                    'created_at'                => date('Y-m-d H:i:s'),
                    'updated_at'                => date('Y-m-d H:i:s'),
                ],
                [
                    'title'                     => 'Staf Administrasi & Data Entry',
                    'company_name'              => 'PT Bank Mandiri (Persero) Tbk',
                    'company_logo_path'         => 'images/logo_mandiri.png',
                    'location'                  => 'Surabaya (WFO / Hybrid)',
                    'category'                  => 'Administrasi',
                    'job_type'                  => 'Kontrak',
                    'salary_range'              => 'Rp 4.000.000 - Rp 5.000.000',
                    'education_level'           => 'SMA / D3',
                    'experience_level'          => 'Fresh Graduate',
                    'suitable_disability_types' => 'Netra,Daksa',
                    'job_description'           => 'Menginput data operasional harian perusahaan ke dalam database internal dan merapikan arsip dokumen kantor.',
                    'responsibilities'          => "- Input invoice dan rekapitulasi data penjualan harian\n- Mengelola arsip digital dan korespondensi dokumen\n- Melakukan verifikasi kelengkapan data berkas",
                    'qualifications'            => "- Teliti, rapi, dan bertanggung jawab\n- Menguasai Microsoft Excel / Google Sheets (VLOOKUP, Pivot Table)\n- Kecepatan mengetik minimal 50 WPM",
                    'work_schedule'             => 'Senin - Jumat, 08:00 - 17:00 WIB',
                    'skills'                    => "- Data Entry\n- MS Excel / Google Sheets\n- File Management",
                    'posted_by_user_id'         => $companyUserId,
                    'is_active'                 => 1,
                    'created_at'                => date('Y-m-d H:i:s'),
                    'updated_at'                => date('Y-m-d H:i:s'),
                ],
                [
                    'title'                     => 'Customer Service Online (Live Chat)',
                    'company_name'              => 'PT Teleperformance Indonesia',
                    'company_logo_path'         => 'images/logo_teleperformance.png',
                    'location'                  => 'Yogyakarta (Remote)',
                    'category'                  => 'Customer Service',
                    'job_type'                  => 'Full Time',
                    'salary_range'              => 'Rp 3.800.000 - Rp 4.800.000',
                    'education_level'           => 'SMA/SMK',
                    'experience_level'          => 'Fresh Graduate',
                    'suitable_disability_types' => 'Daksa,Netra',
                    'job_description'           => 'Melayani keluhan, pertanyaan, dan konsultasi pengguna melalui saluran chat dan email secara profesional.',
                    'responsibilities'          => "- Menjawab tiket pertanyaan customer secara ramah & solutif\n- Mengeskalasi issue teknis ke tim terkait\n- Menjaga skor kepuasan pelanggan (CSAT)",
                    'qualifications'            => "- Kemampuan komunikasi tulisan yang sangat baik dan santun\n- Mampu mengetik dengan cepat dan minim typo\n- Memiliki koneksi internet stabil untuk WFH",
                    'work_schedule'             => 'Sistem Shift (8 Jam Kerja)',
                    'skills'                    => "- Customer Support\n- Fast Typing\n- Problem Solving",
                    'posted_by_user_id'         => $companyUserId,
                    'is_active'                 => 1,
                    'created_at'                => date('Y-m-d H:i:s'),
                    'updated_at'                => date('Y-m-d H:i:s'),
                ],
            ];

            foreach ($jobs as $j) {
                $jobModel->insert($j);
            }
        }

        // Testimonials
        if ($testimonialModel->countAllResults() == 0) {
            $testimonials = [
                [
                    'user_name'        => 'Siti Rahmawati',
                    'job_title'        => 'Data Entry & Admin di Bank Mandiri',
                    'testimonial_text' => 'Platform JOB4DIS sangat memudahkan saya yang bertuna daksa mendapatkan pekerjaan dengan lingkungan kerja yang inklusif dan suportif.',
                    'photo_path'       => 'images/testimoni_rina.jpg',
                    'is_featured'      => 1,
                    'created_at'       => date('Y-m-d H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s'),
                ],
                [
                    'user_name'        => 'Ahmad Fauzi',
                    'job_title'        => 'IT Support Specialist di BRI',
                    'testimonial_text' => 'Melalui JOB4DIS, proses rekrutmen berlangsung sangat transparan dan ramah disabilitas. Terima kasih!',
                    'photo_path'       => 'images/testimoni_budi.jpg',
                    'is_featured'      => 1,
                    'created_at'       => date('Y-m-d H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s'),
                ],
                [
                    'user_name'        => 'Fitri Handayani',
                    'job_title'        => 'Digital Marketing di Gojek',
                    'testimonial_text' => 'Sangat bersyukur ada portal lowongan yang benar-benar peduli dengan kesetaraan kerja bagi penyandang disabilitas di Indonesia.',
                    'photo_path'       => 'images/testimoni_fitri.jpg',
                    'is_featured'      => 1,
                    'created_at'       => date('Y-m-d H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s'),
                ],
            ];

            foreach ($testimonials as $t) {
                $testimonialModel->insert($t);
            }
        }
    }
}
