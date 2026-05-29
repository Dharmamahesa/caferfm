<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table      = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $allowedFields = ['id_pelanggan', 'tgl_pesanan', 'total_bayar', 'metode_bayar', 'status_pesanan', 'no_meja'];

    /**
     * ==========================================================
     * 1. FITUR PELANGGAN (CHECKOUT)
     * ==========================================================
     */
    public function simpanPesanan($dataPesanan, $dataDetail)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insert Header
            $db->table('pesanan')->insert($dataPesanan);
            $idPesanan = $db->insertID();

            // Sisipkan ID ke Detail
            foreach ($dataDetail as &$detail) {
                $detail['id_pesanan'] = $idPesanan;
            }

            // Insert Batch Detail
            $db->table('detail_pesanan')->insertBatch($dataDetail);

            $db->transComplete();
            return $db->transStatus();

        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }

    /**
     * ==========================================================
     * 2. FITUR KASIR (ANTREAN PEMBAYARAN)
     * ==========================================================
     */
    public function getPesananBelumBayar()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('pesanan');
        $builder->select('pesanan.*, pelanggan.nama_pelanggan');
        $builder->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan', 'left');
        $builder->where('pesanan.status_pesanan', 'belum_bayar');
        $builder->orderBy('pesanan.tgl_pesanan', 'ASC');
        $pesanan = $builder->get()->getResultArray();

        // Ambil detail item
        foreach ($pesanan as &$p) {
            $p['detail'] = $db->table('detail_pesanan')
                              ->select('detail_pesanan.*, menu.nama_item')
                              ->join('menu', 'menu.id_menu = detail_pesanan.id_menu', 'left')
                              ->where('id_pesanan', $p['id_pesanan'])
                              ->get()->getResultArray();
        }

        return $pesanan;
    }

    /**
     * ==========================================================
     * 3. FITUR DAPUR / KDS (ANTREAN MASAK)
     * ==========================================================
     */
    public function getPesananPending()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('pesanan');
        $builder->select('pesanan.*, pelanggan.nama_pelanggan');
        $builder->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan', 'left');
        $builder->where('pesanan.status_pesanan', 'pending');
        $builder->orderBy('pesanan.tgl_pesanan', 'ASC'); 
        $pesanan = $builder->get()->getResultArray();

        foreach ($pesanan as &$p) {
            $p['detail'] = $db->table('detail_pesanan')
                              ->select('detail_pesanan.*, menu.nama_item')
                              ->join('menu', 'menu.id_menu = detail_pesanan.id_menu', 'left')
                              ->where('id_pesanan', $p['id_pesanan'])
                              ->get()->getResultArray();
        }

        return $pesanan;
    }

    /**
     * ==========================================================
     * 4. FUNGSI GLOBAL UPDATE STATUS
     * ==========================================================
     */
    public function updateStatus($idPesanan, $statusBaru)
    {
        return $this->db->table('pesanan')
                        ->where('id_pesanan', $idPesanan)
                        ->update(['status_pesanan' => $statusBaru]);
    }

    /**
     * ==========================================================
     * 5. FITUR DASHBOARD & RIWAYAT ADMIN
     * ==========================================================
     */
    public function getOmzetHariIni()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT SUM(total_bayar) as omzet FROM pesanan WHERE DATE(tgl_pesanan) = CURDATE() AND status_pesanan = 'selesai'");
        $hasil = $query->getRow();
        return $hasil->omzet ?? 0;
    }

    public function getTotalPesananHariIni()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT COUNT(id_pesanan) as total FROM pesanan WHERE DATE(tgl_pesanan) = CURDATE()");
        $hasil = $query->getRow();
        return $hasil->total ?? 0;
    }

    public function getPesananTerbaru($limit = 5)
    {
        $db = \Config\Database::connect();
        return $db->table('pesanan')
                  ->select('pesanan.*, pelanggan.nama_pelanggan')
                  ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan', 'left')
                  ->orderBy('pesanan.tgl_pesanan', 'DESC')
                  ->limit($limit)
                  ->get()->getResultArray();
    }

    public function getRiwayatSelesai($startDate = null, $endDate = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pesanan')
                  ->select('pesanan.*, pelanggan.nama_pelanggan')
                  ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan', 'left')
                  ->whereIn('pesanan.status_pesanan', ['selesai', 'refund']);
                  
        if ($startDate && $endDate) {
            $builder->where('DATE(pesanan.tgl_pesanan) >=', $startDate)
                    ->where('DATE(pesanan.tgl_pesanan) <=', $endDate);
        }
                  
        return $builder->orderBy('pesanan.tgl_pesanan', 'DESC')
                  ->get()->getResultArray();
    }
    public function getRiwayatHariIni($startDate = null, $endDate = null)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('pesanan')
                  ->select('pesanan.*, pelanggan.nama_pelanggan')
                  ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan', 'left')
                  ->whereIn('pesanan.status_pesanan', ['selesai', 'refund']);
                  
        if ($startDate && $endDate) {
            $builder->where('DATE(pesanan.tgl_pesanan) >=', $startDate)
                    ->where('DATE(pesanan.tgl_pesanan) <=', $endDate);
        } else {
            $builder->where('DATE(pesanan.tgl_pesanan)', date('Y-m-d'));
        }
                  
        return $builder->orderBy('pesanan.tgl_pesanan', 'ASC')
                  ->get()->getResultArray();
    }

    public function getPenjualan7HariTerakhir()
    {
        $db = \Config\Database::connect();
        // MySQL specific query to group by date
        $query = $db->query("
            SELECT DATE(tgl_pesanan) as tanggal, SUM(total_bayar) as total 
            FROM pesanan 
            WHERE status_pesanan = 'selesai' 
            AND tgl_pesanan >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(tgl_pesanan)
            ORDER BY DATE(tgl_pesanan) ASC
        ");
        return $query->getResultArray();
    }

    public function getMenuTerlaris($limit = 5)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT m.nama_item, m.foto, m.kategori, SUM(dp.jumlah) as total_terjual
            FROM detail_pesanan dp
            JOIN pesanan p ON p.id_pesanan = dp.id_pesanan
            JOIN menu m ON m.id_menu = dp.id_menu
            WHERE p.status_pesanan = 'selesai'
            GROUP BY m.id_menu
            ORDER BY total_terjual DESC
            LIMIT ?
        ", [$limit]);
        return $query->getResultArray();
    }

    // ==========================================================
    // 6. LAPORAN PENJUALAN (KASIR PINTAR STYLE)
    // ==========================================================

    /**
     * Penjualan 12 bulan terakhir (untuk dashboard chart bulanan)
     */
    public function getPenjualanBulanan()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT DATE_FORMAT(tgl_pesanan, '%Y-%m') as bulan, 
                   SUM(total_bayar) as total,
                   COUNT(id_pesanan) as jumlah_transaksi
            FROM pesanan 
            WHERE status_pesanan = 'selesai' 
            AND tgl_pesanan >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
            GROUP BY DATE_FORMAT(tgl_pesanan, '%Y-%m')
            ORDER BY bulan ASC
        ");
        return $query->getResultArray();
    }

    /**
     * Laporan Harian: breakdown per jam pada tanggal tertentu
     */
    public function getLaporanHarian($tanggal)
    {
        $db = \Config\Database::connect();
        
        // Ringkasan
        $summary = $db->query("
            SELECT 
                COALESCE(SUM(CASE WHEN status_pesanan = 'selesai' THEN total_bayar ELSE 0 END), 0) as total_pendapatan,
                COALESCE(SUM(CASE WHEN status_pesanan = 'selesai' THEN 1 ELSE 0 END), 0) as jumlah_transaksi,
                COALESCE(SUM(CASE WHEN status_pesanan = 'refund' THEN 1 ELSE 0 END), 0) as jumlah_refund
            FROM pesanan 
            WHERE DATE(tgl_pesanan) = ?
        ", [$tanggal])->getRowArray();
        
        // Data per jam
        $chart = $db->query("
            SELECT HOUR(tgl_pesanan) as jam, 
                   SUM(total_bayar) as total,
                   COUNT(id_pesanan) as jumlah
            FROM pesanan 
            WHERE DATE(tgl_pesanan) = ? AND status_pesanan = 'selesai'
            GROUP BY HOUR(tgl_pesanan)
            ORDER BY jam ASC
        ", [$tanggal])->getResultArray();
        
        // Detail transaksi
        $detail = $db->table('pesanan')
                     ->select('pesanan.*, pelanggan.nama_pelanggan')
                     ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan', 'left')
                     ->where('DATE(pesanan.tgl_pesanan)', $tanggal)
                     ->whereIn('pesanan.status_pesanan', ['selesai', 'refund'])
                     ->orderBy('pesanan.tgl_pesanan', 'ASC')
                     ->get()->getResultArray();
        
        return ['summary' => $summary, 'chart' => $chart, 'detail' => $detail];
    }

    /**
     * Laporan Bulanan: breakdown per hari di bulan tertentu
     */
    public function getLaporanBulanan($bulan, $tahun)
    {
        $db = \Config\Database::connect();
        $period = sprintf('%04d-%02d', $tahun, $bulan);
        
        // Ringkasan
        $summary = $db->query("
            SELECT 
                COALESCE(SUM(CASE WHEN status_pesanan = 'selesai' THEN total_bayar ELSE 0 END), 0) as total_pendapatan,
                COALESCE(SUM(CASE WHEN status_pesanan = 'selesai' THEN 1 ELSE 0 END), 0) as jumlah_transaksi,
                COALESCE(SUM(CASE WHEN status_pesanan = 'refund' THEN 1 ELSE 0 END), 0) as jumlah_refund
            FROM pesanan 
            WHERE DATE_FORMAT(tgl_pesanan, '%Y-%m') = ?
        ", [$period])->getRowArray();
        
        // Data per hari
        $chart = $db->query("
            SELECT DAY(tgl_pesanan) as hari, 
                   SUM(total_bayar) as total,
                   COUNT(id_pesanan) as jumlah
            FROM pesanan 
            WHERE DATE_FORMAT(tgl_pesanan, '%Y-%m') = ? AND status_pesanan = 'selesai'
            GROUP BY DAY(tgl_pesanan)
            ORDER BY hari ASC
        ", [$period])->getResultArray();
        
        // Detail per hari (ringkasan)
        $detail = $db->query("
            SELECT DATE(tgl_pesanan) as tanggal,
                   SUM(CASE WHEN status_pesanan = 'selesai' THEN total_bayar ELSE 0 END) as pendapatan,
                   SUM(CASE WHEN status_pesanan = 'selesai' THEN 1 ELSE 0 END) as transaksi,
                   SUM(CASE WHEN status_pesanan = 'refund' THEN 1 ELSE 0 END) as refund
            FROM pesanan
            WHERE DATE_FORMAT(tgl_pesanan, '%Y-%m') = ?
            GROUP BY DATE(tgl_pesanan)
            ORDER BY tanggal ASC
        ", [$period])->getResultArray();
        
        return ['summary' => $summary, 'chart' => $chart, 'detail' => $detail];
    }

    /**
     * Laporan Tahunan: breakdown per bulan di tahun tertentu
     */
    public function getLaporanTahunan($tahun)
    {
        $db = \Config\Database::connect();
        
        // Ringkasan
        $summary = $db->query("
            SELECT 
                COALESCE(SUM(CASE WHEN status_pesanan = 'selesai' THEN total_bayar ELSE 0 END), 0) as total_pendapatan,
                COALESCE(SUM(CASE WHEN status_pesanan = 'selesai' THEN 1 ELSE 0 END), 0) as jumlah_transaksi,
                COALESCE(SUM(CASE WHEN status_pesanan = 'refund' THEN 1 ELSE 0 END), 0) as jumlah_refund
            FROM pesanan 
            WHERE YEAR(tgl_pesanan) = ?
        ", [$tahun])->getRowArray();
        
        // Data per bulan
        $chart = $db->query("
            SELECT MONTH(tgl_pesanan) as bulan, 
                   SUM(total_bayar) as total,
                   COUNT(id_pesanan) as jumlah
            FROM pesanan 
            WHERE YEAR(tgl_pesanan) = ? AND status_pesanan = 'selesai'
            GROUP BY MONTH(tgl_pesanan)
            ORDER BY bulan ASC
        ", [$tahun])->getResultArray();
        
        // Detail per bulan
        $detail = $db->query("
            SELECT MONTH(tgl_pesanan) as bulan,
                   SUM(CASE WHEN status_pesanan = 'selesai' THEN total_bayar ELSE 0 END) as pendapatan,
                   SUM(CASE WHEN status_pesanan = 'selesai' THEN 1 ELSE 0 END) as transaksi,
                   SUM(CASE WHEN status_pesanan = 'refund' THEN 1 ELSE 0 END) as refund
            FROM pesanan
            WHERE YEAR(tgl_pesanan) = ?
            GROUP BY MONTH(tgl_pesanan)
            ORDER BY bulan ASC
        ", [$tahun])->getResultArray();
        
        return ['summary' => $summary, 'chart' => $chart, 'detail' => $detail];
    }

    /**
     * Statistik metode pembayaran per periode
     */
    public function getMetodeBayarStats($tanggalAwal, $tanggalAkhir)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT metode_bayar, COUNT(*) as jumlah, SUM(total_bayar) as total
            FROM pesanan
            WHERE status_pesanan = 'selesai'
            AND DATE(tgl_pesanan) BETWEEN ? AND ?
            GROUP BY metode_bayar
            ORDER BY total DESC
        ", [$tanggalAwal, $tanggalAkhir]);
        return $query->getResultArray();
    }

    /**
     * Menu terlaris per periode
     */
    public function getTopMenuByPeriod($tanggalAwal, $tanggalAkhir, $limit = 5)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT m.nama_item, m.kategori, SUM(dp.jumlah) as total_terjual,
                   SUM(dp.subtotal) as total_pendapatan
            FROM detail_pesanan dp
            JOIN pesanan p ON p.id_pesanan = dp.id_pesanan
            JOIN menu m ON m.id_menu = dp.id_menu
            WHERE p.status_pesanan = 'selesai'
            AND DATE(p.tgl_pesanan) BETWEEN ? AND ?
            GROUP BY m.id_menu
            ORDER BY total_terjual DESC
            LIMIT ?
        ", [$tanggalAwal, $tanggalAkhir, $limit]);
        return $query->getResultArray();
    }
}