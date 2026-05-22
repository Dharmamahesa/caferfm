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

        // Ambil data voucher pelanggan
        $db = \Config\Database::connect();
        $voucher = $db->table('pelanggan_voucher')
            ->where('id_pelanggan', $idPelanggan)
            ->orderBy('status', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        // Bungkus data untuk dikirim ke View
        $data = [
            'title'        => 'Profil & Reward - Kafe Gamified',
            'user'         => $user,
            'tier'         => $tier,
            'nextTier'     => $nextTier,
            'poinNextTier' => $poinNextTier,
            'progress'     => round($progress),
            'voucher'      => $voucher
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

    public function tukar_poin()
    {
        $idPelanggan = session()->get('id_pelanggan');
        $db = \Config\Database::connect();
        
        $user = $db->table('pelanggan')->where('id_pelanggan', $idPelanggan)->get()->getRowArray();
        $katalog = $db->table('katalog_reward')->get()->getResultArray();
        
        $data = [
            'title'   => 'Katalog Tukar Poin',
            'user'    => $user,
            'katalog' => $katalog
        ];
        
        return view('pelanggan/v_tukar_poin', $data);
    }

    public function proses_tukar_poin()
    {
        $idPelanggan = session()->get('id_pelanggan');
        $idReward = $this->request->getPost('id_reward');
        
        $db = \Config\Database::connect();
        $user = $db->table('pelanggan')->where('id_pelanggan', $idPelanggan)->get()->getRowArray();
        $reward = $db->table('katalog_reward')->where('id_reward', $idReward)->get()->getRowArray();
        
        if (!$reward) {
            return redirect()->to(base_url('tukar_poin'))->with('error', 'Item reward tidak valid.');
        }

        if ($user['poin_loyalitas'] >= $reward['poin_dibutuhkan']) {
            // Potong poin
            $db->query("UPDATE pelanggan SET poin_loyalitas = poin_loyalitas - ? WHERE id_pelanggan = ?", [$reward['poin_dibutuhkan'], $idPelanggan]);

            // Generate Kode
            $kodeVoucher = 'VCR-' . strtoupper(substr(md5(uniqid()), 0, 5));
            
            // Simpan Voucher ke profil
            $db->table('pelanggan_voucher')->insert([
                'id_pelanggan'   => $idPelanggan,
                'nama_reward'    => $reward['nama_reward'],
                'kode_voucher'   => $kodeVoucher,
                'tipe_diskon'    => $reward['tipe_diskon'],
                'nominal_diskon' => $reward['nominal_diskon'],
                'status'         => 'aktif'
            ]);

            return redirect()->to(base_url('profil'))->with('sukses', "Berhasil menukar poin! Reward {$reward['nama_reward']} telah ditambahkan ke Voucher Saya.");
        }
        
        return redirect()->to(base_url('tukar_poin'))->with('error', 'Poin tidak mencukupi untuk reward ini.');
    }

    // ==========================================================
    // 5. SISTEM GACHA (LUCKY SPIN)
    // ==========================================================
    public function lucky_spin()
    {
        $idPelanggan = session()->get('id_pelanggan');
        $pelangganModel = new PelangganModel();
        
        $user = $pelangganModel->find($idPelanggan);
        
        $data = [
            'title' => 'Lucky Spin - Kafe Gamified',
            'user'  => $user
        ];

        return view('pelanggan/v_lucky_spin', $data);
    }

    public function beli_spin()
    {
        $idPelanggan = session()->get('id_pelanggan');
        $pelangganModel = new PelangganModel();
        $user = $pelangganModel->find($idPelanggan);

        // 1 chance = 50 points
        if ($user['poin_loyalitas'] >= 50) {
            $pelangganModel->update($idPelanggan, [
                'poin_loyalitas' => $user['poin_loyalitas'] - 50,
                'spin_chances'   => $user['spin_chances'] + 1
            ]);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Berhasil membeli 1 tiket Spin!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Poin tidak cukup! Minimal 50 poin.']);
    }

    public function proses_spin()
    {
        $idPelanggan = session()->get('id_pelanggan');
        $pelangganModel = new PelangganModel();
        $user = $pelangganModel->find($idPelanggan);

        if ($user['spin_chances'] <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki tiket Spin!']);
        }

        // Deduct 1 chance
        $pelangganModel->update($idPelanggan, [
            'spin_chances' => $user['spin_chances'] - 1
        ]);

        // Daftar Hadiah (Probabilitas bisa diatur di sini)
        $prizes = [
            ['id' => 1, 'nama' => 'FREE AREN LATTE', 'tipe' => 'voucher', 'nominal' => 0, 'weight' => 5],
            ['id' => 2, 'nama' => 'DISC 6K', 'tipe' => 'voucher', 'nominal' => 6000, 'weight' => 15],
            ['id' => 3, 'nama' => '100 POINTS', 'tipe' => 'poin', 'nominal' => 100, 'weight' => 10],
            ['id' => 4, 'nama' => '50 POINTS', 'tipe' => 'poin', 'nominal' => 50, 'weight' => 20],
            ['id' => 5, 'nama' => '30 POINTS', 'tipe' => 'poin', 'nominal' => 30, 'weight' => 30],
            ['id' => 6, 'nama' => 'THANKS', 'tipe' => 'zonk', 'nominal' => 0, 'weight' => 20],
        ];

        // Weighted random selection
        $totalWeight = array_sum(array_column($prizes, 'weight'));
        $rand = mt_rand(1, $totalWeight);
        
        $selectedPrize = null;
        $currentWeight = 0;
        foreach ($prizes as $prize) {
            $currentWeight += $prize['weight'];
            if ($rand <= $currentWeight) {
                $selectedPrize = $prize;
                break;
            }
        }

        // Berikan hadiah
        if ($selectedPrize['tipe'] == 'poin') {
            $pelangganModel->update($idPelanggan, [
                'poin_loyalitas' => $user['poin_loyalitas'] + $selectedPrize['nominal']
            ]);
        } elseif ($selectedPrize['tipe'] == 'voucher') {
            $db = \Config\Database::connect();
            $kodeVoucher = 'SPIN-' . strtoupper(substr(md5(uniqid()), 0, 5));
            $db->table('pelanggan_voucher')->insert([
                'id_pelanggan'   => $idPelanggan,
                'nama_reward'    => $selectedPrize['nama'],
                'kode_voucher'   => $kodeVoucher,
                'tipe_diskon'    => $selectedPrize['nominal'] > 0 ? 'nominal' : 'produk',
                'nominal_diskon' => $selectedPrize['nominal'],
                'status'         => 'aktif'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'prize_id' => $selectedPrize['id'],
            'prize_name' => $selectedPrize['nama'],
            'prize_type' => $selectedPrize['tipe']
        ]);
    }
}