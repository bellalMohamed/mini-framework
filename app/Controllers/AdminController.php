<?php

use App\Auth;
use App\Controllers\Controller;
use App\Hash;
use App\Models\Book;
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

	public function librarianIndex(Request $request)
	{
		$this->guardAgainstNonAdmins();

		$librarians = $this->getLibrarians();

		$admin = Auth::guard('admin')->user();
		return $this->view('admin-librarian', [
			'librarians' => $librarians
		]);
	}

	public function BooksIndex(Request $request)
	{
		$this->guardAgainstNonAdmins();

		$books = $this->getBooks();

		return $this->view('admin-books', [
			'books' => $books
		]);
	}

	protected function getBooks()
	{
		$booksQuery = $this->db()->query("SELECT * FROM books");

		$books = $booksQuery->fetchAll(PDO::FETCH_CLASS, Book::class);

		return $books;
	}

	public function newBook(Request $request)
	{
		if (!$request->name || !$request->copies || !$request->author) {
			Session::flash('error', 'Please Fill All Data');
			return $this->back();
		}

		$this->insertNewBook($request) ? Session::flash('success', 'Book added successfully') : Session::flash('error', 'Error Adding Book');

		return $this->back();
	}

	protected function insertNewBook(Request $request)
	{
		$userQuery = $this->db()->prepare("INSERT INTO books (name, author, copies, book_id) VALUES (?, ?, ?, ?)");

		var_dump($userQuery->execute([$request->name, $request->author, $request->copies, uniqid()]));
		return true;
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
