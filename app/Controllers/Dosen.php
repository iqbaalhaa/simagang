<?php

namespace App\Controllers;

use App\Models\ModelDosen;
use App\Models\ModelLaporan;
use App\Models\ModelMahasiswa;

class Dosen extends BaseController
{
    protected $ModelDosen;
    protected $ModelLaporan;
    protected $ModelMahasiswa;

    public function __construct()
    {
        // Inisialisasi model
        $this->ModelDosen = new ModelDosen();
        $this->ModelLaporan = new ModelLaporan();
        $this->ModelMahasiswa = new ModelMahasiswa();
        
        // Cek session login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }
        
        // Cek role
        if (session()->get('level') !== 'dosen') {
            return redirect()->to(base_url('auth'));
        }
    }

    public function index()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));

        // Ambil data statistik
        $total_mahasiswa = $modelDosen->getTotalMahasiswa();
        $total_instansi = $modelDosen->getTotalInstansi();
        $total_pengajuan_aktif = $modelDosen->getTotalPengajuanAktif();
        $total_dosen = $modelDosen->getTotalDosen();
        $pengajuan_terbaru = $modelDosen->getPengajuanTerbaru();
        $statistik = $modelDosen->getStatistikPengajuan();

        $data = [
            'judul' => 'Dashboard Dosen',
            'page' => 'dosen/v_dashboard',
            'dosen' => $dosenData,
            'total_mahasiswa' => $total_mahasiswa,
            'total_instansi' => $total_instansi,
            'total_pengajuan_aktif' => $total_pengajuan_aktif,
            'total_dosen' => $total_dosen,
            'pengajuan_terbaru' => $pengajuan_terbaru,
            'statistik' => $statistik
        ];

        return view('v_template_backend_dosen', $data);
    }

    public function Profil()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));

        // Jika data dosen belum ada, insert data baru
        if (empty($dosenData) || !isset($dosenData['id_dosen'])) {
            $data = [
                'id_user' => session()->get('id_user'),
                'nama' => session()->get('username'),
                'nidn' => 0 // Set default NIDN ke 0
            ];
            
            try {
                $modelDosen->insert($data);
                $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));
                session()->setFlashdata('success', 'Data profil berhasil dibuat, silakan lengkapi data Anda.');
            } catch (\Exception $e) {
                log_message('error', 'Gagal membuat data dosen: ' . $e->getMessage());
                session()->setFlashdata('error', 'Gagal membuat data dosen.');
                return redirect()->to(base_url('Dosen'));
            }
        }
        
        $data = [
            'judul' => 'Profil Dosen',
            'page' => 'dosen/v_profil',
            'dosen' => $dosenData
        ];

        return view('v_template_backend_dosen', $data);
    }

    public function updateProfil()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $id_user = session()->get('id_user');
        
        // Ambil data dosen berdasarkan id_user
        $dosenData = $modelDosen->getDosenByUserId($id_user);
        if (empty($dosenData)) {
            session()->setFlashdata('error', 'Data dosen tidak ditemukan.');
            return redirect()->to('Dosen/Profil');
        }

        // Validasi NIDN
        if (empty($this->request->getPost('nidn'))) {
            session()->setFlashdata('error', 'NIDN wajib diisi.');
            return redirect()->to('Dosen/Profil');
        }

        // Siapkan data untuk diupdate
        $data = [
            'nama' => $this->request->getPost('nama'),
            'nidn' => $this->request->getPost('nidn')
        ];

        // Validasi dan proses foto
        $foto = $this->request->getFile('foto');
        $fotoLama = $this->request->getPost('foto_lama');

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $type = $foto->getClientMimeType();
                $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
                
                if (!in_array($type, $allowedTypes)) {
                    session()->setFlashdata('error', 'Tipe file tidak didukung. Gunakan PNG, JPEG, atau GIF.');
                return redirect()->to('Dosen/Profil');
                }

                if ($foto->getSize() > 2097152) {
                    session()->setFlashdata('error', 'Ukuran file terlalu besar. Maksimal 2MB.');
                return redirect()->to('Dosen/Profil');
                }

                try {
                    $namaFoto = $foto->getRandomName();
                    
                if (!is_dir('foto/dosen')) {
                    mkdir('foto/dosen', 0777, true);
                }
                
                if ($foto->move('foto/dosen', $namaFoto)) {
                    if ($fotoLama && file_exists('foto/dosen/' . $fotoLama)) {
                        unlink('foto/dosen/' . $fotoLama);
                    }
                    $data['foto'] = $namaFoto;
                }
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Gagal mengupload foto. ' . $e->getMessage());
                return redirect()->to('Dosen/Profil');
            }
        }

        try {
            if ($modelDosen->updateProfil($dosenData['id_dosen'], $data)) {
                session()->setFlashdata('success', 'Profil berhasil diupdate');
            } else {
                throw new \Exception('Gagal mengupdate profil dosen');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal mengupdate profil. ' . $e->getMessage());
        }

        return redirect()->to('Dosen/Profil');
    }

    public function Logbook()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));
        
        $data = [
            'judul' => 'Logbook Mahasiswa',
            'page' => 'dosen/v_logbook',
            'dosen' => $dosenData,
            'kelompok' => $modelDosen->getKelompokBimbinganLogbook($dosenData['id_dosen'])
        ];

        return view('v_template_backend_dosen', $data);
    }

    public function getLogbookKelompok($id_pengajuan)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $modelDosen = new \App\Models\ModelDosen();
        $logbook = $modelDosen->getLogbookByKelompok($id_pengajuan);
        
        $data = [
            'logbook' => $logbook
        ];

        return view('dosen/v_logbook_detail', $data);
    }

    public function Absensi()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));
        
        $data = [
            'judul' => 'Absensi Mahasiswa',
            'page' => 'dosen/v_absensi',
            'dosen' => $dosenData,
            'kelompok' => $modelDosen->getAbsensiMahasiswaBimbingan($dosenData['id_dosen'])
        ];

        return view('v_template_backend_dosen', $data);
    }

    public function getAbsensiKelompok($id_pengajuan)
    {
        $result = $this->ModelDosen->getAbsensiKelompok($id_pengajuan);
        return $this->response->setJSON($result);
    }

    public function LoA()
    {
        $id_dosen = $this->ModelDosen->getDosenByUserId(session()->get('id_user'))['id_dosen'];
        
        // Ambil data dosen
        $dosenData = $this->ModelDosen->getDosenByUserId(session()->get('id_user'));

        $data = [
            'judul' => 'LoA Journal',
            'page' => 'dosen/v_loa',
            'loa' => $this->ModelDosen->getLoAKelompok($id_dosen),
            'dosen' => $dosenData // Pastikan data dosen dikirim ke view
        ];

        return view('v_template_backend_dosen', $data);
    }

    public function updateParafLogbook()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $modelLogbook = new \App\Models\ModelLogbook();
        
        try {
            $id = $this->request->getPost('id_logbook');
            $status = $this->request->getPost('status');
            
            if (!$modelLogbook->updateParaf($id, $status)) {
                throw new \Exception('Gagal mengupdate status paraf');
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Status paraf berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function Penilaian()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));

        // Ambil parameter pencarian
        $search = $this->request->getGet('search');
        $currentPage = $this->request->getGet('page') ? (int)$this->request->getGet('page') : 1;
        $perPage = 10; // Jumlah mahasiswa per halaman

        // Ambil data mahasiswa berdasarkan dosen dan pencarian
        $mahasiswa = $modelDosen->getMahasiswaBimbinganForNilai($dosenData['id_dosen'], $search, $currentPage, $perPage);
        
        // Hitung total mahasiswa untuk pagination
        $totalMahasiswa = $modelDosen->countMahasiswaByDosen($dosenData['id_dosen'], $search);
        $totalPages = ceil($totalMahasiswa / $perPage);

        $data = [
            'judul' => 'Penilaian Mahasiswa',
            'page' => 'dosen/v_penilaian',
            'dosen' => $dosenData,
            'mahasiswa' => $mahasiswa,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ];

        return view('v_template_backend_dosen', $data);
    }

    public function KelompokBimbingan()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));
        
        // Ambil daftar kelompok bimbingan
        $kelompokBimbingan = $modelDosen->getKelompokBimbingan($dosenData['id_dosen']);
        
        $data = [
            'judul' => 'Kelompok Bimbingan',
            'page' => 'dosen/v_kelompok_bimbingan',
            'dosen' => $dosenData,
            'kelompok' => $kelompokBimbingan
        ];

        return view('v_template_backend_dosen', $data);
    }

    public function getDetailKelompok($id_pengajuan)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $modelDosen = new \App\Models\ModelDosen();
        $anggota = $modelDosen->getAnggotaKelompok($id_pengajuan);
        
        $data = [
            'anggota' => $anggota
        ];

        // Render view dan return sebagai string
        return view('dosen/v_detail_kelompok', $data);
    }

    public function getPengajuanTerbaru()
    {
        return $this->db->table('pengajuan_magang')
            ->select('m.nama as nama_mahasiswa')
            ->join('mahasiswa m', 'm.id_mahasiswa = pengajuan_magang.ketua_id')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
    }

    public function simpanNilai()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $data = $this->request->getJSON();

        // Validasi data
        if (empty($data->id_mahasiswa) || empty($data->nilai)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data tidak valid']);
        }

        // Simpan nilai ke database
        $modelDosen->insertNilai([
            'id_mahasiswa' => $data->id_mahasiswa,
            'nilai' => $data->nilai,
            'id_dosen' => session()->get('id_user') // Ambil ID dosen dari session
        ]);

        return $this->response->setJSON(['status' => true, 'message' => 'Nilai berhasil disimpan']);
    }

    public function Laporan()
    {
        try {
            // Ambil semua laporan yang perlu direview
            $laporan = $this->ModelLaporan->getAllLaporanWithMahasiswa();
            
            $data = [
                'judul' => 'Laporan Magang',
                'page' => 'dosen/v_laporan',
                'laporan' => $laporan,
                'dosen' => $dosenData
            ];

            return view('v_template_backend_dosen', $data);
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->to('Dosen');
        }
    }

    public function reviewLaporan($id_laporan)
    {
        try {
            $status = $this->request->getPost('status');
            $catatan = $this->request->getPost('catatan');

            if ($this->ModelLaporan->updateStatus($id_laporan, $status, $catatan)) {
                session()->setFlashdata('pesan', 'Laporan berhasil direview');
            } else {
                throw new \Exception('Gagal mengupdate status laporan');
            }

            return redirect()->to('Dosen/Laporan');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal mereview laporan: ' . $e->getMessage());
            return redirect()->to('Dosen/Laporan');
        }
    }
}
