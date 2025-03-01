<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelDosen extends Model
{
    protected $table = 'dosen_pembimbing';
    protected $primaryKey = 'id_dosen';
    protected $allowedFields = ['id_dosen', 'id_user', 'nama', 'nidn'];

    public function getDosenWithUser()
    {
        return $this->select('dosen_pembimbing.*, user.username, user.email')
                    ->join('user', 'user.id_user = dosen_pembimbing.id_user')
                    ->findAll();
    }
} 