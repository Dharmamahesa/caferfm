<?php

namespace App\Controllers;

use App\Models\MenuModel;

class MenuAdmin extends BaseController
{
    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    // --- R (READ) : Menampilkan Daftar Menu ---
    public function index()
    {
        $data = [
            'title' => 'Manajemen Menu - Kafe Gamified',
            'menu'  => $this->menuModel->orderBy('kategori', 'ASC')->findAll()
        ];
        return view('admin/v_menu_index', $data);
    }

    // --- C (CREATE) : Menampilkan Form & Simpan ---
    public function tambah()
    {
        $data = ['title' => 'Tambah Menu Baru'];
        return view('admin/v_menu_form', $data);
    }

    public function simpan()
    {
        // 1. Tangkap File Foto
        $fileFoto = $this->request->getFile('foto');
        $namaFoto = '';

        // 2. Cek apakah ada file yang diupload dan valid
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Generate nama acak agar tidak bentrok, lalu pindahkan ke folder public/uploads/menu
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/menu', $namaFoto);
        }

        // 3. Simpan ke database
        $this->menuModel->insert([
            'nama_item' => $this->request->getPost('nama_item'),
            'kategori'  => $this->request->getPost('kategori'),
            'harga'     => $this->request->getPost('harga'),
            'foto'      => $namaFoto
        ]);

        session()->setFlashdata('sukses', 'Menu baru berhasil ditambahkan!');
        return redirect()->to(base_url('admin/menu'));
    }

    // --- U (UPDATE) : Menampilkan Form Edit & Update ---
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Menu',
            'menu'  => $this->menuModel->find($id)
        ];
        return view('admin/v_menu_form', $data); // Menggunakan form yang sama
    }

    public function update($id)
    {
        $menuLama = $this->menuModel->find($id);
        $fileFoto = $this->request->getFile('foto');
        $namaFoto = $menuLama['foto']; // Default: pakai nama foto lama

        // Jika admin mengupload foto baru
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Hapus foto lama secara fisik dari server (jika ada)
            if ($menuLama['foto'] != '' && file_exists('uploads/menu/' . $menuLama['foto'])) {
                unlink('uploads/menu/' . $menuLama['foto']);
            }
            // Upload foto baru
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/menu', $namaFoto);
        }

        $this->menuModel->update($id, [
            'nama_item' => $this->request->getPost('nama_item'),
            'kategori'  => $this->request->getPost('kategori'),
            'harga'     => $this->request->getPost('harga'),
            'foto'      => $namaFoto
        ]);

        session()->setFlashdata('sukses', 'Data menu berhasil diperbarui!');
        return redirect()->to(base_url('admin/menu'));
    }

    // --- D (DELETE) : Menghapus Menu & File Foto ---
    public function hapus($id)
    {
        $menu = $this->menuModel->find($id);
        
        // Hapus file fisik fotonya agar server tidak penuh
        if ($menu['foto'] != '' && file_exists('uploads/menu/' . $menu['foto'])) {
            unlink('uploads/menu/' . $menu['foto']);
        }

        $this->menuModel->delete($id);
        
        session()->setFlashdata('sukses', 'Menu berhasil dihapus!');
        return redirect()->to(base_url('admin/menu'));
    }
}