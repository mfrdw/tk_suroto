<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Invoice_model;
use App\Models\Pesanan_model;

class Transaksi extends BaseController
{
    public function __construct()
    {
        $this->Invoice_model = new Invoice_model();
        $this->Pesanan_model = new Pesanan_model();
    }

    public function index()
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $status = $this->request->getGet('status');
        $data = [
            'title' => 'Kelola Transaksi - Admin Toko Suroto',
            'invoice' => $this->Invoice_model->getAllInvoice($status),
            'status_filter' => $status,
        ];
        return view('admin/transaksi/index', $data);
    }

    public function detail($id_invoice)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $invoice = $this->Invoice_model->getInvoiceById($id_invoice);
        
        if (!$invoice) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invoice tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Transaksi - ' . $invoice['no_invoice'],
            'invoice' => $invoice,
            'pesanan' => $this->Invoice_model->getPesananByInvoice($id_invoice),
        ];
        return view('admin/transaksi/detail', $data);
    }

    public function update_status($id_invoice)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $status = $this->request->getPost('status');
        $valid_status = ['pending', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];
        
        if (!in_array($status, $valid_status)) {
            session()->setFlashdata('error', 'Status tidak valid.');
            return redirect()->back();
        }
        
        $this->Invoice_model->updateInvoice($id_invoice, ['status' => $status]);
        
        session()->setFlashdata('success', 'Status berhasil diperbarui.');
        return redirect()->to('/admin/transaksi');
    }
}