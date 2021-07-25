<?php

namespace App\Controllers;

use App\Models\_Approval;
use App\Models\M_Approval;
use App\Models\M_Buku;
use App\Models\Serverside_model;
use App\Models\M_Users;
use App\Models\M_Peminjaman;
use CodeIgniter\I18n\Time;
use Irsyadulibad\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class Approval extends BaseController
{
    protected $m_approval;
    protected $validation;
    protected $m_serverside;
    protected $m_user;
    protected $m_buku;
    protected $m_peminjaman;
    protected $no_rows;
    protected $_approval_view;

    public function __construct()
    {
        $this->m_serverside = new Serverside_model();
        $this->m_approval = new M_Approval();
        $this->validation = \Config\Services::validation();
        $this->m_user = new M_Users();
        $this->m_buku = new M_Buku();
        $this->m_peminjaman = new M_Peminjaman();
        $this->no_rows = 0;
        $this->_approval_view = new _Approval();
    }

    public function index()
    {
        if (user()->update_bio == 0) {
            return redirect()->to(base_url('home/form_edit_profile'));
        } else {
            $data['title'] = 'Approval';
            $data['title_page'] = 'Persetujuan';
            $data['menu'] = 'approval';

            // cek apakah ada tiket pengajuan yang expirate?
            if (in_groups('anggota') == false) {
                $now = Time::now('Asia/Jakarta', 'id_ID');
                $now = date('Y-m-d', strtotime($now));
                $data_approval = $this->m_approval->select('id, tgl_expirate')->where(['status' => 'pending', 'deleted_at' => NULL])->findAll();
                foreach ($data_approval as $da) {
                    $exp = date('Y-m-d', strtotime($da->tgl_expirate));
                    if ($exp < $now) {
                        $this->m_approval->update($da->id, ['status' => 'rejected', 'updated_by' => user_id()]);
                    }
                }
            }

            return view('approval/approval', $data);
        }
    }

    public function listdata()
    {
        return DataTables::use('_approval')
            ->where($this->where_data())
            ->select('judul_buku, anggota, status, id_buku, id')
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
            ->addColumn('status', function ($data) {
                $status = '<span class="badge ' . (($data->status == 'pending') ? 'bg-warning' : (($data->status == 'approved') ? 'bg-success' : 'bg-danger')) . '">' . $data->status . '</span>';
                return $status;
            })
            ->addColumn('no', function ($data) {
                $no = $this->no_rows + 1;
                $this->no_rows = $no;
                return $no;
            })
            ->rawColumns(['action', 'status', 'no'])
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
            $postData['tgl_expirate'] = Time::tomorrow('Asia/Jakarta', 'id_ID');
            $check_double_order = $this->m_approval->where(['id_buku' => $postData['id_buku'], 'id_anggota' => user_id(), 'status' => 'pending', 'deleted_at' => NULL])->find();
            //cek sudah pernah diorder atau belum?
            if ($check_double_order) {
                session()->setFlashdata('info', 'error_pinjam');
            } else {
                $check_borrow = $this->m_peminjaman->where(['id_buku' => $postData['id_buku'], 'id_anggota' => user_id(), 'deleted_at' => NULL])->find();
                //cek sedang dalam peminjaman?
                if ($check_borrow) {
                    session()->setFlashdata('info', 'error_pinjam2');
                    return redirect()->to(base_url('peminjaman'));
                } else {
                    $this->m_approval->insert($postData);
                    session()->setFlashdata('info', 'success_add');
                }
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
        $data->id_buku = encode($data->id_buku);
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

    public function change_status($id, $status, $id_buku)
    {
        $postData['id_approval'] = decode($id);
        $postData['created_by'] = user_id();
        $postData['id_buku'] = decode($id_buku);
        $approval = $this->m_approval->find(decode($id));
        $postData['id_anggota'] = $approval->id_anggota;
        if ($status == 'approved') {
            $buku = $this->m_buku->select('stok')->find(decode($id_buku));
            $this->m_buku->update(decode($id_buku), ['stok' => $buku->stok - 1, 'updated_by' => user_id()]);
            $this->m_peminjaman->insert($postData);
        }
        $this->m_approval->update(decode($id), ['status' => $status, 'updated_by' => user_id()]);
        session()->setFlashdata('info', 'success_change_status');
        if ($status == 'approved') {
            return redirect()->to(base_url('peminjaman'));
        } else {
            return redirect()->to(base_url('approval'));
        }
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

    public function convert_document($to)
    {
        $fileName = (in_groups('anggota') ? date('Y-m-d') . '-Data-Persetujuan (' . user()->firstname . ' ' . user()->lastname . ')' : date('Y-m-d') . '-Data-Persetujuan');
        if ($to == 'pdf') {
            $options = new Options();
            $options->setChroot(FCPATH);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($this->html_content('Data Persetujuan'));
            $dompdf->setPaper('A4', 'potrait');
            $dompdf->render();
            $dompdf->stream($fileName);
        } else {
            $spreadsheet = new Spreadsheet();
            if (in_groups('anggota')) {
                $data = $this->_approval_view->select('judul_buku, anggota, status')->where(['id_anggota' => user_id()])->findAll();
            } else {
                $data = $this->_approval_view->select('judul_buku, anggota, status')->findAll();
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Judul Buku')
                ->setCellValue('B1', 'Peminjam')
                ->setCellValue('C1', 'Status');

            $column = 2;
            foreach ($data as $dt) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $column, $dt->judul_buku)
                    ->setCellValue('B' . $column, $dt->anggota)
                    ->setCellValue('C' . $column, $dt->status);
                $column++;
            }

            if ($to == 'excel') {
                $writer = new Xlsx($spreadsheet);
            } else if ($to == 'csv') {
                $writer = new Csv($spreadsheet);
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if ($to == 'excel') {
                header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
            } else if ($to == 'csv') {
                header('Content-Disposition: attachment;filename=' . $fileName . '.csv');
            }
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit();
        }
    }

    private function html_content($judul)
    {
        if (in_groups('anggota')) {
            $data = $this->_approval_view->select('judul_buku, anggota, status')->where(['id_anggota' => user_id()])->findAll();
        } else {
            $data = $this->_approval_view->select('judul_buku, anggota, status')->findAll();
        }
        $no = 1;
        $html = '<html>
                    <head>
                        <title>Document</title>
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <style>
                            .content-table {
                                max-width: inherit;
                            }
                            .table {
                                font-family: sans-serif;
                                color: #444;
                                border-collapse: collapse;
                                border: 1px solid #f2f5f7;
                                width: 100%;
                                font-size: 12px;
                            }
                            .table tr th{
                                background: #35A9DB;
                                color: #fff;
                                font-weight: normal;
                            }
                            .table, th {
                                padding: 5px;
                                text-align: left;
                            }
                            td {
                                padding: 5px;
                                text-align: left;
                            }
                            .table tr:nth-child(even) {
                                background-color: #f2f2f2;
                            }
                        </style>
                    </head>
                    <body>
                        <div style="display:inline;"><img id="logo" src="./img/logo.png" width="65" alt="Logo"></div>
                        <div style="display:inline-block;">
                            <h3 style="text-decoration: underline;margin-bottom:5px; margin-left:10px;">' . $judul . '</h3>
                            <h5 style="display:inline; margin-left:10px;">Myperpus | SMKN 1 CIKAMPEK </h5>
                            <small style="display:inline">- Kabupaten Karawang, Jawa Barat 41373</small>
                        </div>
                        <hr style="margin-top:0"><div class="content-table">
                        <table class="table" width="100">
                            <tr>
                                <th>#</th>
                                <th>Judul Buku</th>
                                <th>Anggota</th>
                                <th>Status</th>
                            </tr>';
        foreach ($data as $dt) {
            $html .=        '<tr>
                                <td>' . $no . '</td>
                                <td>' . $dt->judul_buku . '</td>
                                <td>' . $dt->anggota . '</td>
                                <td>' . $dt->status . '</td>
                            </tr>';
            $no++;
        }
        $html .=        '</table></div>
                    </body>
                </html>';
        return $html;
    }
}
