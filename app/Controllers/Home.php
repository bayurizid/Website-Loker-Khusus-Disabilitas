<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\TestimonialModel;
use App\Models\SavedJobModel;

class Home extends BaseController
{
    public function index()
    {
        $jobModel         = new JobModel();
        $testimonialModel = new TestimonialModel();
        $savedJobModel    = new SavedJobModel();

        $popularSearches = [
            "Administrasi", "Guru", "Driver", "IT Support", 
            "Accounting Finance", "Content Creator", "Fresh Graduate", 
            "Penjualan", "Konstruksi Bangunan", "Digital Marketing", 
            "Desain Grafis", "Komunikasi Pemasaran"
        ];

        $popularJobs = $jobModel->getPopularJobs(6);
        $testimonials = $testimonialModel->getFeatured();

        // Check saved jobs for current logged-in user
        $savedJobIds = [];
        if (session()->get('logged_in')) {
            $savedJobIds = $savedJobModel->getUserSavedJobIds(session()->get('user_id'));
        }

        $data = [
            'title'            => 'JOB4DIS - Portal Lowongan Kerja Khusus Disabilitas',
            'popular_searches' => $popularSearches,
            'popular_jobs'     => $popularJobs,
            'testimonials'     => $testimonials,
            'saved_job_ids'    => $savedJobIds,
        ];

        return view('home/index', $data);
    }
}
