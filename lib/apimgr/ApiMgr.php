<?php

/**
 * 
 */
class ApiMgr {
	private static $url;
	private static $curl;
	private static $bInit = false;

	private static function init() {
		if (Self::$bInit) {
			return;
		}

		Self::$url = API_URL.'/';
		Self::$curl = new CurlMgr();
	}

	public static function login($mail, $pass) {
		Self::init();

		if (!preg_match('/[a-z0-9.-_]+@[a-z0-9-_]+\.[a-z]{2,6}/i', $mail) || strlen($pass) < 2) {
			var_dump("Verify Mail: ", !preg_match('/[a-z0-9.-_]+@[a-z0-9-_]\.[a-z]{2, 6}/i', $mail));
			var_dump("Verify pass: ", strlen($pass) < 2);
			return false;
		}

		$c = &Self::$curl;
		$c->verbose();
		
		$c->setUrl(Self::$url.'login');
		$c->setData([
			'email' => $mail,
			'password' => $pass
		], false);

		$c->isPost();

		var_dump("CALLING API");
		
		$res = $c->exec();
		var_dump("RESPONSE", $res);

		$res = json_decode($res);
		var_dump($res);
		
		if (empty($res) || !$res->status || empty($res->token)) {
			return false;
		}

		$_SESSION['user'] = [
			'email' => $mail,
			'token' => $res->token,
			'aut_access' => 'ADMIN'
		];

		var_dump($_SESSION['user']);

		return true;
	}
}