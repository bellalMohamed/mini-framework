<?php

namespace App;

use App\Container;
use App\Exceptions\EmailAlreadyRegisteredException;
use App\Exceptions\NoGuardException;
use App\Hash;
use App\Models\User;
use App\Session;
use PDO;

class Auth
{
	protected $db;

	protected static $logedIn;

	protected static $user;

	protected static $loginAttempt;

	protected static $loginSession;

	protected static $guard;


	public function __construct()
	{
		global $container;
		$this->db = $container->db;
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function attempt($email, $password)
	{
		if (!$this->getGuard()) {
			throw new NoGuardException("No Guard Provided");
		}

		if (!empty($email) || !empty($password)) {
			$user = $this->getUserBy(['email' => $email]);

			if ($user && $this->checkPassword($password, $user->password)) {
				self::$user = $user;
				self::$loginAttempt = true;
				return true;
			}
		}

		return false;
	}

	protected function attemptWithId($id)
	{
		$user = $this->getUserBy(['id' => $id]);
		if ($user) {
			return true;
		}

		return false;
	}

	public function login()
	{
		if (self::$loginAttempt && self::$user) {
			Session::set('login', true);
			Session::set('id', $this->getGuard() . ':' . self::$user->id);
			return true;
		}

		return false;
	}

	public function register($name, $email, $password)
	{
		if (!empty($name) || !empty($email) || !empty($password)) {
			$user = $this->getUserBy(['email' => $email]);
			if ($user) {
				throw new EmailAlreadyRegisteredException("Email already registered");
			}
			if ($this->registerNewUser($name, $email, $password)) {
				return true;
			}
			return false;
		}

		return false;
	}

	protected function registerNewUser($name, $email, $password)
	{
		$table = $this->extractTableFromGuard();
		$hashedPassword = Hash::make($password);

		$userQuery = $this->db->prepare("INSERT INTO $table (name, email, password) VALUES (?, ?, ?)");

		$userQuery->execute([$name, $email, $hashedPassword]);

		return true;
	}

	protected function extractTableFromGuard()
	{
		return $this->getGuard() . 's';
	}

	public function check()
	{
		$loginSession = explode(':', Session::get('id'));

		return $this->attemptWithId($loginSession[1]);
	}


	public function user()
	{
		return $this->getUserBy(['id' => $this->getLoginSessionId()]);
	}

	protected function getLoginSessionId()
	{
		$loginSession = explode(':', $this->getLoginSession());
		return $loginSession[1];
	}


	protected function getLoginSession()
	{
		if (self::$loginSession) {
			return self::$loginSession;
		}

		self::$loginSession = Session::get('id');
		return self::$loginSession;
	}

	protected function checkPassword($password, $userPassword)
	{
		return Hash::check($password, $userPassword);
	}

	protected function getUserBy(array $params = [])
	{
		$column = array_keys($params)[0];
		$value = array_values($params)[0];
		$table = $this->getGuard() . 's';

		// $userQuery = $this->db->prepare("SELECT TOP 1 * FROM $table WHERE {$column} = ?");
		$userQuery = $this->db->prepare("SELECT * FROM $table WHERE {$column} = ?");
		$userQuery->execute([$value]);
		$user = $userQuery->fetchAll(PDO::FETCH_CLASS, User::class);
		if (!empty($user)) {
			return $user[0];
		}
		return null;
	}

	public static function guard($guard)
	{
		self::$guard = $guard;
		return (new self);
	}

	public function getGuard()
	{
		return self::$guard;
	}
}
