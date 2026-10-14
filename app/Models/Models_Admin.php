<?php

namespace App\Models;

use CodeIgniter\Model;

class Models_Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['nama_admin', 'username', 'password', 'email', 'no_telp', 'foto'];

    public function verifyLogin($username, $password)
    {
        $user = $this->where('username', $username)->first();
        if ($user) {
            return password_verify($password, $user['password']) ? $user : false;
        }
        return false;
    }

    public function getAdminById($id)
    {
        return $this->find($id);
    }

    public function updateAdmin($id, $data)
    {
        return $this->update($id, $data);
    }
}