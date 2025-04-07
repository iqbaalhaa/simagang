<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelInstansi extends Model
{
    protected $table = 'instansi';
    protected $primaryKey = 'id_instansi';
    protected $allowedFields = ['nama_instansi', 'alamat'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getInstansi($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }

        return $this->where(['id_instansi' => $id])->first();
    }

    public function getInstansiByNama($nama)
    {
        return $this->where(['nama_instansi' => $nama])->first();
    }
} 