<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelLogbook extends Model
{
    protected $table = 'logbook';
    protected $primaryKey = 'id_logbook';
    protected $allowedFields = ['id_mahasiswa', 'hari_ke', 'tanggal', 'uraian_kegiatan', 'paraf_pembimbing'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllLogbook()
    {
        return $this->db->table('logbook l')
            ->select('l.*, m.nama as nama_mahasiswa')
            ->join('mahasiswa m', 'm.id_mahasiswa = l.id_mahasiswa')
            ->orderBy('m.nama', 'ASC')
            ->orderBy('l.tanggal', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getLogbookById($id)
    {
        return $this->db->table('logbook l')
            ->select('l.*, m.nama as nama_mahasiswa')
            ->join('mahasiswa m', 'm.id_mahasiswa = l.id_mahasiswa')
            ->where('l.id_logbook', $id)
            ->get()
            ->getRowArray();
    }

    public function updateParaf($id, $status)
    {
        return $this->update($id, ['paraf_pembimbing' => $status]);
    }
} 