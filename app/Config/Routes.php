<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');
// ==============================================================================
// RUTE HALAMAN PELANGGAN (FRONTEND)
// ==============================================================================

// 1. Rute Default (Halaman Utama)
// Ketika pelanggan mengakses localhost/caferfm/public/, arahkan ke Controller Katalog
$routes->get('/', 'Katalog::index');
$routes->get('keranjang', 'Katalog::keranjang');
$routes->post('checkout/proses', 'Checkout::proses'); // Untuk rute proses nanti
// 2. Rute Scan QR Code Meja
// Ketika pelanggan scan QR, URL-nya misal: localhost/caferfm/public/meja/05
// (:num) akan menangkap angka 05 dan mengirimkannya ke method index di Controller Katalog
$routes->get('meja/(:num)', 'Katalog::index/$1');
$routes->get('katalog', 'Katalog::index');

// ==============================================================================
// RUTE HALAMAN ADMIN (BACKEND) - Akan kita kerjakan di fase berikutnya
// ==============================================================================
$routes->group('admin', static function ($routes) {
    // Nanti rute admin seperti login, kelola menu, dll masuk di sini
    // $routes->get('dashboard', 'Admin::dashboard');
});