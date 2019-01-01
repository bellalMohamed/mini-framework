<?php

namespace App;

class Request
{
	protected $data = [];
	public function __construct()
	{
		$this->mapRequst();
	}

	protected function mapRequst()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->data = $_POST;
		}

		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$this->data = $_GET;
		}
	}

	public function all()
	{
		return $this->data;
	}

	public function __get($key)
	{
		if (isset($this->data[$key])) {
			return $this->data[$key];
		}
		return null;
	}
}
