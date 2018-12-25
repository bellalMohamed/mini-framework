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

	public function giveBookIndex(Request $request)
	{
		$books = $this->getAvailableBooks();
		$userBook = $this->getUserBook($request);

		return $this->view('give-book', [
			'id' => $request->id,
			'type' => $request->type,
			'books' => $books,
			'userBooks' => $userBook,
		]);
	}

	protected function getUserBook(Request $request)
	{
		$booksQuery = $this->db()->prepare("
			SELECT * FROM books_borrowed
			LEFT JOIN books ON books.id = books_borrowed.book_id
			WHERE books_borrowed.user_type = ? AND books_borrowed.user_id = ?
		");

		$booksQuery->execute([$request->type, $request->id]);
		$books = $booksQuery->fetchAll(PDO::FETCH_CLASS, BookBorrowed::class);
		return $books;
	}

	public function giveBook(Request $request)
	{
		$noOfBorrowedBooks = $this->getNOBorrowedBooks($request);
		$userBooksLimit = $this->getUserBooksLimit($request);
		if ($noOfBorrowedBooks  >= $userBooksLimit) {
			Session::flash('error', 'User Exceeded Books Limit');
			return $this->back();
		}

		$giveQuery = $this->db()->prepare("INSERT INTO books_borrowed (book_id, user_id, user_type, return_date) VALUES (?, ?, ?, ?)");

		$giveQuery->execute([$request->book_id, $request->id, $request->type, $request->date]);

		Session::flash('success', 'Book Given Successfully');
		return $this->back();
	}

	protected function getNOBorrowedBooks(Request $request)
	{
		$noOfBorrowedBooksQuery = $this->db()->prepare("SELECT COUNT(user_id) AS total FROM books_borrowed WHERE user_id = ? AND user_type = ?");

		$noOfBorrowedBooksQuery->execute([$request->id, $request->type]);

		$noOfBorrowedBooks = $noOfBorrowedBooksQuery->fetchAll(PDO::FETCH_CLASS, BookBorrowed::class);

		return $noOfBorrowedBooks[0]->total;
	}

	protected function getUserBooksLimit(Request $request)
	{
		$table = $request->type . 's';

		$limitQuery = $this->db()->prepare("SELECT books FROM {$table} WHERE id = ?");

		$limitQuery->execute([$request->id]);

		$limit = $limitQuery->fetchAll(PDO::FETCH_CLASS, BookBorrowed::class);
		return $limit[0]->books;
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
			return $this->redirect('/librarian/books');
		}

		return $this->view('librarian-auth');
	}

	public function loginLibrarian(Request $request)
	{
		if (Auth::guard('librarian')->attempt($request->email, $request->password)) {
			Auth::guard('librarian')->login();
			return $this->redirect('/librarian/books');
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
				return $this->redirect('/librarian/books');
			}

		} catch (Exception $e) {
			Session::flash('message', $e->getMessage());
			return $this->redirect('/librarian/login/index');

		}
	}
}
