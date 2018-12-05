<?php

$app->get('/home', 'HomeController@index');
$app->get('/users', 'UserController@index');

$app->get('/login', 'UserController@loginIndex');
