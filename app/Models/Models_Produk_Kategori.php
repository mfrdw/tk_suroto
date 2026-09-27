<?php

namespace App\Models;

use CodeIgniter\Model;

class Models_Produk_Kategori extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $allowedFields = ['id_kategori', 'nama_produk', 'slug_produk', 'deskripsi', 'harga', 'stok', 'satuan', 'gambar', 'berat', 'is_aktif'];

    public function getAllProduk()
    {
        $db = \Config\Database::connect();
        return $db->table('produk as p')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->orderBy('p.created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function getProdukById($id)
    {
        $db = \Config\Database::connect();
        return $db->table('produk as p')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->where('p.id_produk', $id)
            ->get()->getRowArray();
    }

    public function getProdukBySlug($slug)
    {
        $db = \Config\Database::connect();
        return $db->table('produk as p')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->where('p.slug_produk', $slug)
            ->get()->getRowArray();
    }

    public function getProdukTerbaru($limit = 10)
    {
        $db = \Config\Database::connect();
        return $db->table('produk as p')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->where('p.is_aktif', 'ya')
            ->orderBy('p.created_at', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    public function getProdukRandom($limit = 4)
    {
        $db = \Config\Database::connect();
        return $db->table('produk as p')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->where('p.is_aktif', 'ya')
            ->orderBy('RAND()')
            ->limit($limit)
            ->get()->getResultArray();
    }

    public function getProdukRelated($limit = 4, $exclude_id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('produk as p')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->where('p.is_aktif', 'ya');
        
        if ($exclude_id) {
            $builder->where('p.id_produk !=', $exclude_id);
        }
        
        return $builder->orderBy('RAND()')->limit($limit)->get()->getResultArray();
    }

    public function getProdukFilter($kategori_id = null, $keyword = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('produk as p')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->where('p.is_aktif', 'ya');

        if ($kategori_id) {
            $builder->where('p.id_kategori', $kategori_id);
        }

        if ($keyword) {
            $builder->like('p.nama_produk', $keyword);
        }

        return $builder->orderBy('p.nama_produk', 'ASC')->get()->getResultArray();
    }

    public function insertProduk($data)
    {
        return $this->insert($data);
    }

    public function updateProduk($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteProduk($id)
    {
        return $this->delete($id);
    }

    public function countAll()
    {
        return $this->countAllResults();
    }

    public function kurangiStok($id_produk, $qty)
    {
        $produk = $this->find($id_produk);
        $new_stok = $produk['stok'] - $qty;
        return $this->update($id_produk, ['stok' => $new_stok]);
    }

    public function tambahStok($id_produk, $qty)
    {
        $produk = $this->find($id_produk);
        $new_stok = $produk['stok'] + $qty;
        return $this->update($id_produk, ['stok' => $new_stok]);
    }
}