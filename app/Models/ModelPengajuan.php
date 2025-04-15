<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPengajuan extends Model
{
    protected $table = 'pengajuan_magang';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_kelompok',
        'ketua_id',
        'instansi_id',
        'id_dosen',
        'status',
        'surat_permohonan',
        'surat_pengantar',
        'surat_balasan'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getPengajuan($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }

        return $this->where(['id' => $id])->first();
    }

    public function getPengajuanByMahasiswa($mahasiswa_id)
    {
        return $this->where(['ketua_id' => $mahasiswa_id])->findAll();
    }

    public function getPengajuanByDosen($dosen_id)
    {
        return $this->where(['id_dosen' => $dosen_id])->findAll();
    }

    public function getPengajuanByInstansi($instansi_id)
    {
        return $this->where(['instansi_id' => $instansi_id])->findAll();
    }

    public function getPengajuanAktif()
    {
        return $this->where(['status' => 'aktif'])->findAll();
    }

    public function getAllKelompok()
    {
        return $this->db->table('pengajuan_magang pm')
            ->select('pm.id, pm.nama_kelompok, i.nama_instansi')
            ->join('instansi i', 'i.id_instansi = pm.instansi_id')
            ->where('pm.status', 'disetujui')
            ->get()
            ->getResultArray();
    }
} 