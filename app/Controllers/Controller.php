<?php

namespace App\Controllers;

use App\Container;
use App\View;

class Controller {
	public static $container;
	public static $db;
	public static $view;
	public static $response;
	public static $session;

	public function __construct(Container $container)
	{
		self::$container = $container;
		self::$db = $container->db;
		self::$view = $container->view;
		self::$response = $container->response;
		self::$session = $container->session;
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

	public function redirect($url)
	{
		return self::$response->redirect($url);
	}

	public function session()
	{
		return self::$session;
	}
}
