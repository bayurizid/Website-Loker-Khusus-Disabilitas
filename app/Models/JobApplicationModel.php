<?php

namespace App\Models;

use CodeIgniter\Model;

class JobApplicationModel extends Model
{
    protected $table            = 'job_applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_id',
        'user_id',
        'status',
        'resume_path',
        'cover_letter_path',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function hasApplied($userId, $jobId)
    {
        return $this->where('user_id', $userId)
                    ->where('job_id', $jobId)
                    ->countAllResults() > 0;
    }

    public function getApplicationsByUserId($userId)
    {
        return $this->select('job_applications.*, jobs.title as job_title, jobs.company_name, jobs.location, jobs.company_logo_path, jobs.category')
                    ->join('jobs', 'jobs.id = job_applications.job_id')
                    ->where('job_applications.user_id', $userId)
                    ->orderBy('job_applications.created_at', 'DESC')
                    ->findAll();
    }

    public function getApplicantsByJobId($jobId)
    {
        return $this->select('job_applications.*, users.nama_lengkap, users.email, users.phone_number, users.jenis_disabilitas, users.profile_picture_path')
                    ->join('users', 'users.id = job_applications.user_id')
                    ->where('job_applications.job_id', $jobId)
                    ->orderBy('job_applications.created_at', 'DESC')
                    ->findAll();
    }
}
