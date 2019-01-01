<?php

use App\Auth;
use App\Controllers\Controller;
use App\Hash;
use App\Models\Book;
use App\Models\Librarian;
use App\Models\Student;
use App\Models\Teacher;
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

	public function editLibrarianIndex(Request $request)
	{
		$librarian = $this->getLibrarianById($request->id);

		return $this->view('edit-librarian', [
			'librarian' => $librarian,
		]);
	}

	public function editLibrarian(Request $request)
	{
		$limitQuery = $this->db()->prepare("UPDATE librarians SET name = ?, email = ? WHERE id = ?");
		$limitQuery->execute([$request->name, $request->email, $request->id]);

		Session::flash('success', 'Librarian Updated Successfully');
		return $this->back();
	}

	protected function getLibrarianById($librarianId)
	{
		$librariansQuery = $this->db()->prepare("SELECT id, name, email FROM librarians WHERE id = ?");
		$librariansQuery->execute([$librarianId]);
		$librarian = $librariansQuery->fetchAll(PDO::FETCH_CLASS, Librarian::class);

		return $librarian[0];
	}

	public function userIndex()
	{
		$this->guardAgainstNonAdmins();

		$students = $this->getStudents();
		$teachers = $this->getTeachers();

		return $this->view('admin-users', [
			'students' => $students,
			'teachers' => $teachers,
		]);
	}

	public function updateUserLimit(Request $request)
	{
		$limitQuery = $this->db()->prepare("UPDATE $request->role SET books = ? WHERE id = ?");
		$limitQuery->execute([$request->limit, $request->id]);

		Session::flash('success', 'Limit Updated');
		return $this->back();
	}

	protected function getStudents()
	{
		$studentsQuery = $this->db()->query("SELECT * FROM students");

		$students = $studentsQuery->fetchAll(PDO::FETCH_CLASS, Student::class);

		return $students;
	}

	protected function getTeachers()
	{
		$teachersQuery = $this->db()->query("SELECT * FROM teachers");

		$teachers = $teachersQuery->fetchAll(PDO::FETCH_CLASS, Teacher::class);

		return $teachers;
	}

	public function BooksIndex(Request $request)
	{
		$this->guardAgainstNonAdmins();

		$books = $this->getBooks();

		return $this->view('admin-books', [
			'books' => $books
		]);
	}

	public function deleteBook(Request $request)
	{
		$this->guardAgainstNonAdmins();

		$userQuery = $this->db()->prepare("DELETE FROM books WHERE id = ?");

		$userQuery->execute([$request->id]);

		Session::flash('success', 'Book deleted successfully');
		return $this->back();
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

		$userQuery->execute([$request->name, $request->author, $request->copies, uniqid()]);
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
			return $this->redirect('/admin/librarians');
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
