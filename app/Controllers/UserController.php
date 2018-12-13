<?php

use App\Auth;
use App\Controllers\Controller;
use App\Models\User;
use App\Request;
use App\Hash;

class UserController extends Controller
{
	public function index(Request $request)
	{
		if (Auth::guard('user')->check()) {
			$user = Auth::guard('user')->user();
		}
		return $this->response()->json($user);
	}

	public function UserLoginIndex()
	{
		return $this->view('user-login');
	}

	public function loginUser(Request $request)
	{
		if (Auth::guard('user')->attempt($request->email, $request->password)) {
			Auth::guard('user')->login();
			return $this->redirect('/home');
		}
		return $this->redirect('/login');

	}
}
