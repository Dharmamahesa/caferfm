<?php

namespace App\Controllers;

use App\Models\PelangganModel;

class Pelanggan extends BaseController
{
    public function profil()
    {
        $pelangganModel = new PelangganModel();
        
        // Ambil ID dari session yang sedang login
        $idPelanggan = session()->get('id_pelanggan');

        // Tarik data profil dari database
        $user = $pelangganModel->find($idPelanggan);

        // =======================================================
        // LOGIKA GAMIFIKASI: PENENTUAN LEVEL (TIER) & PROGRESS
        // =======================================================
        $poin = $user['poin_loyalitas'];
        
        // Setup Default (Member Baru)
        $tier = 'Bronze';
        $nextTier = 'Silver';
        $poinNextTier = 100; // Butuh 100 poin untuk ke Silver
        $progress = 0;

        // Logika Leveling (Bisa kamu sesuaikan angkanya untuk skripsi)
        if ($poin >= 1000) {
            $tier = 'Platinum';
            $nextTier = 'Maksimal';
            $poinNextTier = $poin; 
            $progress = 100;
        } elseif ($poin >= 300) {
            $tier = 'Gold';
            $nextTier = 'Platinum';
            $poinNextTier = 1000;
            $progress = (($poin - 300) / (1000 - 300)) * 100;
        } elseif ($poin >= 100) {
            $tier = 'Silver';
            $nextTier = 'Gold';
            $poinNextTier = 300;
            $progress = (($poin - 100) / (300 - 100)) * 100;
        } else {
            $tier = 'Bronze';
            $nextTier = 'Silver';
            $poinNextTier = 100;
            $progress = ($poin / 100) * 100;
        }

        // Bungkus data untuk dikirim ke View
        $data = [
            'title'        => 'Profil & Reward - Kafe Gamified',
            'user'         => $user,
            'tier'         => $tier,
            'nextTier'     => $nextTier,
            'poinNextTier' => $poinNextTier,
            'progress'     => round($progress) // Bulatkan persentase
        ];

        return view('pelanggan/v_profil', $data);
    }

    public function pesanan_saya()
    {
        $idPelanggan = session()->get('id_pelanggan');
        $db = \Config\Database::connect();
        
        $pesanan = $db->table('pesanan')
            ->where('id_pelanggan', $idPelanggan)
            ->orderBy('tgl_pesanan', 'DESC')
            ->get()->getResultArray();
            
        $data = [
            'title' => 'Riwayat Pesanan',
            'pesanan' => $pesanan
        ];
        
        return view('pelanggan/v_pesanan_saya', $data);
    }

    public function misi_saya()
    {
        $idPelanggan = session()->get('id_pelanggan');
        $db = \Config\Database::connect();
        
        // Pastikan misi digenerate untuk pelanggan ini
        $misi = $db->table('misi')->get()->getResultArray();
        foreach($misi as $m) {
            $cek = $db->table('pelanggan_misi')
                ->where(['id_pelanggan' => $idPelanggan, 'id_misi' => $m['id_misi']])
                ->get()->getRowArray();
            if(!$cek) {
                $db->table('pelanggan_misi')->insert([
                    'id_pelanggan' => $idPelanggan,
                    'id_misi' => $m['id_misi'],
                    'progress' => 0,
                    'status' => 'berjalan'
                ]);
            }
        }

        // Ambil daftar misi berserta status
        $misiUser = $db->table('pelanggan_misi')
            ->join('misi', 'misi.id_misi = pelanggan_misi.id_misi')
            ->where('pelanggan_misi.id_pelanggan', $idPelanggan)
            ->get()->getResultArray();

        $data = [
            'title' => 'Misi Gamifikasi',
            'misi' => $misiUser
        ];
        
        return view('pelanggan/v_misi_saya', $data);
    }

    public function klaim_misi($idMisi)
    {
        $idPelanggan = session()->get('id_pelanggan');
        $db = \Config\Database::connect();
        
        $misi = $db->table('pelanggan_misi')
            ->join('misi', 'misi.id_misi = pelanggan_misi.id_misi')
            ->where(['pelanggan_misi.id_pelanggan' => $idPelanggan, 'pelanggan_misi.id_misi' => $idMisi])
            ->get()->getRowArray();
            
        if($misi && $misi['status'] == 'selesai') {
            // Berikan poin
            $pelangganModel = new PelangganModel();
            $user = $pelangganModel->find($idPelanggan);
            $poinBaru = $user['poin_loyalitas'] + $misi['poin_reward'];
            $pelangganModel->update($idPelanggan, ['poin_loyalitas' => $poinBaru]);
            
            // Ubah status jadi diklaim
            $db->table('pelanggan_misi')
                ->where(['id_pelanggan' => $idPelanggan, 'id_misi' => $idMisi])
                ->update(['status' => 'diklaim']);
                
            return redirect()->to(base_url('misi_saya'))->with('sukses', 'Berhasil klaim ' . $misi['poin_reward'] . ' Poin!');
        }
        
        return redirect()->to(base_url('misi_saya'))->with('error', 'Misi belum selesai atau sudah diklaim.');
    }
}