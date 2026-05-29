<?php

namespace App\Controllers;

use App\Models\PesananModel;
use App\Models\RfmModel; // Memanggil model algoritma RFM

class Checkout extends BaseController
{
    public function cek_voucher()
    {
        $json = $this->request->getJSON();
        $kode = $json->kode ?? '';
        $cart = $json->cart ?? [];
        $idPelanggan = session()->get('id_pelanggan');

        if(empty($kode) || !$idPelanggan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kode voucher tidak valid.']);
        }

        $db = \Config\Database::connect();
        
        // 1. Cek di tabel voucher_global (Promo Admin)
        $voucherGlobal = $db->table('voucher_global')
            ->where('kode_voucher', $kode)
            ->where('status', 'aktif')
            ->get()->getRowArray();

        if ($voucherGlobal) {
            // Cek kuota jika ada batas
            if ($voucherGlobal['kuota'] > 0) {
                // Bisa tambahkan log pemakaian di tabel lain nanti, sementara asumsikan kuota adalah sisa
                $sisaKuota = $voucherGlobal['kuota'];
                if ($sisaKuota <= 0) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Kuota voucher promo sudah habis.']);
                }
            }

            // Cek target item jika ada
            $exactDiscount = null;
            if (!empty($voucherGlobal['target_id_menu'])) {
                $targetId = $voucherGlobal['target_id_menu'];
                $itemInCart = false;
                $targetSubtotal = 0;
                
                if (is_array($cart) || is_object($cart)) {
                    foreach($cart as $c) {
                        if (isset($c->id) && $c->id == $targetId) {
                            $itemInCart = true;
                            $targetSubtotal += $c->subtotal;
                        }
                    }
                }
                
                if (!$itemInCart) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Voucher ini hanya berlaku untuk menu spesifik yang tidak ada di keranjang Anda.']);
                }
                
                // Hitung diskon khusus item
                if ($voucherGlobal['tipe_diskon'] == 'persen') {
                    $exactDiscount = $targetSubtotal * ($voucherGlobal['diskon'] / 100);
                } else {
                    $exactDiscount = $voucherGlobal['diskon'];
                }
                // Batasi maksimal diskon
                if ($exactDiscount > $targetSubtotal) {
                    $exactDiscount = $targetSubtotal;
                }
            }

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Promo ' . $voucherGlobal['nama_voucher'] . ' berhasil diterapkan!',
                'diskon' => $voucherGlobal['diskon'],
                'tipe' => $voucherGlobal['tipe_diskon'],
                'target_id_menu' => $voucherGlobal['target_id_menu'],
                'exact_discount' => $exactDiscount,
                'id_voucher_global' => $voucherGlobal['id_voucher'] // Flag untuk membedakan
            ]);
        }

        // 2. Cek di tabel pelanggan_voucher (Tukar Poin Pribadi)
        $tujuhHariLalu = date('Y-m-d H:i:s', strtotime('-7 days'));
        $voucherPribadi = $db->table('pelanggan_voucher')
            ->where('kode_voucher', $kode)
            ->where('id_pelanggan', $idPelanggan)
            ->where('status', 'aktif')
            ->where('created_at >=', $tujuhHariLalu) // Pastikan belum hangus (<= 7 hari)
            ->get()->getRowArray();

        if($voucherPribadi) {
            $diskon = $voucherPribadi['nominal_diskon'];
            $tipe   = $voucherPribadi['tipe_diskon'];

            // Cek apakah voucher ini terikat ke menu tertentu (voucher produk gratis)
            $targetIdMenu  = $voucherPribadi['target_id_menu'] ?? null;
            $exactDiscount = null;

            if (!empty($targetIdMenu)) {
                // Cek apakah item target ada di keranjang
                $itemInCart     = false;
                $targetSubtotal = 0;

                if (is_array($cart) || is_object($cart)) {
                    foreach ($cart as $c) {
                        if (isset($c->id) && $c->id == $targetIdMenu) {
                            $itemInCart      = true;
                            $targetSubtotal += $c->subtotal;
                        }
                    }
                }

                if (!$itemInCart) {
                    // Ambil nama menu untuk pesan error yang jelas
                    $db2      = \Config\Database::connect();
                    $namaMenu = $db2->table('menu')->select('nama_item')->where('id_menu', $targetIdMenu)->get()->getRowArray();
                    $label    = $namaMenu ? "\"{$namaMenu['nama_item']}\"" : 'menu tertentu';
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => "Voucher ini hanya berlaku untuk item $label yang tidak ada di keranjang Anda."
                    ]);
                }

                // Diskon nominal = harga 1 item (gratis satu porsi)
                $exactDiscount = min($diskon, $targetSubtotal);
            }

            return $this->response->setJSON([
                'status'          => 'success',
                'message'         => 'Voucher ' . $voucherPribadi['nama_reward'] . ' berhasil diterapkan!',
                'diskon'          => $diskon,
                'tipe'            => $tipe,
                'target_id_menu'  => $targetIdMenu,
                'exact_discount'  => $exactDiscount,
                'id_pelanggan_voucher' => $voucherPribadi['id_voucher']
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Kode voucher tidak ditemukan atau sudah terpakai.']);
    }

    public function proses()
    {
        // 1. Pastikan request yang masuk benar-benar berupa AJAX / JSON
        if ($this->request->isAJAX() || $this->request->getHeaderLine('Content-Type') === 'application/json') {
            
            // Tangkap paket JSON dari frontend (dari Fetch API Keranjang)
            $json = $this->request->getJSON();

            // Validasi Data Sederhana
            if (empty($json->items) || empty($json->no_meja)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Data pesanan kosong atau nomor meja tidak valid.'
                ]);
            }

            // =========================================================
            // LOGIKA HYBRID CHECKOUT (GUEST VS MEMBER)
            // =========================================================
            $idPelanggan = session()->get('isLoggedIn') ? session()->get('id_pelanggan') : 1;

            // Siapkan Data Header untuk tabel `pesanan`
            $dataPesanan = [
                'id_pelanggan'   => $idPelanggan,
                'tgl_pesanan'    => date('Y-m-d H:i:s'),
                'total_bayar'    => $json->total_bayar,
                'metode_bayar'   => $json->metode_bayar,
                'status_pesanan' => 'belum_bayar', // <-- UPDATE ALUR KASIR: Masuk antrean pembayaran dulu
                'no_meja'        => $json->no_meja
            ];

            // Siapkan Data Detail untuk tabel `detail_pesanan`
            $dataDetail = [];
            foreach ($json->items as $item) {
                $dataDetail[] = [
                    'id_menu'  => $item->id,
                    'jumlah'   => $item->qty,
                    'subtotal' => $item->subtotal
                ];
            }

            // Panggil Model Pesanan untuk menyimpan ke Database
            $pesananModel = new PesananModel();
            
            // Server-side stok check sebelum transaksi
            $db = \Config\Database::connect();
            foreach ($json->items as $item) {
                $menuCek = $db->table('menu')->where('id_menu', $item->id)->get()->getRowArray();
                if(!$menuCek || $menuCek['stok'] < $item->qty) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'Gagal: Stok untuk ' . ($menuCek['nama_item'] ?? 'item') . ' tidak mencukupi.'
                    ]);
                }
            }

            $simpan = $pesananModel->simpanPesanan($dataPesanan, $dataDetail);

            // Jika transaksi Database sukses
            if ($simpan) {
                // Kurangi stok menu
                foreach ($json->items as $item) {
                    $db->query("UPDATE menu SET stok = stok - ? WHERE id_menu = ?", [$item->qty, $item->id]);
                }

                // Hapus session meja karena pesanan sudah tercatat
                session()->remove('no_meja');

                // Jika ada voucher terpakai (Voucher Pribadi), update statusnya di database
                if (!empty($json->id_voucher)) {
                    $db = \Config\Database::connect();
                    $db->table('pelanggan_voucher')
                        ->where('id_voucher', $json->id_voucher)
                        ->update(['status' => 'terpakai']);
                }

                // Jika ada voucher terpakai (Voucher Global), kurangi kuota
                if (!empty($json->id_voucher_global)) {
                    $db = \Config\Database::connect();
                    $db->query("UPDATE voucher_global SET kuota = kuota - 1 WHERE id_voucher = ? AND kuota > 0", [$json->id_voucher_global]);
                }

                // Berikan respon kembali ke halaman Keranjang
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Pesanan dicatat! Silakan bayar ke kasir.'
                ]);

            } else {
                // Jika transaksi Database gagal
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal menyimpan transaksi ke database server.'
                ]);
            }
        }

        return $this->response->setStatusCode(400)->setBody('Bad Request: Akses ditolak.');
    }
}