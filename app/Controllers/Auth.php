<?php

namespace App\Controllers;

use App\Models\PelangganModel;

class Auth extends BaseController
{
    public function __construct()
    {
        // Memuat helper form dan session
        helper(['form', 'url']);
    }

    // --- MENAMPILKAN HALAMAN ---
    public function login()
    {
        return view('pelanggan/v_login');
    }

    public function register()
    {
        return view('pelanggan/v_register');
    }

    // --- LOGIKA PROSES REGISTER ---
    public function proses_register()
    {
        $pelangganModel = new PelangganModel();

        // 1. Ambil inputan dari form pendaftaran
        $nama  = $this->request->getPost('nama_pelanggan');
        $email = $this->request->getPost('email');
        $telp  = $this->request->getPost('no_telp');
        $pass  = $this->request->getPost('password');

        // 2. Hash Password demi keamanan (Sangat penting untuk skripsi!)
        $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

        // 3. Simpan ke database
        $data = [
            'nama_pelanggan' => $nama,
            'email'          => $email,
            'no_telp'        => $telp,
            'password'       => $hashedPassword,
            'poin_loyalitas' => 0 // Pelanggan baru mulai dari 0 poin
        ];

        $pelangganModel->insert($data);

        // 4. Arahkan kembali ke halaman login dengan pesan sukses
        session()->setFlashdata('sukses', 'Pendaftaran berhasil! Silakan Login.');
        return redirect()->to(base_url('auth/login'));
    }

    // --- LOGIKA PROSES LOGIN ---
    public function proses_login()
    {
        $pelangganModel = new PelangganModel();
        
        $email = $this->request->getPost('email');
        $pass  = $this->request->getPost('password');

        // 1. Cari data pelanggan berdasarkan email
        $user = $pelangganModel->where('email', $email)->first();

        if ($user) {
            // 2. Cocokkan password yang diketik dengan password hash di database
            if (password_verify($pass, $user['password'])) {
                
                // 3. Jika cocok, buat identitas Session
                $sessionData = [
                    'id_pelanggan'   => $user['id_pelanggan'],
                    'nama_pelanggan' => $user['nama_pelanggan'],
                    'isLoggedIn'     => true
                ];
                session()->set($sessionData);

                // Arahkan ke halaman utama (Katalog Menu)
                return redirect()->to(base_url('/'));
            } else {
                session()->setFlashdata('error', 'Password salah!');
                return redirect()->to(base_url('auth/login'));
            }
        } else {
            session()->setFlashdata('error', 'Email tidak ditemukan!');
            return redirect()->to(base_url('auth/login'));
        }
    }

    // --- LOGIKA LOGOUT ---
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'));
    }
}