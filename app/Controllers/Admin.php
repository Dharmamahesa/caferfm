<?php

namespace App\Controllers;

use App\Models\PesananModel;

class Admin extends BaseController
{
    public function migrate()
    {
        $db = \Config\Database::connect();
        
        // Add poin_didapat to pesanan
        if (!$db->fieldExists('poin_didapat', 'pesanan')) {
            $db->query("ALTER TABLE pesanan ADD poin_didapat INT DEFAULT 0 AFTER no_meja");
        }

        // Add role to admin table
        if (!$db->fieldExists('role', 'admin')) {
            $db->query("ALTER TABLE admin ADD COLUMN role ENUM('super_admin', 'manajer', 'kasir', 'koki') NOT NULL DEFAULT 'kasir' AFTER nama_admin");
        }

        // Add email to admin table
        if (!$db->fieldExists('email', 'admin')) {
            $db->query("ALTER TABLE admin ADD COLUMN email VARCHAR(100) NULL AFTER role");
        }

        // Add is_active to admin table
        if (!$db->fieldExists('is_active', 'admin')) {
            $db->query("ALTER TABLE admin ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER email");
        }

        // Update existing admin account to be super_admin
        $db->query("UPDATE admin SET role = 'super_admin', is_active = 1 WHERE role = 'kasir' AND username = 'admin'");

        // Add target_id_menu to pelanggan_voucher (untuk voucher produk gratis dari spin)
        if (!$db->fieldExists('target_id_menu', 'pelanggan_voucher')) {
            $db->query("ALTER TABLE pelanggan_voucher ADD COLUMN target_id_menu INT NULL COMMENT 'ID menu yang digratiskan' AFTER nominal_diskon");
        }
        
        return "Migration complete — kolom role, email, is_active berhasil ditambahkan ke tabel admin. Kolom target_id_menu ditambahkan ke pelanggan_voucher.";
    }

    /**
     * DIAGNOSTIK: Cek status role di database & session (akses: /admin/cek_role)
     * Hapus rute ini setelah masalah teratasi.
     */
    public function cek_role()
    {
        $db = \Config\Database::connect();

        $output = "<pre style='font-family:monospace; padding:20px; font-size:14px;'>";
        $output .= "<b>== DIAGNOSTIK ROLE ADMIN ==</b>\n\n";

        // Cek kolom di DB
        $hasRole     = $db->fieldExists('role', 'admin');
        $hasEmail    = $db->fieldExists('email', 'admin');
        $hasIsActive = $db->fieldExists('is_active', 'admin');

        $output .= "Kolom 'role'      : " . ($hasRole ? "✅ ADA" : "❌ TIDAK ADA — jalankan /admin/migrate!") . "\n";
        $output .= "Kolom 'email'     : " . ($hasEmail ? "✅ ADA" : "❌ TIDAK ADA") . "\n";
        $output .= "Kolom 'is_active' : " . ($hasIsActive ? "✅ ADA" : "❌ TIDAK ADA") . "\n\n";

        if ($hasRole) {
            $admins = $db->table('admin')->select('id_admin, username, nama_admin, role, is_active')->get()->getResultArray();
            $output .= "<b>Data Tabel Admin:</b>\n";
            foreach ($admins as $a) {
                $output .= "  ID:{$a['id_admin']} | {$a['username']} | {$a['nama_admin']} | role={$a['role']} | aktif={$a['is_active']}\n";
            }
        }

        $output .= "\n<b>Session Saat Ini:</b>\n";
        $output .= "  id_admin        = " . (session()->get('id_admin') ?? 'NULL') . "\n";
        $output .= "  nama_admin      = " . (session()->get('nama_admin') ?? 'NULL') . "\n";
        $output .= "  admin_role      = " . (session()->get('admin_role') ?? 'NULL ⚠️ (belum ada — refresh halaman lain dulu)') . "\n";
        $output .= "  isAdminLoggedIn = " . (session()->get('isAdminLoggedIn') ? 'true' : 'false') . "\n";

        $output .= "\n<a href='" . base_url('admin/migrate') . "'>▶ Jalankan Migrate</a> | ";
        $output .= "<a href='" . base_url('admin/dashboard') . "'>▶ Ke Dashboard</a> | ";
        $output .= "<a href='" . base_url('admin/logout') . "'>▶ Logout & Login Ulang</a>";
        $output .= "</pre>";

        return $output;
    }

    // ==========================================================
    // 1. DASHBOARD UTAMA ADMIN (MARKAS BESAR)
    // ==========================================================
    public function index()
    {
        $pesananModel = new PesananModel();

        // Mengambil data ringkasan untuk hari ini dan analitik
        $data = [
            'title'             => 'Dashboard Admin - Toko Kopi Jaya Lestari',
            'omzet'             => $pesananModel->getOmzetHariIni(),
            'total_pesanan'     => $pesananModel->getTotalPesananHariIni(),
            'pesanan_baru'      => $pesananModel->getPesananTerbaru(5),
            'grafik_penjualan'  => $pesananModel->getPenjualanBulanan(),
            'menu_terlaris'     => $pesananModel->getMenuTerlaris(5)
        ];

        return view('admin/v_dashboard', $data);
    }

    // ==========================================================
    // 2. KITCHEN DISPLAY SYSTEM (DAPUR)
    // ==========================================================
    public function dapur()
    {
        $pesananModel = new PesananModel();

        // Mengambil semua pesanan yang statusnya masih 'pending'
        $data = [
            'title'   => 'Kitchen Display System - Toko Kopi Jaya Lestari',
            'pesanan' => $pesananModel->getPesananPending()
        ];

        return view('admin/v_dapur', $data);
    }

    // ==========================================================
    // 3. AKSI SELESAIKAN PESANAN (TOMBOL DI LAYAR DAPUR)
    // ==========================================================
    public function selesaikan_pesanan($idPesanan)
    {
        $pesananModel = new PesananModel();
        
        // Ubah status dari 'pending' menjadi 'selesai' di database
        $pesananModel->updateStatus($idPesanan, 'selesai');

        // ==== LOGIKA UPDATE PROGRESS MISI PELANGGAN ====
        $db = \Config\Database::connect();
        $pesanan = $db->table('pesanan')->where('id_pesanan', $idPesanan)->get()->getRowArray();
        
        if ($pesanan && $pesanan['id_pelanggan'] != 1) { // 1 = Guest
            $idPelanggan = $pesanan['id_pelanggan'];
            
            // Metrik pesanan saat ini
            $jmlTransaksi = 1;
            $nominalBelanja = $pesanan['total_bayar'];
            
            // Hitung jumlah minuman
            $detail = $db->table('detail_pesanan')
                ->join('menu', 'menu.id_menu = detail_pesanan.id_menu')
                ->where('id_pesanan', $idPesanan)
                ->where('menu.kategori', 'minuman')
                ->get()->getResultArray();
            $jmlMinuman = 0;
            foreach($detail as $d) { $jmlMinuman += $d['jumlah']; }

            // Ambil semua misi yang berjalan untuk pelanggan ini
            $misiBerjalan = $db->table('pelanggan_misi')
                ->join('misi', 'misi.id_misi = pelanggan_misi.id_misi')
                ->where('pelanggan_misi.id_pelanggan', $idPelanggan)
                ->where('pelanggan_misi.status', 'berjalan')
                ->get()->getResultArray();
            
            foreach ($misiBerjalan as $mb) {
                $progressTambah = 0;
                if ($mb['tipe_misi'] == 'transaksi') {
                    $progressTambah = $jmlTransaksi;
                } elseif ($mb['tipe_misi'] == 'nominal_belanja') {
                    $progressTambah = $nominalBelanja;
                } elseif ($mb['tipe_misi'] == 'item_minuman') {
                    // Jika ada target item menu khusus, hitung hanya item tersebut
                    if (!empty($mb['target_id_menu'])) {
                        $itemKhusus = $db->table('detail_pesanan')
                            ->where('id_pesanan', $idPesanan)
                            ->where('id_menu', $mb['target_id_menu'])
                            ->get()->getRowArray();
                        $progressTambah = $itemKhusus ? (int)$itemKhusus['jumlah'] : 0;
                    } else {
                        $progressTambah = $jmlMinuman;
                    }
                }
                
                if ($progressTambah > 0) {
                    $newProgress = $mb['progress'] + $progressTambah;
                    $status = 'berjalan';
                    if ($newProgress >= $mb['target_jumlah']) {
                        $newProgress = $mb['target_jumlah']; // limit to max
                        $status = 'selesai';
                    }
                    $db->table('pelanggan_misi')
                        ->where(['id_pelanggan' => $idPelanggan, 'id_misi' => $mb['id_misi']])
                        ->update(['progress' => $newProgress, 'status' => $status]);
                }
            }
        }
        // ===============================================

        // Kirim pesan sukses dan kembalikan koki ke halaman dapur
        session()->setFlashdata('sukses', 'Pesanan #' . $idPesanan . ' berhasil diselesaikan!');
        return redirect()->to(base_url('admin/dapur'));
    }
    // ==========================================================
    // 3.5 AKSI REFUND PESANAN (TOMBOL DI RIWAYAT)
    // ==========================================================
    public function refund_pesanan($idPesanan)
    {
        $pesananModel = new PesananModel();
        
        // 1. Ubah status pesanan menjadi 'refund'
        $pesananModel->updateStatus($idPesanan, 'refund');

        // 2. Kembalikan stok menu yang di-refund
        $db = \Config\Database::connect();
        $detailPesanan = $db->table('detail_pesanan')->where('id_pesanan', $idPesanan)->get()->getResultArray();
        
        foreach ($detailPesanan as $item) {
            $db->query("UPDATE menu SET stok = stok + ? WHERE id_menu = ?", [$item['jumlah'], $item['id_menu']]);
        }

        // 3. Tarik kembali poin jika pesanan ini memberikan poin
        $pesanan = $db->table('pesanan')->where('id_pesanan', $idPesanan)->get()->getRowArray();
        $pesanTambahan = "";
        if ($pesanan && $pesanan['id_pelanggan'] != 1 && $pesanan['poin_didapat'] > 0) {
            $poinTarik = $pesanan['poin_didapat'];
            $db->query("UPDATE pelanggan SET poin = poin - ? WHERE id_pelanggan = ?", [$poinTarik, $pesanan['id_pelanggan']]);
            $pesanTambahan = " dan $poinTarik poin ditarik dari pelanggan";
        }

        session()->setFlashdata('sukses', 'Pesanan #' . $idPesanan . ' berhasil di-refund, stok dikembalikan' . $pesanTambahan . '.');
        return redirect()->to(base_url('admin/riwayat'));
    }

    // ==========================================================
    // 3.8 BROADCAST PROMO RFM
    // ==========================================================
    public function broadcast_rfm()
    {
        $segment = $this->request->getPost('segment');
        $namaVoucher = $this->request->getPost('nama_voucher');
        $tipeDiskon = $this->request->getPost('tipe_diskon');
        $nominalDiskon = $this->request->getPost('nominal_diskon');
        $kodeVoucher = strtoupper(substr(md5(uniqid()), 0, 8)); // Generate random code

        // Dapatkan semua pelanggan
        $rfmModel = new \App\Models\RfmModel();
        $semuaPelanggan = $rfmModel->getAllCustomerRfm();
        
        $db = \Config\Database::connect();
        $count = 0;

        foreach($semuaPelanggan as $p) {
            if($p['segment'] == $segment) {
                // Beri voucher pribadi
                $db->table('pelanggan_voucher')->insert([
                    'id_pelanggan'   => $p['id_pelanggan'],
                    'nama_reward'    => $namaVoucher,
                    'kode_voucher'   => $kodeVoucher . $p['id_pelanggan'],
                    'tipe_diskon'    => $tipeDiskon,
                    'nominal_diskon' => $nominalDiskon,
                    'status'         => 'aktif'
                ]);
                $count++;
            }
        }

        session()->setFlashdata('sukses', "Berhasil mengirim $count voucher '$namaVoucher' ke segmen $segment!");
        return redirect()->to(base_url('admin/rfm'));
    }

    // ==========================================================
    // 4. RIWAYAT TRANSAKSI & PENDAPATAN
    // ==========================================================
    public function riwayat()
    {
        $pesananModel = new PesananModel();
        
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $data = [
            'title'      => 'Riwayat Transaksi - Toko Kopi Jaya Lestari',
            'riwayat'    => $pesananModel->getRiwayatSelesai($startDate, $endDate),
            'omzet'      => $pesananModel->getOmzetHariIni(), // Panggil ulang untuk info di atas tabel
            'start_date' => $startDate,
            'end_date'   => $endDate
        ];

        return view('admin/v_riwayat', $data);
    }

    // ==========================================================
    // 4.5 LAPORAN PENJUALAN (KASIR PINTAR STYLE)
    // ==========================================================
    public function laporan()
    {
        $pesananModel = new PesananModel();
        $mode = $this->request->getGet('mode') ?? 'harian';

        // Default values
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $bulan   = $this->request->getGet('bulan') ?? date('m');
        $tahun   = $this->request->getGet('tahun') ?? date('Y');

        // Fetch data based on mode
        if ($mode === 'bulanan') {
            $laporan = $pesananModel->getLaporanBulanan($bulan, $tahun);
            $dateStart = sprintf('%04d-%02d-01', $tahun, $bulan);
            $dateEnd   = date('Y-m-t', strtotime($dateStart));
        } elseif ($mode === 'tahunan') {
            $laporan = $pesananModel->getLaporanTahunan($tahun);
            $dateStart = "$tahun-01-01";
            $dateEnd   = "$tahun-12-31";
        } else {
            $mode = 'harian';
            $laporan = $pesananModel->getLaporanHarian($tanggal);
            $dateStart = $tanggal;
            $dateEnd   = $tanggal;
        }

        // Shared data for widgets
        $metodeBayar = $pesananModel->getMetodeBayarStats($dateStart, $dateEnd);
        $topMenu     = $pesananModel->getTopMenuByPeriod($dateStart, $dateEnd);

        $data = [
            'title'        => 'Laporan Penjualan - Toko Kopi Jaya Lestari',
            'mode'         => $mode,
            'tanggal'      => $tanggal,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'laporan'      => $laporan,
            'metodeBayar'  => $metodeBayar,
            'topMenu'      => $topMenu,
        ];

        return view('admin/v_laporan', $data);
    }
    public function rfm()
    {
        $rfmModel = new \App\Models\RfmModel();
        $dataRfm = $rfmModel->getAllCustomerRfm();

        $data = [
            'title' => 'Analitik Segmentasi RFM',
            'pelanggan' => $dataRfm
        ];

        return view('admin/v_rfm', $data);
    }
    // ==========================================================
    // 5. FITUR KASIR (VERIFIKASI PEMBAYARAN)
    // ==========================================================
    public function kasir()
    {
        $pesananModel = new \App\Models\PesananModel();

        $data = [
            'title'   => 'Verifikasi Kasir - Toko Kopi Jaya Lestari',
            'pesanan' => $pesananModel->getPesananBelumBayar()
        ];

        return view('admin/v_kasir', $data);
    }

    public function verifikasi_bayar($idPesanan)
    {
        $pesananModel = new \App\Models\PesananModel();
        
        // Ubah status dari 'belum_bayar' menjadi 'pending' (agar masuk ke layar KDS Dapur)
        $pesananModel->updateStatus($idPesanan, 'pending');

        // =========================================================
        // TRIGGER ALGORITMA RFM & GAMIFIKASI SETELAH BAYAR
        // =========================================================
        $db = \Config\Database::connect();
        $pesanan = $db->table('pesanan')->where('id_pesanan', $idPesanan)->get()->getRowArray();
        
        $pesanTambahan = "";
        if ($pesanan && $pesanan['id_pelanggan'] != 1) { // Bukan Guest
            $rfmModel = new \App\Models\RfmModel();
            $tambahanPoin = $rfmModel->hitungDanUpdatePoin($pesanan['id_pelanggan']);
            
            if ($tambahanPoin > 0) {
                // Simpan poin yang didapat ke transaksi ini
                $db->table('pesanan')
                   ->where('id_pesanan', $idPesanan)
                   ->update(['poin_didapat' => $tambahanPoin]);

                $pesanTambahan = " Pelanggan mendapat +$tambahanPoin Poin!";
            }
        }

        session()->setFlashdata('sukses', 'Pembayaran pesanan #' . $idPesanan . ' tervalidasi! Tiket diteruskan ke Dapur.' . $pesanTambahan);
        return redirect()->to(base_url('admin/kasir'));
    }
    // ==========================================================
    // 6. FITUR CETAK LAPORAN HARIAN (PDF)
    // ==========================================================
    public function cetak_laporan()
    {
        $pesananModel = new \App\Models\PesananModel();

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if($startDate && $endDate) {
            $riwayat = $pesananModel->getRiwayatSelesai($startDate, $endDate);
            $tanggal = date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate));
            
            // Hitung total khusus rentang ini
            $totalOmzet = 0;
            foreach($riwayat as $r) {
                if($r['status_pesanan'] != 'refund') {
                    $totalOmzet += $r['total_bayar'];
                }
            }
        } else {
            $riwayat = $pesananModel->getRiwayatHariIni();
            $tanggal = date('d F Y');
            $totalOmzet = $pesananModel->getOmzetHariIni();
        }

        $data = [
            'title'   => 'Laporan Pendapatan',
            'riwayat' => $riwayat,
            'omzet'   => $totalOmzet,
            'tanggal' => $tanggal
        ];

        return view('admin/v_cetak_laporan', $data);
    }

    // ==========================================================
    // 7. PENGATURAN KAFE
    // ==========================================================
    public function pengaturan()
    {
        $db = \Config\Database::connect();
        $settings = $db->table('pengaturan')->get()->getResultArray();
        
        $dataPengaturan = [];
        foreach($settings as $s) {
            $dataPengaturan[$s['key_setting']] = $s['value_setting'];
        }

        $data = [
            'title'      => 'Pengaturan Kafe',
            'pengaturan' => $dataPengaturan
        ];

        return view('admin/v_pengaturan', $data);
    }

    public function update_pengaturan()
    {
        $db = \Config\Database::connect();
        $postData = $this->request->getPost();
        
        foreach($postData as $key => $val) {
            $cek = $db->table('pengaturan')->where('key_setting', $key)->countAllResults();
            if($cek > 0) {
                $db->table('pengaturan')->where('key_setting', $key)->update(['value_setting' => $val]);
            } else {
                $db->table('pengaturan')->insert(['key_setting' => $key, 'value_setting' => $val]);
            }
        }

        session()->setFlashdata('sukses', 'Pengaturan berhasil diperbarui!');
        return redirect()->to(base_url('admin/pengaturan'));
    }

    // ==========================================================
    // 8. QR CODE MEJA (SMART ORDERING)
    // ==========================================================
    public function qr_meja()
    {
        $data = [
            'title' => 'Smart Ordering - QR Meja'
        ];
        return view('admin/v_qr_meja', $data);
    }

    // ==========================================================
    // 9. VISUAL MAP MEJA
    // ==========================================================
    public function map_meja()
    {
        $pesananModel = new \App\Models\PesananModel();
        
        // Ambil pesanan yang masih aktif (belum_bayar atau pending)
        $db = \Config\Database::connect();
        $mejaAktif = $db->table('pesanan')
                        ->select('no_meja')
                        ->whereIn('status_pesanan', ['belum_bayar', 'pending'])
                        ->groupBy('no_meja')
                        ->get()->getResultArray();
        
        $activeTables = array_column($mejaAktif, 'no_meja');

        $data = [
            'title' => 'Visual Map Meja Kafe',
            'activeTables' => $activeTables,
            'totalTables' => 20 // Misal ada 20 meja
        ];
        return view('admin/v_map_meja', $data);
    }
}