<?php

namespace App\Controllers;

use App\Container;
use App\View;

class Controller {
	public static $db;
	public static $response;
	public static $view;
	public static $container;

	public function __construct(Container $container)
	{
		self::$container = $container;
		self::$db = $container->db;
		self::$view = $container->view;
		self::$response = $container->response;
	}

	public static function getDB() 
	{
		return self::$db;		
	}

	public function view($view, array $vars = [])
	{
		return self::$view->load($view, $vars);
	}

	public function response()
	{
		return self::$response;
	}
}