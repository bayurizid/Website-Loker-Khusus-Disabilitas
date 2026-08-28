<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\CompanyModel;
use App\Models\JobApplicationModel;
use App\Models\UserModel;

class Company extends BaseController
{
    protected $jobModel;
    protected $companyModel;
    protected $applicationModel;
    protected $userModel;

    public function __construct()
    {
        $this->jobModel         = new JobModel();
        $this->companyModel     = new CompanyModel();
        $this->applicationModel = new JobApplicationModel();
        $this->userModel        = new UserModel();
    }

    /**
     * Halaman Informasi untuk Perusahaan
     */
    public function landing()
    {
        return view('company/landing', ['title' => 'Pasang Lowongan & Rekrut Talenta Disabilitas - JOB4DIS']);
    }

    /**
     * Form Pasang Lowongan Baru
     */
    public function postJob()
    {
        $userId = session()->get('user_id');
        $company = $this->companyModel->getByUserId($userId);

        $data = [
            'title'      => 'Pasang Lowongan Kerja Baru - JOB4DIS',
            'company'    => $company,
            'active_tab' => 'post_job',
        ];

        return view('company/post_job', $data);
    }

    /**
     * Proses Simpan Lowongan Baru
     */
    public function saveJob()
    {
        $userId = session()->get('user_id');
        $company = $this->companyModel->getByUserId($userId);

        $validationRules = [
            'title'            => 'required|min_length[3]|max_length[200]',
            'location'         => 'required',
            'category'         => 'required',
            'job_type'         => 'required',
            'salary_range'     => 'required',
            'education_level'  => 'required',
            'experience_level' => 'required',
            'disabilitas'      => 'required',
            'job_description'  => 'required|min_length[10]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $disabilities = $this->request->getPost('disabilitas');
        $disabilityString = is_array($disabilities) ? implode(',', $disabilities) : $disabilities;

        $companyLogo = $company['company_logo_path'] ?? 'images/placeholder_logo.png';
        $companyName = $company['company_name'] ?? session()->get('nama_lengkap');

        $jobId = $this->jobModel->insert([
            'title'                     => $this->request->getPost('title'),
            'company_name'              => $companyName,
            'company_logo_path'         => $companyLogo,
            'location'                  => $this->request->getPost('location'),
            'category'                  => $this->request->getPost('category'),
            'job_type'                  => $this->request->getPost('job_type'),
            'salary_range'              => $this->request->getPost('salary_range'),
            'education_level'           => $this->request->getPost('education_level'),
            'experience_level'          => $this->request->getPost('experience_level'),
            'suitable_disability_types' => $disabilityString,
            'job_description'           => $this->request->getPost('job_description'),
            'responsibilities'          => $this->request->getPost('responsibilities'),
            'qualifications'            => $this->request->getPost('qualifications'),
            'work_schedule'             => $this->request->getPost('work_schedule'),
            'skills'                    => $this->request->getPost('skills'),
            'posted_by_user_id'         => $userId,
            'is_active'                 => 1,
        ]);

        return redirect()->to(base_url("jobs/detail/{$jobId}"))
                         ->with('success', 'Lowongan kerja Anda berhasil dipublikasikan!');
    }

    /**
     * Kelola Seluruh Pelamar pada Lowongan Perusahaan
     */
    public function applicants()
    {
        $userId = session()->get('user_id');

        // Ambil semua lowongan yang dibuat oleh perusahaan ini
        $myJobs = $this->jobModel->where('posted_by_user_id', $userId)->findAll();
        $jobIds = array_column($myJobs, 'id');

        $applicants = [];
        if (!empty($jobIds)) {
            $applicants = $this->applicationModel
                               ->select('job_applications.*, jobs.title as job_title, users.nama_lengkap, users.email, users.phone_number, users.jenis_disabilitas, users.profile_picture_path')
                               ->join('jobs', 'jobs.id = job_applications.job_id')
                               ->join('users', 'users.id = job_applications.user_id')
                               ->whereIn('job_applications.job_id', $jobIds)
                               ->orderBy('job_applications.created_at', 'DESC')
                               ->findAll();
        }

        $data = [
            'title'      => 'Kelola Pelamar Kerja - JOB4DIS',
            'applicants' => $applicants,
            'my_jobs'    => $myJobs,
            'active_tab' => 'applicants',
        ];

        return view('company/applicants', $data);
    }

    /**
     * Update Status Lamaran (Diterima / Wawancara / Ditolak)
     */
    public function updateApplicationStatus()
    {
        $applicationId = (int) $this->request->getPost('application_id');
        $newStatus     = trim($this->request->getPost('status') ?? '');

        if (!$applicationId || !in_array($newStatus, ['pending', 'interview', 'accepted', 'rejected'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->applicationModel->update($applicationId, [
            'status' => $newStatus,
        ]);

        return redirect()->back()->with('success', 'Status pelamar berhasil diperbarui menjadi: ' . ucfirst($newStatus));
    }
}
