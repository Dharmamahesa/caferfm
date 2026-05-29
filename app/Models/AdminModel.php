<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'admin';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['username', 'password', 'nama_admin', 'email', 'role', 'is_active'];

    /**
     * Ambil semua admin yang aktif, diurutkan berdasarkan role
     */
    public function getAllAdmin()
    {
        return $this->select('id_admin, username, nama_admin, email, role, is_active')
                    ->orderBy('FIELD(role, "super_admin", "manajer", "kasir", "koki")')
                    ->findAll();
    }

    /**
     * Cek apakah username sudah dipakai (opsional: kecualikan id tertentu saat edit)
     */
    public function isUsernameTaken($username, $exceptId = null)
    {
        $builder = $this->where('username', $username);
        if ($exceptId) {
            $builder->where('id_admin !=', $exceptId);
        }
        return $builder->countAllResults() > 0;
    }

    /**
     * Label dan warna badge untuk tiap role
     */
    public static function getRoleLabel($role)
    {
        $labels = [
            'super_admin' => ['label' => 'Super Admin', 'color' => 'purple'],
            'manajer'     => ['label' => 'Manajer',     'color' => 'blue'],
            'kasir'       => ['label' => 'Kasir',       'color' => 'green'],
            'koki'        => ['label' => 'Koki',        'color' => 'orange'],
        ];
        return $labels[$role] ?? ['label' => $role, 'color' => 'gray'];
    }
}