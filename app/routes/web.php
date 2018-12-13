<?php

$app->get('/home', 'HomeController@index');
$app->get('/users', 'UserController@index');

$app->get('/student/login/index', 'StudentController@studentLoginIndex');
$app->post('/student/login', 'StudentController@loginStudent');
$app->post('/student/register', 'StudentController@registerStudent');

$app->get('/teacher/login/index', 'TeacherController@teacherLoginIndex');
$app->post('/teacher/login', 'TeacherController@loginTeacher');
$app->post('/teacher/register', 'TeacherController@registerTeacher');
