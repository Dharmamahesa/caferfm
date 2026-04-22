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
}