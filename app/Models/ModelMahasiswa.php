<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelMahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $allowedFields = ['id_user', 'nim', 'nama', 'prodi', 'angkatan', 'instansi_id'];

    public function getMahasiswaWithUser()
    {
        $result = $this->select('mahasiswa.*, user.username, user.email')
                       ->join('user', 'user.id_user = mahasiswa.id_user')
                       ->findAll();
                       
        return is_array($result) ? $result : [];
    }
} 