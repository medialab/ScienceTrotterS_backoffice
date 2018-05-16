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
		if (!preg_match('/[a-z0-9.-_]+@[a-z0-9-_]\.[a-z]{2, 6}/i', $mail) || strlen($pass) < 2) {
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
		var_dump($c->getInfos());
		var_dump($c->getError());

		$res = $c->exec();
	}
}