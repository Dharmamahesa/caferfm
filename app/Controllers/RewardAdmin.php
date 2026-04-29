<?php

namespace App\Controllers;

use App\Models\PelangganModel;

class RewardAdmin extends BaseController
{
    public function index()
    {
        $pelangganModel = new PelangganModel();
        
        // Tangkap kata kunci pencarian dari kolom search (jika ada)
        $keyword = $this->request->getGet('q');
        $db = \Config\Database::connect();
        $katalog = $db->table('katalog_reward')->get()->getResultArray();
        
        $data = [
            'title'     => 'Tukar Poin Reward - Kafe Gamified',
            'pelanggan' => $pelangganModel->cariPelanggan($keyword),
            'keyword'   => $keyword,
            'katalog'   => $katalog
        ];

        return view('admin/v_reward', $data);
    }

    public function proses_redeem()
    {
        $idPelanggan = $this->request->getPost('id_pelanggan');
        $poinDipotong = $this->request->getPost('poin_dibutuhkan');
        $namaReward = $this->request->getPost('nama_reward');

        $pelangganModel = new PelangganModel();
        $user = $pelangganModel->find($idPelanggan);

        // Validasi keamanan: Pastikan poin pelanggan cukup
        if ($user['poin_loyalitas'] >= $poinDipotong) {
            
            // Kurangi poin menggunakan Query Native CI4 agar aman dari race-condition
            $db = \Config\Database::connect();
            $db->query("UPDATE pelanggan SET poin_loyalitas = poin_loyalitas - ? WHERE id_pelanggan = ?", [$poinDipotong, $idPelanggan]);

            // Buat kode voucher unik (Contoh: VCR-A1B2C)
            $kodeVoucher = 'VCR-' . strtoupper(substr(md5(uniqid()), 0, 5));
            
            // Simpan voucher ke database
            $db->query("INSERT INTO pelanggan_voucher (id_pelanggan, nama_reward, kode_voucher, status) VALUES (?, ?, ?, 'aktif')", [
                $idPelanggan, $namaReward, $kodeVoucher
            ]);

            session()->setFlashdata('sukses', "Berhasil menukarkan $poinDipotong Poin! Kode Voucher: $kodeVoucher");
        } else {
            session()->setFlashdata('error', "Gagal! Poin {$user['nama_pelanggan']} tidak mencukupi untuk reward tersebut.");
        }

        return redirect()->to(base_url('admin/reward'));
    }
}