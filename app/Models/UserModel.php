<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_lengkap',
        'jenis_kelamin',
        'username',
        'email',
        'password_hash',
        'jenis_disabilitas',
        'role',
        'phone_number',
        'status',
        'profile_picture_path',
        'instagram_url',
        'twitter_url',
        'facebook_url',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari user berdasarkan username atau email untuk login
     */
    public function findByCredentials(string $identifier)
    {
        return $this->where('username', $identifier)
                    ->orWhere('email', $identifier)
                    ->first();
    }
}
