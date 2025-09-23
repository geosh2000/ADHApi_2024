<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;

class AppController extends BaseController
{
    public function index()
    {
        if (!session()->get('shortname')) {
            return redirect()->to(site_url('login'));
        }
        $data['username'] = session()->get('shortname');
        return view('app/home', $data);
    }
}