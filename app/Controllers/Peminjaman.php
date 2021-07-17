<?php

namespace App\Controllers;

use App\Models\Serverside_model;

class Peminjaman extends BaseController
{
    protected $validation;
    protected $m_serverside;
    protected $m_peminjaman;

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
            $data['title'] = 'Peminjaman';
            $data['title_page'] = 'Peminjaman';
            $data['menu'] = 'peminjaman';
            return view('peminjaman/peminjaman', $data);
        }
    }
}
