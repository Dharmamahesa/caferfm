<?php

namespace App\Models;

use CodeIgniter\Model;

class RfmModel extends Model
{
    /**
     * Fungsi utama untuk menghitung RFM dan menambahkan Poin Loyalitas
     */
    public function hitungDanUpdatePoin($idPelanggan)
    {
        $db = \Config\Database::connect();

        // 1. Dapatkan Nilai Mentah (Raw) Recency, Frequency, Monetary dari database
        $query = $db->query("
            SELECT 
                DATEDIFF(CURDATE(), MAX(tgl_pesanan)) as recency_hari,
                COUNT(id_pesanan) as frequency_total,
                SUM(total_bayar) as monetary_total
            FROM pesanan 
            WHERE id_pelanggan = ? AND status_pesanan != 'dibatalkan'
        ", [$idPelanggan]);

        $dataRFM = $query->getRow();

        if (!$dataRFM) return false;

        // 2. Berikan Penilaian (Scoring) 1 sampai 5
        // (Catatan: Angka patokan ini bisa disesuaikan dengan aturan kafemu nanti)
        $skorR = $this->skorRecency($dataRFM->recency_hari);
        $skorF = $this->skorFrequency($dataRFM->frequency_total);
        $skorM = $this->skorMonetary($dataRFM->monetary_total);

        // 3. Rumus Poin Gamifikasi (Contoh sederhana: R + F + M dikali 10)
        // Jika pelanggan dapat skor maksimal (5+5+5) = 15 x 10 = 150 Poin
        $poinDidapat = ($skorR + $skorF + $skorM) * 10;

        // 4. Update Poin ke Tabel Pelanggan
        $db->query("
            UPDATE pelanggan 
            SET poin_loyalitas = poin_loyalitas + ? 
            WHERE id_pelanggan = ?
        ", [$poinDidapat, $idPelanggan]);

        return $poinDidapat;
    }

    // --- LOGIKA PENENTUAN SKOR (RULE BASED) ---

    private function skorRecency($hari)
    {
        // Semakin kecil hari (baru saja beli), skor makin besar
        if ($hari <= 7) return 5;
        if ($hari <= 14) return 4;
        if ($hari <= 30) return 3;
        if ($hari <= 60) return 2;
        return 1;
    }

    private function skorFrequency($totalTransaksi)
    {
        // Semakin sering beli, skor makin besar
        if ($totalTransaksi >= 10) return 5;
        if ($totalTransaksi >= 7) return 4;
        if ($totalTransaksi >= 4) return 3;
        if ($totalTransaksi >= 2) return 2;
        return 1;
    }

    private function skorMonetary($totalBelanja)
    {
        // Semakin besar total belanja, skor makin besar
        if ($totalBelanja >= 500000) return 5;
        if ($totalBelanja >= 300000) return 4;
        if ($totalBelanja >= 150000) return 3;
        if ($totalBelanja >= 50000) return 2;
        return 1;
    }
    public function getAllCustomerRfm()
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil data mentah RFM untuk SEMUA pelanggan (kecuali Guest)
        $query = $db->query("
            SELECT 
                p.id_pelanggan, 
                p.nama_pelanggan,
                DATEDIFF(CURDATE(), MAX(ps.tgl_pesanan)) as recency_raw,
                COUNT(ps.id_pesanan) as frequency_raw,
                SUM(ps.total_bayar) as monetary_raw
            FROM pelanggan p
            JOIN pesanan ps ON p.id_pelanggan = ps.id_pelanggan
            WHERE p.id_pelanggan != 1 AND ps.status_pesanan = 'selesai'
            GROUP BY p.id_pelanggan
        ");

        $results = $query->getResultArray();
        $finalData = [];

        foreach ($results as $row) {
            // 2. Konversi nilai mentah ke skor 1-5 menggunakan fungsi yang sudah ada
            $sR = $this->skorRecency($row['recency_raw']);
            $sF = $this->skorFrequency($row['frequency_raw']);
            $sM = $this->skorMonetary($row['monetary_raw']);
            
            $totalSkor = $sR + $sF + $sM;

            // 3. Logika Penentuan Segmen (Ini Inti Teori RFM)
            $segment = $this->tentukanSegmen($sR, $sF, $sM);

            $finalData[] = array_merge($row, [
                'skor_r' => $sR,
                'skor_f' => $sF,
                'skor_m' => $sM,
                'total_skor' => $totalSkor,
                'segment' => $segment
            ]);
        }

        return $finalData;
    }

    private function tentukanSegmen($R, $F, $M)
    {
        $avg = ($R + $F + $M) / 3;

        if ($R >= 4 && $avg >= 4.5) return 'Champions';
        if ($avg >= 4) return 'Loyal Customers';
        if ($R <= 2 && $avg >= 3) return 'At Risk';
        if ($R <= 2 && $avg <= 2) return 'Lost Customers';
        if ($R >= 4 && $avg <= 3) return 'New Customers';
        return 'About to Sleep';
    }
}