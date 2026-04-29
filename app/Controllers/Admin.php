<?php

namespace App\Controllers;

use App\Models\PesananModel;

class Admin extends BaseController
{
    // ==========================================================
    // 1. DASHBOARD UTAMA ADMIN (MARKAS BESAR)
    // ==========================================================
    public function index()
    {
        $pesananModel = new PesananModel();

        // Mengambil data ringkasan untuk hari ini
        $data = [
            'title'         => 'Dashboard Admin - Kafe Gamified',
            'omzet'         => $pesananModel->getOmzetHariIni(),
            'total_pesanan' => $pesananModel->getTotalPesananHariIni(),
            'pesanan_baru'  => $pesananModel->getPesananTerbaru(5) // Ambil 5 transaksi terakhir untuk tabel
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
            'title'   => 'Kitchen Display System - Kafe Gamified',
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

        // Kirim pesan sukses dan kembalikan koki ke halaman dapur
        session()->setFlashdata('sukses', 'Pesanan #' . $idPesanan . ' berhasil diselesaikan!');
        return redirect()->to(base_url('admin/dapur'));
    }
    // ==========================================================
    // 4. RIWAYAT TRANSAKSI & PENDAPATAN
    // ==========================================================
    public function riwayat()
    {
        $pesananModel = new PesananModel();

        $data = [
            'title'   => 'Riwayat Transaksi - Kafe Gamified',
            'riwayat' => $pesananModel->getRiwayatSelesai(),
            'omzet'   => $pesananModel->getOmzetHariIni() // Panggil ulang untuk info di atas tabel
        ];

        return view('admin/v_riwayat', $data);
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
            'title'   => 'Verifikasi Kasir - Kafe Gamified',
            'pesanan' => $pesananModel->getPesananBelumBayar()
        ];

        return view('admin/v_kasir', $data);
    }

    public function verifikasi_bayar($idPesanan)
    {
        $pesananModel = new \App\Models\PesananModel();
        
        // Ubah status dari 'belum_bayar' menjadi 'pending' (agar masuk ke layar KDS Dapur)
        $pesananModel->updateStatus($idPesanan, 'pending');

        session()->setFlashdata('sukses', 'Pembayaran pesanan #' . $idPesanan . ' tervalidasi! Tiket otomatis diteruskan ke Dapur.');
        return redirect()->to(base_url('admin/kasir'));
    }
    // ==========================================================
    // 6. FITUR CETAK LAPORAN HARIAN (PDF)
    // ==========================================================
    public function cetak_laporan()
    {
        $pesananModel = new \App\Models\PesananModel();

        $data = [
            'title'   => 'Laporan Pendapatan Harian',
            'riwayat' => $pesananModel->getRiwayatHariIni(),
            'omzet'   => $pesananModel->getOmzetHariIni(),
            'tanggal' => date('d F Y') // Format tanggal hari ini
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
}