<?php

namespace App\Controllers;

class KatalogRewardAdmin extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $reward = $db->table('katalog_reward')->get()->getResultArray();

        $data = [
            'title'  => 'Master Katalog Reward',
            'reward' => $reward
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
            'ikon'            => $postData['ikon'] ?: '🎁'
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
            'ikon'            => $postData['ikon'] ?: '🎁'
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
