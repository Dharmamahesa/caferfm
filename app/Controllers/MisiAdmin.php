<?php

namespace App\Controllers;

class MisiAdmin extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $misi = $db->table('misi')->get()->getResultArray();

        $data = [
            'title' => 'Manajemen Misi Gamifikasi',
            'misi'  => $misi
        ];

        return view('admin/v_misi_index', $data);
    }

    public function simpan()
    {
        $db = \Config\Database::connect();
        $postData = $this->request->getPost();

        $db->table('misi')->insert([
            'nama_misi'     => $postData['nama_misi'],
            'deskripsi'     => $postData['deskripsi'],
            'tipe_misi'     => $postData['tipe_misi'],
            'target_jumlah' => $postData['target_jumlah'],
            'poin_reward'   => $postData['poin_reward']
        ]);

        session()->setFlashdata('sukses', 'Misi baru berhasil ditambahkan!');
        return redirect()->to(base_url('admin/misi'));
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $postData = $this->request->getPost();

        $db->table('misi')->where('id_misi', $id)->update([
            'nama_misi'     => $postData['nama_misi'],
            'deskripsi'     => $postData['deskripsi'],
            'tipe_misi'     => $postData['tipe_misi'],
            'target_jumlah' => $postData['target_jumlah'],
            'poin_reward'   => $postData['poin_reward']
        ]);

        session()->setFlashdata('sukses', 'Misi berhasil diperbarui!');
        return redirect()->to(base_url('admin/misi'));
    }

    public function hapus($id)
    {
        $db = \Config\Database::connect();
        $db->table('misi')->where('id_misi', $id)->delete();
        $db->table('pelanggan_misi')->where('id_misi', $id)->delete(); // Cascade delete

        session()->setFlashdata('sukses', 'Misi berhasil dihapus!');
        return redirect()->to(base_url('admin/misi'));
    }
}
