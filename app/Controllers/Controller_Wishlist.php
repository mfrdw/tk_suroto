<?php

namespace App\Controllers;

use App\Models\Wishlist_model;
use App\Models\Konfigurasi_model;

class Wishlist extends BaseController
{
    public function __construct()
    {
        $this->Wishlist_model = new Wishlist_model();
        $this->Konfigurasi_model = new Konfigurasi_model();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $id_user = session()->get('id_user');
        $data = [
            'title' => 'Wishlist - Toko Suroto',
            'konfigurasi' => $this->Konfigurasi_model->getKonfigurasi(),
            'wishlist' => $this->Wishlist_model->getWishlistByUser($id_user),
        ];
        return view('wishlist/index', $data);
    }

    public function tambah($id_produk)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $id_user = session()->get('id_user');
        
        // Cek apakah sudah ada di wishlist
        $existing = $this->Wishlist_model->checkProdukDiWishlist($id_user, $id_produk);
        
        if ($existing) {
            session()->setFlashdata('info', 'Produk sudah ada di wishlist.');
        } else {
            $this->Wishlist_model->insert([
                'id_user' => $id_user,
                'id_produk' => $id_produk,
            ]);
            session()->setFlashdata('success', 'Produk berhasil ditambahkan ke wishlist.');
        }
        
        return redirect()->back();
    }

    public function hapus($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $id_user = session()->get('id_user');
        $wishlist = $this->Wishlist_model->getWishlistById($id);
        
        if (!$wishlist || $wishlist['id_user'] != $id_user) {
            session()->setFlashdata('error', 'Item tidak ditemukan.');
            return redirect()->back();
        }
        
        $this->Wishlist_model->delete($id);
        session()->setFlashdata('success', 'Produk berhasil dihapus dari wishlist.');
        
        return redirect()->to('/wishlist');
    }
}