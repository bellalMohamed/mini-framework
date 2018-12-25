<?php

$app->get('/home', 'HomeController@index');
$app->get('/', 'HomeController@index');


$app->get('/student/login/index', 'StudentController@studentLoginIndex');
$app->post('/student/login', 'StudentController@loginStudent');
$app->post('/student/register', 'StudentController@registerStudent');
$app->get('/student/home', 'StudentController@index');




$app->get('/teacher/login/index', 'TeacherController@teacherLoginIndex');
$app->post('/teacher/login', 'TeacherController@loginTeacher');
$app->post('/teacher/register', 'TeacherController@registerTeacher');
$app->get('/teacher/home', 'TeacherController@index');




$app->get('/admin/login/index', 'AdminController@adminLoginIndex');
$app->post('/admin/login', 'AdminController@loginAdmin');
$app->get('/admin/librarians', 'AdminController@librarianIndex');
$app->post('/admin/librarian/new', 'AdminController@registerNewLibrarian');
$app->get('/admin/librarian/delete', 'AdminController@deleteLibrarian');

$app->get('/admin/users/give/student', 'AdminController@giveBookToStudentIndex');
$app->get('/admin/users/give/teacher', 'AdminController@giveBookToStudentIndex');


$app->get('/admin/books', 'AdminController@booksIndex');
$app->post('/admin/book/new', 'AdminController@newBook');

$app->get('/admin/users', 'AdminController@userIndex');
$app->get('/admin/users/limit', 'AdminController@updateUserLimit');



$app->get('/librarian/login/index', 'LibrarianController@librarianLoginIndex');
$app->post('/librarian/login', 'LibrarianController@loginLibrarian');
$app->get('/librarian/books', 'LibrarianController@librarianGiveBooks');
$app->get('/librarian/give', 'LibrarianController@giveBookIndex');
$app->post('/librarian/give/book', 'LibrarianController@giveBook');



