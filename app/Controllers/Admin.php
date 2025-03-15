<?php

namespace App\Controllers;

class Admin extends BaseController
{
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
            'total_mahasiswa' => $db->table('mahasiswa')->countAllResults(),
            'total_instansi' => $db->table('instansi')->countAllResults(),
            'total_dosen' => $db->table('dosen_pembimbing')->countAllResults(),
        ];

        // Cek struktur tabel pengajuan_magang
        try {
            // Ambil pengajuan aktif
            $data['total_pengajuan_aktif'] = $db->table('pengajuan')
                                               ->where('status', 'Menunggu')
                                               ->countAllResults();

            // Ambil pengajuan terbaru
            $data['pengajuan_terbaru'] = $db->table('pengajuan')
                                           ->select('pengajuan.*, mahasiswa.nama as nama_mahasiswa')
                                           ->join('mahasiswa', 'mahasiswa.nim = pengajuan.nim')
                                           ->orderBy('tgl_pengajuan', 'DESC')
                                           ->limit(5)
                                           ->get()
                                           ->getResultArray();

            // Data untuk pie chart status pengajuan
            $data['status_pengajuan'] = [
                'Menunggu' => $db->table('pengajuan')->where('status', 'Menunggu')->countAllResults(),
                'Diterima' => $db->table('pengajuan')->where('status', 'Diterima')->countAllResults(),
                'Ditolak' => $db->table('pengajuan')->where('status', 'Ditolak')->countAllResults()
            ];

            // Data untuk grafik statistik 6 bulan terakhir
            $bulan = [];
            $pengajuan = [];
            $diterima = [];
            $ditolak = [];

            for ($i = 5; $i >= 0; $i--) {
                $bulan_ini = date('Y-m', strtotime("-$i month"));
                $bulan[] = date('M', strtotime("-$i month"));

                $pengajuan[] = $db->table('pengajuan')
                                 ->where('DATE_FORMAT(tgl_pengajuan, "%Y-%m")', $bulan_ini)
                                 ->countAllResults();

                $diterima[] = $db->table('pengajuan')
                                ->where('DATE_FORMAT(tgl_pengajuan, "%Y-%m")', $bulan_ini)
                                ->where('status', 'Diterima')
                                ->countAllResults();

                $ditolak[] = $db->table('pengajuan')
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
        $adminData = $modelAdmin->getAdminByUserId(session()->get('id_user'));
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        $data = [
            'judul'     => 'Data Pengajuan Magang',
            'page'      => 'admin/v_pengajuan_mahasiswa',
            'pengajuan' => $modelMahasiswa->getAllPengajuan(),
            'admin'     => $adminData,
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
        
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        try {
            $data = ['status' => $status];

            // Jika status disetujui, proses upload surat pengantar
            if ($status === 'disetujui') {
                $fileSurat = $this->request->getFile('surat_pengantar');
                
                if ($fileSurat->isValid() && !$fileSurat->hasMoved()) {
                    // Validasi tipe file
                    if ($fileSurat->getClientMimeType() !== 'application/pdf') {
                        throw new \Exception('File harus dalam format PDF');
                    }
                    
                    // Validasi ukuran file (2MB)
                    if ($fileSurat->getSize() > 2097152) {
                        throw new \Exception('Ukuran file maksimal 2MB');
                    }
                    
                    // Generate nama unik untuk file
                    $namaSurat = $fileSurat->getRandomName();
                    
                    // Buat direktori jika belum ada
                    if (!is_dir('uploads/surat_pengantar')) {
                        mkdir('uploads/surat_pengantar', 0777, true);
                    }
                    
                    // Pindahkan file
                    $fileSurat->move('uploads/surat_pengantar', $namaSurat);
                    
                    // Tambahkan nama file ke data yang akan diupdate
                    $data['surat_pengantar'] = $namaSurat;
                } else {
                    throw new \Exception('Surat pengantar wajib diupload');
                }
            }

            $modelMahasiswa->updateStatusPengajuan($id, $data);
            session()->setFlashdata('pesan', 'Status pengajuan berhasil diupdate');
            
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal mengupdate status pengajuan: ' . $e->getMessage());
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
}
