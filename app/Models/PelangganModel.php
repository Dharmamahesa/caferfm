<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table      = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $allowedFields = ['nama_pelanggan', 'username', 'password', 'poin_loyalitas'];

    // Fungsi untuk mencari pelanggan di halaman Kasir/Admin
    public function cariPelanggan($keyword = null)
    {
        $builder = $this->where('id_pelanggan !=', 1); // Jangan tampilkan ID 1 (Guest)
        
        if ($keyword) {
            $builder->like('nama_pelanggan', $keyword);
        }
        
        // Tampilkan yang poinnya paling banyak di urutan atas
        return $builder->orderBy('poin_loyalitas', 'DESC')->findAll();
    }
}