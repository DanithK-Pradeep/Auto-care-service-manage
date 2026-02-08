<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class EmployeeAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('employee_logged_in')) {
            return redirect()->to(site_url('employee/login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
