<?php

namespace App\Controllers;

use App\Models\M_Buku;
use App\Models\M_Category;
use App\Models\Serverside_model;

class Buku extends BaseController
{
    protected $m_buku;
    protected $validation;
    protected $m_serverside;
    protected $m_category;

    public function __construct()
    {
        $this->m_buku = new M_Buku();
        $this->validation = \Config\Services::validation();
        $this->m_serverside = new Serverside_model();
        $this->m_category = new M_Category();
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
        $column_order = array('judul', 'penulis', 'kategori', 'stok', 'id');
        $column_search = array('judul', 'penulis', 'kategori');
        $order = array('judul' => 'asc');
        // $where = array('deleted_by' => NULL);
        $list = $this->m_serverside->get_datatables('_buku', $column_order, $column_search, $order);
        $data = array();
        // $no = $this->request->getPost('start');
        foreach ($list as $lt) {
            if (in_groups('anggota')) {
                if ($lt->stok < 1) {
                    $button_action = '<span class="badge bg-secondary">Kosong</span>';
                } else {
                    $button_action = '<span class="badge bg-success">Tersedia ' . $lt->stok . '</span>';
                }
            } else {
                $button_action = '
                                <a href="' . base_url('buku/form/' . encode($lt->id)) . '" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteData(\'_datbk\',\'' . encode($lt->id) . '\')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>';
            }

            $judul = '<a href="javscript:void(0)" onclick="detail(\'' . encode($lt->id) . '\')" data-bs-toggle="modal" data-bs-target="#modalData">' . $lt->judul . '</a>';

            $row = array();
            $row[] = $judul;
            $row[] = $lt->penulis;
            $row[] = $lt->kategori;
            $row[] = $button_action;

            $data[] = $row;
        }
        $output = array(
            'draw' => $this->request->getPost('draw'),
            'recordsTotal' => $this->m_serverside->count_all('_buku'),
            'recordsFiltered' => $this->m_serverside->count_filtered('_buku', $column_order, $column_search, $order),
            'data' => $data,
        );

        return json_encode($output);
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
                $postData['stok'] = $data->stok - $selisih;
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

    public function pinjam($id)
    {
        $id = decode($id);
    }
}
