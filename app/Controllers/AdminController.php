<?php

use App\Auth;
use App\Controllers\Controller;
use App\Hash;
use App\Models\Librarian;
use App\Request;
use App\Session;

class AdminController extends Controller
{
	public function index(Request $request)
	{
		if (!Auth::guard('admin')->check()) {
			return $this->redirect('/admin/login/index');
		}

		$librarians = $this->getLibrarians();

		$admin = Auth::guard('admin')->user();
		return $this->view('admin-home', [
			'librarians' => $librarians
		]);
	}

	public function AdminLoginIndex()
	{
		return $this->view('admin-auth');
	}

	public function loginAdmin(Request $request)
	{
		if (Auth::guard('admin')->attempt($request->email, $request->password)) {
			Auth::guard('admin')->login();
			return $this->redirect('/admin/home');
		}
		Session::flash('message', 'Wrong Credentials');
		return $this->redirect('/admin/login/index');
	}


	public function registerNewLibrarian(Request $request)
	{
		$this->guardAgainstNonAdmins();
		if (!$request->name || !$request->email || !$request->password) {
			return $this->back();
		}

		if ($this->registerLibrarianData($request)) {
			Session::flash('success', 'Librarian Registered');
			return $this->back();
		}
	}

	protected function registerLibrarianData(Request $request)
	{
		return Auth::guard('librarian')->register($request->name, $request->email, $request->password);
	}

	protected function getLibrarians()
	{
		$librariansQuery = $this->db()->query("SELECT * FROM librarians");

		$librarians = $librariansQuery->fetchAll(PDO::FETCH_CLASS, Librarian::class);

		return $librarians;
	}

	public function deleteLibrarian(Request $request)
	{
		$this->guardAgainstNonAdmins();

		$userQuery = $this->db()->prepare("DELETE FROM librarians WHERE id = ?");

		$userQuery->execute([$request->id]);

		Session::flash('success', 'Librarian deleted successfully');
		return $this->back();
	}

	protected function guardAgainstNonAdmins()
	{
		if (!Auth::guard('admin')->check()) {
			die('401');
		}
	}
}
