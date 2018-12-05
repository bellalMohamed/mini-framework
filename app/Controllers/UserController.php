<?php

use App\Controllers\Controller;

class UserController extends Controller
{
	public function index()
	{
		return $this->view('users', [
			'name' => 'Bellal'
		]);
	}

	public function loginIndex()
	{
		return $this->view('login');
	}
}