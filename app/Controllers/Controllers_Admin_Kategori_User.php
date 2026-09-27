<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Kategori_model;

class Kategori extends BaseController
{
    public function __construct()
    {
        $this->Kategori_model = new Kategori_model();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $data = [
            'title' => 'Kelola Kategori - Admin Toko Suroto',
            'kategori' => $this->Kategori_model->listing(),
        ];
        return view('admin/kategori/index', $data);
    }

    public function tambah()
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $data = [
            'title' => 'Tambah Kategori - Admin Toko Suroto',
            'validation' => \Config\Services::validation(),
        ];
        return view('admin/kategori/tambah', $data);
    }

    public function simpan()
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $rules = [
            'nama_kategori' => 'required|min_length[2]|max_length[100]',
            'deskripsi' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', \Config\Services::validation()->getErrors());
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'slug_kategori' => url_title($this->request->getPost('nama_kategori'), '-', true),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        $this->Kategori_model->insert($data);
        
        session()->setFlashdata('success', 'Kategori berhasil ditambahkan.');
        return redirect()->to('/admin/kategori');
    }

    public function edit($id)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $kategori = $this->Kategori_model->getKategoriById($id);
        
        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Kategori - Admin Toko Suroto',
            'kategori' => $kategori,
            'validation' => \Config\Services::validation(),
        ];
        return view('admin/kategori/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $rules = [
            'nama_kategori' => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', \Config\Services::validation()->getErrors());
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'slug_kategori' => url_title($this->request->getPost('nama_kategori'), '-', true),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        $this->Kategori_model->updateKategori($id, $data);
        
        session()->setFlashdata('success', 'Kategori berhasil diperbarui.');
        return redirect()->to('/admin/kategori');
    }

    public function hapus($id)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $this->Kategori_model->deleteKategori($id);
        
        session()->setFlashdata('success', 'Kategori berhasil dihapus.');
        return redirect()->to('/admin/kategori');
    }
}

---

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\User_model;

class User extends BaseController
{
    public function __construct()
    {
        $this->User_model = new User_model();
    }

    public function index()
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $data = [
            'title' => 'Kelola Member - Admin Toko Suroto',
            'user' => $this->User_model->getAllUser(),
        ];
        return view('admin/user/index', $data);
    }

    public function detail($id)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $user = $this->User_model->getUserById($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Member - ' . $user['nama'],
            'user' => $user,
            'invoice' => $this->User_model->getUserInvoice($id),
        ];
        return view('admin/user/detail', $data);
    }

    public function update_status($id)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $status = $this->request->getPost('status');
        $valid_status = ['aktif', 'nonaktif'];
        
        if (!in_array($status, $valid_status)) {
            session()->setFlashdata('error', 'Status tidak valid.');
            return redirect()->back();
        }
        
        $this->User_model->updateUser($id, ['status' => $status]);
        
        session()->setFlashdata('success', 'Status member berhasil diperbarui.');
        return redirect()->to('/admin/user');
    }

    public function hapus($id)
    {
        if (!session()->get('logged_in_admin')) {
            return redirect()->to('/admin/login');
        }
        
        $this->User_model->deleteUser($id);
        
        session()->setFlashdata('success', 'Member berhasil dihapus.');
        return redirect()->to('/admin/user');
    }
}