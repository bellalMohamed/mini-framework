<?php

$app->get('/home', 'HomeController@index');
$app->get('/users', 'UserController@index');

$app->get('/login/user', 'UserController@UserLoginIndex');
$app->post('/login/user/attempt', 'UserController@loginUser');
