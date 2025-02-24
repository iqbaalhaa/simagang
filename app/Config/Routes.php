<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('Auth/register', 'Auth::register');
$routes->post('/register', 'Auth::store');
$routes->get('/Auth', 'Auth');
$routes->post('Auth/processLogin', 'Auth::processLogin');
$routes->get('/logout', 'Auth::logout');
$routes->post('auth/login', 'Auth::login');


$routes->get('Admin', 'Admin::index');
$routes->get('Admin/PengajuanMahasiswa', 'Admin::PengajuanMahasiswa');
$routes->get('Admin/DataAdmin', 'Admin::DataAdmin');
$routes->get('Admin/DataDosen', 'Admin::DataDosen');

$routes->get('/admin', 'Admin::index');
$routes->get('/dosen/dashboard', 'Dosen::dashboard');
$routes->get('/mahasiswa/dashboard', 'Mahasiswa::dashboard');
