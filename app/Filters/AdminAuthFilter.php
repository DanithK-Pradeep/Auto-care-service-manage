<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
{
    if (!session()->get('admin_logged_in')) {

        // Prevent repeated flash messages
        if (!session()->has('error')) {
            session()->setFlashdata('error', 'Please login first');
        }

        return redirect()->to('/admin/login');
    }
}


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}
