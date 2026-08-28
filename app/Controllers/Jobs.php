<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\SavedJobModel;
use App\Models\JobApplicationModel;
use App\Models\CompanyModel;
use App\Models\UserModel;

class Jobs extends BaseController
{
    protected $jobModel;
    protected $savedJobModel;
    protected $applicationModel;

    public function __construct()
    {
        $this->jobModel         = new JobModel();
        $this->savedJobModel    = new SavedJobModel();
        $this->applicationModel = new JobApplicationModel();
    }

    /**
     * Halaman Semua Lowongan Kerja dengan Filter & Pagination
     */
    public function index()
    {
        $keyword    = trim($this->request->getGet('keyword') ?? '');
        $location   = trim($this->request->getGet('lokasi') ?? '');
        $category   = trim($this->request->getGet('kategori') ?? '');
        $disability = trim($this->request->getGet('disabilitas') ?? '');

        $perPage = 6;
        $queryBuilder = $this->jobModel->filterJobs($keyword, $location, $category, $disability);

        $jobs = $queryBuilder->paginate($perPage, 'jobs');
        $pager = $this->jobModel->pager;

        // Format job items
        $formattedJobs = [];
        foreach ($jobs as $job) {
            $formattedJobs[] = $this->jobModel->formatJobData($job);
        }

        // Saved jobs ID for current user
        $savedJobIds = [];
        if (session()->get('logged_in')) {
            $savedJobIds = $this->savedJobModel->getUserSavedJobIds(session()->get('user_id'));
        }

        $data = [
            'title'         => 'Semua Lowongan Kerja - JOB4DIS',
            'jobs'          => $formattedJobs,
            'pager'         => $pager,
            'total_results' => $pager->getTotal('jobs'),
            'keyword'       => $keyword,
            'location'      => $location,
            'category'      => $category,
            'disability'    => $disability,
            'saved_job_ids' => $savedJobIds,
        ];

        return view('jobs/index', $data);
    }

    /**
     * Halaman Detail Lowongan Kerja
     */
    public function detail($id = null)
    {
        if (!$id) {
            return redirect()->to(base_url('jobs'));
        }

        $job = $this->jobModel->find($id);
        if (!$job || !$job['is_active']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lowongan kerja tidak ditemukan atau sudah tidak aktif.');
        }

        $job = $this->jobModel->formatJobData($job);

        // Ambil data detail profil perusahaan dari company table jika ada
        $companyModel = new CompanyModel();
        $companyDetail = null;
        if (!empty($job['posted_by_user_id'])) {
            $companyDetail = $companyModel->getByUserId($job['posted_by_user_id']);
        }

        $isSaved = false;
        $hasApplied = false;
        if (session()->get('logged_in')) {
            $userId = session()->get('user_id');
            $isSaved = $this->savedJobModel->isSaved($userId, $id);
            $hasApplied = $this->applicationModel->hasApplied($userId, $id);
        }

        $data = [
            'title'          => $job['title'] . ' di ' . $job['company_name'] . ' - JOB4DIS',
            'job'            => $job,
            'company_detail' => $companyDetail,
            'is_saved'       => $isSaved,
            'has_applied'    => $hasApplied,
        ];

        return view('jobs/detail', $data);
    }

    /**
     * Halaman Lamar Lowongan
     */
    public function apply($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth/login'))
                             ->with('error', 'Silakan login terlebih dahulu untuk melamar lowongan ini.')
                             ->with('redirect_url', base_url("jobs/detail/{$id}"));
        }

        $job = $this->jobModel->find($id);
        if (!$job || !$job['is_active']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lowongan kerja tidak ditemukan.');
        }

        $userId = session()->get('user_id');
        if ($this->applicationModel->hasApplied($userId, $id)) {
            return redirect()->to(base_url("jobs/detail/{$id}"))
                             ->with('info', 'Anda sudah pernah mengajukan lamaran untuk lowongan ini.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $data = [
            'title' => 'Lamar Pekerjaan: ' . $job['title'] . ' - JOB4DIS',
            'job'   => $this->jobModel->formatJobData($job),
            'user'  => $user,
        ];

        return view('jobs/apply', $data);
    }

    /**
     * Proses Submit Lamaran Kerja (Upload Resume & Cover Letter)
     */
    public function processApply($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth/login'));
        }

        $userId = session()->get('user_id');
        $job = $this->jobModel->find($id);

        if (!$job || !$job['is_active']) {
            return redirect()->to(base_url('jobs'))->with('error', 'Lowongan tidak ditemukan.');
        }

        if ($this->applicationModel->hasApplied($userId, $id)) {
            return redirect()->to(base_url("jobs/detail/{$id}"))->with('info', 'Anda sudah pernah melamar pekerjaan ini.');
        }

        $validationRules = [
            'resume' => [
                'rules'  => 'uploaded[resume]|ext_in[resume,pdf,doc,docx]|max_size[resume,5120]',
                'errors' => [
                    'uploaded' => 'File CV / Resume wajib diunggah.',
                    'ext_in'   => 'Format CV harus berupa PDF, DOC, atau DOCX.',
                    'max_size' => 'Ukuran CV maksimal adalah 5MB.',
                ],
            ],
            'cover_letter' => [
                'rules'  => 'permit_empty|ext_in[cover_letter,pdf,doc,docx]|max_size[cover_letter,5120]',
                'errors' => [
                    'ext_in'   => 'Format Surat Lamaran harus berupa PDF, DOC, atau DOCX.',
                    'max_size' => 'Ukuran Surat Lamaran maksimal adalah 5MB.',
                ],
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $resumePath = null;
        $coverLetterPath = null;

        $resumeFile = $this->request->getFile('resume');
        if ($resumeFile && $resumeFile->isValid() && !$resumeFile->hasMoved()) {
            $newName = 'resume_' . $userId . '_' . time() . '.' . $resumeFile->getExtension();
            $resumeFile->move(FCPATH . 'uploads/resumes', $newName);
            $resumePath = 'uploads/resumes/' . $newName;
        }

        $coverLetterFile = $this->request->getFile('cover_letter');
        if ($coverLetterFile && $coverLetterFile->isValid() && !$coverLetterFile->hasMoved()) {
            $newCoverName = 'cover_' . $userId . '_' . time() . '.' . $coverLetterFile->getExtension();
            $coverLetterFile->move(FCPATH . 'uploads/cover_letters', $newCoverName);
            $coverLetterPath = 'uploads/cover_letters/' . $newCoverName;
        }

        $this->applicationModel->insert([
            'job_id'            => $id,
            'user_id'           => $userId,
            'status'            => 'pending',
            'resume_path'       => $resumePath,
            'cover_letter_path' => $coverLetterPath,
            'notes'             => $this->request->getPost('notes'),
        ]);

        return redirect()->to(base_url("dashboard/history"))
                         ->with('success', 'Lamaran Anda untuk posisi ' . $job['title'] . ' berhasil dikirim!');
    }

    /**
     * Toggle Save / Bookmark Lowongan (Endpoint AJAX)
     */
    public function toggleSave()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Silakan login terlebih dahulu untuk menyimpan lowongan.',
            ])->setStatusCode(401);
        }

        $userId = session()->get('user_id');
        $jobId  = (int) $this->request->getPost('job_id');

        if (!$jobId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Lowongan tidak valid.']);
        }

        $isSaved = $this->savedJobModel->isSaved($userId, $jobId);

        if ($isSaved) {
            $this->savedJobModel->where('user_id', $userId)->where('job_id', $jobId)->delete();
            return $this->response->setJSON([
                'status'  => 'success',
                'action'  => 'removed',
                'message' => 'Lowongan dihapus dari daftar simpan.',
            ]);
        } else {
            $this->savedJobModel->insert([
                'user_id' => $userId,
                'job_id'  => $jobId,
            ]);
            return $this->response->setJSON([
                'status'  => 'success',
                'action'  => 'saved',
                'message' => 'Lowongan berhasil disimpan ke favorit Anda.',
            ]);
        }
    }
}
