<?php

use App\Auth;
use App\Controllers\Controller;

class HomeController extends Controller
{
	public function index()
	{
		return $this->view('home');
	}
}
