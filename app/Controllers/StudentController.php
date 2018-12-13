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
		if (Auth::guard('student')->check()) {
			$student = Auth::guard('student')->user();
		}
		return $this->response()->json($student);
	}

	public function studentLoginIndex()
	{
		if (Auth::guard('student')->check()) {
			return $this->redirect('/student/home');
		}

		return $this->view('student-auth');
	}

	public function loginStudent(Request $request)
	{
		if (Auth::guard('student')->attempt($request->email, $request->password)) {
			Auth::guard('student')->login();
			return $this->redirect('/student/home');
		}
		Session::flash('message', 'Wrong Credentials');
		return $this->redirect('/student/login/index');
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
