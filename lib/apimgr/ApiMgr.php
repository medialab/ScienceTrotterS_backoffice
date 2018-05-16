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
		]);

		$c->isPost();

		var_dump("CALLING API");
		$res = $c->exec();
		var_dump($c->getInfos());
		var_dump($c->getError());
		//var_dump($c);

		return $res;
	}
}