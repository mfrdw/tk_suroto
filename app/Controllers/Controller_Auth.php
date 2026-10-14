<?php

namespace App\Controllers;

use App\Models\Models_User;
use App\Models\Models_Konfigurasi;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Controller_Auth extends BaseController
{
    protected $userModel;
    protected $konfigurasiModel;

    public function __construct()
    {
        $this->userModel = new Models_User();
        $this->konfigurasiModel = new Models_Konfigurasi();
        helper(['form', 'url']);
    }

    private function isLoggedIn()
    {
        return session()->get('logged_in') && session()->get('id_user');
    }

    private function postOnly()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setStatusCode(405)->setHeader('Allow', 'POST');
        }
        return null;
    }

    private function postString($field, $trim = true)
    {
        $value = $this->request->getPost($field);
        if (!is_string($value)) return '';
        return $trim ? trim($value) : $value;
    }

    private function formData($title)
    {
        return [
            'title' => $title . ' - Toko Suroto',
            'konfigurasi' => $this->konfigurasiModel->getKonfigurasi(),
        ];
    }

    private function formError($page, $errors, $old = [])
    {
        // Hanya field non-rahasia yang masuk flashdata. Password tidak disimpan.
        return redirect()->to(base_url('auth/' . $page))
            ->with('auth_errors', $errors)->with('auth_old', $old);
    }

    private function allowAttempt($action, $capacity, $seconds)
    {
        $key = 'auth-' . $action . '-' . hash('sha256', $this->request->getIPAddress());
        return service('throttler')->check($key, $capacity, $seconds);
    }

    public function login()
    {
        if ($this->isLoggedIn()) return redirect()->to(base_url('keranjang'));
        return view('auth/login', $this->formData('Masuk'));
    }

    public function login_process()
    {
        if ($response = $this->postOnly()) return $response;
        if ($this->isLoggedIn()) return redirect()->to(base_url('keranjang'));
        $email = strtolower($this->postString('email'));
        $password = $this->postString('password', false);
        $old = ['email' => substr($email, 0, 254)];
        if (!$this->allowAttempt('login', 10, 60)) {
            return $this->formError('login', ['Terlalu banyak percobaan masuk. Tunggu sebentar lalu coba lagi.'], $old);
        }
        if (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '' || strlen($password) > 4096) {
            return $this->formError('login', ['Isi email yang valid dan password.'], $old);
        }
        $user = $this->userModel->getUserByEmail($email);
        // Hash dummy menyamakan proses verifikasi ketika email belum terdaftar.
        $hash = $user['password'] ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi';
        $validPassword = password_verify($password, $hash);
        if (!$user || !$validPassword || ($user['status'] ?? '') !== 'aktif') {
            return $this->formError('login', ['Email atau password tidak sesuai, atau akun tidak aktif.'], $old);
        }
        $session = session();
        $session->regenerate(true);
        $session->set([
            'id_user' => $user['id_user'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'logged_in' => true,
            'role' => 'user',
        ]);
        return redirect()->to(base_url('keranjang'))->with('success', 'Berhasil masuk. Selamat berbelanja!');
    }

    public function register()
    {
        if ($this->isLoggedIn()) return redirect()->to(base_url('keranjang'));
        return view('auth/register', $this->formData('Daftar Akun'));
    }

    public function register_process()
    {
        if ($response = $this->postOnly()) return $response;
        if ($this->isLoggedIn()) return redirect()->to(base_url('keranjang'));
        $input = [
            'nama' => $this->postString('nama'),
            'email' => strtolower($this->postString('email')),
            'no_telp' => $this->postString('no_telp'),
            'alamat' => $this->postString('alamat'),
            'password' => $this->postString('password', false),
            'password_confirm' => $this->postString('password_confirm', false),
        ];
        $old = [
            'nama' => substr($input['nama'], 0, 400),
            'email' => substr($input['email'], 0, 254),
            'no_telp' => substr($input['no_telp'], 0, 30),
            'alamat' => substr($input['alamat'], 0, 2000),
        ];
        if (!$this->allowAttempt('register', 5, 300)) {
            return $this->formError('register', ['Terlalu banyak percobaan daftar. Tunggu beberapa saat lalu coba lagi.'], $old);
        }
        $validation = service('validation');
        $validation->reset();
        $validation->setRules([
            'nama' => ['label' => 'Nama lengkap', 'rules' => 'required|min_length[3]|max_length[100]', 'errors' => ['required' => 'Nama lengkap wajib diisi.', 'min_length' => 'Nama minimal 3 karakter.', 'max_length' => 'Nama maksimal 100 karakter.']],
            'email' => ['label' => 'Email', 'rules' => 'required|valid_email|max_length[254]|is_unique[user.email]', 'errors' => ['required' => 'Email wajib diisi.', 'valid_email' => 'Format email tidak valid.', 'max_length' => 'Email terlalu panjang.', 'is_unique' => 'Email sudah terdaftar. Silakan masuk.']],
            'no_telp' => ['label' => 'Nomor telepon', 'rules' => 'required|regex_match[/^\+?[0-9]{10,15}$/]', 'errors' => ['required' => 'Nomor telepon wajib diisi.', 'regex_match' => 'Nomor telepon harus 10–15 digit; boleh diawali +.']],
            'alamat' => ['label' => 'Alamat', 'rules' => 'permit_empty|max_length[500]', 'errors' => ['max_length' => 'Alamat maksimal 500 karakter.']],
            'password' => ['label' => 'Password', 'rules' => 'required|min_length[8]|max_length[72]', 'errors' => ['required' => 'Password wajib diisi.', 'min_length' => 'Password minimal 8 karakter.', 'max_length' => 'Password maksimal 72 karakter.']],
            'password_confirm' => ['label' => 'Konfirmasi password', 'rules' => 'required|matches[password]', 'errors' => ['required' => 'Konfirmasi password wajib diisi.', 'matches' => 'Konfirmasi password belum sama.']],
        ]);
        if (!$validation->run($input)) {
            return $this->formError('register', $validation->getErrors(), $old);
        }
        // Bcrypt dibatasi 72 byte, termasuk jika password memakai karakter multibyte.
        if (strlen($input['password']) > 72 || strpos($input['password'], "\0") !== false) {
            return $this->formError('register', ['Password terlalu panjang atau memuat karakter yang tidak didukung.'], $old);
        }
        $data = [
            'nama' => $input['nama'],
            'email' => $input['email'],
            'no_telp' => $input['no_telp'],
            'alamat' => $input['alamat'],
            'password' => password_hash($input['password'], PASSWORD_BCRYPT),
            'status' => 'aktif',
        ];
        try {
            $created = $this->userModel->insertUser($data);
        } catch (DatabaseException $exception) {
            // Jangan mem-flash atau mencatat query INSERT yang berisi data akun.
            return $this->formError('register', ['Akun belum berhasil dibuat. Periksa email atau coba lagi.'], $old);
        }
        if ($created === false) {
            return $this->formError('register', ['Akun belum berhasil dibuat. Periksa data atau coba lagi.'], $old);
        }
        return redirect()->to(base_url('auth/login'))
            ->with('success', 'Registrasi berhasil! Silakan masuk menggunakan akun baru kamu.')
            ->with('auth_old', ['email' => $input['email']]);
    }

    public function logout()
    {
        // Endpoint GET mengikuti group route yang digunakan project.
        session()->destroy();
        return redirect()->to(base_url('auth/login'));
    }
}