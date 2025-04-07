<?php

namespace App\Models;

use CodeIgniter\Model;


class ModelLaporan extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';
    protected $allowedFields = ['id_mahasiswa', 'judul', 'deskripsi', 'file_laporan', 'status', 'catatan', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getLaporanByMahasiswa($id_mahasiswa)
    {
        return $this->where('id_mahasiswa', $id_mahasiswa)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getLaporanById($id_laporan)
    {
        return $this->find($id_laporan);
    }

    public function updateStatus($id_laporan, $status, $catatan = null)
    {
        $data = [
            'status' => $status,
            'catatan' => $catatan
        ];
        return $this->update($id_laporan, $data);
    }

    public function getAllLaporanWithMahasiswa()
    {
        return $this->select('laporan.*, mahasiswa.nama as nama_mahasiswa')
                    ->join('mahasiswa', 'mahasiswa.id_mahasiswa = laporan.id_mahasiswa')
                    ->orderBy('laporan.created_at', 'DESC')
                    ->findAll();
    }
} 