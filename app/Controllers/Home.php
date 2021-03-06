<?php

namespace App\Controllers;

use App\Models\M_Approval;
use App\Models\M_Users;
use Myth\Auth\Entities\User;
use App\Models\M_authGroupsUsers;
use App\Models\M_Buku;
use App\Models\M_Groups;
use App\Models\M_Peminjaman;

class Home extends BaseController
{
	protected $m_user;
	protected $validation;
	protected $config;
	protected $m_authGroupsUsers;
	protected $m_groups;
	protected $m_buku;
	protected $m_approval;
	protected $m_peminjaman;

	public function __construct()
	{
		$this->validation = \Config\Services::validation();
		$this->m_user = new M_Users();
		$this->m_authGroupsUsers = new M_authGroupsUsers();
		$this->m_groups = new M_Groups();
		$this->config = config('Auth');
		$this->m_buku = new M_Buku();
		$this->m_approval = new M_Approval();
		$this->m_peminjaman = new M_Peminjaman();
	}

	public function index()
	{
		if (user()->update_bio == 0) {
			return redirect()->to(base_url('home/form_edit_profile'));
		} else {
			$data['title'] = 'Dashboard';
			$data['title_page'] = 'Dashboard';
			$data['menu'] = 'dashboard';
			$data['total_buku'] = count($this->m_buku->where(['deleted_at' => NULL])->findAll());
			$data['total_pengguna'] = count($this->m_user->where(['deleted_at' => NULL, 'update_bio' => 1])->findAll());
			if (in_groups('anggota')) {
				$data['total_persetujuan'] = count($this->m_approval->where(['deleted_at' => NULL, 'status' => 'pending', 'id_anggota' => user_id()])->findAll());
				$data['total_peminjaman'] = count($this->m_peminjaman->where(['deleted_at' => NULL, 'id_anggota' => user_id()])->findAll());
			} else {
				$data['total_persetujuan'] = count($this->m_approval->where(['deleted_at' => NULL, 'status' => 'pending'])->findAll());
				$data['total_peminjaman'] = count($this->m_peminjaman->where(['deleted_at' => NULL])->findAll());
			}

			return view('home/dashboard', $data);
		}
	}

	public function profile()
	{
		if (user()->update_bio == 0) {
			return redirect()->to(base_url('home/form_edit_profile'));
		} else {
			$data['title'] = 'Profile';
			$data['title_page'] = 'Profile';
			$data['menu'] = 'profile';
			return view('home/profile', $data);
		}
	}

	public function form_edit_profile()
	{
		$data['title'] = 'Edit Profile';
		$data['title_page'] = 'Edit Profile';
		$data['menu'] = 'profile';
		$data['validation'] = $this->validation;
		$data['back'] = base_url('home/profile');

		$data['username'] = user()->username;
		$data['firstname'] = user()->firstname;
		$data['lastname'] = user()->lastname;
		$data['jk'] = user()->jk;
		$data['nis'] = user()->nis;
		$data['tlp'] = user()->tlp;
		$data['about'] = user()->about;
		$data['email'] = user()->email;
		$data['id'] = encode(user_id());
		$data['action_url'] = base_url('home/edit_profile/' . encode(user_id()));

		return view('home/form_profile', $data);
	}

	public function edit_profile($id)
	{
		if (!$this->validate($this->validation->getRuleGroup('edit_profile'))) {
			session()->setFlashdata('info', 'error_edit');
			return redirect()->to(base_url('home/form_edit_profile'))->withInput();
		}

		$id = decode($id);
		$postData = $this->request->getPost();

		if (user()->update_bio == 0) {
			$postData['update_bio'] = 1;
			// if (in_groups('super admin') == false && in_groups('admin') == false) {
			// 	//Menambahkan user yg register ke user group "anggota".
			// 	//pastikan sudah membuat membuat user group "anggota" di tabel "auth_groups"
			// 	//itu bisa dilakukan di php my admin.
			// 	$userGroupsID = $this->m_groups->where('name', 'anggota')->find();

			// 	$this->m_authGroupsUsers->insert(['group_id' => $userGroupsID[0]->id, 'user_id' => user()->id]);
			// }
		}

		$length_pass = strlen($postData['password']);
		if ($length_pass != 0) {
			//save password
			$allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
			$user = new User($this->request->getPost($allowedPostFields));
			$this->m_user->update($id, $user);
		}
		$this->m_user->update($id, $postData);

		session()->setFlashdata('info', 'success_edit');
		return redirect()->to(base_url('home/profile'));
	}
}
