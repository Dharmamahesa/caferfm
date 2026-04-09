<?php

namespace App\Controllers;

use App\Models\PesananModel; // Kita akan membuat model ini di Langkah 4

class Checkout extends BaseController
{
    public function proses()
    {
        // 1. Pastikan request yang masuk benar-benar berupa AJAX / JSON
        // Ini adalah langkah keamanan untuk mencegah akses langsung via URL browser
        if ($this->request->isAJAX() || $this->request->getHeaderLine('Content-Type') === 'application/json') {
            
            // 2. Tangkap paket JSON dari frontend (dari Fetch API)
            $json = $this->request->getJSON();

            // 3. Validasi Data Sederhana
            if (empty($json->items) || empty($json->no_meja)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Data pesanan kosong atau nomor meja tidak valid.'
                ]);
            }

            // 4. Siapkan Data Header untuk tabel `pesanan`
            /* CATATAN SKRIPSI: 
               Karena kita belum membuat fitur Login Pelanggan, untuk sementara 
               kita isi 'id_pelanggan' dengan angka 1 (Asumsi ID 1 adalah Guest/Walk-in).
               Nanti di fase Gamification, ini kita ganti dengan ID session user yang login.
            */
            $dataPesanan = [
                'id_pelanggan'   => 1, 
                'tgl_pesanan'    => date('Y-m-d H:i:s'),
                'total_bayar'    => $json->total_bayar,
                'metode_bayar'   => $json->metode_bayar,
                'status_pesanan' => 'pending',
                'no_meja'        => $json->no_meja
            ];

            // 5. Siapkan Data Detail untuk tabel `detail_pesanan`
            $dataDetail = [];
            foreach ($json->items as $item) {
                // Sesuai dengan payload JSON dari frontend
                $dataDetail[] = [
                    'id_menu'  => $item->id,
                    'jumlah'   => $item->qty,
                    'subtotal' => $item->subtotal
                ];
            }

            // 6. Panggil Model untuk menyimpan ke Database (Database Transaction)
            $pesananModel = new PesananModel();
            $simpan = $pesananModel->simpanPesanan($dataPesanan, $dataDetail);

            // 7. Berikan respon kembali ke JavaScript Frontend
            if ($simpan) {
                // Hapus session meja karena proses order di meja ini sudah masuk antrean
                session()->remove('no_meja');

                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Pesanan berhasil masuk ke dapur.'
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal menyimpan transaksi ke database server.'
                ]);
            }
        }

        // Jika ada orang iseng mengakses localhost/checkout/proses langsung di browser
        return $this->response->setStatusCode(400)->setBody('Bad Request: Akses ditolak.');
    }
}