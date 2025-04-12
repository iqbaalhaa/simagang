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
    
    // Tambahkan property untuk field yang diizinkan di tabel pengajuan_magang
    protected $allowedFieldsPengajuan = [
        'nama_kelompok',
        'ketua_id',
        'instansi_id',
        'status',
        'created_at',
        'surat_permohonan'  // Tambahkan field baru ini
    ];

    // Tambahkan property untuk tabel absensi
    protected $tableAbsensi = 'absensi';

    public function getMahasiswaByUserId($id_user)
    {
        try {
            log_message('info', 'Getting mahasiswa data for user ID: ' . $id_user);
            
            $result = $this->db->table('mahasiswa')
                ->where('id_user', $id_user)
                ->get()
                ->getRowArray();
                
            log_message('info', 'Query result: ' . json_encode($result));
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error in getMahasiswaByUserId: ' . $e->getMessage());
            return null;
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
                ->select('pm.*, pm.surat_permohonan, i.nama_instansi, m.nama as nama_ketua, dp.nama as nama_dosen')
                ->join('instansi i', 'i.id_instansi = pm.instansi_id')
                ->join('mahasiswa m', 'm.id_mahasiswa = pm.ketua_id')
                ->join('dosen_pembimbing dp', 'dp.id_dosen = pm.id_dosen', 'left');

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
        // Filter data sesuai dengan field yang diizinkan
        $filteredData = array_intersect_key($data, array_flip($this->allowedFieldsPengajuan));
        
        $this->db->table($this->tablePengajuan)->insert($filteredData);
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

    public function updateStatusPengajuan($id, $data)
    {
        return $this->db->table('pengajuan_magang')
                    ->where('id', $id)
                    ->update($data);
    }

    public function getAllPengajuan()
    {
        return $this->db->table('pengajuan_magang pm')
            ->select('pm.*, m.nama as nama_ketua, m.nim as nim_ketua, 
                     i.nama_instansi, dp.nama as nama_dosen')
            ->join('mahasiswa m', 'm.id_mahasiswa = pm.ketua_id')
            ->join('instansi i', 'i.id_instansi = pm.instansi_id')
            ->join('dosen_pembimbing dp', 'dp.id_dosen = pm.id_dosen', 'left')
            ->get()
            ->getResultArray();
    }

    public function getAnggotaKelompokDetail($id_pengajuan)
    {
        return $this->db->table('anggota_kelompok ak')
            ->select('m.id_mahasiswa, m.nim, m.nama')
            ->join('mahasiswa m', 'm.id_mahasiswa = ak.mahasiswa_id')
            ->where('ak.pengajuan_id', $id_pengajuan)
            ->get()
            ->getResultArray();
    }

    public function getTotalBimbingan($id_mahasiswa)
    {
        try {
            return $this->db->table('bimbingan')
                        ->where('id_mahasiswa', $id_mahasiswa)
                        ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error di getTotalBimbingan: ' . $e->getMessage());
            return 0;
        }
    }

    public function getBimbinganSelesai($id_mahasiswa)
    {
        try {
            return $this->db->table('bimbingan')
                        ->where('id_mahasiswa', $id_mahasiswa)
                        ->where('status', 'selesai')
                        ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error di getBimbinganSelesai: ' . $e->getMessage());
            return 0;
        }
    }

    public function getBimbinganPending($id_mahasiswa)
    {
        try {
            return $this->db->table('bimbingan')
                        ->where('id_mahasiswa', $id_mahasiswa)
                        ->where('status', 'pending')
                        ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error di getBimbinganPending: ' . $e->getMessage());
            return 0;
        }
    }

    public function getDosenPembimbing($id_mahasiswa)
    {
        $builder = $this->db->table('pengajuan_magang pm')
            ->select('dp.nama')
            ->join('dosen_pembimbing dp', 'dp.id_dosen = pm.id_dosen')
            ->join('anggota_kelompok ak', 'ak.pengajuan_id = pm.id', 'left')
            ->where('pm.status', 'disetujui')
            ->groupStart()
                ->where('pm.ketua_id', $id_mahasiswa)
                ->orWhere('ak.mahasiswa_id', $id_mahasiswa)
            ->groupEnd();

        $result = $builder->get()->getRowArray();
        return $result['nama'] ?? '-';
    }

    public function getRiwayatBimbingan($id_mahasiswa, $limit = 5)
    {
        try {
            return $this->db->table('bimbingan')
                        ->where('id_mahasiswa', $id_mahasiswa)
                        ->orderBy('tanggal', 'DESC')
                        ->limit($limit)
                        ->get()
                        ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error di getRiwayatBimbingan: ' . $e->getMessage());
            return [];
        }
    }

    // Tambahkan method untuk mengambil surat permohonan
    public function getSuratPermohonan($pengajuan_id)
    {
        return $this->db->table($this->tablePengajuan)
                    ->select('surat_permohonan')
                    ->where('id', $pengajuan_id)
                    ->get()
                    ->getRowArray();
    }

    // Tambahkan method untuk update surat permohonan
    public function updateSuratPermohonan($pengajuan_id, $filename)
    {
        return $this->db->table($this->tablePengajuan)
                    ->where('id', $pengajuan_id)
                    ->update(['surat_permohonan' => $filename]);
    }

    public function getAbsensiMahasiswa($id_mahasiswa)
    {
        return $this->db->table('absensi')
            ->where('id_mahasiswa', $id_mahasiswa)
            ->orderBy('tanggal', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function insertAbsensi($data)
    {
        return $this->db->table($this->tableAbsensi)->insert($data);
    }

    public function updateAbsensi($id, $data)
    {
        return $this->db->table($this->tableAbsensi)
                    ->where('id_absensi', $id)
                    ->update($data);
    }

    public function getAllKelompokMagang()
    {
        return $this->db->table('pengajuan_magang pm')
            ->select('pm.*, i.nama_instansi, m.nama as nama_ketua')
            ->join('instansi i', 'i.id_instansi = pm.instansi_id')
            ->join('mahasiswa m', 'm.id_mahasiswa = pm.ketua_id')
            ->where('pm.status', 'disetujui')
            ->get()
            ->getResultArray();
    }

    public function updatePengajuan($id, $data)
    {
        return $this->db->table($this->tablePengajuan)
                    ->where('id', $id)
                    ->update($data);
    }

    public function countPengajuanPending()
    {
        try {
            return $this->db->table($this->tablePengajuan)
                        ->where('status', 'pending')
                        ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error di countPengajuanPending: ' . $e->getMessage());
            return 0;
        }
    }

    public function isKetuaKelompok($id_mahasiswa)
    {
        try {
            $result = $this->db->table('pengajuan_magang')
                ->where('ketua_id', $id_mahasiswa)
                ->where('status', 'disetujui')
                ->countAllResults();
            
            return $result > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error di isKetuaKelompok: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getTotalLogbook($id_mahasiswa)
    {
        return $this->db->table('logbook')->where('id_mahasiswa', $id_mahasiswa)->countAllResults();
    }

    public function getTotalAbsensi($id_mahasiswa)
    {
        return $this->db->table('absensi')->where('id_mahasiswa', $id_mahasiswa)->countAllResults();
    }

    public function getNilaiMahasiswa($id_mahasiswa)
    {
        return $this->db->table('nilai')
            ->select('nilai, keterangan')
            ->where('id_mahasiswa', $id_mahasiswa)
            ->get()
            ->getResultArray();
    }

    public function getMahasiswaDinilai()
    {
        $db = \Config\Database::connect();
        
        return $db->table('mahasiswa m')
                 ->select('m.id_mahasiswa, m.nim, m.nama, i.nama_instansi as instansi, n.nilai')
                 ->join('nilai n', 'n.id_mahasiswa = m.id_mahasiswa')
                 ->join('pengajuan_magang pm', 'pm.ketua_id = m.id_mahasiswa')
                 ->join('instansi i', 'i.id_instansi = pm.instansi_id')
                 ->where('n.nilai IS NOT NULL')
                 ->get()
                 ->getResultArray();
    }

    public function getMahasiswaById($id_mahasiswa)
    {
        try {
            return $this->db->table('mahasiswa m')
                ->select('m.*, i.nama_instansi as instansi')
                ->join('pengajuan_magang pm', 'pm.ketua_id = m.id_mahasiswa')
                ->join('instansi i', 'i.id_instansi = pm.instansi_id')
                ->where('m.id_mahasiswa', $id_mahasiswa)
                ->get()
                ->getRowArray();
        } catch (\Exception $e) {
            log_message('error', 'Error di getMahasiswaById: ' . $e->getMessage());
            return null;
        }
    }
} 