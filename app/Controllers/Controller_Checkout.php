<?php

namespace App\Controllers;

use App\Models\Keranjang_model;
use App\Models\Invoice_model;
use App\Models\Pesanan_model;
use App\Models\Produk_model;
use App\Models\Konfigurasi_model;

class Checkout extends BaseController
{
    public function __construct()
    {
        $this->Keranjang_model = new Keranjang_model();
        $this->Invoice_model = new Invoice_model();
        $this->Pesanan_model = new Pesanan_model();
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
        $keranjang = $this->Keranjang_model->getKeranjangByUser($id_user);
        
        if (empty($keranjang)) {
            session()->setFlashdata('error', 'Keranjang belanja masih kosong.');
            return redirect()->to('/');
        }
        
        $data = [
            'title' => 'Checkout - Toko Suroto',
            'konfigurasi' => $this->Konfigurasi_model->getKonfigurasi(),
            'keranjang' => $keranjang,
            'sub_total' => $this->Keranjang_model->getTotalKeranjang($id_user),
            'user' => $this->Invoice_model->getUserData($id_user),
        ];
        return view('checkout/index', $data);
    }

    public function proses()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $id_user = session()->get('id_user');
        $keranjang = $this->Keranjang_model->getKeranjangByUser($id_user);
        
        if (empty($keranjang)) {
            return redirect()->to('/');
        }
        
        // Generate nomor invoice
        $no_invoice = $this->Invoice_model->generateNoInvoice();
        
        // Hitung total
        $sub_total = 0;
        foreach ($keranjang as $item) {
            $sub_total += $item['harga'] * $item['qty'];
        }
        
        $ongkir = 15000; // Ongkos kirim default
        $total = $sub_total + $ongkir;
        
        // Simpan invoice
        $data_invoice = [
            'id_user' => $id_user,
            'no_invoice' => $no_invoice,
            'tanggal' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'sub_total' => $sub_total,
            'ongkir' => $ongkir,
            'total' => $total,
            'metode_bayar' => $this->request->getPost('metode_bayar'),
            'catatan' => $this->request->getPost('catatan'),
            'alamat_pengiriman' => $this->request->getPost('alamat_pengiriman'),
            'nama_penerima' => $this->request->getPost('nama_penerima'),
            'no_telp_penerima' => $this->request->getPost('no_telp_penerima'),
        ];
        
        $id_invoice = $this->Invoice_model->insertInvoice($data_invoice);
        
        // Simpan pesanan
        foreach ($keranjang as $item) {
            $data_pesanan = [
                'id_invoice' => $id_invoice,
                'id_produk' => $item['id_produk'],
                'qty' => $item['qty'],
                'harga_saat_beli' => $item['harga'],
                'subtotal' => $item['harga'] * $item['qty'],
            ];
            $this->Pesanan_model->insertPesanan($data_pesanan);
            
            // Kurangi stok
            $this->Produk_model->kurangiStok($item['id_produk'], $item['qty']);
        }
        
        // Kosongkan keranjang
        $this->Keranjang_model->clearKeranjang($id_user);
        
        session()->setFlashdata('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
        return redirect()->to('/pesanan/detail/' . $id_invoice);
    }

    public function upload_bukti()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $id_invoice = $this->request->getPost('id_invoice');
        $invoice = $this->Invoice_model->getInvoiceById($id_invoice);
        
        if (!$invoice || $invoice['id_user'] != session()->get('id_user')) {
            session()->setFlashdata('error', 'Invoice tidak ditemukan.');
            return redirect()->back();
        }
        
        $file = $this->request->getFile('bukti_bayar');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('public/uploads/bukti_bayar', $newName);
            
            $this->Invoice_model->updateInvoice($id_invoice, [
                'bukti_bayar' => $newName,
                'tanggal_bayar' => date('Y-m-d H:i:s'),
                'status' => 'dikonfirmasi',
            ]);
            
            session()->setFlashdata('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
        } else {
            session()->setFlashdata('error', 'Gagal upload bukti pembayaran.');
        }
        
        return redirect()->to('/pesanan/detail/' . $id_invoice);
    }
}