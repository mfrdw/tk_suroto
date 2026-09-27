<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Produk_model;
use App\Models\Kategori_model;

class Produk extends BaseController
{
    public function __construct()
    {
        $this->Produk_model = new Produk_model();
        $this->Kategori_model = new Kategori_model();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $data = [
            'title' => 'Kelola Produk - Admin Toko Suroto',
            'produk' => $this->Produk_model->getAllProduk(),
        ];
        return view('admin/produk/index', $data);
    }

    public function tambah()
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $data = [
            'title' => 'Tambah Produk - Admin Toko Suroto',
            'kategori' => $this->Kategori_model->listing(),
            'validation' => \Config\Services::validation(),
        ];
        return view('admin/produk/tambah', $data);
    }

    public function simpan()
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        // Validation rules
        $rules = [
            'nama_produk' => 'required|min_length[3]|max_length[200]',
            'id_kategori' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'deskripsi' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', \Config\Services::validation()->getErrors());
        }

        // Handle upload gambar
        $gambar = 'default.jpg';
        $file = $this->request->getFile('gambar');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('public/uploads/produk', $newName);
            $gambar = $newName;
        }

        $data = [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama_produk' => $this->request->getPost('nama_produk'),
            'slug_produk' => url_title($this->request->getPost('nama_produk'), '-', true),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
            'satuan' => $this->request->getPost('satuan') ?? 'pcs',
            'berat' => $this->request->getPost('berat') ?? 0,
            'gambar' => $gambar,
            'is_aktif' => $this->request->getPost('is_aktif') ?? 'ya',
        ];

        $this->Produk_model->insert($data);
        
        session()->setFlashdata('success', 'Produk berhasil ditambahkan.');
        return redirect()->to('/admin/produk');
    }

    public function edit($id)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $produk = $this->Produk_model->getProdukById($id);
        
        if (!$produk) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Produk - Admin Toko Suroto',
            'produk' => $produk,
            'kategori' => $this->Kategori_model->listing(),
            'validation' => \Config\Services::validation(),
        ];
        return view('admin/produk/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $rules = [
            'nama_produk' => 'required|min_length[3]|max_length[200]',
            'id_kategori' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', \Config\Services::validation()->getErrors());
        }

        $gambar = $this->request->getPost('gambar_lama');
        $file = $this->request->getFile('gambar');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('public/uploads/produk', $newName);
            $gambar = $newName;
            
            // Hapus gambar lama
            $old_file = 'public/uploads/produk/' . $this->request->getPost('gambar_lama');
            if (file_exists($old_file) && $this->request->getPost('gambar_lama') != 'default.jpg') {
                unlink($old_file);
            }
        }

        $data = [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama_produk' => $this->request->getPost('nama_produk'),
            'slug_produk' => url_title($this->request->getPost('nama_produk'), '-', true),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
            'satuan' => $this->request->getPost('satuan') ?? 'pcs',
            'berat' => $this->request->getPost('berat') ?? 0,
            'gambar' => $gambar,
            'is_aktif' => $this->request->getPost('is_aktif') ?? 'ya',
        ];

        $this->Produk_model->updateProduk($id, $data);
        
        session()->setFlashdata('success', 'Produk berhasil diperbarui.');
        return redirect()->to('/admin/produk');
    }

    public function hapus($id)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $produk = $this->Produk_model->getProdukById($id);
        
        if ($produk && $produk['gambar'] != 'default.jpg') {
            $file_path = 'public/uploads/produk/' . $produk['gambar'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $this->Produk_model->deleteProduk($id);
        
        session()->setFlashdata('success', 'Produk berhasil dihapus.');
        return redirect()->to('/admin/produk');
    }
}