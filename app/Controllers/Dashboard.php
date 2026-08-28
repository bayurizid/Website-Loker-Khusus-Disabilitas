<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\JobModel;
use App\Models\SavedJobModel;
use App\Models\JobApplicationModel;
use App\Models\CompanyModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $jobModel;
    protected $savedJobModel;
    protected $applicationModel;
    protected $companyModel;

    public function __construct()
    {
        $this->userModel        = new UserModel();
        $this->jobModel         = new JobModel();
        $this->savedJobModel    = new SavedJobModel();
        $this->applicationModel = new JobApplicationModel();
        $this->companyModel     = new CompanyModel();
    }

    /**
     * Dashboard Home Overview
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('auth/logout'));
        }

        $appliedCount = $this->applicationModel->where('user_id', $userId)->countAllResults();
        $savedCount   = $this->savedJobModel->where('user_id', $userId)->countAllResults();

        $recentApplications = $this->applicationModel->getApplicationsByUserId($userId);
        $recentApplications = array_slice($recentApplications, 0, 3);

        $data = [
            'title'               => 'Dashboard - JOB4DIS',
            'user'                => $user,
            'applied_count'       => $appliedCount,
            'saved_count'         => $savedCount,
            'recent_applications' => $recentApplications,
            'active_tab'          => 'overview',
        ];

        return view('dashboard/index', $data);
    }

    /**
     * Halaman Edit Profil
     */
    public function profile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $company = null;
        if ($user['role'] === 'company') {
            $company = $this->companyModel->getByUserId($userId);
        }

        $data = [
            'title'      => 'Edit Profil - JOB4DIS',
            'user'       => $user,
            'company'    => $company,
            'active_tab' => 'profile',
        ];

        return view('dashboard/profile', $data);
    }

    /**
     * Proses Update Profil
     */
    public function updateProfile()
    {
        $userId = session()->get('user_id');
        $user   = $this->userModel->find($userId);

        $updateData = [
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'phone_number'  => $this->request->getPost('phone_number'),
            'status'        => $this->request->getPost('status') ?: 'aktif',
            'instagram_url' => $this->request->getPost('instagram_url'),
            'twitter_url'   => $this->request->getPost('twitter_url'),
            'facebook_url'  => $this->request->getPost('facebook_url'),
        ];

        // Handle foto profil jika diunggah
        $photo = $this->request->getFile('profile_picture');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $newName = 'avatar_' . $userId . '_' . time() . '.' . $photo->getExtension();
            $photo->move(FCPATH . 'uploads/avatars', $newName);
            $updateData['profile_picture_path'] = 'uploads/avatars/' . $newName;

            // update session picture
            session()->set('profile_picture_path', $updateData['profile_picture_path']);
        }

        $this->userModel->update($userId, $updateData);
        session()->set('nama_lengkap', $updateData['nama_lengkap']);

        // Jika company, update juga data di tabel companies
        if ($user['role'] === 'company') {
            $this->companyModel->where('user_id', $userId)->set([
                'company_name'        => $updateData['nama_lengkap'],
                'company_description' => $this->request->getPost('company_description'),
                'company_industry'    => $this->request->getPost('company_industry'),
                'company_address'     => $this->request->getPost('company_address'),
            ])->update();
        }

        return redirect()->to(base_url('dashboard/profile'))
                         ->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Riwayat Lamaran Pekerjaan
     */
    public function history()
    {
        $userId = session()->get('user_id');
        $applications = $this->applicationModel->getApplicationsByUserId($userId);

        $data = [
            'title'        => 'Riwayat Lamaran - JOB4DIS',
            'applications' => $applications,
            'active_tab'   => 'history',
        ];

        return view('dashboard/history', $data);
    }

    /**
     * Daftar Lowongan yang Disimpan
     */
    public function saved()
    {
        $userId = session()->get('user_id');
        $savedJobs = $this->savedJobModel->getSavedJobsByUserId($userId);

        // Format data jobs
        $formattedSaved = [];
        foreach ($savedJobs as $job) {
            $formattedSaved[] = $this->jobModel->formatJobData($job);
        }

        $data = [
            'title'      => 'Lowongan Tersimpan - JOB4DIS',
            'saved_jobs' => $formattedSaved,
            'active_tab' => 'saved',
        ];

        return view('dashboard/saved', $data);
    }
}
