<?php

namespace App\Controllers;

class MisiAdmin extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $misi = $db->table('misi')
            ->select('misi.*, menu.nama_item as nama_item_target, menu.kode_item as kode_item_target')
            ->join('menu', 'menu.id_menu = misi.target_id_menu', 'left')
            ->get()->getResultArray();

        $menuList = $db->table('menu')->orderBy('nama_item', 'ASC')->get()->getResultArray();

        $data = [
            'title'    => 'Manajemen Misi Gamifikasi',
            'misi'     => $misi,
            'menuList' => $menuList
        ];

        return view('admin/v_misi_index', $data);
    }

    public function simpan()
    {
        $db = \Config\Database::connect();
        $postData = $this->request->getPost();

        $db->table('misi')->insert([
            'nama_misi'      => $postData['nama_misi'],
            'deskripsi'      => $postData['deskripsi'],
            'tipe_misi'      => $postData['tipe_misi'],
            'target_id_menu' => empty($postData['target_id_menu']) ? null : $postData['target_id_menu'],
            'target_jumlah'  => $postData['target_jumlah'],
            'poin_reward'    => $postData['poin_reward']
        ]);

        session()->setFlashdata('sukses', 'Misi baru berhasil ditambahkan!');
        return redirect()->to(base_url('admin/misi'));
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $postData = $this->request->getPost();

        $db->table('misi')->where('id_misi', $id)->update([
            'nama_misi'      => $postData['nama_misi'],
            'deskripsi'      => $postData['deskripsi'],
            'tipe_misi'      => $postData['tipe_misi'],
            'target_id_menu' => empty($postData['target_id_menu']) ? null : $postData['target_id_menu'],
            'target_jumlah'  => $postData['target_jumlah'],
            'poin_reward'    => $postData['poin_reward']
        ]);

        session()->setFlashdata('sukses', 'Misi berhasil diperbarui!');
        return redirect()->to(base_url('admin/misi'));
    }

    public function hapus($id)
    {
        $db = \Config\Database::connect();
        $db->table('pelanggan_misi')->where('id_misi', $id)->delete(); // Cascade delete
        $db->table('misi')->where('id_misi', $id)->delete();

        session()->setFlashdata('sukses', 'Misi berhasil dihapus!');
        return redirect()->to(base_url('admin/misi'));
    }
}
