<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// 1. Beranda
$routes->get('/', 'Home::index');

// 2. Lowongan Kerja
$routes->get('jobs', 'Jobs::index');
$routes->get('jobs/detail/(:num)', 'Jobs::detail/$1');
$routes->get('jobs/apply/(:num)', 'Jobs::apply/$1', ['filter' => 'auth']);
$routes->post('jobs/process-apply/(:num)', 'Jobs::processApply/$1', ['filter' => 'auth']);
$routes->post('jobs/toggle-save', 'Jobs::toggleSave', ['filter' => 'auth']);

// 3. Autentikasi
$routes->group('auth', function ($routes) {
    $routes->get('choice', 'Auth::registerChoice');
    $routes->get('login', 'Auth::login');
    $routes->post('process-login', 'Auth::processLogin');
    $routes->get('register', 'Auth::registerSeeker');
    $routes->post('process-register', 'Auth::processRegisterSeeker');
    $routes->get('register-company', 'Auth::registerCompany');
    $routes->post('process-register-company', 'Auth::processRegisterCompany');
    $routes->get('logout', 'Auth::logout');
});

// 4. Dashboard (Pencari Kerja & Perusahaan)
$routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('profile', 'Dashboard::profile');
    $routes->post('update-profile', 'Dashboard::updateProfile');
    $routes->get('history', 'Dashboard::history');
    $routes->get('saved', 'Dashboard::saved');
});

// 5. Perusahaan (Company Feature)
$routes->group('company', function ($routes) {
    $routes->get('/', 'Company::landing');
    $routes->get('post-job', 'Company::postJob', ['filter' => 'company']);
    $routes->post('save-job', 'Company::saveJob', ['filter' => 'company']);
    $routes->get('applicants', 'Company::applicants', ['filter' => 'company']);
    $routes->post('update-status', 'Company::updateApplicationStatus', ['filter' => 'company']);
});
