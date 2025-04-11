<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ModelAuth;

class Auth extends BaseController
{
    protected $modelAuth;

    public function __construct()
    {
        $this->modelAuth = new ModelAuth();
        helper(['form', 'url']);
    }

    public function index()
    {
        // Redirect jika sudah login
        // if (session()->get('is_logged_in')) {
        //     return $this->redirectToDashboard();
        // }

        $data['validation'] = \Config\Services::validation();

        if ($this->request->getMethod() === 'post') {
            return $this->login();
        }

        return view('v_login', $data);
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->modelAuth->checkUser($username, $password);

        if ($user) {
            session()->set([
                'id_user' => $user['id_user'],
                'username' => $user['username'],
                'role' => $user['role'],
                'logged_in' => TRUE
            ]);

            // Pengalihan berdasarkan role
            switch ($user['role']) {
                case 'admin':
                    return redirect()->to('/admin'); // Mengarah ke method index di Admin
                case 'dosen':
                    return redirect()->to('/dosen'); // Ganti dengan route dashboard dosen
                case 'mahasiswa':
                    return redirect()->to('/mahasiswa'); // Ganti dengan route dashboard mahasiswa
                default:
                    return redirect()->to('/'); // Pengalihan default
            }
        } else {
            return redirect()->back()->with('error', 'Username atau password salah');
        }
    }

    private function setUserSession($user)
    {
        $sessionData = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'is_logged_in' => true
        ];

        switch ($user['role']) {
            case 'admin':
                $sessionData['admin_id'] = $user['admin_data']['id'];
                $sessionData['nama'] = $user['admin_data']['nama'];
                break;

            case 'dosen':
                $sessionData['dosen_id'] = $user['dosen_data']['id'];
                $sessionData['nama'] = $user['dosen_data']['nama'];
                $sessionData['nip'] = $user['dosen_data']['nip'];
                break;

            case 'mahasiswa':
                $sessionData['mahasiswa_id'] = $user['mahasiswa_data']['id'];
                $sessionData['nama'] = $user['mahasiswa_data']['nama'];
                $sessionData['nim'] = $user['mahasiswa_data']['nim'];
                $sessionData['instansi_id'] = $user['mahasiswa_data']['instansi_id'];
                break;
        }

        session()->set($sessionData);
    }

    private function redirectToDashboard()
    {
        switch (session()->get('role')) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'dosen':
                return redirect()->to('/dosen/dashboard');
            case 'mahasiswa':
                return redirect()->to('/mahasiswa/dashboard');
            default:
                return redirect()->to('/');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('Auth');
    }

    public function register()
    {
        log_message('info', 'Masuk ke metode register');
        $data['validation'] = \Config\Services::validation();
        return view('v_register', $data);
    }

    public function store()
    {
        log_message('info', 'Masuk ke metode store');

        // Tambahkan log untuk memeriksa apakah data POST ada
        log_message('info', 'Data POST: ' . json_encode($this->request->getPost()));

        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]|is_unique[user.username]',
            'email'    => 'required|valid_email|is_unique[user.email]',
            'passwordsignin' => 'required|min_length[6]',
            'confirmpassword' => 'required|matches[passwordsignin]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            log_message('info', 'Validasi gagal: ' . json_encode($validation->getErrors()));
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Debugging: Tampilkan data yang akan disimpan
        log_message('info', 'Data yang akan disimpan: ' . json_encode([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('passwordsignin'), PASSWORD_DEFAULT),
            'role'     => 'mahasiswa'
        ]));

        $this->modelAuth->save([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('passwordsignin'), PASSWORD_DEFAULT),
            'role'     => 'mahasiswa'
        ]);

        log_message('info', 'Data berhasil disimpan');

        // Set flashdata untuk notifikasi sukses
        return redirect()->to('/Auth')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function lupaPassword()
    {
        return view('v_lupapassword');
    }

    public function prosesLupaPassword()
    {
        $email = $this->request->getPost('email');
        $model = new \App\Models\ModelAuth();
        
        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            session()->setFlashdata('error', 'Format email tidak valid');
            return redirect()->back();
        }
        
        // Cek apakah email ada di database
        $user = $model->where('email', $email)->first();
        
        if (!$user) {
            session()->setFlashdata('error', 'Email tidak terdaftar dalam sistem');
            return redirect()->back();
        }

        try {
            // Generate token reset password
            $token = bin2hex(random_bytes(32));
            $expired = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Simpan token ke database
            $result = $model->update($user['id_user'], [
                'reset_token' => $token,
                'reset_expired' => $expired
            ]);

            if (!$result) {
                log_message('error', 'Gagal update token reset password: ' . json_encode($model->errors()));
                session()->setFlashdata('error', 'Gagal memproses permintaan reset password. Silakan coba lagi nanti.');
                return redirect()->back();
            }

            // Konfigurasi email
            $email = \Config\Services::email();
            
            $email->setFrom('rezkikurniasnp@gmail.com', 'SiMagang System');
            $email->setTo($user['email']);
            $email->setSubject('Reset Password SiMagang');
            
            $message = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .container { padding: 20px; }
                        .btn { 
                            background-color: #4CAF50;
                            border: none;
                            color: white;
                            padding: 15px 32px;
                            text-align: center;
                            text-decoration: none;
                            display: inline-block;
                            font-size: 16px;
                            margin: 4px 2px;
                            cursor: pointer;
                            border-radius: 4px;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <h2>Reset Password SiMagang</h2>
                        <p>Kami menerima permintaan untuk mereset password akun SiMagang Anda.</p>
                        <p>Silakan klik tombol di bawah ini untuk mereset password Anda:</p>
                        <p>
                            <a href='" . base_url("Auth/resetPassword/$token") . "' class='btn'>Reset Password</a>
                        </p>
                        <p>Link ini akan kadaluarsa dalam 1 jam.</p>
                        <p>Jika Anda tidak merasa meminta reset password, abaikan email ini.</p>
                        <br>
                        <p>Hormat kami,<br>Tim SiMagang</p>
                    </div>
                </body>
                </html>
            ";
            
            $email->setMessage($message);
            
            if ($email->send()) {
                session()->setFlashdata('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
            } else {
                // Log error untuk debugging
                log_message('error', 'Error kirim email: ' . $email->printDebugger(['headers']));
                session()->setFlashdata('error', 'Gagal mengirim email reset password. Silakan coba lagi nanti.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error dalam proses reset password: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi nanti.');
        }
        
        return redirect()->back();
    }

    public function resetPassword($token)
    {
        $model = new \App\Models\ModelAuth();
        
        // Cek token dan expired time
        $user = $model->where('reset_token', $token)
                     ->where('reset_expired >', date('Y-m-d H:i:s'))
                     ->first();
        
        if (!$user) {
            session()->setFlashdata('error', 'Link reset password tidak valid atau sudah kadaluarsa');
            return redirect()->to('Auth/lupaPassword');
        }

        $data = [
            'token' => $token,
            'validation' => \Config\Services::validation()
        ];

        return view('v_reset_password', $data);
    }

    public function prosesResetPassword()
    {
        // Validasi input
        $rules = [
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        
        $model = new \App\Models\ModelAuth();
        
        // Cek token dan expired time
        $user = $model->where('reset_token', $token)
                     ->where('reset_expired >', date('Y-m-d H:i:s'))
                     ->first();
        
        if (!$user) {
            session()->setFlashdata('error', 'Link reset password tidak valid atau sudah kadaluarsa');
            return redirect()->to('Auth/lupaPassword');
        }

        try {
            // Update password
            $result = $model->update($user['id_user'], [
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'reset_token' => null,
                'reset_expired' => null
            ]);

            if (!$result) {
                log_message('error', 'Gagal update password: ' . json_encode($model->errors()));
                session()->setFlashdata('error', 'Gagal mereset password. Silakan coba lagi nanti.');
                return redirect()->back();
            }

            session()->setFlashdata('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
            return redirect()->to('Auth');
            
        } catch (\Exception $e) {
            log_message('error', 'Error dalam proses update password: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi nanti.');
            return redirect()->back();
        }
    }
}
