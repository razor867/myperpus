<?php

namespace App\Controllers;

use App\Models\M_Category;
use App\Models\Serverside_model;

class Category extends BaseController
{
    protected $m_category;
    protected $m_serverside;
    protected $validation;

    public function __construct()
    {
        $this->m_category = new M_Category();
        $this->m_serverside = new Serverside_model();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data['title'] = 'Kategori';
        $data['title_page'] = 'Daftar Kategori Buku';
        $data['menu'] = 'kategori';

        return view('kategori/kategori', $data);
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
        $column_order = array('nama', 'deskripsi', 'id');
        $column_search = array('nama', 'deskripsi');
        $order = array('nama' => 'asc');
        // $where = array('deleted_by' => NULL);
        $list = $this->m_serverside->get_datatables('_category', $column_order, $column_search, $order);
        $data = array();
        // $no = $this->request->getPost('start');
        foreach ($list as $lt) {
            $button_action = '
    						  <a href="' . base_url('category/form/' . encode($lt->id)) . '" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                              </a>
                              <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteData(\'_datcat\',\'' . encode($lt->id) . '\')" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                              </a>';

            $row = array();
            $row[] = $lt->nama;
            $row[] = $lt->deskripsi;
            $row[] = $button_action;

            $data[] = $row;
        }
        $output = array(
            'draw' => $this->request->getPost('draw'),
            'recordsTotal' => $this->m_serverside->count_all('_category'),
            'recordsFiltered' => $this->m_serverside->count_filtered('_category', $column_order, $column_search, $order),
            'data' => $data,
        );

        return json_encode($output);
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
}
