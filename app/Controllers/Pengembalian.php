<?php

namespace App\Controllers;

use App\Models\M_Approval;
use App\Models\M_Buku;
use App\Models\M_Peminjaman;
use App\Models\M_Pengembalian;
use App\Models\M_Users;
use App\Models\Serverside_model;
use Irsyadulibad\DataTables\DataTables;
use Config\Services;

class Pengembalian extends BaseController
{
    protected $m_pengembalian;
    protected $m_serverside;
    protected $m_validation;
    protected $m_peminjaman;
    protected $m_buku;
    protected $m_user;
    protected $m_approval;
    protected $no_rows;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_serverside = new Serverside_model();
        $this->m_pengembalian = new M_Pengembalian();
        $this->m_peminjaman = new M_Peminjaman();
        $this->m_buku = new M_Buku();
        $this->m_user = new M_Users();
        $this->m_approval = new M_Approval();
        $this->no_rows = 0;
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

    public function save($id_approval, $id_peminjaman)
    {
        if (!$this->validate($this->validation->getRuleGroup('pengembalian'))) {
            session()->setFlashdata('info', 'error_add');
            return redirect()->to(base_url('peminjaman/form_pengembalian/' . $id_approval . '/' . $id_peminjaman))->withInput();
        }
        $postData = $this->request->getPost();
        $id = decode($postData['id']);
        $postData['id_anggota'] = decode($postData['id_anggota']);
        $postData['id_buku'] = decode($postData['id_buku']);
        $postData['id_approval'] = decode($postData['id_approval']);
        $postData['created_by'] = user_id();

        if ($id == 0) {
            $buku = $this->m_buku->select('stok')->find($postData['id_buku']);

            $this->m_peminjaman->update(decode($id_peminjaman), ['deleted_by' => user_id()]);
            $this->m_peminjaman->delete(decode($id_peminjaman));
            $this->m_buku->update($postData['id_buku'], ['stok' => $buku->stok + 1, 'updated_by' => user_id()]);
            $this->m_pengembalian->insert($postData);

            session()->setFlashdata('info', 'success_add');
            return redirect()->to(base_url('pengembalian'));
        }
    }

    public function listdata()
    {
        return DataTables::use('_pengembalian')
            ->where($this->where_data())
            ->select('id, anggota, judul_buku, denda, ket, tgl_dikembalikan, id_approval')
            ->addColumn('action', function ($data) {
                $button_action = '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->id_approval) . '\')">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>';
                return $button_action;
            })
            ->addColumn('no', function ($data) {
                $no = $this->no_rows + 1;
                $this->no_rows = $no;
                return $no;
            })
            ->rawColumns(['action', 'no'])
            ->make(true);
    }

    public function detail()
    {
        $this->request->isAJAX() or exit();

        $id = decode($this->request->getPost('id'));
        $data = $this->m_approval->select(['id', 'id_anggota', 'id_buku', 'total_pinjam', 'tgl_pinjam', 'tgl_pengembalian'])->find($id);
        $data->id = encode($data->id);
        $data->judul_buku = $this->getJudulBuku($data->id_buku);
        $data->peminjam = $this->getNameUser($data->id_anggota, true);

        echo json_encode($data);
    }

    private function where_data()
    {
        if (in_groups('anggota')) {
            $where = ['id_anggota' => user_id()];
        } else {
            $where = [];
        }

        return $where;
    }

    private function getJudulBuku($id)
    {
        $data = $this->m_buku->select('judul')->find($id);
        return $data->judul;
    }

    private function getNameUser($id, $nis = '')
    {
        if ($nis) {
            $data = $this->m_user->select(['firstname', 'lastname', 'nis'])->find($id);
            return $data->firstname . ' ' . $data->lastname . ' (' . $data->nis . ')';
        } else {
            $data = $this->m_user->select(['firstname', 'lastname'])->find($id);
            return $data->firstname . ' ' . $data->lastname;
        }
    }
}
