<?php

namespace App\Controllers;

use PDO;

class UserController
{
	public function __construct(PDO $db)
	{
		var_dump($db);
		die();
	}

	public function index($response)
	{
		return $response->withJson([
			'error' => false,
		]);
	}
}