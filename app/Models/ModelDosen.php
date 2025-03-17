<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelDosen extends Model
{
    protected $table = 'dosen_pembimbing';
    protected $primaryKey = 'id_dosen';
    protected $allowedFields = ['id_user', 'nama', 'nidn', 'foto'];

    public function getDosenByUserId($userId)
    {
        try {
            $builder = $this->db->table($this->table);
            $result = $builder->select('dosen_pembimbing.*, user.username, user.email')
                            ->join('user', 'user.id_user = dosen_pembimbing.id_user')
                            ->where('dosen_pembimbing.id_user', $userId)
                            ->get()
                            ->getRowArray();

            if (empty($result)) {
                return [
                    'id_dosen' => null,
                    'id_user' => $userId,
                    'nama' => '',
                    'nidn' => '',
                    'foto' => null,
                    'username' => '',
                    'email' => ''
                ];
            }

            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error di getDosenByUserId: ' . $e->getMessage());
            return [
                'id_dosen' => null,
                'id_user' => $userId,
                'nama' => '',
                'nidn' => '',
                'foto' => null,
                'username' => '',
                'email' => ''
            ];
        }
    }

    public function updateProfil($id_dosen, $data)
    {
        try {
            if (!$id_dosen || !is_array($data)) {
                throw new \Exception('Invalid input data');
            }

            // Pastikan data yang diupdate sesuai dengan struktur tabel
            $updateData = [
                'nama' => $data['nama'],
                'nidn' => $data['nidn']
            ];

            // Jika ada foto baru, tambahkan ke data update
            if (!empty($data['foto'])) {
                $updateData['foto'] = $data['foto'];
            }
            
            return $this->update($id_dosen, $updateData);
            
        } catch (\Exception $e) {
            log_message('error', 'Error di updateProfil: ' . $e->getMessage());
            return false;
        }
    }

    public function getAllDosen()
    {
        try {
            $result = $this->select('dosen_pembimbing.*, user.username, user.email')
                        ->join('user', 'user.id_user = dosen_pembimbing.id_user')
                        ->findAll();
            
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            log_message('error', 'Error di getAllDosen: ' . $e->getMessage());
            return [];
        }
    }

    public function getDosenWithUser($orderBy = 'id_dosen')
    {
        $builder = $this->db->table('dosen_pembimbing');
        $builder->select('dosen_pembimbing.*, user.username, user.email');
        $builder->join('user', 'user.id_user = dosen_pembimbing.id_user', 'left');
        $builder->orderBy($orderBy);
        return $builder->get()->getResultArray();
    }

    public function getBimbingan($id_dosen)
    {
        return $this->db->table('bimbingan b')
                        ->select('b.*, m.nama as nama_mahasiswa, m.nim')
                        ->join('mahasiswa m', 'm.id_mahasiswa = b.id_mahasiswa')
                        ->where('b.id_dosen', $id_dosen)
                        ->orderBy('b.tanggal', 'DESC')
                        ->get()
                        ->getResultArray();
    }

    public function getBimbinganById($id_bimbingan)
    {
        return $this->db->table('bimbingan')
                        ->where('id_bimbingan', $id_bimbingan)
                        ->get()
                        ->getRowArray();
    }

    public function insertBimbingan($data)
    {
        return $this->db->table('bimbingan')->insert($data);
    }

    public function updateBimbingan($id_bimbingan, $data)
    {
        return $this->db->table('bimbingan')
                        ->where('id_bimbingan', $id_bimbingan)
                        ->update($data);
    }

    public function deleteBimbingan($id_bimbingan)
    {
        return $this->db->table('bimbingan')
                        ->where('id_bimbingan', $id_bimbingan)
                        ->delete();
    }

    public function getMahasiswaBimbingan($id_dosen)
    {
        return $this->db->table('mahasiswa')
                        ->select('id_mahasiswa, nim, nama')
                        ->get()
                        ->getResultArray();
    }

    public function getKelompokBimbingan($id_dosen)
    {
        return $this->db->table('pengajuan_magang p')
                        ->select('p.*, i.nama_instansi, m.nama as nama_ketua, m.nim as nim_ketua,
                                (SELECT COUNT(*) FROM anggota_kelompok ak WHERE ak.pengajuan_id = p.id) as jumlah_anggota')
                        ->join('instansi i', 'i.id_instansi = p.instansi_id')
                        ->join('mahasiswa m', 'm.id_mahasiswa = p.ketua_id')
                        ->where('p.id_dosen', $id_dosen)
                        ->where('p.status', 'disetujui')
                        ->get()
                        ->getResultArray();
    }

    public function getAnggotaKelompok($id_pengajuan)
    {
        return $this->db->table('anggota_kelompok ak')
                        ->select('m.nim, m.nama, ak.pengajuan_id, 
                                 IF(m.id_mahasiswa = p.ketua_id, "Ketua", "Anggota") as status')
                        ->join('mahasiswa m', 'm.id_mahasiswa = ak.mahasiswa_id')
                        ->join('pengajuan_magang p', 'p.id = ak.pengajuan_id')
                        ->where('ak.pengajuan_id', $id_pengajuan)
                        ->get()
                        ->getResultArray();
    }

    public function getPengajuanById($id_pengajuan)
    {
        return $this->db->table('pengajuan_magang p')
                        ->select('p.*, i.nama_instansi')
                        ->join('instansi i', 'i.id_instansi = p.instansi_id')
                        ->where('p.id', $id_pengajuan)
                        ->get()
                        ->getRowArray();
    }

    public function getLogbookMahasiswaBimbingan($id_dosen)
    {
        return $this->db->table('logbook l')
            ->select('l.*, m.nama as nama_mahasiswa, m.nim')
            ->join('mahasiswa m', 'm.id_mahasiswa = l.id_mahasiswa')
            ->join('anggota_kelompok ak', 'ak.mahasiswa_id = m.id_mahasiswa')
            ->join('pengajuan_magang p', 'p.id = ak.pengajuan_id')
            ->where('p.id_dosen', $id_dosen)
            ->where('p.status', 'disetujui')
            ->orderBy('l.tanggal', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAbsensiMahasiswaBimbingan($id_dosen)
    {
        return $this->db->table('pengajuan_magang p')
            ->select('p.*, i.nama_instansi, m.nama as nama_ketua, m.nim as nim_ketua')
            ->join('instansi i', 'i.id_instansi = p.instansi_id')
            ->join('mahasiswa m', 'm.id_mahasiswa = p.ketua_id')
            ->where('p.id_dosen', $id_dosen)
            ->where('p.status', 'disetujui')
            ->get()
            ->getResultArray();
    }

    public function getLoAMahasiswaBimbingan($id_dosen)
    {
        return $this->db->table('loa_journal l')
            ->select('l.*, m.nama as nama_mahasiswa, m.nim')
            ->join('mahasiswa m', 'm.id_mahasiswa = l.id_mahasiswa')
            ->join('pengajuan_magang p', 'p.id_dosen = ' . $id_dosen . ' AND m.id_mahasiswa IN (
                SELECT mahasiswa_id FROM anggota_kelompok WHERE pengajuan_id = p.id
            )')
            ->orderBy('l.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getKelompokBimbinganLogbook($id_dosen)
    {
        return $this->db->table('pengajuan_magang p')
            ->select('p.id, p.nama_kelompok, i.nama_instansi, m.nama as nama_ketua, m.nim as nim_ketua')
            ->join('instansi i', 'i.id_instansi = p.instansi_id')
            ->join('mahasiswa m', 'm.id_mahasiswa = p.ketua_id')
            ->where('p.id_dosen', $id_dosen)
            ->where('p.status', 'disetujui')
            ->get()
            ->getResultArray();
    }

    public function getLogbookByKelompok($id_pengajuan)
    {
        return $this->db->table('logbook l')
            ->select('l.*, m.nama as nama_mahasiswa, m.nim')
            ->join('mahasiswa m', 'm.id_mahasiswa = l.id_mahasiswa')
            ->join('anggota_kelompok ak', 'ak.mahasiswa_id = m.id_mahasiswa')
            ->where('ak.pengajuan_id', $id_pengajuan)
            ->orderBy('l.tanggal', 'DESC')
            ->orderBy('l.hari_ke', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAbsensiKelompok($id_pengajuan)
    {
        try {
            // Ambil data pengajuan untuk mendapatkan ID ketua
            $pengajuan = $this->db->table('pengajuan_magang')
                ->where('id', $id_pengajuan)
                ->get()
                ->getRowArray();

            if (!$pengajuan) {
                throw new \Exception('Data pengajuan tidak ditemukan');
            }

            // Ambil data ketua
            $ketua = $this->db->table('mahasiswa')
                ->select('id_mahasiswa, nim, nama')
                ->where('id_mahasiswa', $pengajuan['ketua_id'])
                ->get()
                ->getRowArray();

            // Ambil anggota kelompok (selain ketua)
            $anggota = $this->db->table('anggota_kelompok ak')
                ->select('m.id_mahasiswa, m.nim, m.nama')
                ->join('mahasiswa m', 'm.id_mahasiswa = ak.mahasiswa_id')
                ->where('ak.pengajuan_id', $id_pengajuan)
                ->where('m.id_mahasiswa !=', $pengajuan['ketua_id'])
                ->get()
                ->getResultArray();

            // Gabungkan ketua di awal array anggota
            array_unshift($anggota, $ketua);

            // Ambil data absensi untuk ketua dan semua anggota
            $absensi = [];
            foreach ($anggota as $a) {
                $absensi[$a['id_mahasiswa']] = $this->db->table('absensi')
                    ->where('id_mahasiswa', $a['id_mahasiswa'])
                    ->orderBy('tanggal', 'DESC')
                    ->get()
                    ->getResultArray();
            }

            return [
                'status' => true,
                'anggota' => $anggota,
                'absensi' => $absensi
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error di getAbsensiKelompok: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Gagal mengambil data absensi'
            ];
        }
    }

    public function getLoAKelompok($id_dosen)
    {
        try {
            return $this->db->table('loa_journal l')
                ->select('l.*, m.nama as nama_mahasiswa, m.nim, p.nama_kelompok, i.nama_instansi')
                ->join('mahasiswa m', 'm.id_mahasiswa = l.id_mahasiswa')
                ->join('pengajuan_magang p', 'p.ketua_id = l.id_mahasiswa')
                ->join('instansi i', 'i.id_instansi = p.instansi_id')
                ->where('p.id_dosen', $id_dosen)
                ->where('p.status', 'disetujui')
                ->orderBy('l.created_at', 'DESC')
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error di getLoAKelompok: ' . $e->getMessage());
            return [];
        }
    }
} 