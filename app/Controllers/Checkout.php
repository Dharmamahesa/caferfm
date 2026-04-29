<?php

namespace App\Controllers;

use App\Models\PesananModel;
use App\Models\RfmModel; // Memanggil model algoritma RFM

class Checkout extends BaseController
{
    public function cek_voucher()
    {
        $kode = $this->request->getJSON()->kode ?? '';
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

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Promo ' . $voucherGlobal['nama_voucher'] . ' berhasil diterapkan!',
                'diskon' => $voucherGlobal['diskon'],
                'tipe' => $voucherGlobal['tipe_diskon'],
                'id_voucher_global' => $voucherGlobal['id_voucher'] // Flag untuk membedakan
            ]);
        }

        // 2. Cek di tabel pelanggan_voucher (Tukar Poin Pribadi)
        $voucherPribadi = $db->table('pelanggan_voucher')
            ->where('kode_voucher', $kode)
            ->where('id_pelanggan', $idPelanggan)
            ->where('status', 'aktif')
            ->get()->getRowArray();

        if($voucherPribadi) {
            $diskon = 0;
            $tipe = 'nominal';
            
            if(stripos($voucherPribadi['nama_reward'], '50.000') !== false) {
                $diskon = 50000;
            } elseif(stripos($voucherPribadi['nama_reward'], '10%') !== false) {
                $diskon = 10;
                $tipe = 'persen';
            } else {
                $diskon = 15000;
            }

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Voucher ' . $voucherPribadi['nama_reward'] . ' berhasil diterapkan!',
                'diskon' => $diskon,
                'tipe' => $tipe,
                'id_voucher' => $voucherPribadi['id_voucher']
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
            $simpan = $pesananModel->simpanPesanan($dataPesanan, $dataDetail);

            // Jika transaksi Database sukses
            if ($simpan) {
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

                // =========================================================
                // TRIGGER ALGORITMA RFM & GAMIFIKASI
                // =========================================================
                $pesanTambahan = "";
                
                if ($idPelanggan != 1) {
                    $rfmModel = new RfmModel();
                    $tambahanPoin = $rfmModel->hitungDanUpdatePoin($idPelanggan);
                    
                    if ($tambahanPoin) {
                        $pesanTambahan = " Anda mendapatkan +$tambahanPoin Poin Loyalitas!";
                    }
                }

                // Berikan respon kembali ke halaman Keranjang
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Pesanan dicatat! Silakan bayar ke kasir.' . $pesanTambahan
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