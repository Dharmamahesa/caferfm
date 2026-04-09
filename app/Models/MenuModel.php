<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    // 1. Tentukan nama tabel di database
    protected $table      = 'menu';
    
    // 2. Tentukan primary key dari tabel tersebut
    protected $primaryKey = 'id_menu';

    // 3. Tentukan format output data (bisa 'array' atau 'object')
    // Untuk skripsi ini, 'array' lebih mudah dikelola saat di-looping di View
    protected $returnType = 'array';

    // 4. (SANGAT PENTING) Tentukan kolom apa saja yang BOLEH diisi secara massal
    // Kolom id_menu tidak perlu dimasukkan karena Auto Increment
    protected $allowedFields = [
        'nama_item', 
        'kategori', 
        'harga', 
        'stok', 
        'foto', 
        'deskripsi'
    ];

    // 5. Fitur tambahan: Menangkap data berdasarkan kategori dengan mudah
    // Ini adalah custom method kita sendiri untuk merapikan Controller nanti
    public function getMenuByKategori($kategori)
    {
        return $this->where('kategori', $kategori)->findAll();
    }
}