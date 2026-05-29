<?php

namespace App\Controllers;

use App\Models\AdminModel;

class UserAdminController extends BaseController
{
    /**
     * Daftar semua akun admin
     */
    public function index()
    {
        $adminModel = new AdminModel();
        $data = [
            'title'      => 'Kelola Akun Admin',
            'listAdmin'  => $adminModel->getAllAdmin(),
        ];
        return view('admin/v_user_admin_index', $data);
    }

    /**
     * Form tambah admin baru
     */
    public function tambah()
    {
        $data = ['title' => 'Tambah Akun Admin'];
        return view('admin/v_user_admin_form', $data);
    }

    /**
     * Proses simpan admin baru
     */
    public function simpan()
    {
        $adminModel = new AdminModel();
        $username   = $this->request->getPost('username');
        $password   = $this->request->getPost('password');

        // Validasi
        if (empty($username) || empty($password)) {
            session()->setFlashdata('error', 'Username dan Password wajib diisi!');
            return redirect()->back()->withInput();
        }

        if (strlen($password) < 6) {
            session()->setFlashdata('error', 'Password minimal 6 karakter!');
            return redirect()->back()->withInput();
        }

        if ($adminModel->isUsernameTaken($username)) {
            session()->setFlashdata('error', 'Username sudah digunakan, pilih username lain.');
            return redirect()->back()->withInput();
        }

        $adminModel->insert([
            'username'   => $username,
            'password'   => password_hash($password, PASSWORD_BCRYPT),
            'nama_admin' => $this->request->getPost('nama_admin'),
            'email'      => $this->request->getPost('email'),
            'role'       => $this->request->getPost('role') ?? 'kasir',
            'is_active'  => 1,
        ]);

        session()->setFlashdata('sukses', 'Akun admin baru berhasil dibuat!');
        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Form edit data admin
     */
    public function edit($id)
    {
        $adminModel = new AdminModel();
        $admin = $adminModel->find($id);

        if (!$admin) {
            session()->setFlashdata('error', 'Admin tidak ditemukan.');
            return redirect()->to(base_url('admin/users'));
        }

        // Super admin tidak bisa diedit oleh admin lain (hanya oleh dirinya sendiri)
        $currentId = session()->get('id_admin');
        if ($admin['role'] === 'super_admin' && $admin['id_admin'] != $currentId) {
            session()->setFlashdata('error', 'Akun Super Admin hanya bisa diedit oleh pemiliknya sendiri.');
            return redirect()->to(base_url('admin/users'));
        }

        $data = [
            'title' => 'Edit Akun Admin',
            'admin' => $admin,
        ];
        return view('admin/v_user_admin_form', $data);
    }

    /**
     * Proses update data admin
     */
    public function update($id)
    {
        $adminModel = new AdminModel();
        $admin = $adminModel->find($id);

        if (!$admin) {
            session()->setFlashdata('error', 'Admin tidak ditemukan.');
            return redirect()->to(base_url('admin/users'));
        }

        $username = $this->request->getPost('username');

        if ($adminModel->isUsernameTaken($username, $id)) {
            session()->setFlashdata('error', 'Username sudah digunakan, pilih username lain.');
            return redirect()->back()->withInput();
        }

        $updateData = [
            'username'   => $username,
            'nama_admin' => $this->request->getPost('nama_admin'),
            'email'      => $this->request->getPost('email'),
            'role'       => $this->request->getPost('role') ?? $admin['role'],
            'is_active'  => $this->request->getPost('is_active') ?? 1,
        ];

        // Hanya update password jika diisi
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                session()->setFlashdata('error', 'Password baru minimal 6 karakter!');
                return redirect()->back()->withInput();
            }
            $updateData['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $adminModel->update($id, $updateData);

        session()->setFlashdata('sukses', 'Data akun admin berhasil diperbarui!');
        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Toggle status aktif/nonaktif akun admin
     */
    public function toggle_aktif($id)
    {
        $adminModel = new AdminModel();
        $admin = $adminModel->find($id);

        if (!$admin) {
            session()->setFlashdata('error', 'Admin tidak ditemukan.');
            return redirect()->to(base_url('admin/users'));
        }

        // Proteksi: jangan sampai Super Admin menonaktifkan dirinya sendiri
        $currentId = session()->get('id_admin');
        if ($admin['id_admin'] == $currentId) {
            session()->setFlashdata('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri!');
            return redirect()->to(base_url('admin/users'));
        }

        $newStatus = $admin['is_active'] == 1 ? 0 : 1;
        $adminModel->update($id, ['is_active' => $newStatus]);

        $pesan = $newStatus == 1 ? 'diaktifkan' : 'dinonaktifkan';
        session()->setFlashdata('sukses', "Akun '{$admin['nama_admin']}' berhasil $pesan.");
        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Hapus akun admin
     */
    public function hapus($id)
    {
        $adminModel = new AdminModel();
        $admin = $adminModel->find($id);

        if (!$admin) {
            session()->setFlashdata('error', 'Admin tidak ditemukan.');
            return redirect()->to(base_url('admin/users'));
        }

        // Proteksi: jangan sampai menghapus diri sendiri
        $currentId = session()->get('id_admin');
        if ($admin['id_admin'] == $currentId) {
            session()->setFlashdata('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
            return redirect()->to(base_url('admin/users'));
        }

        // Proteksi: jangan hapus Super Admin lain sembarangan
        if ($admin['role'] === 'super_admin') {
            session()->setFlashdata('error', 'Akun Super Admin tidak bisa dihapus melalui panel ini.');
            return redirect()->to(base_url('admin/users'));
        }

        $adminModel->delete($id);
        session()->setFlashdata('sukses', "Akun '{$admin['nama_admin']}' berhasil dihapus.");
        return redirect()->to(base_url('admin/users'));
    }
}
