<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelAuth extends Model

{
    protected $table = 'user';
    protected $allowedFields = ['username', 'password', 'email', 'role'];
    protected $useTimestamps = true;

    public function checkUser($email, $password)
    {
        // Cari user berdasarkan email
        $user = $this->where('email', $email)->first();

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
                    ->where('user_id', $user['id'])
                    ->get()
                    ->getRowArray();
                break;

            case 'dosen':
                $user['dosen_data'] = $this->db->table('dosen_pembimbing')
                    ->where('user_id', $user['id'])
                    ->get()
                    ->getRowArray();
                break;

            case 'mahasiswa':
                $user['mahasiswa_data'] = $this->db->table('mahasiswa')
                    ->where('user_id', $user['id'])
                    ->get()
                    ->getRowArray();
                break;
        }

        return $user;
    }
}
