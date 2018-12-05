<?php

namespace App;

class View
{
	protected $statusCode = 200;
	protected $view;


	public function load($view)
	{
		$this->view = $view;
		return $this;
	}

	public function getViewPath()
	{
		return $this->view . '.php';
	}

	public function withStatus($statusCode)
	{
		$this->statusCode = $statusCode;
		return $this;
	}

	public function getStatusCode()
	{
		return $this->statusCode;
	}
}