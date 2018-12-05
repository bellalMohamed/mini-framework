<?php

$app->get('/home', [App\Controllers\HomeController::class, 'index']);
$app->get('/users', [new App\Controllers\UserController($container->db, $container->view), 'index']);
// $app->get('/users', function ($response) {
// 	var_dump($response);
// });
