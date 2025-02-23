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
        if (session()->get('is_logged_in')) {
            return $this->redirectToDashboard();
        }

        $data['validation'] = \Config\Services::validation();

        if ($this->request->getMethod() === 'post') {
            return $this->processLogin();
        }

        return view('v_login', $data);
    }

    private function processLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $credentials = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ];

        $user = $this->modelAuth->checkUser(
            $credentials['email'],
            $credentials['password']
        );

        if ($user) {
            $this->setUserSession($user);
            return $this->redirectToDashboard();
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Email atau password salah');
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
        return redirect()->to('/login');
    }
}
