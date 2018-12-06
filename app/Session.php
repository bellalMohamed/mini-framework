<?php

namespace App;

class Session
{
	public function set($name, $value){
		return $_SESSION[$name] = $value;
	}

	public function get($name){
		return $_SESSION[$name];
	}

	public function exists($name){
		return (isset($_SESSION[$name])) ? true : false;
	}
	
	public function delete($name){
		if ($this->exists($name)) {
			unset($_SESSION[$name]);
		}
	}

	public function flash($name, $value = ''){
		if ($this->exists($name)) {
			$session = $this->get($name);
			$this->delete($name);
			return $session;
		}else{
			$this->put($name, $value);
		}
	}
}