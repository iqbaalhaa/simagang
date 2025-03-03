<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('Auth/register', 'Auth::register');
$routes->post('/Auth/store', 'Auth::store');
$routes->get('/Auth', 'Auth');
$routes->post('Auth/processLogin', 'Auth::processLogin');
$routes->get('Auth/logout', 'Auth::logout');
$routes->post('auth/login', 'Auth::login');

//Admin Route
$routes->get('Admin', 'Admin::index');
$routes->get('Admin/PengajuanMahasiswa', 'Admin::PengajuanMahasiswa');
$routes->get('Admin/DataAdmin', 'Admin::DataAdmin');
$routes->get('Admin/DataDosen', 'Admin::DataDosen');
$routes->get('Admin/DataMahasiswa', 'Admin::DataMahasiswa');
$routes->get('Admin/Profil', 'Admin::Profil');
$routes->post('Admin/updateProfil', 'Admin::updateProfil');
$routes->get('Admin/Instansi', 'Admin::Instansi');
$routes->get('Admin/DataInstansi', 'Admin::Instansi');
$routes->get('Admin/editInstansi/(:num)', 'Admin::editInstansi/$1');
$routes->post('Admin/updateInstansi', 'Admin::updateInstansi');
$routes->get('Admin/deleteInstansi/(:num)', 'Admin::deleteInstansi/$1');
$routes->get('Admin/tambahInstansi', 'Admin::tambahInstansi');
$routes->post('Admin/simpanInstansi', 'Admin::simpanInstansi');
$routes->get('Admin/tambahAdmin', 'Admin::tambahAdmin');
$routes->post('Admin/simpanAdmin', 'Admin::simpanAdmin');
$routes->get('Admin/tambahDosen', 'Admin::tambahDosen');
$routes->post('Admin/simpanDosen', 'Admin::simpanDosen');
$routes->get('Admin/tambahMahasiswa', 'Admin::tambahMahasiswa');
$routes->post('Admin/simpanMahasiswa', 'Admin::simpanMahasiswa');

//Mahasiswa Route
$routes->get('Mahasiswa', 'Mahasiswa::index');
$routes->get('Mahasiswa/Profil', 'Mahasiswa::Profil');
$routes->post('Mahasiswa/updateProfil', 'Mahasiswa::updateProfil');
$routes->get('Mahasiswa/PengajuanMagang', 'Mahasiswa::PengajuanMagang');

$routes->get('/admin', 'Admin::index');
$routes->get('/dosen', 'Dosen::index');
$routes->get('/mahasiswa', 'Mahasiswa::index');
