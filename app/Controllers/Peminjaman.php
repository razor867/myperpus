<?php

namespace App\Controllers;

use App\Models\M_Approval;
use App\Models\M_Buku;
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

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_serverside = new Serverside_model();
        $this->no_rows = 0;
        $this->m_buku = new M_Buku();
        $this->m_user = new M_Users();
        $this->m_approval = new M_Approval();
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
        return DataTables::use('peminjaman')
            ->where($this->where_data())
            ->select('approval.id_anggota as peminjam, approval.id_buku as buku, approval.tgl_pinjam as tgl_pinjam, approval.tgl_pengembalian as tgl_pengembalian, id_approval')
            ->join('approval', 'peminjaman.id_approval = approval.id', 'LEFT JOIN')
            ->addColumn('action', function ($data) {
                $button_action = '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->id_approval) . '\')">
                                        <i class="fas fa-info-circle"></i> Detail
                                  </button>';
                return $button_action;
            })
            ->addColumn('buku', function ($data) {
                $buku = $this->getJudulBuku($data->buku);
                return $buku;
            })
            ->addColumn('peminjam', function ($data) {
                $peminjam = $this->getNameUser($data->peminjam);
                return $peminjam;
            })
            ->addColumn('no', function ($data) {
                $no = $this->no_rows + 1;
                $this->no_rows = $no;
                return $no;
            })
            ->rawColumns(['action', 'no', 'buku', 'peminjam'])
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
            $where = ['peminjaman.deleted_at' => NULL, 'approval.id_anggota' => user_id()];
        } else {
            $where = ['peminjaman.deleted_at' => NULL];
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
