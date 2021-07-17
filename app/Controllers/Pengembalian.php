<?php

namespace App\Controllers;

use App\Models\Serverside_model;
use Config\Services;

class Pengembalian extends BaseController
{
    protected $m_pengembalian;
    protected $m_serverside;
    protected $m_validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_serverside = new Serverside_model();
    }

    public function index()
    {
        if (user()->update_bio == 0) {
            return redirect()->to(base_url('home/form_edit_profile'));
        } else {
            $data['title'] = 'Pengembalian';
            $data['title_page'] = 'Pengembalian';
            $data['menu'] = 'pengembalian';
            return view('pengembalian/pengembalian', $data);
        }
    }
}
