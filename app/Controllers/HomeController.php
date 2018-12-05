<?php

namespace App\Controllers;

use App\Controllers\Controller;

class HomeController extends Controller
{
	public function index($response)
	{
		return $response->setBody('Hey');
	}
}