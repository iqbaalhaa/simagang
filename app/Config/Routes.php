<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::store');
$routes->get('/Auth', 'Auth');
$routes->post('/login', 'Auth::authenticate');
$routes->get('/logout', 'Auth::logout');

$routes->get('Admin', 'Admin::index');
$routes->get('Admin/PengajuanMahasiswa', 'Admin::PengajuanMahasiswa');
$routes->get('Admin/DataAdmin', 'Admin::DataAdmin');
$routes->get('Admin/DataDosen', 'Admin::DataDosen');
