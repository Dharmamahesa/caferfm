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
        
        // --- FITUR EXPIRED VOUCHER ---
        // Tandai hangus untuk voucher aktif yang umurnya > 7 hari
        $tujuhHariLalu = date('Y-m-d H:i:s', strtotime('-7 days'));
        $db->table('pelanggan_voucher')
           ->where('id_pelanggan', $idPelanggan)
           ->where('status', 'aktif')
           ->where('created_at <', $tujuhHariLalu)
           ->update(['status' => 'hangus']);

        // Hanya tampilkan voucher yang masih aktif (sudah terpakai / hangus akan hilang)
        $voucher = $db->table('pelanggan_voucher')
            ->where('id_pelanggan', $idPelanggan)
            ->where('status', 'aktif')
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        // Bungkus data untuk dikirim ke View
        $data = [
            'title'        => 'Profil & Reward - Toko Kopi Jaya Lestari',
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
                'status'         => 'aktif',
                'created_at'     => date('Y-m-d H:i:s')
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

        // Ambil konfigurasi hadiah dari database (hanya yang aktif)
        $db    = \Config\Database::connect();
        $this->ensureSpinTable($db);
        $hadiah = $db->table('spin_hadiah')
                     ->where('is_active', 1)
                     ->orderBy('urutan', 'ASC')
                     ->get()->getResultArray();
        
        $data = [
            'title'  => 'Lucky Spin - Toko Kopi Jaya Lestari',
            'user'   => $user,
            'hadiah' => $hadiah,
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
        $idPelanggan    = session()->get('id_pelanggan');
        $pelangganModel = new PelangganModel();
        $user           = $pelangganModel->find($idPelanggan);

        if ($user['spin_chances'] <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki tiket Spin!']);
        }

        // Kurangi tiket
        $pelangganModel->update($idPelanggan, ['spin_chances' => $user['spin_chances'] - 1]);

        // ====================================================
        // Ambil hadiah dari database (hanya yang aktif)
        // ====================================================
        $db = \Config\Database::connect();
        $this->ensureSpinTable($db);
        $prizes = $db->table('spin_hadiah')
                     ->where('is_active', 1)
                     ->orderBy('urutan', 'ASC')
                     ->get()->getResultArray();

        if (empty($prizes)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Hadiah spin belum dikonfigurasi.']);
        }

        // Weighted random selection
        $totalWeight = array_sum(array_column($prizes, 'weight'));
        $rand        = mt_rand(1, $totalWeight);
        
        $selectedPrize = null;
        $currentWeight = 0;
        foreach ($prizes as $i => $prize) {
            $currentWeight += $prize['weight'];
            if ($rand <= $currentWeight) {
                // prize_id = posisi 1-based dalam array (dipakai frontend untuk animasi)
                $selectedPrize = array_merge($prize, ['prize_index' => $i + 1]);
                break;
            }
        }

        // Berikan hadiah
        if ($selectedPrize['tipe'] === 'poin') {
            $tambahPoin = (int) $selectedPrize['nominal'];
            $pelangganModel->update($idPelanggan, [
                'poin_loyalitas' => $user['poin_loyalitas'] + $tambahPoin
            ]);
        } elseif ($selectedPrize['tipe'] === 'voucher') {
            $kodeVoucher = 'SPIN-' . strtoupper(substr(md5(uniqid()), 0, 6));

            // Untuk voucher produk: ambil harga menu sebagai nilai diskon (gratis = diskon 100% harga)
            $nominalDiskon   = $selectedPrize['nominal'];
            $tipeDiskon      = $selectedPrize['tipe_diskon'];
            $targetIdMenu    = $selectedPrize['target_id_menu'] ?? null;

            if ($tipeDiskon === 'produk' && !empty($targetIdMenu)) {
                // Ambil harga menu untuk disimpan sebagai nominal (agar checkout tahu berapa nilai diskonnya)
                $menuData = $db->table('menu')->select('harga, nama_item')->where('id_menu', $targetIdMenu)->get()->getRowArray();
                if ($menuData) {
                    $nominalDiskon = $menuData['harga'];
                }
            }

            $db->table('pelanggan_voucher')->insert([
                'id_pelanggan'   => $idPelanggan,
                'nama_reward'    => $selectedPrize['nama_hadiah'],
                'kode_voucher'   => $kodeVoucher,
                'tipe_diskon'    => $tipeDiskon,
                'nominal_diskon' => $nominalDiskon,
                'target_id_menu' => $targetIdMenu,
                'status'         => 'aktif',
                'created_at'     => date('Y-m-d H:i:s')
            ]);
        }
        // zonk: tidak ada hadiah

        return $this->response->setJSON([
            'status'      => 'success',
            'prize_index' => $selectedPrize['prize_index'],
            'prize_id'    => $selectedPrize['prize_index'], // alias untuk kompatibilitas JS lama
            'prize_name'  => $selectedPrize['nama_hadiah'],
            'prize_type'  => $selectedPrize['tipe'],
            'prize_emoji' => $selectedPrize['emoji'],
        ]);
    }

    // ----------------------------------------------------------------
    // HELPER: Pastikan tabel spin_hadiah ada
    // ----------------------------------------------------------------
    private function ensureSpinTable($db)
    {
        if (!$db->tableExists('spin_hadiah')) {
            // Inisiasi via SpinAdminController
            $spinCtrl = new \App\Controllers\SpinAdminController();
            // Panggil via refleksi private method
            $reflection = new \ReflectionMethod($spinCtrl, 'ensureTable');
            $reflection->setAccessible(true);
            $reflection->invoke($spinCtrl, $db);
        }
    }
}