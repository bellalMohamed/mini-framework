<?php

namespace App\Controllers;

use App\Controllers\Controller;

class UserController extends Controller
{
	public function index($response)
	{
		return $this->view('users', [
			'name' => 'Bellal'
		]);
	}
}