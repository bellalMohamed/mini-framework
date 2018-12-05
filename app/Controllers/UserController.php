<?php

namespace App\Controllers;

use PDO;

class UserController
{
	protected $db;

	public function __construct(PDO $db)
	{
		$this->db = $db;
	}

	public function index($response, $view)
	{
		// var_dump($view);
		// die();
		return $view->load('users');
		// $useers = $this->db->query(SELECT * FROM )
	}
}