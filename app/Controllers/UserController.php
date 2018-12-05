<?php

use App\Controllers\Controller;
use App\Models\User;
use App\Request;

class UserController extends Controller
{
	public function index(Request $request)
	{
		$result = $this->getDB()->query('SELECT * FROM users')->fetchAll(PDO::FETCH_CLASS, User::class);
		return $this->response()->json($result);
	}

	public function loginIndex()
	{
		$users = $this->getDB()->query('SELECT * FROM users')->fetchAll(PDO::FETCH_CLASS, User::class);

		return $this->view('login', [
			'users' => $users,
		]);
	}
}