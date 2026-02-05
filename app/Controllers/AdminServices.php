<?php

namespace App\Controllers;

use App\Models\ServiceModel;    

class AdminServices extends BaseController
{
    public function dashboard()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login')->with('error', 'Please login first');
        }

        $data = [
            'title' => 'Services Dashboard',
            'activeMenu' => 'services'
        ];

        return view('admin/services/dashboard', $data);
    }   
}