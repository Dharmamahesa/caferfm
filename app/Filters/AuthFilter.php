<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika tidak ada session 'isLoggedIn' (artinya belum login)
        if (!session()->get('isLoggedIn')) {
            // Beri pesan error sementara
            session()->setFlashdata('error', 'Akses ditolak! Silakan login untuk melihat profil dan poin loyalitas.');
            
            // Tendang kembali ke halaman login
            return redirect()->to(base_url('auth/login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan saja, kita tidak perlu melakukan apa-apa setelah halaman dimuat
    }
}