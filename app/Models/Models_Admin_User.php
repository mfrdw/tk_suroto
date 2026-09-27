<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin_model extends Model
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

---

<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['nama', 'email', 'password', 'no_telp', 'alamat', 'provinsi', 'kota', 'kode_pos', 'foto', 'status'];

    public function getAllUser()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getUserById($id)
    {
        return $this->find($id);
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function insertUser($data)
    {
        return $this->insert($data);
    }

    public function updateUser($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->delete($id);
    }

    public function countAll()
    {
        return $this->countAllResults();
    }

    public function getUserInvoice($id_user)
    {
        $db = \Config\Database::connect();
        return $db->table('invoice')
            ->where('id_user', $id_user)
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();
    }

    public function updateProfile($id, $data)
    {
        return $this->update($id, $data);
    }
}