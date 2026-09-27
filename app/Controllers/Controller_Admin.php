<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin_model;
use App\Models\Invoice_model;
use App\Models\Produk_model;
use App\Models\User_model;

class Auth_Admin extends BaseController
{
    public function __construct()
    {
        $this->Admin_model = new Admin_model();
    }

    public function login()
    {
        $data['title'] = 'Login Admin - Toko Suroto';
        return view('admin/auth/login', $data);
    }

    public function login_process()
    {
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $admin = $this->Admin_model->where('username', $username)->first();
        
        if ($admin) {
            $pass = $admin['password'];
            $authenticatePassword = password_verify($password, $pass);
            
            if ($authenticatePassword) {
                $ses_data = [
                    'id_admin' => $admin['id_admin'],
                    'nama_admin' => $admin['nama_admin'],
                    'username' => $admin['username'],
                    'logged_in_admin' => TRUE,
                    'role' => 'admin'
                ];
                $session->set($ses_data);
                return redirect()->to('/admin/dashboard');
            } else {
                $session->setFlashdata('error', 'Password yang Anda masukkan salah.');
                return redirect()->to('/admin/login');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to('/admin/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/admin/login');
    }
}