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
$routes->get('lucky_spin', 'Pelanggan::lucky_spin', ['filter' => 'authGuard']);
$routes->post('lucky_spin/beli', 'Pelanggan::beli_spin', ['filter' => 'authGuard']);
$routes->post('lucky_spin/proses', 'Pelanggan::proses_spin', ['filter' => 'authGuard']);


// ==============================================================================
// 4. RUTE ADMIN (BACK-OFFICE)
// ==============================================================================

// --- A. Akses Publik Admin (Tanpa Login) ---
$routes->get('admin/migrate', 'Admin::migrate'); // TEMPORARY
$routes->get('admin/cek_role', 'Admin::cek_role'); // DIAGNOSTIK — hapus setelah selesai
$routes->get('admin', 'AdminAuth::login');
$routes->get('admin/login', 'AdminAuth::login');
$routes->post('admin/proses_login', 'AdminAuth::proses_login');
$routes->get('admin/logout', 'AdminAuth::logout');
$routes->get('admin/setup', 'AdminAuth::setup'); // Jalankan 1x untuk buat user admin

// --- B. Akses Terkunci Admin (PROTECTED BY adminGuard) ---
$routes->group('admin', ['filter' => 'adminGuard'], static function ($routes) {
    
    // Dashboard Utama (Super Admin & Manajer)
    $routes->get('dashboard', 'Admin::index', ['filter' => 'roleGuard:super_admin,manajer']);
    
    // Operasional: Kitchen Display System (Dapur) — akses semua role
    $routes->get('dapur', 'Admin::dapur');
    $routes->get('selesai/(:num)', 'Admin::selesaikan_pesanan/$1');
    
    // Operasional: Kasir (Super Admin & Kasir)
    $routes->get('kasir', 'Admin::kasir', ['filter' => 'roleGuard:super_admin,kasir']);
    $routes->get('kasir/verifikasi/(:num)', 'Admin::verifikasi_bayar/$1', ['filter' => 'roleGuard:super_admin,kasir']);

    // Operasional: Manajemen Master Menu (Super Admin & Manajer)
    $routes->get('menu', 'MenuAdmin::index', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('menu/tambah', 'MenuAdmin::tambah', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('menu/simpan', 'MenuAdmin::simpan', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('menu/edit/(:num)', 'MenuAdmin::edit/$1', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('menu/update/(:num)', 'MenuAdmin::update/$1', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('menu/hapus/(:num)', 'MenuAdmin::hapus/$1', ['filter' => 'roleGuard:super_admin,manajer']);

    // Operasional: Riwayat Transaksi (Super Admin, Manajer, Kasir)
    $routes->get('riwayat', 'Admin::riwayat', ['filter' => 'roleGuard:super_admin,manajer,kasir']);
    $routes->get('riwayat/refund/(:num)', 'Admin::refund_pesanan/$1', ['filter' => 'roleGuard:super_admin,manajer']);

    // Operasional: Laporan Penjualan (Super Admin & Manajer)
    $routes->get('laporan', 'Admin::laporan', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('laporan/cetak', 'Admin::cetak_laporan', ['filter' => 'roleGuard:super_admin,manajer']);

    // Gamifikasi & CRM: Analitik Segmentasi RFM (Super Admin & Manajer)
    $routes->get('rfm', 'Admin::rfm', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('rfm/broadcast', 'Admin::broadcast_rfm', ['filter' => 'roleGuard:super_admin,manajer']);

    // Gamifikasi & CRM: Penukaran Poin Reward (Super Admin & Manajer)
    $routes->get('reward', 'RewardAdmin::index', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('reward/proses', 'RewardAdmin::proses_redeem', ['filter' => 'roleGuard:super_admin,manajer']);

    // Manajemen Gamifikasi & Promosi (Super Admin & Manajer)
    $routes->get('misi', 'MisiAdmin::index', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('misi/simpan', 'MisiAdmin::simpan', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('misi/update/(:num)', 'MisiAdmin::update/$1', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('misi/hapus/(:num)', 'MisiAdmin::hapus/$1', ['filter' => 'roleGuard:super_admin,manajer']);

    $routes->get('katalog_reward', 'KatalogRewardAdmin::index', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('katalog_reward/simpan', 'KatalogRewardAdmin::simpan', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('katalog_reward/update/(:num)', 'KatalogRewardAdmin::update/$1', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('katalog_reward/hapus/(:num)', 'KatalogRewardAdmin::hapus/$1', ['filter' => 'roleGuard:super_admin,manajer']);

    $routes->get('voucher', 'VoucherAdmin::index', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('voucher/simpan', 'VoucherAdmin::simpan', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('voucher/update/(:num)', 'VoucherAdmin::update/$1', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('voucher/hapus/(:num)', 'VoucherAdmin::hapus/$1', ['filter' => 'roleGuard:super_admin,manajer']);

    // Fitur Tambahan (Super Admin & Manajer)
    $routes->get('pengaturan', 'Admin::pengaturan', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('pengaturan/update', 'Admin::update_pengaturan', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('qr_meja', 'Admin::qr_meja', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('map_meja', 'Admin::map_meja', ['filter' => 'roleGuard:super_admin,manajer']);

    // ============================================================
    // Kelola Akun Admin — KHUSUS SUPER ADMIN
    // ============================================================
    $routes->get('users', 'UserAdminController::index', ['filter' => 'roleGuard:super_admin']);
    $routes->get('users/tambah', 'UserAdminController::tambah', ['filter' => 'roleGuard:super_admin']);
    $routes->post('users/simpan', 'UserAdminController::simpan', ['filter' => 'roleGuard:super_admin']);
    $routes->get('users/edit/(:num)', 'UserAdminController::edit/$1', ['filter' => 'roleGuard:super_admin']);
    $routes->post('users/update/(:num)', 'UserAdminController::update/$1', ['filter' => 'roleGuard:super_admin']);
    $routes->get('users/toggle/(:num)', 'UserAdminController::toggle_aktif/$1', ['filter' => 'roleGuard:super_admin']);
    $routes->get('users/hapus/(:num)', 'UserAdminController::hapus/$1', ['filter' => 'roleGuard:super_admin']);

    // ============================================================
    // Pengaturan Lucky Spin — Super Admin & Manajer
    // ============================================================
    $routes->get('spin', 'SpinAdminController::index', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('spin/simpan', 'SpinAdminController::simpan', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->post('spin/update/(:num)', 'SpinAdminController::update/$1', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('spin/toggle/(:num)', 'SpinAdminController::toggle/$1', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('spin/hapus/(:num)', 'SpinAdminController::hapus/$1', ['filter' => 'roleGuard:super_admin,manajer']);
    $routes->get('spin/reset', 'SpinAdminController::reset_default', ['filter' => 'roleGuard:super_admin']);
});