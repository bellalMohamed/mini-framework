<?php

use App\Auth;
use App\Controllers\Controller;

class HomeController extends Controller
{
	public function index()
	{
		if (!Auth::guard('user')->check()) {
			return $this->redirect('/login');
		}

		return $this->view('home');
	}
}
