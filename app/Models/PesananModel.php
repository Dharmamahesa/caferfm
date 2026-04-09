<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table      = 'pesanan';
    protected $primaryKey = 'id_pesanan';

    /**
     * Fungsi untuk menyimpan data transaksi secara keseluruhan (Header & Line Items).
     * Menggunakan Database Transaction untuk memastikan prinsip ACID (Atomicity).
     */
    public function simpanPesanan($dataPesanan, $dataDetail)
    {
        // 1. Inisialisasi koneksi database manual untuk mengontrol transaksi
        $db = \Config\Database::connect();
        
        // 2. Mulai Transaksi (Mencegah data tersimpan sebagian jika terjadi error di tengah jalan)
        $db->transStart();

        try {
            // 3. Insert ke tabel 'pesanan' (Header)
            $db->table('pesanan')->insert($dataPesanan);
            
            // 4. Tangkap ID pesanan yang baru saja terbuat (Auto Increment)
            $idPesanan = $db->insertID();

            // 5. Sisipkan ID pesanan tersebut ke dalam setiap baris array detail item
            foreach ($dataDetail as &$detail) {
                $detail['id_pesanan'] = $idPesanan;
            }

            // 6. Insert ke tabel 'detail_pesanan' sekaligus banyak (Batch Insert)
            // insertBatch jauh lebih cepat dan efisien pada server dibanding insert satu per satu di dalam loop
            $db->table('detail_pesanan')->insertBatch($dataDetail);

            // 7. Selesaikan Transaksi (Commit jika aman, Rollback jika ada yang gagal)
            $db->transComplete();

            // Kembalikan status transaksi (true/false)
            return $db->transStatus();

        } catch (\Exception $e) {
            // Jika terjadi error (misal database mati tiba-tiba), paksa Rollback
            $db->transRollback();
            return false;
        }
    }
}