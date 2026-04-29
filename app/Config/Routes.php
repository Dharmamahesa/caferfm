<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==============================================================================
// 1. RUTE PELANGGAN (FRONTEND / KATALOG)
// ==============================================================================
$routes->get('/', 'Katalog::index');
$routes->get('meja/(:num)', 'Katalog::index/$1');
$routes->get('katalog', 'Katalog::index');
$routes->get('keranjang', 'Katalog::keranjang');
$routes->post('checkout/proses', 'Checkout::proses');
$routes->post('checkout/cek_voucher', 'Checkout::cek_voucher');

// ==============================================================================
// 2. RUTE AUTENTIKASI PELANGGAN (MEMBER)
// ==============================================================================
$routes->get('auth/login', 'Auth::login');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/proses_login', 'Auth::proses_login');
$routes->post('auth/proses_register', 'Auth::proses_register');
$routes->get('auth/logout', 'Auth::logout');

// ==============================================================================
// 3. RUTE PROFIL & GAMIFIKASI (PROTECTED BY authGuard)
// ==============================================================================
$routes->get('profil', 'Pelanggan::profil', ['filter' => 'authGuard']);
$routes->get('pesanan_saya', 'Pelanggan::pesanan_saya', ['filter' => 'authGuard']);
$routes->get('misi_saya', 'Pelanggan::misi_saya', ['filter' => 'authGuard']);
$routes->post('klaim_misi/(:num)', 'Pelanggan::klaim_misi/$1', ['filter' => 'authGuard']);
$routes->get('tukar_poin', 'Pelanggan::tukar_poin', ['filter' => 'authGuard']);
$routes->post('tukar_poin/proses', 'Pelanggan::proses_tukar_poin', ['filter' => 'authGuard']);


// ==============================================================================
// 4. RUTE ADMIN (BACK-OFFICE)
// ==============================================================================

// --- A. Akses Publik Admin (Tanpa Login) ---
$routes->get('admin', 'AdminAuth::login');
$routes->get('admin/login', 'AdminAuth::login');
$routes->post('admin/proses_login', 'AdminAuth::proses_login');
$routes->get('admin/logout', 'AdminAuth::logout');
$routes->get('admin/setup', 'AdminAuth::setup'); // Jalankan 1x untuk buat user admin

// --- B. Akses Terkunci Admin (PROTECTED BY adminGuard) ---
$routes->group('admin', ['filter' => 'adminGuard'], static function ($routes) {
    
    // Dashboard Utama
    $routes->get('dashboard', 'Admin::index'); 
    
    // Operasional: Kitchen Display System (Dapur)
    $routes->get('dapur', 'Admin::dapur');
    $routes->get('selesai/(:num)', 'Admin::selesaikan_pesanan/$1');
    
    // Operasional: Manajemen Master Menu (CRUD)
    $routes->get('menu', 'MenuAdmin::index');
    $routes->get('menu/tambah', 'MenuAdmin::tambah');
    $routes->post('menu/simpan', 'MenuAdmin::simpan');
    $routes->get('menu/edit/(:num)', 'MenuAdmin::edit/$1');
    $routes->post('menu/update/(:num)', 'MenuAdmin::update/$1');
    $routes->get('menu/hapus/(:num)', 'MenuAdmin::hapus/$1');

    // Operasional: Riwayat Transaksi
    $routes->get('riwayat', 'Admin::riwayat');

    // Gamifikasi & CRM: Analitik Segmentasi RFM
    $routes->get('rfm', 'Admin::rfm');

    // Gamifikasi & CRM: Penukaran Poin Reward
    $routes->get('reward', 'RewardAdmin::index');
    $routes->post('reward/proses', 'RewardAdmin::proses_redeem');
    // Operasional: Kasir / Verifikasi Pembayaran
    $routes->get('kasir', 'Admin::kasir');
    $routes->get('kasir/verifikasi/(:num)', 'Admin::verifikasi_bayar/$1');
    // Fitur Cetak Laporan PDF
    $routes->get('laporan/cetak', 'Admin::cetak_laporan');
    
    // Fitur Tambahan
    $routes->get('pengaturan', 'Admin::pengaturan');
    $routes->post('pengaturan/update', 'Admin::update_pengaturan');
    $routes->get('qr_meja', 'Admin::qr_meja');

    // Manajemen Gamifikasi & Promosi
    $routes->get('misi', 'MisiAdmin::index');
    $routes->post('misi/simpan', 'MisiAdmin::simpan');
    $routes->post('misi/update/(:num)', 'MisiAdmin::update/$1');
    $routes->get('misi/hapus/(:num)', 'MisiAdmin::hapus/$1');

    $routes->get('katalog_reward', 'KatalogRewardAdmin::index');
    $routes->post('katalog_reward/simpan', 'KatalogRewardAdmin::simpan');
    $routes->post('katalog_reward/update/(:num)', 'KatalogRewardAdmin::update/$1');
    $routes->get('katalog_reward/hapus/(:num)', 'KatalogRewardAdmin::hapus/$1');

    $routes->get('voucher', 'VoucherAdmin::index');
    $routes->post('voucher/simpan', 'VoucherAdmin::simpan');
    $routes->post('voucher/update/(:num)', 'VoucherAdmin::update/$1');
    $routes->get('voucher/hapus/(:num)', 'VoucherAdmin::hapus/$1');
});