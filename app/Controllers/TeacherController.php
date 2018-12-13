<?php

use App\Auth;
use App\Controllers\Controller;
use App\Hash;
use App\Models\Teacher;
use App\Request;
use App\Session;

class TeacherController extends Controller
{
	public function index(Request $request)
	{
		if (Auth::guard('teacher')->check()) {
			$teacher = Auth::guard('teacher')->user();
		}
		return $this->response()->json($teacher);
	}

	public function teacherLoginIndex()
	{
		if (Auth::guard('teacher')->check()) {
			return $this->redirect('/teacher/home');
		}

		return $this->view('teacher-auth');
	}

	public function loginTeacher(Request $request)
	{
		if (Auth::guard('teacher')->attempt($request->email, $request->password)) {
			Auth::guard('teacher')->login();
			return $this->redirect('/teacher/home');
		}

		Session::flash('message', 'Wrong Credentials');

		return $this->redirect('/teacher/login/index');
	}

	public function registerTeacher(Request $request)
	{
		try {
			Auth::guard('teacher')->register($request->name, $request->email, $request->password);

			if (Auth::guard('teacher')->attempt($request->email, $request->password)) {
				Auth::guard('teacher')->login();
				return $this->redirect('/teacher/home');
			}

		} catch (Exception $e) {
			Session::flash('message', $e->getMessage());
			return $this->redirect('/teacher/login/index');

		}
	}
}
