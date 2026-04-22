<?php

namespace App\Controllers;

use App\Models\PesananModel;
use App\Models\RfmModel; // Memanggil model algoritma RFM

class Checkout extends BaseController
{
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