<?php

namespace App\Models;

use CodeIgniter\Model;

class Models_User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $returnType = 'array';
    protected $allowedFields = ['nama', 'email', 'password', 'no_telp', 'alamat', 'provinsi', 'kota', 'kode_pos', 'foto', 'status'];

    public function getAllUser()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getUserById($id) { return $this->find($id); }
    public function getUserByEmail($email) { return $this->where('email', $email)->first(); }
    public function insertUser($data) { return $this->insert($data); }
    public function updateUser($id, $data) { return $this->update($id, $data); }
    public function deleteUser($id) { return $this->delete($id); }
    public function countAll() { return $this->countAllResults(); }
    public function updateProfile($id, $data) { return $this->update($id, $data); }

    public function getUserInvoice($id_user)
    {
        return $this->db->table('invoice')->where('id_user', $id_user)
            ->orderBy('tanggal', 'DESC')->get()->getResultArray();
    }
}
