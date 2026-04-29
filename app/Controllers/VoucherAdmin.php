<?php

namespace App\Controllers;

class VoucherAdmin extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $voucher = $db->table('voucher_global')->get()->getResultArray();

        $data = [
            'title'   => 'Manajemen Voucher Promo',
            'voucher' => $voucher
        ];

        return view('admin/v_voucher_index', $data);
    }

    public function simpan()
    {
        $db = \Config\Database::connect();
        $postData = $this->request->getPost();

        // Cek duplikasi kode
        $cekKode = $db->table('voucher_global')->where('kode_voucher', $postData['kode_voucher'])->countAllResults();
        if ($cekKode > 0) {
            return redirect()->to(base_url('admin/voucher'))->with('error', 'Kode voucher sudah ada, gunakan kode lain!');
        }

        $db->table('voucher_global')->insert([
            'nama_voucher' => $postData['nama_voucher'],
            'kode_voucher' => strtoupper($postData['kode_voucher']),
            'diskon'       => $postData['diskon'],
            'tipe_diskon'  => $postData['tipe_diskon'],
            'kuota'        => $postData['kuota'] ?: 0,
            'status'       => 'aktif'
        ]);

        session()->setFlashdata('sukses', 'Voucher promo baru berhasil dibuat!');
        return redirect()->to(base_url('admin/voucher'));
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $postData = $this->request->getPost();

        $db->table('voucher_global')->where('id_voucher', $id)->update([
            'nama_voucher' => $postData['nama_voucher'],
            'diskon'       => $postData['diskon'],
            'tipe_diskon'  => $postData['tipe_diskon'],
            'kuota'        => $postData['kuota'] ?: 0,
            'status'       => $postData['status']
        ]);

        session()->setFlashdata('sukses', 'Voucher promo berhasil diperbarui!');
        return redirect()->to(base_url('admin/voucher'));
    }

    public function hapus($id)
    {
        $db = \Config\Database::connect();
        $db->table('voucher_global')->where('id_voucher', $id)->delete();

        session()->setFlashdata('sukses', 'Voucher promo berhasil dihapus!');
        return redirect()->to(base_url('admin/voucher'));
    }
}
