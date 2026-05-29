<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isAdminLoggedIn')) {
            session()->setFlashdata('error', 'Akses Terlarang! Silakan login sebagai Admin.');
            return redirect()->to(base_url('admin/login'));
        }

        // Jika admin_role belum ada di session (misal: login sebelum fitur role ditambahkan),
        // ambil langsung dari database dan simpan ke session sekarang.
        if (!session()->get('admin_role')) {
            $idAdmin = session()->get('id_admin');
            if ($idAdmin) {
                $db    = \Config\Database::connect();
                // Cek apakah kolom role sudah ada
                if ($db->fieldExists('role', 'admin')) {
                    $admin = $db->table('admin')
                                ->select('role')
                                ->where('id_admin', $idAdmin)
                                ->get()
                                ->getRowArray();
                    if ($admin) {
                        session()->set('admin_role', $admin['role']);
                    }
                } else {
                    // Kolom role belum ada (belum migrate), anggap super_admin agar tidak lockout
                    session()->set('admin_role', 'super_admin');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}