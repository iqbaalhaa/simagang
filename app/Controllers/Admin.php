<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use TCPDF;

class Admin extends BaseController
{
    protected $session;
    protected $admin;
    protected $totalPengajuan;

    public function __construct()
    {
        helper(['tcpdf']);
        $this->session = \Config\Services::session();
        if (!$this->session->get('id_user')) {
            header('Location: ' . base_url('Auth'));
            exit();
        }
        
        // Initialize ModelAdmin
        $modelAdmin = new \App\Models\ModelAdmin();
        
        // Get admin data
        $this->admin = $modelAdmin->getAdminByUserId($this->session->get('id_user'));
        
        // Tambahkan di constructor Admin
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        $this->totalPengajuan = $modelMahasiswa->countPengajuanPending();
    }

    public function index(): string
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
        
        // Ambil data untuk dashboard
        $db = \Config\Database::connect();
        
        // Hitung total data
        $data = [
            'judul' => 'Dashboard Admin',
            'page' => 'admin/v_dashboard',
            'admin' => $adminData,
            'total_pengajuan' => $this->totalPengajuan,
            'total_mahasiswa' => $db->table('mahasiswa')->countAllResults(),
            'total_instansi' => $db->table('instansi')->countAllResults(),
            'total_dosen' => $db->table('dosen_pembimbing')->countAllResults(),
        ];

        // Cek struktur tabel pengajuan_magang
        try {
            // Ambil pengajuan aktif
            $data['total_pengajuan_aktif'] = $db->table('pengajuan_magang')->where('status', 'Menunggu')->countAllResults();

            // Ambil pengajuan terbaru
            $data['pengajuan_terbaru'] = $db->table('pengajuan_magang')
                                           ->select('pengajuan_magang.*, mahasiswa.nama as nama_mahasiswa')
                                           ->join('mahasiswa', 'mahasiswa.nim = pengajuan_magang.nim')
                                           ->orderBy('tgl_pengajuan', 'DESC')
                                           ->limit(5)
                                           ->get()
                                           ->getResultArray();

            // Data untuk pie chart status pengajuan
            $data['status_pengajuan'] = [
                'Menunggu' => $db->table('pengajuan_magang')->where('status', 'Menunggu')->countAllResults(),
                'Diterima' => $db->table('pengajuan_magang')->where('status', 'Diterima')->countAllResults(),
                'Ditolak' => $db->table('pengajuan_magang')->where('status', 'Ditolak')->countAllResults()
            ];

            // Data untuk grafik statistik 6 bulan terakhir
            $bulan = [];
            $pengajuan = [];
            $diterima = [];
            $ditolak = [];

            for ($i = 5; $i >= 0; $i--) {
                $bulan_ini = date('Y-m', strtotime("-$i month"));
                $bulan[] = date('M', strtotime("-$i month"));

                $pengajuan[] = $db->table('pengajuan_magang')
                                 ->where('DATE_FORMAT(tgl_pengajuan, "%Y-%m")', $bulan_ini)
                                 ->countAllResults();

                $diterima[] = $db->table('pengajuan_magang')
                                ->where('DATE_FORMAT(tgl_pengajuan, "%Y-%m")', $bulan_ini)
                                ->where('status', 'Diterima')
                                ->countAllResults();

                $ditolak[] = $db->table('pengajuan_magang')
                               ->where('DATE_FORMAT(tgl_pengajuan, "%Y-%m")', $bulan_ini)
                               ->where('status', 'Ditolak')
                               ->countAllResults();
            }

            $data['statistik'] = [
                'labels' => $bulan,
                'pengajuan' => $pengajuan,
                'diterima' => $diterima,
                'ditolak' => $ditolak
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error saat mengambil data pengajuan: ' . $e->getMessage());
            
            // Set nilai default jika terjadi error
            $data['total_pengajuan_aktif'] = 0;
            $data['pengajuan_terbaru'] = [];
            $data['status_pengajuan'] = ['Menunggu' => 0, 'Diterima' => 0, 'Ditolak' => 0];
            $data['statistik'] = [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'pengajuan' => [0, 0, 0, 0, 0, 0],
                'diterima' => [0, 0, 0, 0, 0, 0],
                'ditolak' => [0, 0, 0, 0, 0, 0]
            ];
        }

        return view('v_template_backend', $data);
    }

    public function PengajuanMahasiswa()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $modelDosen = new \App\Models\ModelDosen();
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
        
        $data = [
            'judul'     => 'Data Pengajuan Magang',
            'page'      => 'admin/v_pengajuan_mahasiswa',
            'pengajuan' => $modelMahasiswa->getAllPengajuan(),
            'admin'     => $adminData,
            'dosen_list' => $modelDosen->findAll(),
            'total_pengajuan' => $this->totalPengajuan
        ];

        return view('v_template_backend', $data);
    }

    public function DataAdmin()
    {
        try {
            $modelAdmin = new \App\Models\ModelAdmin();
            
            // Data untuk sidebar/navbar
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            // Data untuk tabel admin
            $allAdminData = $modelAdmin->getAllAdmin();
            
            $data = [
                'judul' => 'Data Admin',
                'page' => 'admin/v_data_admin',
                'admin' => $adminData,
                'list_admin' => $allAdminData
            ];

            return view('v_template_backend', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error di DataAdmin: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
    }

    public function DataDosen()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $modelDosen = new \App\Models\ModelDosen();
        
        $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
        $dosenData = $modelDosen->getDosenWithUser('id_dosen');
        
        $data = [
            'judul' => 'Data Dosen',
            'page' => 'admin/v_data_dosen',
            'admin' => $adminData,
            'dosen' => $dosenData
        ];

        return view('v_template_backend', $data);
    }

    public function DataMahasiswa()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        
        try {
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            $mahasiswaData = $modelAdmin->getAllMahasiswa();
            
            // Debug
            log_message('info', 'Data mahasiswa: ' . json_encode($mahasiswaData));
            
            $data = [
                'judul' => 'Data Mahasiswa',
                'page' => 'admin/v_data_mahasiswa',
                'admin' => $adminData,
                'mahasiswa' => $mahasiswaData
            ];

            return view('v_template_backend', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di DataMahasiswa: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
    }

    public function Profil()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $session = session();
        
        // Debug session
        $id_user = $session->get('id_user');
        if (!$id_user) {
            // Log data session untuk debugging
            log_message('error', 'Session data: ' . json_encode($session->get()));
            session()->setFlashdata('error', 'Sesi login tidak valid. Silakan login ulang.');
            return redirect()->to(base_url('Auth'));
        }
        
        // Ambil data admin berdasarkan user_id yang login
        $adminData = $modelAdmin->getAdminByUserId($id_user);
        
        // Jika data admin belum ada, insert data baru
        if (empty($adminData)) {
            $data = [
                'id_user' => $id_user,
                'nama' => $session->get('username') ?? '',
                'foto' => ''
            ];
            
            try {
                // Debug data yang akan diinsert
                log_message('info', 'Data yang akan diinsert: ' . json_encode($data));
                
                $modelAdmin->insert($data);
                $adminData = $modelAdmin->getAdminByUserId($id_user);
                
                if (!$adminData) {
                    throw new \Exception('Gagal mengambil data admin setelah insert');
                }
            } catch (\Exception $e) {
                log_message('error', 'Error saat insert admin: ' . $e->getMessage());
                session()->setFlashdata('error', 'Gagal membuat data admin. Silakan coba lagi.');
                return redirect()->to(base_url('Auth'));
            }
        }
        
        $data = [
            'judul' => 'Profil Admin',
            'page' => 'admin/v_profil',
            'admin' => $adminData
        ];

        return view('v_template_backend', $data);
    }

    public function updateProfil()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $id_admin = $this->request->getPost('id_admin');
        
        // Validasi foto
        $foto = $this->request->getFile('foto');
        $fotoLama = $this->request->getPost('foto_lama');
        
        if ($foto->isValid() && !$foto->hasMoved()) {
            // Hapus foto lama jika ada
            if ($fotoLama != '' && file_exists('foto/admin/' . $fotoLama)) {
                unlink('foto/admin/' . $fotoLama);
            }
            
            // Generate nama file baru
            $namaFoto = $foto->getRandomName();
            // Pindahkan foto ke folder public/foto/admin
            $foto->move('foto/admin', $namaFoto);
        } else {
            $namaFoto = $fotoLama;
        }
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'foto' => $namaFoto
        ];
        
        $modelAdmin->updateProfil($id_admin, $data);
        
        session()->setFlashdata('pesan', 'Profil berhasil diupdate');
        return redirect()->to('Admin/Profil');
    }

    public function editMahasiswa($id)
    {
        try {
            $modelAdmin = new \App\Models\ModelAdmin();
            $modelMahasiswa = new \App\Models\ModelMahasiswa();
            
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            $mahasiswaData = $modelMahasiswa->find($id);
            
            if (!$mahasiswaData) {
                throw new \Exception('Data mahasiswa tidak ditemukan');
            }
            
            $data = [
                'judul' => 'Edit Data Mahasiswa',
                'page' => 'admin/v_edit_mahasiswa',
                'admin' => $adminData,
                'mahasiswa' => $mahasiswaData
            ];

            return view('v_template_backend', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error di editMahasiswa: ' . $e->getMessage());
            session()->setFlashdata('error', 'Data mahasiswa tidak ditemukan');
            return redirect()->to('Admin/DataMahasiswa');
        }
    }

    public function updateMahasiswa()
    {
        try {
            $modelMahasiswa = new \App\Models\ModelMahasiswa();
            
            $id = $this->request->getPost('id_mahasiswa');
            if (!$id) {
                throw new \Exception('ID Mahasiswa tidak valid');
            }
            
            $data = [
                'nim' => $this->request->getPost('nim'),
                'nama' => $this->request->getPost('nama'),
                'prodi' => $this->request->getPost('prodi'),
                'angkatan' => $this->request->getPost('angkatan')
            ];
            
            if (!$modelMahasiswa->update($id, $data)) {
                throw new \Exception('Gagal mengupdate data');
            }
            
            session()->setFlashdata('pesan', 'Data berhasil diupdate');
            return redirect()->to('Admin/DataMahasiswa');
            
        } catch (\Exception $e) {
            log_message('error', 'Error di updateMahasiswa: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal mengupdate data');
            return redirect()->back();
        }
    }

    public function deleteMahasiswa($id)
    {
        try {
            $modelMahasiswa = new \App\Models\ModelMahasiswa();
            
            if (!$id) {
                throw new \Exception('ID Mahasiswa tidak valid');
            }
            
            if (!$modelMahasiswa->delete($id)) {
                throw new \Exception('Gagal menghapus data');
            }
            
            session()->setFlashdata('pesan', 'Data berhasil dihapus');
            return redirect()->to('Admin/DataMahasiswa');
            
        } catch (\Exception $e) {
            log_message('error', 'Error di deleteMahasiswa: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus data');
            return redirect()->back();
        }
    }

    public function deleteDosen($id_dosen)
    {
        try {
            $modelDosen = new \App\Models\ModelDosen();
            $db = \Config\Database::connect();
            
            // Mulai transaksi
            $db->transStart();
            
            // Ambil id_user dari dosen yang akan dihapus
            $dosen = $modelDosen->find($id_dosen);
            if (!$dosen) {
                throw new \Exception('Data dosen tidak ditemukan');
            }
            
            // Hapus foto dosen jika ada
            if (!empty($dosen['foto']) && file_exists('foto/dosen/' . $dosen['foto'])) {
                unlink('foto/dosen/' . $dosen['foto']);
            }
            
            // Hapus data dosen
            if (!$modelDosen->delete($id_dosen)) {
                throw new \Exception('Gagal menghapus data dosen');
            }
            
            // Hapus data user
            if (!empty($dosen['id_user'])) {
                $db->table('user')->where('id_user', $dosen['id_user'])->delete();
            }
            
            // Commit transaksi
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menghapus data dosen');
            }
            
            session()->setFlashdata('pesan', 'Data dosen berhasil dihapus');
            return redirect()->to('Admin/DataDosen');
            
        } catch (\Exception $e) {
            log_message('error', 'Error di deleteDosen: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus data dosen: ' . $e->getMessage());
            return redirect()->to('Admin/DataDosen');
        }
    }

    public function tambahAdmin()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
        
        $data = [
            'judul' => 'Tambah Admin',
            'page' => 'admin/v_tambah_admin',
            'admin' => $adminData
        ];

        return view('v_template_backend', $data);
    }

    public function simpanAdmin()
    {
        try {
            $rules = [
                'username' => 'required|min_length[4]|is_unique[user.username]',
                'email' => 'required|valid_email|is_unique[user.email]',
                'password' => 'required|min_length[6]',
                'nama' => 'required',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $modelAdmin = new \App\Models\ModelAdmin();

            // Data untuk tabel user
            $userData = [
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'email' => $this->request->getPost('email'),
                'role' => 'admin'
            ];

            // Data untuk tabel admin
            $adminData = [
                'nama' => $this->request->getPost('nama'),
                'foto' => ''
            ];

            // Upload foto jika ada
            $foto = $this->request->getFile('foto');
            if ($foto->isValid() && !$foto->hasMoved()) {
                $namaFoto = $foto->getRandomName();
                $foto->move('foto/admin', $namaFoto);
                $adminData['foto'] = $namaFoto;
            }

            if (!$modelAdmin->tambahAdmin($userData, $adminData)) {
                throw new \Exception('Gagal menyimpan data admin');
            }

            session()->setFlashdata('pesan', 'Data admin berhasil ditambahkan');
            return redirect()->to('Admin/DataAdmin');

        } catch (\Exception $e) {
            log_message('error', 'Error di simpanAdmin: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menambahkan admin. ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function tambahDosen()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
        
        $data = [
            'judul' => 'Tambah Dosen',
            'page' => 'admin/v_tambah_dosen',
            'admin' => $adminData
        ];

        return view('v_template_backend', $data);
    }

    public function simpanDosen()
    {
        try {
            $rules = [
                'username' => 'required|min_length[4]|is_unique[user.username]',
                'email' => 'required|valid_email|is_unique[user.email]',
                'password' => 'required|min_length[6]',
                'nama' => 'required',
                'nidn' => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $modelAdmin = new \App\Models\ModelAdmin();
            
            // Data untuk tabel user
            $userData = [
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'email' => $this->request->getPost('email'),
                'role' => 'dosen'  // Ubah role menjadi dosen
            ];

            // Data untuk tabel dosen
            $dosenData = [
                'nidn' => $this->request->getPost('nidn'),
                'nama' => $this->request->getPost('nama')
            ];

            if (!$modelAdmin->tambahDosen($userData, $dosenData)) {
                throw new \Exception('Gagal menyimpan data dosen');
            }

            session()->setFlashdata('pesan', 'Data dosen berhasil ditambahkan');
            return redirect()->to('Admin/DataDosen');

        } catch (\Exception $e) {
            log_message('error', 'Error di simpanDosen: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menambahkan dosen. ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function tambahMahasiswa()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
        $instansiData = $modelAdmin->getAllInstansi();
        
        $data = [
            'judul' => 'Tambah Mahasiswa',
            'page' => 'admin/v_tambah_mahasiswa',
            'admin' => $adminData,
            'instansi' => $instansiData
        ];

        return view('v_template_backend', $data);
    }
    public function simpanMahasiswa()
    {
        try {
            $rules = [
                'username' => 'required|min_length[4]|is_unique[user.username]',
                'email' => 'required|valid_email|is_unique[user.email]',
                'password' => 'required|min_length[6]',
                'nama' => 'required',
                'nim' => 'required',
                'angkatan' => 'required',
                'id_instansi' => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $modelAdmin = new \App\Models\ModelAdmin();
            
            // Data untuk tabel user
            $userData = [
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'email' => $this->request->getPost('email'),
                'role' => 'mahasiswa'
            ];

            // Data untuk tabel mahasiswa
            $mahasiswaData = [
                'nim' => $this->request->getPost('nim'),
                'nama' => $this->request->getPost('nama'),
                'angkatan' => $this->request->getPost('angkatan'),
                'id_instansi' => $this->request->getPost('id_instansi'),
                'foto' => ''
            ];

            // Upload foto jika ada
            $foto = $this->request->getFile('foto');
            if ($foto->isValid() && !$foto->hasMoved()) {
                $namaFoto = $foto->getRandomName();
                $foto->move('foto/mahasiswa', $namaFoto);
                $mahasiswaData['foto'] = $namaFoto;
            }

            if (!$modelAdmin->tambahMahasiswa($userData, $mahasiswaData)) {
                throw new \Exception('Gagal menyimpan data mahasiswa');
            }

            session()->setFlashdata('pesan', 'Data mahasiswa berhasil ditambahkan');
            return redirect()->to('Admin/DataMahasiswa');

        } catch (\Exception $e) {
            log_message('error', 'Error di simpanMahasiswa: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menambahkan mahasiswa. ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function Instansi()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        
        try {
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            $instansiData = $modelAdmin->getAllInstansi();
            
            $data = [
                'judul' => 'Data Instansi',
                'page' => 'admin/v_data_instansi',
                'admin' => $adminData,
                'instansi' => $instansiData
            ];

            return view('v_template_backend', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di Instansi: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
    }

    public function tambahInstansi()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
        
        $data = [
            'judul' => 'Tambah Instansi',
            'page' => 'admin/v_tambah_instansi',
            'admin' => $adminData
        ];

        return view('v_template_backend', $data);
    }

    public function simpanInstansi()
    {
        try {
            $rules = [
                'nama_instansi' => 'required',
                'alamat' => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $modelAdmin = new \App\Models\ModelAdmin();
            
            $data = [
                'nama_instansi' => $this->request->getPost('nama_instansi'),
                'alamat' => $this->request->getPost('alamat')
            ];

            if (!$modelAdmin->tambahInstansi($data)) {
                throw new \Exception('Gagal menyimpan data instansi');
            }

            session()->setFlashdata('pesan', 'Data instansi berhasil ditambahkan');
            return redirect()->to('Admin/Instansi');

        } catch (\Exception $e) {
            log_message('error', 'Error di simpanInstansi: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menambahkan instansi. ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function editInstansi($id_instansi)
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        
        try {
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            $instansiData = $modelAdmin->getInstansiById($id_instansi);
            
            if (!$instansiData) {
                throw new \Exception('Data instansi tidak ditemukan');
            }
            
            $data = [
                'judul' => 'Edit Instansi',
                'page' => 'admin/v_edit_instansi',
                'admin' => $adminData,
                'instansi' => $instansiData
            ];

            return view('v_template_backend', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di editInstansi: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data');
            return redirect()->to('Admin/Instansi');
        }
    }

    public function updateInstansi()
    {
        try {
            $rules = [
                'id_instansi' => 'required',
                'nama_instansi' => 'required',
                'alamat' => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $modelAdmin = new \App\Models\ModelAdmin();
            
            $id_instansi = $this->request->getPost('id_instansi');
            $data = [
                'nama_instansi' => $this->request->getPost('nama_instansi'),
                'alamat' => $this->request->getPost('alamat')
            ];

            if (!$modelAdmin->updateInstansi($id_instansi, $data)) {
                throw new \Exception('Gagal mengupdate data instansi');
            }

            session()->setFlashdata('pesan', 'Data instansi berhasil diupdate');
            return redirect()->to('Admin/Instansi');

        } catch (\Exception $e) {
            log_message('error', 'Error di updateInstansi: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal mengupdate instansi. ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function deleteInstansi($id_instansi)
    {
        try {
            $modelAdmin = new \App\Models\ModelAdmin();
            
            if (!$modelAdmin->deleteInstansi($id_instansi)) {
                throw new \Exception('Gagal menghapus data instansi');
            }

            session()->setFlashdata('pesan', 'Data instansi berhasil dihapus');
            return redirect()->to('Admin/Instansi');

        } catch (\Exception $e) {
            log_message('error', 'Error di deleteInstansi: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus instansi. ' . $e->getMessage());
            return redirect()->to('Admin/Instansi');
        }
    }

    public function getDetailPengajuan($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        try {
            // Ambil data pengajuan
            $pengajuan = $modelMahasiswa->getPengajuanById($id);
            if (!$pengajuan) {
                throw new \Exception('Data pengajuan tidak ditemukan');
            }

            // Ambil data anggota kelompok
            $anggota = $modelMahasiswa->getAnggotaKelompokDetail($id);

            return $this->response->setJSON([
                'status' => true,
                'pengajuan' => $pengajuan,
                'anggota' => $anggota
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function updateStatusPengajuan()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $id_dosen = $this->request->getPost('id_dosen_pembimbing');
        
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        try {
            $db = \Config\Database::connect();
            $db->transStart();

            $data = ['status' => $status];

            // Jika status disetujui
            if ($status === 'disetujui') {
                // Validasi dosen pembimbing harus dipilih
                if (empty($id_dosen)) {
                    throw new \Exception('Dosen pembimbing harus dipilih');
                }

                // Update dosen pembimbing - menggunakan id_dosen sesuai nama kolom di database
                $db->table('pengajuan_magang')->where('id', $id)->update([
                    'id_dosen' => $id_dosen
                ]);

                // Proses upload surat pengantar
                $fileSurat = $this->request->getFile('surat_pengantar');
                if ($fileSurat->isValid() && !$fileSurat->hasMoved()) {
                    if ($fileSurat->getClientMimeType() !== 'application/pdf') {
                        throw new \Exception('File harus dalam format PDF');
                    }
                    if ($fileSurat->getSize() > 2097152) {
                        throw new \Exception('Ukuran file maksimal 2MB');
                    }
                    
                    $namaSurat = $fileSurat->getRandomName();
                    if (!is_dir('uploads/surat_pengantar')) {
                        mkdir('uploads/surat_pengantar', 0777, true);
                    }
                    $fileSurat->move('uploads/surat_pengantar', $namaSurat);
                    $data['surat_pengantar'] = $namaSurat;
                } else {
                    throw new \Exception('Surat pengantar wajib diupload');
                }
            }

            if (!$modelMahasiswa->updateStatusPengajuan($id, $data)) {
                throw new \Exception('Gagal mengupdate status pengajuan');
            }
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal mengupdate status pengajuan');
            }

            session()->setFlashdata('pesan', 'Status pengajuan berhasil diupdate');
            
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e->getMessage());
        }
        
        return redirect()->to('Admin/PengajuanMahasiswa');
    }

    public function deletePengajuan()
    {
        $id = $this->request->getPost('id');
        $modelMahasiswa = new \App\Models\ModelMahasiswa();

        try {
            // Mulai transaksi
            $db = \Config\Database::connect();
            $db->transStart();

            // Hapus anggota kelompok terlebih dahulu
            $modelMahasiswa->deleteAnggotaKelompok($id);
            
            // Hapus pengajuan
            $modelMahasiswa->deletePengajuan($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menghapus pengajuan magang');
            }

            session()->setFlashdata('pesan', 'Pengajuan magang berhasil dihapus');
            
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal menghapus pengajuan magang. Silakan coba lagi.');
            log_message('error', 'Error saat menghapus pengajuan magang: ' . $e->getMessage());
        }

        return redirect()->to('Admin/PengajuanMahasiswa');
    }

    public function Dokumen()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        
        try {
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            $dokumenData = $modelAdmin->getAllDokumen();
            
            $data = [
                'judul' => 'Kelola Dokumen',
                'page' => 'admin/v_dokumen',
                'admin' => $adminData,
                'dokumen' => $dokumenData
            ];

            return view('v_template_backend', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di Dokumen: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
    }

    public function tambahDokumen()
    {
        try {
            $file = $this->request->getFile('file_dokumen');
            $fileName = $file->getRandomName();

            $data = [
                'nama_dokumen' => $this->request->getPost('nama_dokumen'),
                'file_dokumen' => $fileName,
                'keterangan' => $this->request->getPost('keterangan'),
                'status' => $this->request->getPost('status'),
                'tgl_upload' => date('Y-m-d H:i:s')
            ];

            $modelAdmin = new \App\Models\ModelAdmin();

            // Upload file
            if ($file->isValid() && !$file->hasMoved()) {
                $file->move('uploads/dokumen', $fileName);
            } else {
                throw new \Exception('Gagal mengupload file');
            }

            // Simpan ke database
            if (!$modelAdmin->tambahDokumen($data)) {
                throw new \Exception('Gagal menyimpan data dokumen');
            }

            session()->setFlashdata('pesan', 'Dokumen berhasil ditambahkan');
            return redirect()->to('Admin/Dokumen');

        } catch (\Exception $e) {
            log_message('error', 'Error di tambahDokumen: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menambahkan dokumen. ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function hapusDokumen($id)
    {
        try {
            $modelAdmin = new \App\Models\ModelAdmin();
            
            // Ambil info file sebelum dihapus
            $db = \Config\Database::connect();
            $dokumen = $db->table('dokumen')->where('id_dokumen', $id)->get()->getRowArray();
            
            if ($dokumen) {
                // Hapus file fisik
                $filePath = 'uploads/dokumen/' . $dokumen['file_dokumen'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Hapus record dari database
                if (!$modelAdmin->hapusDokumen($id)) {
                    throw new \Exception('Gagal menghapus data dokumen');
                }
                
                session()->setFlashdata('pesan', 'Dokumen berhasil dihapus');
            } else {
                session()->setFlashdata('error', 'Dokumen tidak ditemukan');
            }
            
            return redirect()->to('Admin/Dokumen');

        } catch (\Exception $e) {
            log_message('error', 'Error di hapusDokumen: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus dokumen. ' . $e->getMessage());
            return redirect()->to('Admin/Dokumen');
        }
    }

    public function Absensi()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        try {
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            
            // Debug: tampilkan query yang dijalankan
            $kelompokMagang = $modelMahasiswa->getAllKelompokMagang();
            log_message('info', 'Data kelompok magang: ' . json_encode($kelompokMagang));
            
            $data = [
                'judul' => 'Data Absensi Mahasiswa',
                'page' => 'admin/v_absensi',
                'admin' => $adminData,
                'kelompok' => $kelompokMagang
            ];

            return view('v_template_backend', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di Absensi: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function getAbsensiKelompok($id_pengajuan)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        try {
            // Debug: log ID pengajuan
            log_message('info', 'Mengambil absensi untuk pengajuan ID: ' . $id_pengajuan);
            
            // Ambil data anggota kelompok dan absensinya
            $anggotaKelompok = $modelMahasiswa->getAnggotaKelompokDetail($id_pengajuan);
            log_message('info', 'Data anggota kelompok: ' . json_encode($anggotaKelompok));
            
            $dataAbsensi = [];
            
            foreach ($anggotaKelompok as $anggota) {
                $absensi = $modelMahasiswa->getAbsensiMahasiswa($anggota['id_mahasiswa']);
                $dataAbsensi[$anggota['id_mahasiswa']] = $absensi;
            }

            return $this->response->setJSON([
                'status' => true,
                'anggota' => $anggotaKelompok,
                'absensi' => $dataAbsensi
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error di getAbsensiKelompok: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function Logbook()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $modelLogbook = new \App\Models\ModelLogbook();
        
        try {
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            $logbookData = $modelLogbook->getAllLogbook();
            
            $data = [
                'judul' => 'Data Logbook Mahasiswa',
                'page' => 'admin/v_logbook',
                'admin' => $adminData,
                'logbook' => $logbookData
            ];

            return view('v_template_backend', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di Logbook: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
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

    public function LoA()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $modelLoA = new \App\Models\ModelLoA();
        
        try {
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            $loaData = $modelLoA->getAllLoA();
            
            $data = [
                'judul' => 'Data LoA Journal',
                'page' => 'admin/v_loa',
                'admin' => $adminData,
                'loa' => $loaData
            ];

            return view('v_template_backend', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di LoA: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
    }

    public function updateStatusLoA()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $modelLoA = new \App\Models\ModelLoA();
        
        try {
            $id = $this->request->getPost('id_loa');
            $status = $this->request->getPost('status');
            
            if (!$modelLoA->update($id, ['status' => $status])) {
                throw new \Exception('Gagal mengupdate status LoA');
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Status LoA berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function updateCatatanLoA($id)
    {
        try {
            $modelLoA = new \App\Models\ModelLoA();
            $catatan = $this->request->getPost('catatan');
            
            if (!$modelLoA->update($id, ['catatan' => $catatan])) {
                throw new \Exception('Gagal mengupdate catatan LoA');
            }
            
            session()->setFlashdata('pesan', 'Catatan berhasil diupdate');
            return redirect()->to('Admin/LoA');
            
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal mengupdate catatan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function DataAngkatan()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $db = \Config\Database::connect();

        // Get all students with their data
        $mahasiswa = $modelAdmin->getAllMahasiswa();

        // Get unique angkatan years
        $angkatan_list = array_unique(array_column($mahasiswa, 'angkatan'));
        sort($angkatan_list); // Sort years in ascending order

        // Get status magang for each student
        $status_magang = [];
        foreach ($mahasiswa as $mhs) {
            $query = $db->table('pengajuan_magang pm')
                       ->join('anggota_kelompok ak', 'ak.pengajuan_id = pm.id')
                       ->where('ak.mahasiswa_id', $mhs['id_mahasiswa'])
                       ->select('pm.status')
                       ->get();
            
            $result = $query->getRowArray();
            if ($result) {
                $status_magang[$mhs['id_mahasiswa']] = $result['status'];
            }
        }

        $data = [
            'judul' => 'Data Mahasiswa per Angkatan',
            'page' => 'admin/v_data_angkatan',
            'mahasiswa' => $mahasiswa,
            'angkatan_list' => $angkatan_list,
            'status_magang' => $status_magang,
            'admin' => $this->admin
        ];

        return view('v_template_backend', $data);
    }

    public function cetakPDFAngkatan()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $db = \Config\Database::connect();
        
        // Get filter parameter
        $angkatan = $this->request->getGet('angkatan');
        
        // Get all students with their data
        $mahasiswa = $modelAdmin->getAllMahasiswa();
        
        // Filter by angkatan if specified
        if (!empty($angkatan)) {
            $mahasiswa = array_filter($mahasiswa, function($mhs) use ($angkatan) {
                return $mhs['angkatan'] == $angkatan;
            });
        }

        // Get status magang for each student
        $status_magang = [];
        foreach ($mahasiswa as $mhs) {
            $query = $db->table('pengajuan_magang pm')
                       ->join('anggota_kelompok ak', 'ak.pengajuan_id = pm.id')
                       ->where('ak.mahasiswa_id', $mhs['id_mahasiswa'])
                       ->select('pm.status')
                       ->get();
            
            $result = $query->getRowArray();
            if ($result) {
                $status_magang[$mhs['id_mahasiswa']] = $result['status'];
            }
        }

        // Create new TCPDF object using helper
        $pdf = tcpdf();
        
        // Set document information
        $pdf->SetCreator('SiMagang');
        $pdf->SetAuthor('Admin SiMagang');
        $pdf->SetTitle('Data Mahasiswa per Angkatan');

        // Remove header and footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Add a page in landscape orientation
        $pdf->AddPage('L', 'A4');
        
        // Set font
        $pdf->SetFont('helvetica', 'B', 16);
        
        // Title
        $title = 'Data Mahasiswa' . (!empty($angkatan) ? " Angkatan " . $angkatan : " Semua Angkatan");
        $pdf->Cell(0, 15, $title, 0, 1, 'C');
        
        // Set font for table header
        $pdf->SetFont('helvetica', 'B', 11);
        
        // Table header
        $pdf->Cell(15, 10, 'No', 1, 0, 'C');
        $pdf->Cell(30, 10, 'NIM', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Nama', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Angkatan', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Email', 1, 0, 'C');
        $pdf->Cell(50, 10, 'Instansi', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Status', 1, 1, 'C');
        
        // Set font for table content
        $pdf->SetFont('helvetica', '', 10);
        
        // Table content
        $no = 1;
        foreach ($mahasiswa as $mhs) {
            $pdf->Cell(15, 10, $no++, 1, 0, 'C');
            $pdf->Cell(30, 10, $mhs['nim'] ?? '-', 1, 0, 'C');
            $pdf->Cell(60, 10, $mhs['nama'], 1, 0, 'L');
            $pdf->Cell(30, 10, $mhs['angkatan'] ?? '-', 1, 0, 'C');
            $pdf->Cell(60, 10, $mhs['email'] ?? '-', 1, 0, 'L');
            $pdf->Cell(50, 10, $mhs['instansi'] ?? '-', 1, 0, 'L');
            
            // Status
            $status = 'Belum Mendaftar';
            if (isset($status_magang[$mhs['id_mahasiswa']])) {
                switch ($status_magang[$mhs['id_mahasiswa']]) {
                    case 'pending':
                        $status = 'Menunggu';
                        break;
                    case 'disetujui':
                        $status = 'Aktif Magang';
                        break;
                    case 'ditolak':
                        $status = 'Ditolak';
                        break;
                }
            }
            $pdf->Cell(30, 10, $status, 1, 1, 'C');
        }
        
        // Output PDF
        $pdf->Output('Data_Mahasiswa_' . (!empty($angkatan) ? 'Angkatan_'.$angkatan : 'Semua_Angkatan') . '.pdf', 'I');
        exit();
    }

    public function ESertifikat()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        try {
            $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
            
            // Ambil data mahasiswa yang sudah dinilai
            $mahasiswaDinilai = $modelMahasiswa->getMahasiswaDinilai();
            
            $data = [
                'judul' => 'E-Sertifikat',
                'page' => 'admin/v_esertifikat',
                'admin' => $adminData,
                'mahasiswa' => $mahasiswaDinilai
            ];

            return view('v_template_backend', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di ESertifikat: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
    }

    public function generateSertifikat($id_mahasiswa)
    {
        try {
            $modelMahasiswa = new \App\Models\ModelMahasiswa();
            $mahasiswa = $modelMahasiswa->getMahasiswaById($id_mahasiswa);
            
            if (!$mahasiswa) {
                throw new \Exception('Data mahasiswa tidak ditemukan');
            }
            
            // Ambil data nilai
            $nilai = $modelMahasiswa->getNilaiMahasiswa($id_mahasiswa);
            if (empty($nilai)) {
                throw new \Exception('Nilai mahasiswa belum tersedia');
            }

            // Generate nomor sertifikat
            $noSertifikat = 'CERT/' . date('Y') . '/' . str_pad($id_mahasiswa, 4, '0', STR_PAD_LEFT);
            
            // Load library TCPDF
            $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');
            
            // Set document information
            $pdf->SetCreator('Sistem Informasi Magang');
            $pdf->SetAuthor('Program Studi Sistem Informasi');
            $pdf->SetTitle('E-Sertifikat Magang - ' . $mahasiswa['nama']);

            // Remove header and footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // Set margins
            $pdf->SetMargins(0, 0, 0);
            $pdf->SetAutoPageBreak(false, 0);

            // Add a page
            $pdf->AddPage('L', 'A4');

            // Set background image
            $imagePath = FCPATH . 'img/Frame1.png';
            if (file_exists($imagePath)) {
                // Debug: Log file info
                log_message('info', 'Loading image from: ' . $imagePath);
                log_message('info', 'File size: ' . filesize($imagePath) . ' bytes');
                
                // Get page dimensions in mm
                $pageWidth = 297;  // A4 landscape width in mm
                $pageHeight = 210; // A4 landscape height in mm
                
                // Add image with exact dimensions
                $pdf->Image($imagePath, 0, 0, $pageWidth, $pageHeight, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
            } else {
                log_message('error', 'Certificate template not found at: ' . $imagePath);
            }

            // Set font untuk nomor sertifikat
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 65);
            $pdf->Cell(297, 0, 'No : ' . $noSertifikat, 0, 1, 'C');
            $pdf->SetXY(0, 66);
            $pdf->Cell(297, 0, '__________________', 0, 1, 'C');

            // Text "Diberikan Kepada"
            $pdf->SetFont('helvetica', '', 14);
            $pdf->SetXY(0, 75);
            $pdf->Cell(297, 0, 'Diberikan Kepada', 0, 1, 'C');

            // Nama Mahasiswa
            $pdf->SetFont('helvetica', 'B', 24);
            $pdf->SetXY(0, 90);
            $pdf->Cell(297, 0, $mahasiswa['nama'], 0, 1, 'C');

            // Keterangan magang
            $pdf->SetFont('helvetica', '', 14);
            $pdf->SetXY(0, 110);
            $pdf->writeHTML('<div style="text-align:center;">telah berhasil menyelesaikan <b>Magang</b><br>di ' . $mahasiswa['instansi'] . ' dengan nilai ' . $nilai[0]['nilai'] . '</div>', true, false, true, false, 'C');

            // Informasi program studi
            $pdf->SetFont('helvetica', '', 14);
            $pdf->SetXY(0, 130);
            $pdf->Cell(297, 0, 'yang diselenggarakan oleh', 0, 1, 'C');
            
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->SetXY(0, 140);
            $pdf->Cell(297, 0, 'Program Studi Sistem Informasi Fakultas Sains dan Teknologi', 0, 1, 'C');
            $pdf->SetXY(0, 145);
            $pdf->Cell(297, 0, 'UIN Sulthan Thaha Saifuddin Jambi', 0, 1, 'C');

            // Tanda tangan
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetXY(50, 160);
            $pdf->Cell(80, 0, 'Kepala Program Studi', 0, 0, 'C');
            $pdf->SetXY(170, 160);
            $pdf->Cell(80, 0, 'Sekretaris Program Studi', 0, 0, 'C');

            $pdf->SetXY(50, 185);
            $pdf->Cell(80, 0, '_____________________', 0, 0, 'C');
            $pdf->SetXY(170, 185);
            $pdf->Cell(80, 0, '_____________________', 0, 0, 'C');

            $pdf->SetXY(50, 190);
            $pdf->Cell(80, 0, 'Pol Metra', 0, 0, 'C');
            $pdf->SetXY(170, 190);
            $pdf->Cell(80, 0, 'Heri Afriadi', 0, 0, 'C');

            // Set header untuk download PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="Sertifikat_' . $mahasiswa['nim'] . '.pdf"');
            
            // Output PDF
            $pdf->Output('Sertifikat_' . $mahasiswa['nim'] . '.pdf', 'I');
            exit();
            
        } catch (\Exception $e) {
            log_message('error', 'Error di generateSertifikat: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal generate sertifikat: ' . $e->getMessage());
            return redirect()->to('Admin/ESertifikat');
        }
    }
}
