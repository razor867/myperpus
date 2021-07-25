<?php

namespace App\Controllers;

use App\Models\_Pengembalian;
use App\Models\M_Approval;
use App\Models\M_Buku;
use App\Models\M_Peminjaman;
use App\Models\M_Pengembalian;
use App\Models\M_Users;
use App\Models\Serverside_model;
use Irsyadulibad\DataTables\DataTables;
use Config\Services;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

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
    protected $_pengembalian_view;

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
        $this->_pengembalian_view = new _Pengembalian();
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

    public function convert_document($to)
    {
        $fileName = (in_groups('anggota') ? date('Y-m-d') . '-Data-Pengembalian (' . user()->firstname . ' ' . user()->lastname . ')' : date('Y-m-d') . '-Data-Pengembalian');
        if ($to == 'pdf') {
            $options = new Options();
            $options->setChroot(FCPATH);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($this->html_content('Data Pengembalian'));
            $dompdf->setPaper('A4', 'potrait');
            $dompdf->render();
            $dompdf->stream($fileName);
        } else {
            $spreadsheet = new Spreadsheet();
            if (in_groups('anggota')) {
                $data = $this->_pengembalian_view->select('anggota, judul_buku, denda, ket, tgl_pinjam, tgl_pengembalian, tgl_dikembalikan')->where(['id_anggota' => user_id()])->findAll();
            } else {
                $data = $this->_pengembalian_view->select('anggota, judul_buku, denda, ket, tgl_pinjam, tgl_pengembalian, tgl_dikembalikan')->findAll();
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'No')
                ->setCellValue('B1', 'Pengembali')
                ->setCellValue('C1', 'Buku')
                ->setCellValue('D1', 'Denda')
                ->setCellValue('E1', 'Keterangan')
                ->setCellValue('F1', 'Tanggal Pinjam')
                ->setCellValue('G1', 'Tanggal Pengembalian')
                ->setCellValue('H1', 'Tanggal Dikembalikan');
            $no = 1;
            $column = 2;
            foreach ($data as $dt) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $column, $no)
                    ->setCellValue('B' . $column, $dt->anggota)
                    ->setCellValue('C' . $column, $dt->judul_buku)
                    ->setCellValue('D' . $column, $dt->denda)
                    ->setCellValue('E' . $column, $dt->ket)
                    ->setCellValue('F' . $column, $dt->tgl_pinjam)
                    ->setCellValue('G' . $column, $dt->tgl_pengembalian)
                    ->setCellValue('H' . $column, $dt->tgl_dikembalikan);
                $column++;
                $no++;
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
            $data = $this->_pengembalian_view->select('anggota, judul_buku, denda, ket, tgl_pinjam, tgl_pengembalian, tgl_dikembalikan')->where(['id_anggota' => user_id()])->findAll();
        } else {
            $data = $this->_pengembalian_view->select('anggota, judul_buku, denda, ket, tgl_pinjam, tgl_pengembalian, tgl_dikembalikan')->findAll();
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
                                <th>Pengembali</th>
                                <th>Buku</th>
                                <th>Denda</th>
                                <th>Keterangan</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Pengembalian</th>
                                <th>Tanggal Dikembalikan</th>
                            </tr>';
        foreach ($data as $dt) {
            $html .=        '<tr>
                                <td>' . $no . '</td>
                                <td>' . $dt->anggota . '</td>
                                <td>' . $dt->judul_buku . '</td>
                                <td>' . $dt->denda . '</td>
                                <td>' . $dt->ket . '</td>
                                <td>' . $dt->tgl_pinjam . '</td>
                                <td>' . $dt->tgl_pengembalian . '</td>
                                <td>' . $dt->tgl_dikembalikan . '</td>
                            </tr>';
            $no++;
        }
        $html .=        '</table></div>
                    </body>
                </html>';
        return $html;
    }
}
