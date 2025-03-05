<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika user belum login dan mencoba mengakses halaman selain Auth
        if (!session()->get('logged_in') && !in_array(uri_string(), ['Auth', 'Auth/login', 'Auth/register'])) {
            return redirect()->to(base_url('Auth'));
        }

        // Jika user sudah login dan mencoba mengakses Auth
        if (session()->get('logged_in') && uri_string() === 'Auth') {
            $role = session()->get('role');
            return redirect()->to(base_url($role));
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
} 