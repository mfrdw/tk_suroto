<?php

namespace App\Controllers;

use App\Models\Keranjang_model;
use App\Models\Produk_model;
use App\Models\Konfigurasi_model;

class Keranjang extends BaseController
{
    public function __construct()
    {
        $this->Keranjang_model = new Keranjang_model();
        $this->Produk_model = new Produk_model();
        $this->Konfigurasi_model = new Konfigurasi_model();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $id_user = session()->get('id_user');
        $data = [
            'title' => 'Keranjang Belanja - Toko Suroto',
            'konfigurasi' => $this->Konfigurasi_model->getKonfigurasi(),
            'keranjang' => $this->Keranjang_model->getKeranjangByUser($id_user),
            'total' => $this->Keranjang_model->getTotalKeranjang($id_user),
        ];
        return view('keranjang/index', $data);
    }

    public function tambah()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $id_user = session()->get('id_user');
        $id_produk = $this->request->getPost('id_produk');
        $qty = $this->request->getPost('qty') ?? 1;
        
        // Cek stok
        $produk = $this->Produk_model->getProdukById($id_produk);
        if (!$produk) {
            session()->setFlashdata('error', 'Produk tidak ditemukan.');
            return redirect()->back();
        }
        
        if ($qty > $produk['stok']) {
            session()->setFlashdata('error', 'Jumlah melebihi stok yang tersedia.');
            return redirect()->back();
        }
        
        // Cek apakah produk sudah ada di keranjang
        $existing = $this->Keranjang_model->checkProdukDiKeranjang($id_user, $id_produk);
        
        if ($existing) {
            $new_qty = $existing['qty'] + $qty;
            if ($new_qty > $produk['stok']) {
                session()->setFlashdata('error', 'Jumlah total melebihi stok yang tersedia.');
                return redirect()->back();
            }
            $this->Keranjang_model->update($existing['id_keranjang'], ['qty' => $new_qty]);
        } else {
            $this->Keranjang_model->insert([
                'id_user' => $id_user,
                'id_produk' => $id_produk,
                'qty' => $qty,
            ]);
        }
        
        session()->setFlashdata('success', 'Produk berhasil ditambahkan ke keranjang.');
        
        $redirect = $this->request->getPost('redirect') ?? 'keranjang';
        return redirect()->to('/' . $redirect);
    }

    public function update()
    {
        $id_keranjang = $this->request->getPost('id_keranjang');
        $qty = $this->request->getPost('qty');
        
        $keranjang = $this->Keranjang_model->getKeranjangById($id_keranjang);
        
        if (!$keranjang || $keranjang['id_user'] != session()->get('id_user')) {
            session()->setFlashdata('error', 'Item tidak ditemukan.');
            return redirect()->back();
        }
        
        if ($qty <= 0) {
            $this->Keranjang_model->delete($id_keranjang);
        } else {
            $produk = $this->Produk_model->getProdukById($keranjang['id_produk']);
            if ($qty > $produk['stok']) {
                session()->setFlashdata('error', 'Jumlah melebihi stok yang tersedia.');
                return redirect()->back();
            }
            $this->Keranjang_model->update($id_keranjang, ['qty' => $qty]);
        }
        
        return redirect()->to('/keranjang');
    }

    public function hapus($id)
    {
        $keranjang = $this->Keranjang_model->getKeranjangById($id);
        
        if (!$keranjang || $keranjang['id_user'] != session()->get('id_user')) {
            session()->setFlashdata('error', 'Item tidak ditemukan.');
            return redirect()->back();
        }
        
        $this->Keranjang_model->delete($id);
        session()->setFlashdata('success', 'Item berhasil dihapus dari keranjang.');
        
        return redirect()->to('/keranjang');
    }

    public function clear()
    {
        $id_user = session()->get('id_user');
        $this->Keranjang_model->clearKeranjang($id_user);
        
        session()->setFlashdata('success', 'Keranjang berhasil dikosongkan.');
        return redirect()->to('/keranjang');
    }
}