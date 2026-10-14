<?php

namespace App\Controllers;

use App\Models\Models_Keranjang_Wishlist;
use App\Models\Models_Produk_Kategori;
use App\Models\Models_Konfigurasi;

class Keranjang extends BaseController
{
    protected $keranjangModel;
    protected $produkModel;
    protected $konfigurasiModel;

    public function __construct()
    {
        $this->keranjangModel = new Models_Keranjang_Wishlist();
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

    private function integer($value, $min = 1)
    {
        return is_scalar($value)
            ? filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min]])
            : false;
    }

    public function index()
    {
        if ($response = $this->requireUser()) return $response;
        $id_user = session()->get('id_user');
        return view('keranjang/index', [
            'title' => 'Keranjang Belanja - Toko Suroto',
            'konfigurasi' => $this->konfigurasiModel->getKonfigurasi(),
            'keranjang' => $this->keranjangModel->getKeranjangByUser($id_user),
            'total' => $this->keranjangModel->getTotalKeranjang($id_user),
        ]);
    }

    public function tambah()
    {
        if ($response = $this->requireUser(true)) return $response;
        $id_user = session()->get('id_user');
        $id_produk = $this->integer($this->request->getPost('id_produk'));
        $qty = $this->integer($this->request->getPost('qty') ?? 1);
        $target = $this->request->getPost('redirect');
        $target = in_array($target, ['keranjang', 'wishlist', 'produk'], true) ? $target : 'keranjang';
        if ($id_produk === false || $qty === false) {
            return redirect()->to(base_url($target))->with('error', 'Produk dan jumlah harus berupa angka bulat positif.');
        }
        $produk = $this->produkModel->getProdukById($id_produk);
        if (!$produk) return redirect()->to(base_url($target))->with('error', 'Produk tidak ditemukan.');
        $stok = max(0, (int) $produk['stok']);
        $existing = $this->keranjangModel->checkProdukDiKeranjang($id_user, $id_produk);
        $oldQty = $existing ? max(0, (int) $existing['qty']) : 0;
        // Membandingkan selisih menghindari penjumlahan qty yang melebihi integer.
        if ($qty > $stok || $oldQty > $stok - $qty) {
            return redirect()->to(base_url($target))->with('error', 'Jumlah total melebihi stok yang tersedia.');
        }
        $result = $existing
            ? $this->keranjangModel->update($existing['id_keranjang'], ['qty' => $oldQty + $qty])
            : $this->keranjangModel->insert(['id_user' => $id_user, 'id_produk' => $id_produk, 'qty' => $qty]);
        if ($result === false) return redirect()->to(base_url($target))->with('error', 'Produk belum berhasil ditambahkan. Silakan coba lagi.');
        return redirect()->to(base_url($target))->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update()
    {
        if ($response = $this->requireUser(true)) return $response;
        $id = $this->integer($this->request->getPost('id_keranjang'));
        $qty = $this->integer($this->request->getPost('qty'), 0);
        if ($id === false || $qty === false) {
            return redirect()->to(base_url('keranjang'))->with('error', 'Jumlah harus berupa angka bulat, minimal 0.');
        }
        $item = $this->keranjangModel->getKeranjangById($id);
        if (!$item || (string) $item['id_user'] !== (string) session()->get('id_user')) {
            return redirect()->to(base_url('keranjang'))->with('error', 'Item tidak ditemukan.');
        }
        if ($qty === 0) {
            $result = $this->keranjangModel->delete($id);
        } else {
            $produk = $this->produkModel->getProdukById($item['id_produk']);
            if (!$produk) return redirect()->to(base_url('keranjang'))->with('error', 'Produk tidak tersedia. Silakan hapus dari keranjang.');
            if ($qty > max(0, (int) $produk['stok'])) {
                return redirect()->to(base_url('keranjang'))->with('error', 'Jumlah melebihi stok yang tersedia.');
            }
            $result = $this->keranjangModel->update($id, ['qty' => $qty]);
        }
        if ($result === false) return redirect()->to(base_url('keranjang'))->with('error', 'Perubahan belum berhasil disimpan.');
        return redirect()->to(base_url('keranjang'))->with('success', $qty === 0 ? 'Item berhasil dihapus.' : 'Jumlah produk berhasil diperbarui.');
    }

    public function hapus($id)
    {
        if ($response = $this->requireUser(true)) return $response;
        $id = $this->integer($id);
        $item = $id !== false ? $this->keranjangModel->getKeranjangById($id) : null;
        if (!$item || (string) $item['id_user'] !== (string) session()->get('id_user')) {
            return redirect()->to(base_url('keranjang'))->with('error', 'Item tidak ditemukan.');
        }
        if (!$this->keranjangModel->delete($id)) return redirect()->to(base_url('keranjang'))->with('error', 'Item belum berhasil dihapus.');
        return redirect()->to(base_url('keranjang'))->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        if ($response = $this->requireUser(true)) return $response;
        if (!$this->keranjangModel->clearKeranjang(session()->get('id_user'))) {
            return redirect()->to(base_url('keranjang'))->with('error', 'Keranjang belum berhasil dikosongkan.');
        }
        return redirect()->to(base_url('keranjang'))->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
