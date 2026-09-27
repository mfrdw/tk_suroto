<?php

namespace App\Models;

use CodeIgniter\Model;

class Invoice_model extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'id_invoice';
    protected $allowedFields = ['id_user', 'no_invoice', 'tanggal', 'status', 'sub_total', 'ongkir', 'total', 'catatan', 'metode_bayar', 'bukti_bayar', 'tanggal_bayar', 'alamat_pengiriman', 'nama_penerima', 'no_telp_penerima'];

    public function generateNoInvoice()
    {
        $db = \Config\Database::connect();
        $prefix = 'INV-' . date('Ymd') . '-';
        $query = $db->query("SELECT MAX(no_invoice) as max_no FROM invoice WHERE DATE(tanggal) = CURDATE()");
        $result = $query->getRowArray();
        
        if ($result['max_no']) {
            $last_no = substr($result['max_no'], -3);
            $new_no = intval($last_no) + 1;
        } else {
            $new_no = 1;
        }
        
        return $prefix . str_pad($new_no, 3, '0', STR_PAD_LEFT);
    }

    public function getAllInvoice($status = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('invoice as i')
            ->join('user as u', 'u.id_user = i.id_user', 'left')
            ->select('i.*, u.nama as nama_pelanggan, u.email as email_pelanggan');

        if ($status) {
            $builder->where('i.status', $status);
        }

        return $builder->orderBy('i.tanggal', 'DESC')->get()->getResultArray();
    }

    public function getInvoiceById($id)
    {
        $db = \Config\Database::connect();
        return $db->table('invoice as i')
            ->join('user as u', 'u.id_user = i.id_user', 'left')
            ->where('i.id_invoice', $id)
            ->get()->getRowArray();
    }

    public function getInvoiceByNo($no_invoice)
    {
        $db = \Config\Database::connect();
        return $db->table('invoice as i')
            ->join('user as u', 'u.id_user = i.id_user', 'left')
            ->where('i.no_invoice', $no_invoice)
            ->get()->getRowArray();
    }

    public function getInvoiceByUser($id_user)
    {
        $db = \Config\Database::connect();
        return $db->table('invoice as i')
            ->where('i.id_user', $id_user)
            ->orderBy('i.tanggal', 'DESC')
            ->get()->getResultArray();
    }

    public function getInvoiceTerbaru($limit = 5)
    {
        $db = \Config\Database::connect();
        return $db->table('invoice as i')
            ->join('user as u', 'u.id_user = i.id_user', 'left')
            ->orderBy('i.tanggal', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    public function getPesananByInvoice($id_invoice)
    {
        $db = \Config\Database::connect();
        return $db->table('pesanan as ps')
            ->join('produk as p', 'p.id_produk = ps.id_produk', 'left')
            ->join('kategori as k', 'k.id_kategori = p.id_kategori', 'left')
            ->where('ps.id_invoice', $id_invoice)
            ->get()->getResultArray();
    }

    public function getUserData($id_user)
    {
        $db = \Config\Database::connect();
        return $db->table('user')->where('id_user', $id_user)->get()->getRowArray();
    }

    public function insertInvoice($data)
    {
        $db = \Config\Database::connect();
        $db->table('invoice')->insert($data);
        return $db->insertID();
    }

    public function updateInvoice($id, $data)
    {
        return $this->update($id, $data);
    }

    public function countAll()
    {
        return $this->countAllResults();
    }

    public function countByStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }

    public function getTotalPendapatan()
    {
        $db = \Config\Database::connect();
        $result = $db->table('invoice')
            ->selectSum('total')
            ->where('status', 'selesai')
            ->get()->getRowArray();
        return $result['total'] ?? 0;
    }
}