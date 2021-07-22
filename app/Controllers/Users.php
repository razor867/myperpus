<?php

namespace App\Controllers;

use App\Models\M_Users;
use Irsyadulibad\DataTables\DataTables;
use App\Models\M_authGroupsUsers;
use App\Models\M_Groups;
use Myth\Auth\Entities\User;

class Users extends BaseController
{
    protected $m_users;
    protected $no_rows;
    protected $m_authGroupsUsers;
    protected $m_group;
    protected $validation;
    protected $config;

    public function __construct()
    {
        $this->m_users = new M_Users();
        $this->no_rows = 0;
        $this->m_authGroupsUsers = new M_authGroupsUsers();
        $this->m_group = new M_Groups();
        $this->validation = \Config\Services::validation();
        $this->config = config('Auth');
    }

    public function index()
    {
        if (user()->update_bio == 0) {
            return redirect()->to(base_url('home/form_edit_profile'));
        } else {
            if (!in_groups('anggota')) {
                $data['title'] = 'Users';
                $data['title_page'] = 'Users';
                $data['menu'] = 'users';

                return view('users/users', $data);
            }
        }
    }

    public function listdata()
    {
        return DataTables::use('auth_groups_users')
            ->where(['users.deleted_at' => NULL])
            ->select('users.username as pengguna, auth_groups.name as role, users.id as users_id')
            ->join('users', 'auth_groups_users.user_id = users.id', 'LEFT JOIN')
            ->join('auth_groups', 'auth_groups_users.group_id = auth_groups.id', 'LEFT JOIN')
            ->addColumn('action', function ($data) {
                if (in_groups('super admin')) {
                    $button_action = '<a href="' . base_url('users/form/' . encode($data->users_id)) . '" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                  </a>
                                  <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteData(\'_datusr\',\'' . encode($data->users_id) . '\')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                  </a>
                                  <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->users_id) . '\')">
                                    <i class="fas fa-info-circle"></i> Detail
                                  </button>';
                } else {
                    //admin tidak boleh hapus atau edit super admin
                    if ($data->pengguna == 'wahyu') {
                        $button_action = '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->users_id) . '\')">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </button>';
                    } else {
                        $button_action = '<a href="' . base_url('users/form/' . encode($data->users_id)) . '" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                  </a>
                                  <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteData(\'_datusr\',\'' . encode($data->users_id) . '\')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                  </a>
                                  <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalData" title="Detail"  onclick="detail(\'' . encode($data->users_id) . '\')">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>';
                    }
                }
                return $button_action;
            })
            ->addColumn('role', function ($data) {
                $role = '<h4><span class="badge ' . (($data->role == 'super admin') ? 'bg-primary' : (($data->role == 'admin') ? 'bg-secondary' : 'bg-success')) . '">' . $data->role . '</span></h4>';
                return $role;
            })
            ->addColumn('no', function ($data) {
                $no = $this->no_rows + 1;
                $this->no_rows = $no;
                return $no;
            })
            ->rawColumns(['action', 'role', 'no'])
            ->make(true);
    }

    public function form($id = '')
    {
        $data['title'] = (empty($id) ? 'Tambah' : 'Edit') . ' Users';
        $data['title_page'] = (empty($id) ? 'Tambah' : 'Edit')  . ' Users';
        $data['validation'] = $this->validation;
        $data['menu'] = 'users';
        $data['back'] = base_url('users');
        $data['is_edit'] = false;
        $data['group'] = $this->m_group->findAll();

        if (!empty($id)) {
            $getData = $this->m_users->find(decode($id));
            $authGroupsUsers = $this->m_authGroupsUsers->where(['user_id' => decode($id)])->find();

            $data['username'] = $getData->username;
            $data['email'] = $getData->email;
            $data['group_id'] = encode($authGroupsUsers[0]->group_id);
            $data['group_name'] = $this->m_group->find($authGroupsUsers[0]->group_id);
            $data['action_url'] = base_url('users/save/' . $id);
            $data['is_edit'] = true;
            $data['id'] = $id;
        } else {
            $data['id'] = encode(0);
            $data['action_url'] = base_url('users/save');
        }

        return view('users/form_users', $data);
    }

    public function save($id = '')
    {
        if (empty($id)) {
            if (!$this->validate($this->validation->getRuleGroup('users_add'))) {
                session()->setFlashdata('info', 'error_edit');
                return redirect()->to(base_url('users/form'))->withInput();
            }
        } else {
            if (!$this->validate($this->validation->getRuleGroup('users_edit'))) {
                session()->setFlashdata('info', 'error_add');
                return redirect()->to(base_url('users/form/' . $id))->withInput();
            }
        }
        $postData = $this->request->getPost();
        $postData['group_id'] = decode($postData['group_id']);
        $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
        $user = new User($this->request->getPost($allowedPostFields));

        $id = decode($postData['id']);
        if ($id == 0) {
            $this->m_users->insert($user);
            // $postData['created_by'] = user_id();
            $id_newUser = $this->m_users->select('id')->where(['deleted_at' => NULL])->orderBy('id', 'DESC')->first();

            $postData['active'] = 1;
            $this->m_users->update($id_newUser->id, $postData);
            $postData['user_id'] = $id_newUser->id;
            $this->m_authGroupsUsers->insert($postData);
            session()->setFlashdata('info', 'success_add');
        } else {
            $this->m_users->update($id, $user);
            // $postData['updated_by'] = user_id();
            $this->m_users->update($id, $postData);
            $postData['user_id'] = $id;
            $this->m_authGroupsUsers->update($id, $postData);
            session()->setFlashdata('info', 'success_edit');
        }

        return redirect()->to(base_url('users'));
    }

    public function delete($id)
    {
        $id = decode($id);
        // $this->m_users->update($id, ['deleted_by' => user_id()]);
        $this->m_users->delete($id);
        session()->setFlashdata('info', 'success_delete');

        return redirect()->to(base_url('users'));
    }

    public function detail()
    {
        $this->request->isAJAX() or exit();

        $id = decode($this->request->getPost('id'));
        $data = $this->m_users->select('firstname, lastname, nis')->find($id);
        $data->nama = $data->firstname . ' ' . $data->lastname;
        return json_encode($data);
    }
}
