<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelAuth extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['username', 'password', 'email', 'role', 'reset_token', 'reset_expired'];
    protected $useTimestamps = true;

    public function checkUser($username, $password)
    {
        // Cari user berdasarkan username
        $user = $this->where('username', $username)->first();

        if (!$user) {
            return false;
        }

        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Ambil data spesifik role
        switch ($user['role']) {
            case 'admin':
                $user['admin_data'] = $this->db->table('admin')
                    ->where('id_user', $user['id_user'])
                    ->get()
                    ->getRowArray();
                break;

            case 'dosen':
                $user['dosen_data'] = $this->db->table('dosen_pembimbing')
                    ->where('id_user', $user['id_user'])
                    ->get()
                    ->getRowArray();
                break;

            case 'mahasiswa':
                $user['mahasiswa_data'] = $this->db->table('mahasiswa')
                    ->where('id_user', $user['id_user'])
                    ->get()
                    ->getRowArray();
                break;
        }

        return $user;
    }
}
