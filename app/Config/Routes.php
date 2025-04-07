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
$routes->get('Admin/deleteDosen/(:num)', 'Admin::deleteDosen/$1');
$routes->get('Admin/tambahMahasiswa', 'Admin::tambahMahasiswa');
$routes->post('Admin/simpanMahasiswa', 'Admin::simpanMahasiswa');
$routes->get('Admin/deleteMahasiswa/(:num)', 'Admin::deleteMahasiswa/$1');
$routes->get('Admin/getDetailPengajuan/(:num)', 'Admin::getDetailPengajuan/$1');
$routes->post('Admin/updateStatusPengajuan', 'Admin::updateStatusPengajuan');
$routes->post('Admin/deletePengajuan', 'Admin::deletePengajuan');
$routes->get('Admin/Dokumen', 'Admin::Dokumen');
$routes->post('Admin/tambahDokumen', 'Admin::tambahDokumen');
$routes->get('Admin/hapusDokumen/(:num)', 'Admin::hapusDokumen/$1');
$routes->get('Admin/Absensi', 'Admin::Absensi');
$routes->get('Admin/getAbsensiKelompok/(:num)', 'Admin::getAbsensiKelompok/$1');
$routes->get('Admin/Logbook', 'Admin::Logbook');
$routes->post('Admin/updateParafLogbook', 'Admin::updateParafLogbook');
$routes->get('Admin/LoA', 'Admin::LoA');
$routes->post('Admin/updateStatusLoA', 'Admin::updateStatusLoA');
$routes->post('Admin/updateCatatanLoA/(:num)', 'Admin::updateCatatanLoA/$1');
$routes->get('Admin/DataAngkatan', 'Admin::DataAngkatan');
$routes->get('Admin/cetakPDFAngkatan', 'Admin::cetakPDFAngkatan');


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
$routes->get('Mahasiswa/Logbook', 'Mahasiswa::Logbook');
$routes->post('Mahasiswa/tambahLogbook', 'Mahasiswa::tambahLogbook');
$routes->post('Mahasiswa/editLogbook/(:num)', 'Mahasiswa::editLogbook/$1');
$routes->get('Mahasiswa/hapusLogbook/(:num)', 'Mahasiswa::hapusLogbook/$1');
$routes->get('Mahasiswa/LoA', 'Mahasiswa::LoA');
$routes->post('Mahasiswa/tambahLoA', 'Mahasiswa::tambahLoA');
$routes->post('Mahasiswa/uploadSuratBalasan', 'Mahasiswa::uploadSuratBalasan');
$routes->get('Mahasiswa/Laporan', 'Mahasiswa::Laporan');
$routes->post('Mahasiswa/tambahLaporan', 'Mahasiswa::tambahLaporan');


// Dosen Route
$routes->group('Dosen', ['filter' => 'auth'], function($routes) {
    // Route yang sudah ada
    $routes->get('/', 'Dosen::index');
    $routes->get('Profil', 'Dosen::Profil');
    $routes->post('updateProfil', 'Dosen::updateProfil');
    $routes->get('KelompokBimbingan', 'Dosen::KelompokBimbingan');
    $routes->get('getDetailKelompok/(:num)', 'Dosen::getDetailKelompok/$1');
    
    // Route baru untuk Logbook
    $routes->get('Logbook', 'Dosen::Logbook');
    $routes->post('updateParafLogbook', 'Dosen::updateParafLogbook');
    
    // Route untuk Absensi
    $routes->get('Absensi', 'Dosen::Absensi');
    $routes->get('getAbsensiKelompok/(:num)', 'Dosen::getAbsensiKelompok/$1');
    
    // Route untuk LoA
    $routes->get('LoA', 'Dosen::LoA');

    // Route untuk Laporan
    $routes->get('Laporan', 'Dosen::Laporan');
});

$routes->get('Dosen/getLogbookKelompok/(:num)', 'Dosen::getLogbookKelompok/$1');

$routes->get('Dosen/Penilaian', 'Dosen::Penilaian');

$routes->get('/admin', 'Admin::index');
$routes->get('/dosen', 'Dosen::index');
$routes->get('/mahasiswa', 'Mahasiswa::index');

$routes->post('Dosen/simpanNilai', 'Dosen::simpanNilai');

