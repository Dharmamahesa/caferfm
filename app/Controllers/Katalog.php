<?php

namespace App\Controllers;

use App\Models\MenuModel; // Memanggil model menu yang sudah kita buat

class Katalog extends BaseController
{
    public function index($no_meja = null)
    {
        // 1. Inisialisasi Model
        $menuModel = new MenuModel();

        // 2. Logika Penyimpanan Nomor Meja (Penting untuk Self-Ordering)
        // Cek apakah ada nomor meja dari parameter URL (Hasil scan QR)
        if ($no_meja !== null) {
            // Jika ada, simpan ke session agar tidak hilang saat refresh/pindah halaman
            session()->set('no_meja', $no_meja);
        } else {
            // Jika tidak ada di URL, ambil dari session. 
            // Jika session kosong juga, beri nilai default.
            $no_meja = session()->get('no_meja') ?? 'Bawa Pulang / Belum Pilih Meja';
        }

        // 3. Menarik Data Menu dari Database berdasarkan Kategori
        $data = [
            'title'   => 'Katalog Menu - Kafe Gamified',
            'no_meja' => $no_meja,
            // Kita gunakan method getMenuByKategori() yang kita buat di Model tadi
            'makanan' => $menuModel->getMenuByKategori('makanan'),
            'minuman' => $menuModel->getMenuByKategori('minuman'),
            'snack'   => $menuModel->getMenuByKategori('snack')
        ];

        // 4. Melempar data ke bagian View (Antarmuka Pengguna)
        return view('pelanggan/v_katalog', $data);
    }
    public function keranjang()
{
    return view('pelanggan/v_keranjang');
}
}