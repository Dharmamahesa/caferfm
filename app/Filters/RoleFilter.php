<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RoleFilter
 * 
 * Digunakan di Routes.php dengan sintaks:
 * ['filter' => 'roleGuard:super_admin,manajer']
 * 
 * Filter ini HANYA bekerja jika admin sudah login (adminGuard sudah dijalankan lebih dulu).
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika tidak ada argumen role, lewati (akses bebas bagi semua admin)
        if (empty($arguments)) {
            return;
        }

        $currentRole = session()->get('admin_role');

        // Jika role sesi tidak ada dalam daftar yang diizinkan
        if (!in_array($currentRole, $arguments)) {
            session()->setFlashdata('error', 'Akses Ditolak! Anda tidak memiliki izin untuk halaman tersebut.');

            // Redirect ke halaman sesuai role mereka
            $redirectMap = [
                'koki'  => base_url('admin/dapur'),
                'kasir' => base_url('admin/kasir'),
            ];

            $redirectTo = $redirectMap[$currentRole] ?? base_url('admin/dashboard');
            return redirect()->to($redirectTo);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
