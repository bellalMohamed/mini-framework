<?php

namespace App;

class Hash
{
	public static function make($string)
	{
		return password_hash('password', PASSWORD_DEFAULT);
	}

	public static function check($string, $hashedString)
	{
		return password_verify($string, $hashedString);
	}

	public static function unique()
	{
		return self::make(uniqid());
	}
}
