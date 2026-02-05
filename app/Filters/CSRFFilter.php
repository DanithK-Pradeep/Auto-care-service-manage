<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Filters\CSRF;

class CSRFFilter extends CSRF implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip CSRF check for AJAX requests with X-Requested-With header
        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return;
        }

        // Apply normal CSRF check for other requests
        return parent::before($request, $arguments);
    }
}
