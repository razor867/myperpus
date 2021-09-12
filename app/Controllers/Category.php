<?php

namespace App\Controllers;

use App\Models\M_Category;
use App\Models\Serverside_model;
use Irsyadulibad\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class Category extends BaseController
{
    protected $m_category;
    // protected $m_serverside;
    protected $validation;

    public function __construct()
    {
        $this->m_category = new M_Category();
        // $this->m_serverside = new Serverside_model();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        if (user()->update_bio == 0) {
            return redirect()->to(base_url('home/form_edit_profile'));
        } else {
            if (in_groups('anggota') == false) {
                $data['title'] = 'Kategori';
                $data['title_page'] = 'Kategori';
                $data['menu'] = 'kategori';
                return view('kategori/kategori', $data);
            } else {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }
    }

    public function form($id = '')
    {
        $data['title'] = (empty($id) ? 'Tambah' : 'Edit') . ' Kategori';
        $data['title_page'] = (empty($id) ? 'Tambah' : 'Edit')  . ' Kategori';
        $data['validation'] = $this->validation;
        $data['menu'] = 'kategori';
        $data['back'] = base_url('category');
        $data['is_edit'] = false;

        if (!empty($id)) {
            $getData = $this->m_category->find(decode($id));
            $data['nama'] = $getData->nama;
            $data['deskripsi'] = $getData->deskripsi;
            $data['is_edit'] = true;
            $data['id'] = $id;
            $data['action_url'] = base_url('category/save/' . $id);
        } else {
            $data['id'] = encode(0);
            $data['action_url'] = base_url('category/save');
        }
        return view('kategori/form_kategori', $data);
    }

    public function listdata()
    {
        return DataTables::use('category')
            ->where(['deleted_at' => NULL])
            ->select('nama, deskripsi, id')
            ->addColumn('action', function ($data) {
                $button_action = '
    						  <a href="' . base_url('category/form/' . encode($data->id)) . '" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                              </a>
                              <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteData(\'_datcat\',\'' . encode($data->id) . '\')" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                              </a>';
                return $button_action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function save($id = '')
    {
        if (!$this->validate($this->validation->getRuleGroup('category'))) {
            session()->setFlashdata('info', (empty($id)) ? 'error_add' : 'error_edit');
            return redirect()->to(base_url('category/form/' . (empty($id) ? '' : $id)))->withInput();
        }
        $postData = $this->request->getPost();

        $id = decode($postData['id']);
        if ($id == 0) {
            $postData['created_by'] = user_id();
            $this->m_category->insert($postData);
            session()->setFlashdata('info', 'success_add');
        } else {
            $postData['updated_by'] = user_id();
            $this->m_category->update($id, $postData);
            session()->setFlashdata('info', 'success_edit');
        }

        return redirect()->to(base_url('category'));
    }

    public function delete($id)
    {
        $id = decode($id);
        $data['deleted_by'] = user_id();
        $this->m_category->update($id, $data);
        $this->m_category->delete($id);
        session()->setFlashdata('info', 'success_delete');

        return redirect()->to(base_url('category'));
    }

    public function convert_document($to)
    {
        $fileName = date('Y-m-d') . '-Data-Kategori-Buku';
        if ($to == 'pdf') {
            $options = new Options();
            $options->setChroot(FCPATH);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($this->html_content('Data Kategori Buku'));
            $dompdf->setPaper('A4', 'potrait');
            $dompdf->render();
            $dompdf->stream($fileName);
            exit();
        } else {
            $spreadsheet = new Spreadsheet();
            $data = $this->m_category->select('nama, deskripsi')->findAll();

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Nama')
                ->setCellValue('B1', 'Deskripsi');

            $column = 2;
            foreach ($data as $dt) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $column, $dt->nama)
                    ->setCellValue('B' . $column, $dt->deskripsi);
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
        $data = $this->m_category->select('nama, deskripsi')->findAll();
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
                                <th>Nama</th>
                                <th>Deskripsi</th>
                            </tr>';
        foreach ($data as $dt) {
            $html .=        '<tr>
                                <td>' . $no . '</td>
                                <td>' . $dt->nama . '</td>
                                <td>' . $dt->deskripsi . '</td>
                            </tr>';
            $no++;
        }
        $html .=        '</table></div>
                    </body>
                </html>';
        return $html;
    }
}
