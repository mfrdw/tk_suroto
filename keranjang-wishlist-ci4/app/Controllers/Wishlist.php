<?php

namespace App\Controllers;

use App\Models\Models_Keranjang_Wishlist;
use App\Models\Models_Produk_Kategori;
use App\Models\Models_Konfigurasi;

class Wishlist extends BaseController
{
    protected $wishlistModel;
    protected $produkModel;
    protected $konfigurasiModel;

    public function __construct()
    {
        $this->wishlistModel = new Models_Keranjang_Wishlist();
        $this->produkModel = new Models_Produk_Kategori();
        $this->konfigurasiModel = new Models_Konfigurasi();
        helper(['form', 'url']);
    }

    private function requireUser($postOnly = false)
    {
        if (!session()->get('logged_in') || !session()->get('id_user')) {
            return redirect()->to(base_url('auth/login'));
        }
        if ($postOnly && strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setStatusCode(405)->setHeader('Allow', 'POST');
        }
        return null;
    }

    public function index()
    {
        if ($response = $this->requireUser()) return $response;
        return view('wishlist/index', [
            'title' => 'Wishlist - Toko Suroto',
            'konfigurasi' => $this->konfigurasiModel->getKonfigurasi(),
            'wishlist' => $this->wishlistModel->getWishlistByUser(session()->get('id_user')),
        ]);
    }

    public function tambah($id_produk)
    {
        if ($response = $this->requireUser(true)) return $response;
        $id_produk = filter_var($id_produk, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id_produk || !$this->produkModel->getProdukById($id_produk)) {
            return redirect()->to(base_url('wishlist'))->with('error', 'Produk tidak ditemukan.');
        }
        $id_user = session()->get('id_user');
        if ($this->wishlistModel->checkProdukDiWishlist($id_user, $id_produk)) {
            return redirect()->to(base_url('wishlist'))->with('info', 'Produk sudah ada di wishlist.');
        }
        // Jangan gunakan insert() di sini: tabel default model gabungan adalah keranjang.
        if (!$this->wishlistModel->tambahWishlist($id_user, $id_produk)) {
            return redirect()->to(base_url('wishlist'))->with('error', 'Produk belum berhasil disimpan. Silakan coba lagi.');
        }
        return redirect()->to(base_url('wishlist'))->with('success', 'Produk berhasil ditambahkan ke wishlist.');
    }

    public function hapus($id)
    {
        if ($response = $this->requireUser(true)) return $response;
        $id = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $item = $id ? $this->wishlistModel->getWishlistById($id) : null;
        $id_user = session()->get('id_user');
        if (!$item || (string) $item['id_user'] !== (string) $id_user) {
            return redirect()->to(base_url('wishlist'))->with('error', 'Item tidak ditemukan.');
        }
        if (!$this->wishlistModel->hapusWishlist($id, $id_user)) {
            return redirect()->to(base_url('wishlist'))->with('error', 'Item belum berhasil dihapus. Silakan coba lagi.');
        }
        return redirect()->to(base_url('wishlist'))->with('success', 'Produk berhasil dihapus dari wishlist.');
    }
}
