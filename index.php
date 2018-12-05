<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

$app = new App\App;
$container = $app->getContainer();

$container['errorHandler'] = function () {
	return function ($response) {
		return $response->setBody('Page Not Found')->withStatus('404');
	};
};

$container['config'] = function () {
	return [
		'db_driver' => 'mysql',
		'db_host' => 'localhost',
		'db_name' => 'hospital',
		'db_user' => 'phpmyadmin',
		'db_pass' => 'root',
	];
};

$container['db'] = function ($c) {
	return new PDO("{$c->config['db_driver']}:host={$c->config['db_host']};dbname={$c->config['db_name']}", $c->config['db_user'], $c->config['db_pass']);
};

// $app->get('/', function () {
// 	echo 'Home'; 
// });

// $app->post('/signin', function () {
// 	echo 'Home'; 
// });

$app->get('/home', [App\Controllers\HomeController::class, 'index']);
$app->get('/users', [new App\Controllers\UserController($container->db), 'index']);

$app->run();