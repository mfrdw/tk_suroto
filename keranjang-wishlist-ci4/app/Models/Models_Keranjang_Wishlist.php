<?php

namespace App\Models;

use CodeIgniter\Model;

class Models_Keranjang_Wishlist extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';
    protected $returnType = 'array';
    protected $allowedFields = ['id_user', 'id_produk', 'qty'];

    public function getKeranjangByUser($id_user)
    {
        return $this->db->table('keranjang as k')
            ->select('p.*, k.*, k2.nama_kategori')
            ->join('produk as p', 'p.id_produk = k.id_produk', 'left')
            ->join('kategori as k2', 'k2.id_kategori = p.id_kategori', 'left')
            ->where('k.id_user', $id_user)
            ->get()->getResultArray();
    }

    public function getKeranjangById($id)
    {
        return $this->find($id);
    }

    public function checkProdukDiKeranjang($id_user, $id_produk)
    {
        return $this->where('id_user', $id_user)->where('id_produk', $id_produk)->first();
    }

    public function getTotalKeranjang($id_user)
    {
        $result = $this->db->table('keranjang as k')
            ->join('produk as p', 'p.id_produk = k.id_produk', 'left')
            // Ekspresi tetap, tidak berisi input pengguna.
            ->select('COALESCE(SUM(p.harga * k.qty), 0) AS total', false)
            ->where('k.id_user', $id_user)
            ->get()->getRowArray();
        return $result['total'] ?? 0;
    }

    public function countItemKeranjang($id_user)
    {
        return $this->where('id_user', $id_user)->countAllResults();
    }

    public function clearKeranjang($id_user)
    {
        return $this->where('id_user', $id_user)->delete();
    }

    // insert(), update(), delete() menggunakan implementasi bawaan Model CI4.

    // Method wishlist harus menggunakan tabel wishlist secara eksplisit.
    public function getWishlistByUser($id_user)
    {
        return $this->db->table('wishlist as w')
            ->select('p.*, w.*, k.nama_kategori')
            ->join('produk as p', 'p.id_produk = w.id_produk', 'left')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->where('w.id_user', $id_user)
            // Tidak mengharuskan adanya kolom created_at.
            ->orderBy('w.id_wishlist', 'DESC')
            ->get()->getResultArray();
    }

    public function getWishlistById($id)
    {
        return $this->db->table('wishlist')->where('id_wishlist', $id)->get()->getRowArray();
    }

    public function checkProdukDiWishlist($id_user, $id_produk)
    {
        return $this->db->table('wishlist')->where('id_user', $id_user)->where('id_produk', $id_produk)->get()->getRowArray();
    }

    public function countWishlist($id_user)
    {
        return $this->db->table('wishlist')->where('id_user', $id_user)->countAllResults();
    }

    public function tambahWishlist($id_user, $id_produk)
    {
        return $this->db->table('wishlist')->insert([
            'id_user' => $id_user,
            'id_produk' => $id_produk,
        ]);
    }

    public function hapusWishlist($id, $id_user)
    {
        return $this->db->table('wishlist')
            ->where('id_wishlist', $id)->where('id_user', $id_user)->delete();
    }
}
