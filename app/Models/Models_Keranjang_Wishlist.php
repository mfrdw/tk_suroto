<?php

namespace App\Models;

use CodeIgniter\Model;

class Keranjang_model extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';
    protected $allowedFields = ['id_user', 'id_produk', 'qty'];

    public function getKeranjangByUser($id_user)
    {
        $db = \Config\Database::connect();
        return $db->table('keranjang as k')
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
        return $this->where('id_user', $id_user)
            ->where('id_produk', $id_produk)
            ->first();
    }

    public function getTotalKeranjang($id_user)
    {
        $db = \Config\Database::connect();
        $result = $db->table('keranjang as k')
            ->join('produk as p', 'p.id_produk = k.id_produk', 'left')
            ->selectSum('p.harga * k.qty', 'total')
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

    public function insert($data)
    {
        return parent::insert($data);
    }

    public function update($id, $data)
    {
        return parent::update($id, $data);
    }

    public function delete($id)
    {
        return parent::delete($id);
    }
}

---

<?php

namespace App\Models;

use CodeIgniter\Model;

class Wishlist_model extends Model
{
    protected $table = 'wishlist';
    protected $primaryKey = 'id_wishlist';
    protected $allowedFields = ['id_user', 'id_produk'];

    public function getWishlistByUser($id_user)
    {
        $db = \Config\Database::connect();
        return $db->table('wishlist as w')
            ->join('produk as p', 'p.id_produk = w.id_produk', 'left')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->where('w.id_user', $id_user)
            ->orderBy('w.created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function getWishlistById($id)
    {
        return $this->find($id);
    }

    public function checkProdukDiWishlist($id_user, $id_produk)
    {
        return $this->where('id_user', $id_user)
            ->where('id_produk', $id_produk)
            ->first();
    }

    public function countWishlist($id_user)
    {
        return $this->where('id_user', $id_user)->countAllResults();
    }

    public function insert($data)
    {
        return parent::insert($data);
    }

    public function delete($id)
    {
        return parent::delete($id);
    }
}