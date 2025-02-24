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
                'username' => $user['username'],
                'role' => $user['role'],
                'is_logged_in' => true
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
        $data['validation'] = \Config\Services::validation();
        return view('v_register', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'fullname' => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[user.email]',
            'passwordsignin' => 'required|min_length[6]',
            'confirmpassword' => 'required|matches[passwordsignin]',
            'agree' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->modelAuth->save([
            'nama'     => $this->request->getPost('fullname'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('passwordsignin'), PASSWORD_DEFAULT),
            'role'     => 'mahasiswa'
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
