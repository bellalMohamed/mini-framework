<?php

use App\Auth;
use App\Controllers\Controller;
use App\Hash;
use App\Models\Book;
use App\Models\BookBorrowed;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\librarian;
use App\Request;
use App\Session;

class LibrarianController extends Controller
{

	public function librarianGiveBooks(Request $request)
	{
		$students = $this->getStudents();
		$teachers = $this->getTeachers();
		// $booksBorrowed = $this->getBooksBorrowed();
		// $studentsWithBorrowedBooks = $this->studentsWithBorrowedBooks();
		$availableBooks = $this->getAvailableBooks();

		// return $this->response()->json($availableBooks);
		return $this->view('librarian-books', [
			'students' => $students,
			'teachers' => $teachers,
			'books' => $availableBooks,
		]);
	}

	protected function getAvailableBooks()
	{
		$this->db()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$borrowerQuery = $this->db()->query("
			SELECT books.id, books.name, books.id, books.copies, books_borrowed.book_id, COUNT(books_borrowed.book_id) AS borrows FROM books
			LEFT JOIN books_borrowed ON books.id = books_borrowed.book_id
			GROUP BY books.id
			having books.copies > COUNT(books_borrowed.book_id)
		");

		$books = $borrowerQuery->fetchAll(PDO::FETCH_CLASS, BookBorrowed::class);
		return $books;
	}

	protected function studentsWithBorrowedBooks()
	{
		$this->db()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$borrowerQuery = $this->db()->query("
			SELECT * FROM students
			INNER JOIN books_borrowed ON students.id = books_borrowed.user_id
			INNER JOIN books ON books_borrowed.book_id = books.id
		");

		$books = $borrowerQuery->fetchAll(PDO::FETCH_CLASS, BookBorrowed::class);

		return $this->regulateStudentsWithBooks($books);
	}

	protected function getBooksBorrowed()
	{
		$borrowerQuery = $this->db()->query("SELECT * FROM books_borrowed");

		$books = $borrowerQuery->fetchAll(PDO::FETCH_CLASS, BookBorrowed::class);

		return $books;
	}

	protected function extractAvailableBooks(array $books, array $booksBorrowed)
	{
		$availableBooks = [];
	}

	protected function getStudents()
	{
		$studentsQuery = $this->db()->query("SELECT id, name, email, books FROM students");

		$students = $studentsQuery->fetchAll(PDO::FETCH_CLASS, Student::class);

		return $students;
	}

	protected function getTeachers()
	{
		$teachersQuery = $this->db()->query("SELECT id, name, email, books FROM teachers");

		$teachers = $teachersQuery->fetchAll(PDO::FETCH_CLASS, Teacher::class);

		return $teachers;
	}

	protected function getBooks()
	{
		$teachersQuery = $this->db()->query("SELECT * FROM books");

		$teachers = $teachersQuery->fetchAll(PDO::FETCH_CLASS, Book::class);

		return $teachers;
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
