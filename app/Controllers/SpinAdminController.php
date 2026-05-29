<?php

namespace App\Controllers;

class SpinAdminController extends BaseController
{
    /**
     * Daftar semua hadiah spin
     */
    public function index()
    {
        $db = \Config\Database::connect();

        // Pastikan tabel ada
        $this->ensureTable($db);

        $hadiah = $db->table('spin_hadiah')
                     ->orderBy('urutan', 'ASC')
                     ->get()->getResultArray();

        // Join nama menu untuk yang punya target_id_menu
        foreach ($hadiah as &$h) {
            $h['nama_menu_target'] = null;
            if (!empty($h['target_id_menu'])) {
                $menu = $db->table('menu')->select('nama_item, harga')->where('id_menu', $h['target_id_menu'])->get()->getRowArray();
                $h['nama_menu_target'] = $menu ? $menu['nama_item'] . ' (Rp ' . number_format($menu['harga']) . ')' : '(menu dihapus)';
            }
        }
        unset($h);

        $totalWeight = array_sum(array_column($hadiah, 'weight'));

        // Daftar menu untuk dropdown pilihan
        $menuList = $db->table('menu')
                       ->where('stok >', 0)
                       ->orderBy('nama_item', 'ASC')
                       ->get()->getResultArray();

        $data = [
            'title'       => 'Pengaturan Lucky Spin',
            'hadiah'      => $hadiah,
            'totalWeight' => $totalWeight,
            'menuList'    => $menuList,
        ];

        return view('admin/v_spin_hadiah', $data);
    }

    /**
     * Simpan hadiah baru
     */
    public function simpan()
    {
        $db = \Config\Database::connect();
        $this->ensureTable($db);

        $tipe    = $this->request->getPost('tipe');
        $nominal = (int) $this->request->getPost('nominal');
        $weight  = max(1, (int) $this->request->getPost('weight'));

        // Validasi minimal
        $namaHadiah = trim($this->request->getPost('nama_hadiah'));
        if (empty($namaHadiah)) {
            session()->setFlashdata('error', 'Nama hadiah tidak boleh kosong!');
            return redirect()->back();
        }

        // Urutan: taruh di akhir
        $maxUrutan = $db->table('spin_hadiah')->selectMax('urutan')->get()->getRowArray();
        $urutan    = ($maxUrutan['urutan'] ?? 0) + 1;

        // Untuk tipe voucher: tentukan tipe_diskon & nominal_diskon
        $tipeDiskon    = null;
        $nominalDiskon = 0;
        $emoji         = '🎁';

        if ($tipe === 'poin') {
            $emoji = $nominal >= 100 ? '💰' : '🪙';
        } elseif ($tipe === 'voucher_nominal') {
            $tipeDiskon    = 'nominal';
            $nominalDiskon = $nominal;
            $emoji         = '🎫';
            $tipe          = 'voucher';
        } elseif ($tipe === 'voucher_persen') {
            $tipeDiskon    = 'persen';
            $nominalDiskon = min(100, $nominal); // max 100%
            $emoji         = '🎟️';
            $tipe          = 'voucher';
        } elseif ($tipe === 'voucher_produk') {
            $tipeDiskon    = 'produk';
            $nominalDiskon = 0;
            $emoji         = '☕';
            $tipe          = 'voucher';
        } elseif ($tipe === 'zonk') {
            $emoji = '😭';
        }

        $emojiInput = trim($this->request->getPost('emoji'));
        if (!empty($emojiInput)) $emoji = $emojiInput;

        // target_id_menu hanya relevan untuk voucher_produk
        $targetIdMenu = null;
        if ($tipe === 'voucher') {
            $rawTarget = $this->request->getPost('target_id_menu');
            $targetIdMenu = (!empty($rawTarget) && is_numeric($rawTarget)) ? (int)$rawTarget : null;
        }

        $db->table('spin_hadiah')->insert([
            'nama_hadiah'    => $namaHadiah,
            'tipe'           => $tipe,
            'tipe_diskon'    => $tipeDiskon,
            'nominal'        => $nominalDiskon,
            'weight'         => $weight,
            'emoji'          => $emoji,
            'warna_bg'       => $this->request->getPost('warna_bg') ?: '#f0fff0',
            'warna_text'     => $this->request->getPost('warna_text') ?: '#3d9600',
            'is_active'      => 1,
            'urutan'         => $urutan,
            'target_id_menu' => $targetIdMenu,
        ]);

        session()->setFlashdata('sukses', "Hadiah \"$namaHadiah\" berhasil ditambahkan!");
        return redirect()->to(base_url('admin/spin'));
    }

    /**
     * Update data hadiah (inline update lewat POST)
     */
    public function update($id)
    {
        $db = \Config\Database::connect();

        $tipe    = $this->request->getPost('tipe');
        $nominal = (int) $this->request->getPost('nominal');
        $weight  = max(1, (int) $this->request->getPost('weight'));

        $tipeDiskon    = null;
        $nominalDiskon = 0;
        $emoji         = trim($this->request->getPost('emoji')) ?: '🎁';

        if ($tipe === 'poin') {
            $nominalDiskon = $nominal;
        } elseif ($tipe === 'voucher_nominal') {
            $tipeDiskon    = 'nominal';
            $nominalDiskon = $nominal;
            $tipe          = 'voucher';
        } elseif ($tipe === 'voucher_persen') {
            $tipeDiskon    = 'persen';
            $nominalDiskon = min(100, $nominal);
            $tipe          = 'voucher';
        } elseif ($tipe === 'voucher_produk') {
            $tipeDiskon    = 'produk';
            $nominalDiskon = 0;
            $tipe          = 'voucher';
        } elseif ($tipe === 'zonk') {
            $nominalDiskon = 0;
        }

        $db->table('spin_hadiah')->where('id', $id)->update([
            'nama_hadiah'    => $this->request->getPost('nama_hadiah'),
            'tipe'           => $tipe,
            'tipe_diskon'    => $tipeDiskon,
            'nominal'        => $nominalDiskon,
            'weight'         => $weight,
            'emoji'          => $emoji,
            'warna_bg'       => $this->request->getPost('warna_bg') ?: '#f0fff0',
            'warna_text'     => $this->request->getPost('warna_text') ?: '#3d9600',
            'is_active'      => $this->request->getPost('is_active') ?? 1,
            'urutan'         => (int) $this->request->getPost('urutan'),
            'target_id_menu' => ($tipe === 'voucher' && !empty($this->request->getPost('target_id_menu'))) 
                                    ? (int)$this->request->getPost('target_id_menu') 
                                    : null,
        ]);

        session()->setFlashdata('sukses', 'Hadiah berhasil diperbarui!');
        return redirect()->to(base_url('admin/spin'));
    }

    /**
     * Toggle aktif/nonaktif hadiah
     */
    public function toggle($id)
    {
        $db     = \Config\Database::connect();
        $hadiah = $db->table('spin_hadiah')->where('id', $id)->get()->getRowArray();

        if (!$hadiah) {
            session()->setFlashdata('error', 'Hadiah tidak ditemukan.');
            return redirect()->to(base_url('admin/spin'));
        }

        $newStatus = $hadiah['is_active'] == 1 ? 0 : 1;
        $db->table('spin_hadiah')->where('id', $id)->update(['is_active' => $newStatus]);

        $pesan = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        session()->setFlashdata('sukses', "Hadiah \"{$hadiah['nama_hadiah']}\" berhasil $pesan.");
        return redirect()->to(base_url('admin/spin'));
    }

    /**
     * Hapus hadiah
     */
    public function hapus($id)
    {
        $db = \Config\Database::connect();
        $hadiah = $db->table('spin_hadiah')->where('id', $id)->get()->getRowArray();

        if ($hadiah) {
            $db->table('spin_hadiah')->where('id', $id)->delete();
            session()->setFlashdata('sukses', "Hadiah \"{$hadiah['nama_hadiah']}\" berhasil dihapus.");
        }
        return redirect()->to(base_url('admin/spin'));
    }

    /**
     * Reset ke hadiah default
     */
    public function reset_default()
    {
        $db = \Config\Database::connect();
        $this->ensureTable($db);

        $db->table('spin_hadiah')->emptyTable();
        $this->insertDefault($db);

        session()->setFlashdata('sukses', 'Konfigurasi hadiah spin berhasil direset ke default!');
        return redirect()->to(base_url('admin/spin'));
    }

    // ----------------------------------------------------------------
    // HELPER: Pastikan tabel ada & isi default jika kosong
    // ----------------------------------------------------------------
    private function ensureTable($db)
    {
        if (!$db->tableExists('spin_hadiah')) {
            $db->query("
                CREATE TABLE spin_hadiah (
                    id             INT AUTO_INCREMENT PRIMARY KEY,
                    nama_hadiah    VARCHAR(100) NOT NULL,
                    tipe           ENUM('poin','voucher','zonk') NOT NULL DEFAULT 'zonk',
                    tipe_diskon    ENUM('nominal','persen','produk') NULL,
                    nominal        INT NOT NULL DEFAULT 0,
                    weight         INT NOT NULL DEFAULT 10 COMMENT 'Bobot probabilitas',
                    emoji          VARCHAR(10) NOT NULL DEFAULT '🎁',
                    warna_bg       VARCHAR(20) NOT NULL DEFAULT '#f0fff0',
                    warna_text     VARCHAR(20) NOT NULL DEFAULT '#3d9600',
                    is_active      TINYINT(1) NOT NULL DEFAULT 1,
                    urutan         INT NOT NULL DEFAULT 1,
                    target_id_menu INT NULL COMMENT 'ID menu yang digratiskan (untuk tipe_diskon=produk)'
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            $this->insertDefault($db);
        } else {
            // Tambahkan kolom target_id_menu jika belum ada (migrasi incremental)
            if (!$db->fieldExists('target_id_menu', 'spin_hadiah')) {
                $db->query("ALTER TABLE spin_hadiah ADD COLUMN target_id_menu INT NULL COMMENT 'ID menu yang digratiskan'");
            }
            if ($db->table('spin_hadiah')->countAllResults() === 0) {
                $this->insertDefault($db);
            }
        }
    }

    private function insertDefault($db)
    {
        $defaults = [
            ['nama_hadiah' => 'FREE AREN LATTE', 'tipe' => 'voucher', 'tipe_diskon' => 'produk', 'nominal' => 0,     'weight' => 5,  'emoji' => '☕', 'warna_bg' => '#f0fff0', 'warna_text' => '#3d9600', 'is_active' => 1, 'urutan' => 1],
            ['nama_hadiah' => 'DISC 6K',          'tipe' => 'voucher', 'tipe_diskon' => 'nominal', 'nominal' => 6000,  'weight' => 15, 'emoji' => '🎫', 'warna_bg' => '#dcfadc', 'warna_text' => '#2d7a00', 'is_active' => 1, 'urutan' => 2],
            ['nama_hadiah' => '100 POINTS',        'tipe' => 'poin',    'tipe_diskon' => null,       'nominal' => 100,   'weight' => 10, 'emoji' => '💰', 'warna_bg' => '#f0fff0', 'warna_text' => '#3d9600', 'is_active' => 1, 'urutan' => 3],
            ['nama_hadiah' => '50 POINTS',         'tipe' => 'poin',    'tipe_diskon' => null,       'nominal' => 50,    'weight' => 20, 'emoji' => '🪙', 'warna_bg' => '#dcfadc', 'warna_text' => '#2d7a00', 'is_active' => 1, 'urutan' => 4],
            ['nama_hadiah' => '30 POINTS',         'tipe' => 'poin',    'tipe_diskon' => null,       'nominal' => 30,    'weight' => 30, 'emoji' => '🪙', 'warna_bg' => '#f0fff0', 'warna_text' => '#3d9600', 'is_active' => 1, 'urutan' => 5],
            ['nama_hadiah' => 'THANKS',            'tipe' => 'zonk',    'tipe_diskon' => null,       'nominal' => 0,     'weight' => 20, 'emoji' => '😭', 'warna_bg' => '#dcfadc', 'warna_text' => '#2d7a00', 'is_active' => 1, 'urutan' => 6],
        ];
        $db->table('spin_hadiah')->insertBatch($defaults);
    }
}
