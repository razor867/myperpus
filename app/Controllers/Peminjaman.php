<?php

namespace App\Controllers;

use App\Models\M_Approval;
use App\Models\M_Buku;
use App\Models\M_Pengembalian;
use App\Models\M_Users;
use App\Models\Serverside_model;
use Irsyadulibad\DataTables\DataTables;

class Peminjaman extends BaseController
{
    protected $validation;
    protected $m_serverside;
    protected $m_peminjaman;
    protected $no_rows;
    protected $m_buku;
    protected $m_user;
    protected $m_approval;
    protected $m_pengembalian;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_serverside = new Serverside_model();
        $this->no_rows = 0;
        $this->m_buku = new M_Buku();
        $this->m_user = new M_Users();
        $this->m_approval = new M_Approval();
        $this->m_pengembalian = new M_Pengembalian();
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

    public function listdata()
    {
        return DataTables::use('_peminjaman')
            ->where($this->where_data())
            ->select('id, anggota, judul_buku, tgl_pinjam, tgl_pengembalian, id_approval')
            ->addColumn('action', function ($data) {
                if (in_groups('anggota')) {
                    $button_action = '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->id_approval) . '\')">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>';
                } else {
                    $button_action = '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->id_approval) . '\')">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>
                                    <a href="/peminjaman/form_pengembalian/' . encode($data->id_approval) . '/' . encode($data->id) . '" class="btn btn-primary btn-sm">
                                        <i class="fas fa-expand-alt"></i> Kembalikan
                                    </a>';
                }

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

    public function form_pengembalian($id_approval, $id_peminjaman)
    {
        $data['title'] = 'Form Pengembalian Buku';
        $data['title_page'] = 'Form Pengembalian Buku';
        $data['menu'] = 'peminjaman';
        $data['back'] = base_url('peminjaman');
        $data['validation'] = $this->validation;

        $approval = $this->m_approval->select('id_buku, id_anggota, tgl_pinjam, tgl_pengembalian, total_pinjam')->find(decode($id_approval));
        $buku = $this->m_buku->find($approval->id_buku);

        $data['judul_buku'] = $buku->judul;
        $data['id_buku'] = encode($approval->id_buku);
        $data['id_approval'] = $id_approval;
        $data['id_anggota'] = encode($approval->id_anggota);
        $data['peminjam'] = $this->getNameUser($approval->id_anggota, true);
        $data['tgl_pinjam'] = $approval->tgl_pinjam;
        $data['tgl_pengembalian'] = $approval->tgl_pengembalian;
        $data['total_pinjam'] = $approval->total_pinjam;
        $data['id'] = encode(0);
        $data['action_url'] = base_url('pengembalian/save/' . $id_approval . '/' . $id_peminjaman);

        return view('peminjaman/form_pengembalian', $data);
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
