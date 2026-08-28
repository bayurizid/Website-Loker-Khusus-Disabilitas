<?php

namespace App\Models;

use CodeIgniter\Model;

class SavedJobModel extends Model
{
    protected $table            = 'saved_jobs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'job_id',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function isSaved($userId, $jobId)
    {
        return $this->where('user_id', $userId)
                    ->where('job_id', $jobId)
                    ->countAllResults() > 0;
    }

    public function getUserSavedJobIds($userId)
    {
        $rows = $this->select('job_id')
                     ->where('user_id', $userId)
                     ->findAll();
        return array_column($rows, 'job_id');
    }

    public function getSavedJobsByUserId($userId)
    {
        return $this->select('jobs.*, saved_jobs.created_at as saved_at')
                    ->join('jobs', 'jobs.id = saved_jobs.job_id')
                    ->where('saved_jobs.user_id', $userId)
                    ->where('jobs.is_active', 1)
                    ->orderBy('saved_jobs.created_at', 'DESC')
                    ->findAll();
    }
}
