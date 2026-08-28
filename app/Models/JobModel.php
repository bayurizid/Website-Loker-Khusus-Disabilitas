<?php

namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table            = 'jobs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'company_name',
        'company_logo_path',
        'location',
        'category',
        'job_type',
        'salary_range',
        'education_level',
        'experience_level',
        'suitable_disability_types',
        'job_description',
        'responsibilities',
        'qualifications',
        'work_schedule',
        'skills',
        'posted_by_user_id',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Map nama disabilitas ke nama file icon di public/images
     */
    public static function getDisabilityIcon($type)
    {
        $type = trim($type);
        $map = [
            'Daksa'              => 'Daksa.png',
            'Tuna Daksa'         => 'Daksa.png',
            'Rungu Wicara'       => 'Rungu Wicara.png',
            'Rungu'              => 'Rungu Wicara.png',
            'Tuna Rungu'         => 'Rungu Wicara.png',
            'Netra'              => 'Netra.png',
            'Tuna Netra'         => 'Netra.png',
            'Tuna Netra Parsial' => 'Netra.png',
            'Grahita'            => 'Grahita.png',
            'Tuna Grahita'       => 'Grahita.png',
            'Mental'             => 'Mental.png',
            'Tuna Mental'        => 'Mental.png',
        ];

        return $map[$type] ?? ($type . '.png');
    }

    /**
     * Format selisih waktu posting (misal: "2 jam lalu", "3 hari lalu", "Baru saja")
     * Dihitung akurat berdasarkan selisih waktu sekarang dengan created_at di DB.
     */
    public static function formatPostedAgo($datetimeStr)
    {
        if (empty($datetimeStr)) return 'Baru saja';
        
        try {
            $tzName = function_exists('app_timezone') ? app_timezone() : (date_default_timezone_get() ?: 'Asia/Jakarta');
            $tz     = new \DateTimeZone($tzName);
            $posted = new \DateTime($datetimeStr, $tz);
            $now    = new \DateTime('now', $tz);
        } catch (\Exception $e) {
            return 'Baru saja';
        }

        $diffSeconds = $now->getTimestamp() - $posted->getTimestamp();
        if ($diffSeconds < 0) {
            $diffSeconds = 0;
        }

        if ($diffSeconds < 60) {
            return 'Baru saja';
        } elseif ($diffSeconds < 3600) {
            $mins = floor($diffSeconds / 60);
            return $mins . ' menit lalu';
        } elseif ($diffSeconds < 86400) {
            $hours = floor($diffSeconds / 3600);
            return $hours . ' jam lalu';
        } elseif ($diffSeconds < 604800) {
            $days = floor($diffSeconds / 86400);
            return ($days == 1 ? '1 hari lalu' : $days . ' hari lalu');
        } elseif ($diffSeconds < 2592000) {
            $weeks = floor($diffSeconds / 604800);
            return ($weeks == 1 ? '1 minggu lalu' : $weeks . ' minggu lalu');
        } else {
            $months = floor($diffSeconds / 2592000);
            return ($months == 1 ? '1 bulan lalu' : $months . ' bulan lalu');
        }
    }

    /**
     * Ambil Lowongan Populer / Terbaru untuk Homepage
     */
    public function getPopularJobs($limit = 6)
    {
        $jobs = $this->where('is_active', 1)
                     ->orderBy('created_at', 'DESC')
                     ->limit($limit)
                     ->findAll();

        return array_map([$this, 'formatJobData'], $jobs);
    }

    /**
     * Filter & Pencarian Lowongan Kerja dengan Pagination
     */
    public function filterJobs($keyword = '', $location = '', $category = '', $disability = '')
    {
        $builder = $this->where('is_active', 1);

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('title', $keyword)
                    ->orLike('company_name', $keyword)
                    ->orLike('job_description', $keyword)
                    ->orLike('category', $keyword)
                    ->groupEnd();
        }

        if (!empty($location)) {
            $builder->like('location', $location);
        }

        if (!empty($category) && $category !== 'semua') {
            $builder->like('category', $category);
        }

        if (!empty($disability)) {
            $builder->like('suitable_disability_types', $disability);
        }

        return $builder->orderBy('created_at', 'DESC');
    }

    /**
     * Helper pemformat data job (tags, posted_ago, fallback logo)
     */
    public function formatJobData($job)
    {
        if (!$job) return null;

        $tags = [];
        if (!empty($job['job_type'])) $tags[] = $job['job_type'];
        if (!empty($job['education_level'])) $tags[] = $job['education_level'];
        if (!empty($job['experience_level'])) $tags[] = $job['experience_level'];
        $job['display_tags'] = $tags;

        $job['posted_ago'] = self::formatPostedAgo($job['created_at'] ?? null);

        // Logo fallback
        if (empty($job['company_logo_path']) || !file_exists(FCPATH . $job['company_logo_path'])) {
            $job['company_logo_path'] = 'images/placeholder_logo.png';
        }

        return $job;
    }
}
