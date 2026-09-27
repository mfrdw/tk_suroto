<?php

namespace App\Controllers;

use App\Models\User_model;
use App\Models\Konfigurasi_model;

class Auth extends BaseController
{
    public function __construct()
    {
        $this->User_model = new User_model();
        $this->Konfigurasi_model = new Konfigurasi_model();
        helper(['form', 'url']);
    }

    public function login()
    {
        $data = [
            'title' => 'Login - Toko Suroto',
            'konfigurasi' => $this->Konfigurasi_model->getKonfigurasi(),
        ];
        return view('auth/login', $data);
    }

    public function login_process()
    {
        $session = session();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $user = $this->User_model->where('email', $email)->first();
        
        if ($user) {
            if ($user['status'] == 'nonaktif') {
                $session->setFlashdata('error', 'Akun Anda sudah tidak aktif.');
                return redirect()->to('/auth/login');
            }
            
            $pass = $user['password'];
            $authenticatePassword = password_verify($password, $pass);
            
            if ($authenticatePassword) {
                $ses_data = [
                    'id_user' => $user['id_user'],
                    'nama' => $user['nama'],
                    'email' => $user['email'],
                    'logged_in' => TRUE,
                    'role' => 'user'
                ];
                $session->set($ses_data);
                return redirect()->to('/');
            } else {
                $session->setFlashdata('error', 'Password yang Anda masukkan salah.');
                return redirect()->to('/auth/login');
            }
        } else {
            $session->setFlashdata('error', 'Email belum terdaftar.');
            return redirect()->to('/auth/login');
        }
    }

    public function register()
    {
        $data = [
            'title' => 'Register - Toko Suroto',
            'konfigurasi' => $this->Konfigurasi_model->getKonfigurasi(),
        ];
        return view('auth/register', $data);
    }

    public function register_process()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[user.email]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'matches[password]',
            'no_telp' => 'required|min_length[10]|max_length[15]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => strtolower($this->request->getPost('email')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'no_telp' => $this->request->getPost('no_telp'),
            'alamat' => $this->request->getPost('alamat'),
            'status' => 'aktif',
        ];

        $this->User_model->insert($data);
        
        session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
        return redirect()->to('/auth/login');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}