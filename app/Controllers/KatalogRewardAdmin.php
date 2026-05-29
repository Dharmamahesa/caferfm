<?php

namespace App\Controllers;

class KatalogRewardAdmin extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $reward = $db->table('katalog_reward')
            ->select('katalog_reward.*, menu.nama_item as nama_item_target, menu.kode_item as kode_item_target')
            ->join('menu', 'menu.id_menu = katalog_reward.target_id_menu', 'left')
            ->get()->getResultArray();

        $menuList = $db->table('menu')->orderBy('nama_item', 'ASC')->get()->getResultArray();

        $data = [
            'title'    => 'Master Katalog Reward',
            'reward'   => $reward,
            'menuList' => $menuList
        ];

        return view('admin/v_katalog_reward_index', $data);
    }

    public function simpan()
    {
        $db = \Config\Database::connect();
        $postData = $this->request->getPost();

        $db->table('katalog_reward')->insert([
            'nama_reward'     => $postData['nama_reward'],
            'deskripsi'       => $postData['deskripsi'],
            'poin_dibutuhkan' => $postData['poin_dibutuhkan'],
            'tipe_diskon'     => $postData['tipe_diskon'],
            'nominal_diskon'  => $postData['nominal_diskon'] ?: 0,
            'ikon'            => $postData['ikon'] ?: '🎁',
            'target_id_menu'  => empty($postData['target_id_menu']) ? null : $postData['target_id_menu'],
        ]);

        session()->setFlashdata('sukses', 'Item reward baru berhasil ditambahkan ke katalog!');
        return redirect()->to(base_url('admin/katalog_reward'));
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $postData = $this->request->getPost();

        $db->table('katalog_reward')->where('id_reward', $id)->update([
            'nama_reward'     => $postData['nama_reward'],
            'deskripsi'       => $postData['deskripsi'],
            'poin_dibutuhkan' => $postData['poin_dibutuhkan'],
            'tipe_diskon'     => $postData['tipe_diskon'],
            'nominal_diskon'  => $postData['nominal_diskon'] ?: 0,
            'ikon'            => $postData['ikon'] ?: '🎁',
            'target_id_menu'  => empty($postData['target_id_menu']) ? null : $postData['target_id_menu'],
        ]);

        session()->setFlashdata('sukses', 'Item reward berhasil diperbarui!');
        return redirect()->to(base_url('admin/katalog_reward'));
    }

    public function hapus($id)
    {
        $db = \Config\Database::connect();
        $db->table('katalog_reward')->where('id_reward', $id)->delete();

        session()->setFlashdata('sukses', 'Item reward berhasil dihapus dari katalog!');
        return redirect()->to(base_url('admin/katalog_reward'));
    }
}
