<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelMahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $allowedFields = ['id_user', 'nim', 'nama', 'angkatan', 'instansi', 'foto', 'instansi_id'];
    protected $tablePengajuan = 'pengajuan_magang';
    protected $tableAnggota = 'anggota_kelompok';

    public function getMahasiswaByUserId($userId)
    {
        try {
            $result = $this->select('mahasiswa.*, user.username, user.email')
                        ->join('user', 'user.id_user = mahasiswa.id_user')
                        ->where('mahasiswa.id_user', $userId)
                        ->first();

            // Jika tidak ada data, kembalikan array kosong dengan struktur yang sama
            if (empty($result)) {
                return [
                    'id_mahasiswa' => null,
                    'id_user' => $userId,
                    'nim' =>'',
                    'nama' => '',
                    'angkatan' => '',
                    'instansi' => '',
                    'foto' => '',
                    'username' => '',
                    'email' => ''
                ];
            }

            // Pastikan result adalah array
            return is_array($result) ? $result : (array)$result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error di getMahasiswaByUserId: ' . $e->getMessage());
            return [
                'id_mahasiswa' => null,
                'id_user' => $userId,
                'nim' =>'',
                'nama' => '',
                'angkatan' => '',
                'instansi' => '',
                'foto' => '',
                'username' => '',
                'email' => ''
            ];
        }
    }

    public function updateProfil($id_mahasiswa, $data)
    {
        try {
            if (!$id_mahasiswa || !is_array($data)) {
                log_message('error', 'Invalid input data. ID Mahasiswa: ' . $id_mahasiswa);
                throw new \Exception('Invalid input data');
            }

            // Debugging: Tampilkan data yang akan diupdate
            log_message('info', 'Updating mahasiswa with ID: ' . $id_mahasiswa . ' with data: ' . json_encode($data));
            
            // Pastikan data yang diupdate sesuai dengan struktur tabel
            $updateData = [
                'nim' => $data['nim'],
                'nama' => $data['nama'],
                'angkatan' => $data['angkatan'],
                'instansi' => $data['instansi']
            ];

            // Jika ada foto baru, tambahkan ke data update
            if (!empty($data['foto'])) {
                $updateData['foto'] = $data['foto'];
            }
            
            $result = $this->update($id_mahasiswa, $updateData);
            
            if ($result === false) {
                log_message('error', 'Update failed for mahasiswa ID: ' . $id_mahasiswa);
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error di updateProfil: ' . $e->getMessage());
            return false;
        }
    }

    public function getMahasiswaWithUser($id)
    {
        $query = $this->select('mahasiswa.*, user.username, user.email as user_email')
            ->join('user', 'user.id = mahasiswa.user_id')
            ->where('mahasiswa.id', $id)
            ->get();

        // Debugging
        dd($this->getLastQuery()->getQuery()); // Periksa query yang dijalankan

        return $query->getFirstRow();
    }

    public function getAllMahasiswa()
    {
        try {
            $result = $this->select('mahasiswa.*, user.username, user.email')
                        ->join('user', 'user.id_user = mahasiswa.id_user')
                        ->findAll();
            
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            log_message('error', 'Error di getAllMahasiswa: ' . $e->getMessage());
            return [];
        }
    }

    public function getKelompokMagang($mahasiswa_id)
    {
        try {
            $builder = $this->db->table($this->tablePengajuan . ' pm')
                ->select('pm.*, i.nama_instansi, m.nama as nama_ketua')
                ->join('instansi i', 'i.id_instansi = pm.instansi_id')
                ->join('mahasiswa m', 'm.id_mahasiswa = pm.ketua_id');

            // Cek apakah mahasiswa adalah ketua atau anggota
            $builder->groupStart()
                ->where('pm.ketua_id', $mahasiswa_id)
                ->orWhereIn('pm.id', function($builder) use ($mahasiswa_id) {
                    return $builder->select('pengajuan_id')
                        ->from('anggota_kelompok')
                        ->where('mahasiswa_id', $mahasiswa_id);
                })
                ->groupEnd();

            return $builder->get()->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error di getKelompokMagang: ' . $e->getMessage());
            return [];
        }
    }

    public function insertPengajuanMagang($data)
    {
        $this->db->table($this->tablePengajuan)->insert($data);
        return $this->db->insertID();
    }

    public function insertAnggotaKelompok($data)
    {
        return $this->db->table($this->tableAnggota)->insert($data);
    }

    public function getMahasiswaTersedia()
    {
        // Ambil mahasiswa yang belum masuk kelompok magang
        return $this->db->table($this->table)
            ->select('id_mahasiswa, nama, nim')
            ->whereNotIn('id_mahasiswa', function($builder) {
                $builder->select('mahasiswa_id')
                       ->from('anggota_kelompok');
            })
            ->whereNotIn('id_mahasiswa', function($builder) {
                $builder->select('ketua_id')
                       ->from('pengajuan_magang');
            })
            ->get()
            ->getResultArray();
    }

    public function getAllInstansi()
    {
        try {
            return $this->db->table('instansi')
                        ->select('id_instansi, nama_instansi')
                        ->get()
                        ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error di getAllInstansi: ' . $e->getMessage());
            return [];
        }
    }

    public function getPengajuanById($id)
    {
        return $this->db->table('pengajuan_magang pm')
            ->select('pm.*, m.nama as nama_ketua, m.nim as nim_ketua, i.nama_instansi')
            ->join('mahasiswa m', 'm.id_mahasiswa = pm.ketua_id')
            ->join('instansi i', 'i.id_instansi = pm.instansi_id')
            ->where('pm.id', $id)
            ->get()
            ->getRowArray();
    }

    public function deleteAnggotaKelompok($pengajuan_id)
    {
        return $this->db->table($this->tableAnggota)
                    ->where('pengajuan_id', $pengajuan_id)
                    ->delete();
    }

    public function deletePengajuan($id)
    {
        return $this->db->table($this->tablePengajuan)
                    ->where('id', $id)
                    ->delete();
    }

    public function getPengajuanDetail($id)
    {
        return $this->db->table($this->tablePengajuan . ' pm')
            ->select('pm.*, m.nama as nama_ketua, m.nim as nim_ketua, i.nama_instansi')
            ->join('mahasiswa m', 'm.id_mahasiswa = pm.ketua_id')
            ->join('instansi i', 'i.id_instansi = pm.instansi_id')
            ->where('pm.id', $id)
            ->get()
            ->getRowArray();
    }

    public function updateStatusPengajuan($id, $status)
    {
        return $this->db->table($this->tablePengajuan)
            ->where('id', $id)
            ->update(['status' => $status]);
    }

    public function getAllPengajuan()
    {
        return $this->db->table('pengajuan_magang pm')
            ->select('pm.*, m.nama as nama_ketua, m.nim as nim_ketua, i.nama_instansi')
            ->join('mahasiswa m', 'm.id_mahasiswa = pm.ketua_id')
            ->join('instansi i', 'i.id_instansi = pm.instansi_id')
            ->orderBy('pm.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAnggotaKelompokDetail($pengajuan_id)
    {
        return $this->db->table('anggota_kelompok ak')
            ->select('m.nim, m.nama, (pm.ketua_id = m.id_mahasiswa) as is_ketua')
            ->join('mahasiswa m', 'm.id_mahasiswa = ak.mahasiswa_id')
            ->join('pengajuan_magang pm', 'pm.id = ak.pengajuan_id')
            ->where('ak.pengajuan_id', $pengajuan_id)
            ->get()
            ->getResultArray();
    }
} 