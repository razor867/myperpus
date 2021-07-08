<?php

namespace App\Controllers;

use Config\View;

class Home extends BaseController
{
	public function index()
	{
		// return view('welcome_message');
		$data['title'] = 'Dashboard';
		$data['title_page'] = 'Dashboard';
		$data['menu'] = 'dashboard';
		return view('home/dashboard', $data);
	}

	public function profile()
	{
		$data['title'] = 'Profile';
		$data['title_page'] = 'Profile';
		$data['menu'] = 'profile';
		return view('home/profile', $data);
	}
}
