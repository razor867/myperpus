<?php

namespace App\Controllers;

use Config\Services;
use App\Models\M_Approval;
use App\Models\M_Buku;
use App\Models\M_Category;
use App\Models\Serverside_model;
use Irsyadulibad\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class Buku extends BaseController
{
    protected $m_buku;
    protected $validation;
    // protected $m_serverside;
    protected $m_category;
    protected $m_approval;

    public function __construct()
    {
        $this->m_buku = new M_Buku();
        $this->validation = \Config\Services::validation();
        // $this->m_serverside = new Serverside_model();
        $this->m_category = new M_Category();
        $this->m_approval = new M_Approval();
    }

    public function index()
    {
        if (user()->update_bio == 0) {
            return redirect()->to(base_url('home/form_edit_profile'));
        } else {
            $data['title'] = 'Buku';
            $data['title_page'] = 'Buku';
            $data['menu'] = 'buku';

            return view('buku/buku', $data);
        }
    }

    public function form($id = '')
    {
        $data['title'] = (empty($id) ? 'Tambah' : 'Edit') . ' Buku';
        $data['title_page'] = (empty($id) ? 'Tambah' : 'Edit')  . ' Buku';
        $data['validation'] = $this->validation;
        $data['menu'] = 'buku';
        $data['back'] = base_url('buku');
        $data['is_edit'] = false;
        $data['category'] = $this->m_category->findAll();

        if (!empty($id)) {
            $getData = $this->m_buku->find(decode($id));
            $data['judul'] = $getData->judul;
            $data['penulis'] = $getData->penulis;
            $data['penerbit'] = $getData->penerbit;
            $data['category_id'] = encode($getData->category_id);
            $data['jml_buku'] = $getData->jml_buku;
            $data['category_name'] = $this->m_category->find(decode($id));
            $data['deskripsi'] = $getData->deskripsi;
            $data['action_url'] = base_url('buku/save/' . $id);
            $data['is_edit'] = true;
            $data['id'] = $id;
        } else {
            $data['id'] = encode(0);
            $data['action_url'] = base_url('buku/save');
        }

        return view('buku/form_buku', $data);
    }

    public function listdata()
    {
        return DataTables::use('buku')
            ->where(['buku.deleted_at' => NULL])
            ->select('judul, penulis, category.nama as kategori, buku.id as id_buku, stok')
            ->join('category', 'buku.category_id = category.id', 'LEFT JOIN')
            ->addColumn('action', function ($data) {
                if (in_groups('anggota')) {
                    if ($data->stok < 1) {
                        $button_action = '<span class="badge bg-secondary">Kosong</span>';
                    } else {
                        $button_action = '<span class="badge bg-success">Tersedia ' . $data->stok . '</span>';
                    }
                } else {
                    $button_action = '<a href="' . base_url('buku/form/' . encode($data->id_buku)) . '" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                  </a>
                                  <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteData(\'_datbk\',\'' . encode($data->id_buku) . '\')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                  </a>';
                }
                return $button_action;
            })
            ->addColumn('judul', function ($data) {
                $judul = '<a href="javscript:void(0)" onclick="detail(\'' . encode($data->id_buku) . '\')" data-bs-toggle="modal" data-bs-target="#modalData">' . $data->judul . '</a>';
                return $judul;
            })
            ->rawColumns(['action', 'judul'])
            ->make(true);
    }

    public function save($id = '')
    {
        if (!$this->validate($this->validation->getRuleGroup('buku'))) {
            session()->setFlashdata('info', (empty($id)) ? 'error_add' : 'error_edit');
            return redirect()->to(base_url('buku/form/' . (empty($id) ? '' : $id)))->withInput();
        }
        $postData = $this->request->getPost();
        $postData['category_id'] = decode($postData['category_id']);

        $id = decode($postData['id']);
        if ($id == 0) {
            $postData['created_by'] = user_id();
            $postData['stok'] = $postData['jml_buku'];
            $this->m_buku->insert($postData);
            session()->setFlashdata('info', 'success_add');
        } else {
            $data = $this->m_buku->find($id);
            $jml_awal = $data->jml_buku;
            $jml_update = $postData['jml_buku'];
            if ($jml_awal < $jml_update) {
                //penambahan jml buku
                $selisih = $jml_update - $jml_awal;
                $postData['stok'] = $data->stok + $selisih;
            } else {
                //pengurangan jml buku
                $selisih = $jml_awal - $jml_update;
                if ($data->stok > 0) {
                    $postData['stok'] = $data->stok - $selisih;
                }
            }

            $postData['updated_by'] = user_id();
            $this->m_buku->update($id, $postData);
            session()->setFlashdata('info', 'success_edit');
        }

        return redirect()->to(base_url('buku'));
    }

    public function delete($id)
    {
        $id = decode($id);
        $data['deleted_by'] = user_id();
        $this->m_buku->update($id, $data);
        $this->m_buku->delete($id);
        session()->setFlashdata('info', 'success_delete');

        return redirect()->to(base_url('buku'));
    }

    public function detail()
    {
        $this->request->isAJAX() or exit();

        $id = decode($this->request->getPost('id'));
        $data = $this->m_buku->find($id);
        $data->id = encode($data->id);
        $data->created_by = encode($data->created_by);
        $data->updated_by = encode($data->updated_by);
        $data->category_id = encode($data->category_id);

        echo json_encode($data);
    }

    public function pinjam($id_buku, $page, $id = '')
    {
        $data['title'] = (empty($id) ? 'Tambah' : 'Edit') . ' Pengajuan Peminjaman Buku';
        $data['title_page'] = (empty($id) ? 'Tambah' : 'Edit')  . ' Pengajuan Peminjaman Buku';
        $data['validation'] = $this->validation;
        $data['menu'] = 'buku';
        $data['back'] = ($page == 'book') ? base_url('buku') : base_url('approval');
        $data['is_edit'] = false;
        $getJudul = $this->m_buku->select('judul')->find(decode($id_buku));
        $data['judul_buku'] = $getJudul->judul;

        if (!empty($id)) {
            $getData = $this->m_approval->find(decode($id));
            $data['total_pinjam'] = $getData->total_pinjam;
            $data['tgl_pengembalian'] = $getData->tgl_pengembalian;
            $data['action_url'] = base_url('approval/save/' . $id_buku . '/' . $id);
            $data['is_edit'] = true;
            $data['id_buku'] = $id_buku;
            $data['id'] = $id;
        } else {
            $data['id'] = encode(0);
            $data['id_buku'] = $id_buku;
            $data['action_url'] = base_url('approval/save/' . $id_buku);
        }

        return view('buku/form_pinjam', $data);
    }

    public function convert_document($to)
    {
        $fileName = date('Y-m-d') . '-Data-Buku';
        if ($to == 'pdf') {
            $options = new Options();
            $options->setChroot(FCPATH);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($this->html_content('Data Buku'));
            $dompdf->setPaper('A4', 'potrait');
            $dompdf->render();
            $dompdf->stream($fileName);
        } else {
            $spreadsheet = new Spreadsheet();
            $data = $this->m_buku->data_buku_convert();

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Judul')
                ->setCellValue('B1', 'Penulis')
                ->setCellValue('C1', 'Penerbit')
                ->setCellValue('D1', 'Kategori')
                ->setCellValue('E1', 'Deskripsi')
                ->setCellValue('F1', 'Jml_buku')
                ->setCellValue('G1', 'Stok');

            $column = 2;
            foreach ($data as $dt) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $column, $dt->judul)
                    ->setCellValue('B' . $column, $dt->penulis)
                    ->setCellValue('C' . $column, $dt->penerbit)
                    ->setCellValue('D' . $column, $dt->kategori)
                    ->setCellValue('E' . $column, $dt->deskripsi)
                    ->setCellValue('F' . $column, $dt->jml_buku)
                    ->setCellValue('G' . $column, $dt->stok);
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
        $data = $this->m_buku->data_buku_convert();
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
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Jml_buku</th>
                                <th>Stok</th>
                            </tr>';
        foreach ($data as $dt) {
            $html .=        '<tr>
                                <td>' . $no . '</td>
                                <td>' . $dt->judul . '</td>
                                <td>' . $dt->penulis . '</td>
                                <td>' . $dt->penerbit . '</td>
                                <td>' . $dt->kategori . '</td>
                                <td>' . $dt->deskripsi . '</td>
                                <td>' . $dt->jml_buku . '</td>
                                <td>' . $dt->stok . '</td>
                            </tr>';
            $no++;
        }
        $html .=        '</table></div>
                    </body>
                </html>';
        return $html;
    }
}
