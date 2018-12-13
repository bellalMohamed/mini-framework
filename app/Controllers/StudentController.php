<?php

use App\Auth;
use App\Controllers\Controller;
use App\Hash;
use App\Models\User;
use App\Request;
use App\Session;

class StudentController extends Controller
{
	public function index(Request $request)
	{
		if (Auth::guard('user')->check()) {
			$user = Auth::guard('user')->user();
		}
		return $this->response()->json($user);
	}

	public function studentLoginIndex()
	{
		if (Auth::guard('student')->check()) {
			return $this->redirect('/student/home');
		}

		return $this->view('student-login');
	}

	public function loginStudent(Request $request)
	{
		if (Auth::guard('user')->attempt($request->email, $request->password)) {
			Auth::guard('user')->login();
			return $this->redirect('/home');
		}
		return $this->redirect('/login');
	}

	public function registerStudent(Request $request)
	{
		try {
			Auth::guard('student')->register($request->name, $request->email, $request->password);

			if (Auth::guard('student')->attempt($request->email, $request->password)) {
				Auth::guard('student')->login();
				return $this->redirect('/student/home');
			}

		} catch (Exception $e) {
			Session::flash('error', $e->getMessage());
			return $this->redirect('/student/login/index');

		}
	}
}
