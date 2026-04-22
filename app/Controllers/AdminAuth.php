<?php

namespace App\Controllers;

use App\Models\AdminModel;

class AdminAuth extends BaseController
{
    public function login()
    {
        // Jika sudah login, langsung lempar ke dapur
        if (session()->get('isAdminLoggedIn')) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        return view('admin/v_login_admin');
    }

    public function proses_login()
    {
        $adminModel = new AdminModel();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $adminModel->where('username', $username)->first();

        if ($admin) {
            // Verifikasi password Hash
            if (password_verify($password, $admin['password'])) {
                session()->set([
                    'id_admin'        => $admin['id_admin'],
                    'nama_admin'      => $admin['nama_admin'],
                    'isAdminLoggedIn' => true
                ]);
                return redirect()->to(base_url('admin/dapur'));
            } else {
                session()->setFlashdata('error', 'Password Admin salah!');
                return redirect()->to(base_url('admin/login'));
            }
        } else {
            session()->setFlashdata('error', 'Username tidak ditemukan!');
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function logout()
    {
        session()->remove(['id_admin', 'nama_admin', 'isAdminLoggedIn']);
        session()->setFlashdata('sukses', 'Berhasil keluar dari sistem Admin.');
        return redirect()->to(base_url('admin/login'));
    }

    // =======================================================
    // JURUS RAHASIA: SCRIPT GENERATOR AKUN PERTAMA
    // =======================================================
    public function setup()
    {
        $adminModel = new AdminModel();
        
        // Cek apakah sudah ada admin
        if ($adminModel->countAllResults() > 0) {
            return "Akun admin sudah ada. Hapus file atau rute ini demi keamanan.";
        }

        // Buat akun admin default
        $adminModel->insert([
            'username'   => 'admin',
            'password'   => password_hash('admin123', PASSWORD_BCRYPT), // Hash Otomatis
            'nama_admin' => 'Super Admin'
        ]);

        return "Akun Admin berhasil dibuat! Username: admin | Password: admin123";
    }
}