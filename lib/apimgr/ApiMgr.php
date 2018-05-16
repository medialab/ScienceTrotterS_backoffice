<?php

/**
 * 
 */
class ApiMgr {
	private static $url;
	private static $curl;
	private static $token = false;
	private static $bInit = false;

	public static function init() {
		if (Self::$bInit) {
			return;
		}

		Self::$url = API_URL.'/';
		Self::$curl = new CurlMgr();

		var_dump("INIT API", $_SESSION);
		if (!empty($_SESSION['user']['token'])) {
			Self::$token = $_SESSION['user']['token'];
		}

		Self::$bInit = true;
	}

	private static function exec() {
		if (Self::$token) {
			Self::$curl->setHeader('Authorization: '.Self::$token);
		}

		return json_decode(Self::$curl->exec());
	}

	public static function login($mail, $pass) {
		if (!preg_match('/[a-z0-9.-_]+@[a-z0-9-_]+\.[a-z]{2,6}/i', $mail) || strlen($pass) < 2) {
			return false;
		}

		$c = Self::$curl->reset();
		$c->setUrl(Self::$url.'login')
			->isPost()
			->verbose()		
			->setData([
				'email' => $mail,
				'password' => $pass
			], false)
		;

		$res = Self::exec();

		if (empty($res) || !$res->status || empty($res->token)) {
			return false;
		}

		Self::$token = $token;

		$_SESSION['user'] = [
			'pass' => $pass,
			'email' => $mail,
			'token' => $res->token,
			'aut_access' => 'ADMIN'
		];


		return true;
	}

	public static function logout() {
		if (Self::$token) {
			return;
		}

		$c = Self::$curl->reset();
		$c->setUrl(Self::$url.'logout');

		$res = Self::exec();
		var_dump("LOGOUT RESULT", $res);
	}
}