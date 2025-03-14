<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);




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
$routes->get('Admin/getDetailPengajuan/(:num)', 'Admin::getDetailPengajuan/$1');
$routes->post('Admin/updateStatusPengajuan', 'Admin::updateStatusPengajuan');
$routes->post('Admin/deletePengajuan', 'Admin::deletePengajuan');
$routes->get('Admin/Dokumen', 'Admin::Dokumen');
$routes->post('Admin/tambahDokumen', 'Admin::tambahDokumen');
$routes->get('Admin/hapusDokumen/(:num)', 'Admin::hapusDokumen/$1');
$routes->get('Admin/Absensi', 'Admin::Absensi');
$routes->get('Admin/getAbsensiKelompok/(:num)', 'Admin::getAbsensiKelompok/$1');




//Mahasiswa Route
$routes->get('Mahasiswa', 'Mahasiswa::index');
$routes->get('Mahasiswa/Profil', 'Mahasiswa::Profil');
$routes->post('Mahasiswa/updateProfil', 'Mahasiswa::updateProfil');
$routes->get('Mahasiswa/PengajuanMagang', 'Mahasiswa::PengajuanMagang');
$routes->post('Mahasiswa/tambahPengajuanMagang', 'Mahasiswa::tambahPengajuanMagang');
$routes->post('Mahasiswa/hapusPengajuanMagang', 'Mahasiswa::hapusPengajuanMagang');
$routes->get('Mahasiswa/DownloadDokumen', 'Mahasiswa::DownloadDokumen');
$routes->get('Mahasiswa/downloadFile/(:num)', 'Mahasiswa::downloadFile/$1');
$routes->get('Mahasiswa/Absensi', 'Mahasiswa::Absensi');
$routes->get('Mahasiswa/absenPulang/(:num)', 'Mahasiswa::absenPulang/$1');
$routes->post('Mahasiswa/tambahAbsensi', 'Mahasiswa::tambahAbsensi');

// Dosen Route
$routes->get('Dosen', 'Dosen::index');
$routes->get('Dosen/Profil', 'Dosen::Profil');
$routes->post('Dosen/updateProfil', 'Dosen::updateProfil');
$routes->get('Dosen/bimbingan', 'Dosen::bimbingan');
$routes->post('Dosen/TambahBimbingan', 'Dosen::TambahBimbingan');
$routes->get('Dosen/getBimbingan/(:num)', 'Dosen::getBimbingan/$1');
$routes->post('Dosen/updateBimbingan', 'Dosen::updateBimbingan');
$routes->get('/Dosen/deleteBimbingan/(:num)', 'Dosen::deleteBimbingan/$1');


$routes->get('/admin', 'Admin::index');
$routes->get('/dosen', 'Dosen::index');
$routes->get('/mahasiswa', 'Mahasiswa::index');

