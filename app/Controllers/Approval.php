<?php

namespace App\Controllers;

use App\Models\M_Approval;
use App\Models\M_Buku;
use App\Models\Serverside_model;
use App\Models\M_Users;
use App\Models\M_Peminjaman;
use CodeIgniter\I18n\Time;
use Irsyadulibad\DataTables\DataTables;

class Approval extends BaseController
{
    protected $m_approval;
    protected $validation;
    protected $m_serverside;
    protected $m_user;
    protected $m_buku;
    protected $m_peminjaman;
    protected $no_rows;

    public function __construct()
    {
        $this->m_serverside = new Serverside_model();
        $this->m_approval = new M_Approval();
        $this->validation = \Config\Services::validation();
        $this->m_user = new M_Users();
        $this->m_buku = new M_Buku();
        $this->m_peminjaman = new M_Peminjaman();
        $this->no_rows = 0;
    }

    public function index()
    {
        if (user()->update_bio == 0) {
            return redirect()->to(base_url('home/form_edit_profile'));
        } else {
            $data['title'] = 'Approval';
            $data['title_page'] = 'Persetujuan';
            $data['menu'] = 'approval';

            return view('approval/approval', $data);
        }
    }

    public function listdata()
    {
        return DataTables::use('_approval')
            ->where($this->where_data())
            ->select('judul_buku, id_anggota, status, id_buku, id')
            ->addColumn('action', function ($data) {
                if (in_groups('anggota')) {
                    if ($data->status == 'pending') {
                        $button_action = '
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->id) . '\')">
                            <i class="fas fa-info-circle"></i> Detail
                        </button>
                        <a href="' . base_url('buku/pinjam/' . encode($data->id_buku) . '/approval' . '/' . encode($data->id)) . '" class="btn btn-secondary btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="' . base_url('approval/cancel/' . encode($data->id)) . '" class="btn btn-danger btn-sm" title="Cancel">
                            <i class="fas fa-times"></i>
                        </a>';
                    } else {
                        $button_action = '
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->id) . '\')">
                            <i class="fas fa-info-circle"></i> Detail
                        </button>';
                    }
                } else {
                    if ($data->status != 'pending') {
                        $button_action = '
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->id) . '\')">
                            <i class="fas fa-info-circle"></i> Detail
                        </button>';
                    } else {
                        $button_action = '
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->id) . '\')">
                            <i class="fas fa-info-circle"></i> Detail
                        </button>
                        <a href="' . base_url('buku/pinjam/' . encode($data->id_buku) . '/approval' . '/' . encode($data->id)) . '" class="btn btn-secondary btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
                    }
                }
                return $button_action;
            })
            ->addColumn('peminjam', function ($data) {
                $peminjam = $this->getAnggotaName($data->id_anggota);
                return $peminjam;
            })
            ->addColumn('status', function ($data) {
                $status = '<span class="badge ' . (($data->status == 'pending') ? 'bg-warning' : (($data->status == 'approved') ? 'bg-success' : 'bg-danger')) . '">' . $data->status . '</span>';
                return $status;
            })
            ->addColumn('no', function ($data) {
                $no = $this->no_rows + 1;
                $this->no_rows = $no;
                return $no;
            })
            ->rawColumns(['action', 'peminjam', 'status', 'no'])
            ->make(true);
    }

    public function save($id_buku, $id = '')
    {
        if (!$this->validate($this->validation->getRuleGroup('pinjam'))) {
            session()->setFlashdata('info', (empty($id)) ? 'error_add' : 'error_edit');
            return redirect()->to(base_url('buku/pinjam/' . $id_buku . '/' . (empty($id) ? '' : $id)))->withInput();
        }

        $postData = $this->request->getPost();
        $id = decode($postData['id']);
        $postData['id_buku'] = decode($postData['id_buku']);

        if ($id == 0) {
            $postData['tgl_pinjam'] = new Time('now', 'Asia/Jakarta', 'id_ID');
            $postData['id_anggota'] = user_id();
            $postData['created_by'] = user_id();
            $check_double_order = $this->m_approval->where(['id_buku' => $postData['id_buku'], 'id_anggota' => user_id(), 'status' => 'pending', 'deleted_at' => NULL])->find();
            //cek sudah diorder atau belum?
            if ($check_double_order) {
                session()->setFlashdata('info', 'error_pinjam');
            } else {
                // $check_borrow = $this->m_approval->where([])
                //cek sedang dalam peminjaman?

                $this->m_approval->insert($postData);
                session()->setFlashdata('info', 'success_add');
            }
        } else {
            $postData['updated_by'] = user_id();
            $this->m_approval->update($id, $postData);
            session()->setFlashdata('info', 'success_edit');
        }

        return redirect()->to(base_url('approval'));
    }

    public function detail()
    {
        $this->request->isAJAX() or exit();

        $id = decode($this->request->getPost('id'));
        $data = $this->m_approval->select(['id', 'id_anggota', 'id_buku', 'total_pinjam', 'status', 'tgl_pinjam', 'tgl_pengembalian', 'created_at'])->find($id);
        $data->id = encode($data->id);
        $data->edit_status = (in_groups('anggota')) ? false : true;
        $data->created_at = date('Y-m-d', strtotime($data->created_at));
        $data->judul_buku = $this->getJudulBuku($data->id_buku);
        $data->peminjam = $this->getAnggotaName($data->id_anggota, true);
        $data->info_status = '<span class="badge ' . (($data->status == 'pending') ? 'bg-warning' : (($data->status == 'approved') ? 'bg-success' : 'bg-danger')) . '">' . $data->status . '</span>';

        echo json_encode($data);
    }

    public function cancel($id)
    {
        $id = decode($id);
        $data['deleted_by'] = user_id();
        $this->m_approval->update($id, $data);
        $this->m_approval->delete($id);
        session()->setFlashdata('info', 'success_delete');
        return redirect()->to(base_url('approval'));
    }

    public function change_status($id, $status)
    {
        $postData['id_approval'] = decode($id);
        $postData['created_by'] = user_id();
        if ($status == 'approved') {
            $this->m_peminjaman->insert($postData);
        }
        $this->m_approval->update(decode($id), ['status' => $status]);
        session()->setFlashdata('info', 'success_change_status');
        return redirect()->to(base_url('peminjaman'));
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

    private function getAnggotaName($id, $nis = '')
    {
        if ($nis) {
            $data = $this->m_user->select(['firstname', 'lastname', 'nis'])->find($id);
            return $data->firstname . ' ' . $data->lastname . ' (' . $data->nis . ')';
        } else {
            $data = $this->m_user->select(['firstname', 'lastname'])->find($id);
            return $data->firstname . ' ' . $data->lastname;
        }
    }

    private function getJudulBuku($id)
    {
        $data = $this->m_buku->select('judul')->find($id);
        return $data->judul;
    }
}
