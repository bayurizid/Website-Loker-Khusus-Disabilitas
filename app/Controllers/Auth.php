<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CompanyModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $companyModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->companyModel = new CompanyModel();
    }

    /**
     * Halaman Pilihan Role Pendaftaran
     */
    public function registerChoice()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/choice', ['title' => 'Pilih Jenis Pendaftaran - JOB4DIS']);
    }

    /**
     * Halaman Login
     */
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        $redirectUrl = $this->request->getGet('redirect') ?? session()->getFlashdata('redirect_url') ?? base_url('dashboard');

        return view('auth/login', [
            'title'        => 'Masuk ke Akun - JOB4DIS',
            'redirect_url' => $redirectUrl,
        ]);
    }

    /**
     * Proses Login
     */
    public function processLogin()
    {
        $identifier  = trim($this->request->getPost('username_email') ?? '');
        $password    = $this->request->getPost('password') ?? '';
        $redirectUrl = $this->request->getPost('redirect_url') ?: base_url('dashboard');

        if (empty($identifier) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Username/Email dan Password wajib diisi.');
        }

        $user = $this->userModel->findByCredentials($identifier);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Kombinasi Username/Email atau Password salah.');
        }

        // Set session
        session()->set([
            'user_id'              => $user['id'],
            'username'             => $user['username'],
            'email'                => $user['email'],
            'nama_lengkap'         => $user['nama_lengkap'],
            'role'                 => $user['role'],
            'profile_picture_path' => $user['profile_picture_path'],
            'logged_in'            => true,
        ]);

        return redirect()->to($redirectUrl)->with('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');
    }

    /**
     * Form Pendaftaran Pencari Kerja
     */
    public function registerSeeker()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/register_seeker', ['title' => 'Daftar Sebagai Pencari Kerja - JOB4DIS']);
    }

    /**
     * Proses Pendaftaran Pencari Kerja
     */
    public function processRegisterSeeker()
    {
        $validationRules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'username'     => 'required|min_length[3]|max_length[30]|alpha_numeric_punct|is_unique[users.username]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'jenis_kelamin' => 'required|in_list[Laki-laki,Perempuan]',
            'disabilitas'  => 'required',
        ];

        $validationMessages = [
            'username' => [
                'is_unique' => 'Username ini sudah digunakan, silakan pilih yang lain.',
            ],
            'email' => [
                'is_unique' => 'Email ini sudah terdaftar. Silakan login jika sudah punya akun.',
            ],
            'confirm_password' => [
                'matches' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi.',
            ],
            'disabilitas' => [
                'required' => 'Pilih minimal satu jenis disabilitas.',
            ],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $disabilities = $this->request->getPost('disabilitas');
        $disabilityString = is_array($disabilities) ? implode(',', $disabilities) : $disabilities;

        $userId = $this->userModel->insert([
            'nama_lengkap'      => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
            'username'          => $this->request->getPost('username'),
            'email'             => $this->request->getPost('email'),
            'password_hash'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'jenis_disabilitas' => $disabilityString,
            'role'              => 'seeker',
            'status'            => 'aktif',
            'profile_picture_path' => 'images/placeholder_profile.png',
        ]);

        // Auto-login setelah registrasi
        session()->set([
            'user_id'              => $userId,
            'username'             => $this->request->getPost('username'),
            'email'                => $this->request->getPost('email'),
            'nama_lengkap'         => $this->request->getPost('nama_lengkap'),
            'role'                 => 'seeker',
            'profile_picture_path' => 'images/placeholder_profile.png',
            'logged_in'            => true,
        ]);

        return redirect()->to(base_url('dashboard'))
                         ->with('success', 'Pendaftaran berhasil! Selamat datang di JOB4DIS.');
    }

    /**
     * Form Pendaftaran Perusahaan
     */
    public function registerCompany()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/register_company', ['title' => 'Daftar Sebagai Perusahaan Mitra - JOB4DIS']);
    }

    /**
     * Proses Pendaftaran Perusahaan
     */
    public function processRegisterCompany()
    {
        $validationRules = [
            'company_name'     => 'required|min_length[3]|max_length[150]',
            'username'         => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        $validationMessages = [
            'username' => ['is_unique' => 'Username ini sudah digunakan.'],
            'email'    => ['is_unique' => 'Email ini sudah terdaftar.'],
            'confirm_password' => ['matches' => 'Konfirmasi kata sandi tidak cocok.'],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->userModel->insert([
            'nama_lengkap'         => $this->request->getPost('company_name'),
            'username'             => $this->request->getPost('username'),
            'email'                => $this->request->getPost('email'),
            'password_hash'        => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'                 => 'company',
            'status'               => 'aktif',
            'profile_picture_path' => 'images/placeholder_logo.png',
        ]);

        $this->companyModel->insert([
            'user_id'           => $userId,
            'company_name'      => $this->request->getPost('company_name'),
            'company_logo_path' => 'images/placeholder_logo.png',
        ]);

        session()->set([
            'user_id'              => $userId,
            'username'             => $this->request->getPost('username'),
            'email'                => $this->request->getPost('email'),
            'nama_lengkap'         => $this->request->getPost('company_name'),
            'role'                 => 'company',
            'profile_picture_path' => 'images/placeholder_logo.png',
            'logged_in'            => true,
        ]);

        return redirect()->to(base_url('company/post-job'))
                         ->with('success', 'Akun perusahaan berhasil dibuat! Anda dapat mulai memasang lowongan kerja.');
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url(''))
                         ->with('success', 'Anda telah berhasil keluar dari akun.');
    }
}
