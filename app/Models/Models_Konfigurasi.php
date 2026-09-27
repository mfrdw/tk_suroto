<?php

namespace App\Models;

use CodeIgniter\Model;

class Models_Konfigurasi extends Model
{
    protected $table = 'konfigurasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_toko', 'logo', 'alamat', 'no_telp', 'email', 'deskripsi'];

    public function getKonfigurasi()
    {
        return $this->first();
    }

    public function updateKonfigurasi($id, $data)
    {
        return $this->update($id, $data);
    }

    public function getLogo()
    {
        $config = $this->first();
        return $config['logo'] ?? 'logo.png';
    }

    public function getNamaToko()
    {
        $config = $this->first();
        return $config['nama_toko'] ?? 'Toko Suroto';
    }
}