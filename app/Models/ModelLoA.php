<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelLoA extends Model
{
    protected $table = 'loa_journal';
    protected $primaryKey = 'id_loa';
    protected $allowedFields = ['id_mahasiswa', 'judul', 'deskripsi', 'file_loa', 'status', 'catatan'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getLoAByMahasiswa($id_mahasiswa)
    {
        return $this->where('id_mahasiswa', $id_mahasiswa)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getAllLoA()
    {
        return $this->select('loa_journal.*, mahasiswa.nama as nama_mahasiswa')
                    ->join('mahasiswa', 'mahasiswa.id_mahasiswa = loa_journal.id_mahasiswa')
                    ->orderBy('loa_journal.created_at', 'DESC')
                    ->findAll();
    }

    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    public function updateCatatan($id, $catatan)
    {
        return $this->update($id, ['catatan' => $catatan]);
    }
} 