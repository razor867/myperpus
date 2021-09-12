<?php

namespace App\Controllers;

use App\Models\_Peminjaman;
use App\Models\M_Approval;
use App\Models\M_Buku;
use App\Models\M_Pengembalian;
use App\Models\M_Users;
use App\Models\Serverside_model;
use Irsyadulibad\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

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
    protected $_peminjaman_view;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_serverside = new Serverside_model();
        $this->no_rows = 0;
        $this->m_buku = new M_Buku();
        $this->m_user = new M_Users();
        $this->m_approval = new M_Approval();
        $this->m_pengembalian = new M_Pengembalian();
        $this->_peminjaman_view = new _Peminjaman();
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

    public function convert_document($to)
    {
        $fileName = (in_groups('anggota') ? date('Y-m-d') . '-Data-Peminjaman (' . user()->firstname . ' ' . user()->lastname . ')' : date('Y-m-d') . '-Data-Peminjaman');
        if ($to == 'pdf') {
            $options = new Options();
            $options->setChroot(FCPATH);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($this->html_content('Data Peminjaman'));
            $dompdf->setPaper('A4', 'potrait');
            $dompdf->render();
            $dompdf->stream($fileName);
            exit();
        } else {
            $spreadsheet = new Spreadsheet();
            if (in_groups('anggota')) {
                $data = $this->_peminjaman_view->select('anggota, judul_buku, tgl_pinjam, tgl_pengembalian')->where(['id_anggota' => user_id()])->findAll();
            } else {
                $data = $this->_peminjaman_view->select('anggota, judul_buku, tgl_pinjam, tgl_pengembalian')->findAll();
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'No')
                ->setCellValue('B1', 'Peminjam')
                ->setCellValue('C1', 'Buku')
                ->setCellValue('D1', 'Tanggal Pinjam')
                ->setCellValue('E1', 'Tanggal Pengembalian');

            $no = 1;
            $column = 2;
            foreach ($data as $dt) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $column, $no)
                    ->setCellValue('B' . $column, $dt->anggota)
                    ->setCellValue('C' . $column, $dt->judul_buku)
                    ->setCellValue('D' . $column, $dt->tgl_pinjam)
                    ->setCellValue('E' . $column, $dt->tgl_pengembalian);
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
            $data = $this->_peminjaman_view->select('anggota, judul_buku, tgl_pinjam, tgl_pengembalian')->where(['id_anggota' => user_id()])->findAll();
        } else {
            $data = $this->_peminjaman_view->select('anggota, judul_buku, tgl_pinjam, tgl_pengembalian')->findAll();
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
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Pengembalian</th>
                            </tr>';
        foreach ($data as $dt) {
            $html .=        '<tr>
                                <td>' . $no . '</td>
                                <td>' . $dt->anggota . '</td>
                                <td>' . $dt->judul_buku . '</td>
                                <td>' . $dt->tgl_pinjam . '</td>
                                <td>' . $dt->tgl_pengembalian . '</td>
                            </tr>';
            $no++;
        }
        $html .=        '</table></div>
                    </body>
                </html>';
        return $html;
    }
}
