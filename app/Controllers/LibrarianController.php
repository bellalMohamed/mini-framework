<?php

use App\Auth;
use App\Controllers\Controller;
use App\Hash;
use App\Models\librarian;
use App\Request;
use App\Session;

class LibrarianController extends Controller
{
	public function index(Request $request)
	{
		if (Auth::guard('librarian')->check()) {
			$librarian = Auth::guard('librarian')->user();
		}
		return $this->response()->json($librarian);
	}

	public function librarianLoginIndex()
	{
		if (Auth::guard('librarian')->check()) {
			return $this->redirect('/librarian/home');
		}

		return $this->view('librarian-auth');
	}

	public function loginLibrarian(Request $request)
	{
		if (Auth::guard('librarian')->attempt($request->email, $request->password)) {
			Auth::guard('librarian')->login();
			return $this->redirect('/librarian/home');
		}

		Session::flash('message', 'Wrong Credentials');

		return $this->redirect('/librarian/login/index');
	}

	public function registerlibrarian(Request $request)
	{
		try {
			Auth::guard('librarian')->register($request->name, $request->email, $request->password);

			if (Auth::guard('librarian')->attempt($request->email, $request->password)) {
				Auth::guard('librarian')->login();
				return $this->redirect('/librarian/home');
			}

		} catch (Exception $e) {
			Session::flash('message', $e->getMessage());
			return $this->redirect('/librarian/login/index');

		}
	}
}
