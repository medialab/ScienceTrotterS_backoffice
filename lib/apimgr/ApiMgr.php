<?php

/**
 * 
 */
class ApiMgr {
	private static $url;
	private static $curl;
	private static $token = false;
	private static $bInit = false;
	
	private static $curPage = 0;
	private static $sqlLimit = 25;
	private static $sqlMaxLimit = 200;
	
	private static $tmpData = [];

	public static function init() {
		if (Self::$bInit) {
			return;
		}

		Self::$url = API_URL.'/';
		Self::$curl = new CurlMgr();
		Self::$curl->setTimeout(3);
		//var_dump("INIT API", $_SESSION);
		if (!empty($_SESSION['user']['token'])) {
			Self::setToken($_SESSION['user']['token']);
		}

		//var_dump("TOKEN: ", Self::$token);

		Self::$bInit = true;
	}

	private static function exec($method='get', $applyHeaders=true) {
		if (Self::$token && $applyHeaders) {
			Self::$curl->setHeader('Authorization: '.Self::$token);
		}

		Self::$tmpData['limit'] = Self::$sqlLimit;
		Self::$tmpData['offset'] = Self::$sqlLimit * Self::$curPage;

		//var_dump("Request DATA", Self::$tmpData);
		
		Self::$curl->setData(Self::$tmpData)->setMethod($method);

		$r = Self::$curl->exec();
		//var_dump("API RESPONSE", $r);
		return json_decode($r);
	}

	public static function login($mail, $pass) {
		if (!preg_match('/[a-z0-9.-_]+@[a-z0-9-_]+\.[a-z]{2,6}/i', $mail) || strlen($pass) < 2) {
			return false;
		}

		$c = Self::reset();
		$c->setUrl(Self::$url.'login')
			->isPost()
			->verbose()
		;

		Self::setData([
			'email' => $mail,
			'password' => $pass
		]);

		$res = Self::exec('post');

		if (empty($res) || !$res->status || empty($res->token)) {
			return false;
		}

		Self::setToken($token);

		$_SESSION['user'] = [
			'pass' => $pass,
			'email' => $mail,
			'token' => $res->token,
			'aut_access' => 'ADMIN'
		];


		return true;
	}

	private static function setToken($token) {
		global $smarty;
		Self::$token = $token;
		$smarty->assign("_API_TOKEN_", Self::$token);
	}

	public static function logout() {
		if (!Self::$token) {
			return;
		}

		$c = Self::reset();
		$c->setUrl(Self::$url.'logout');

		$res = Self::exec();
	}

	public static function list($model, $public=true) {
		$c = Self::reset();

		$base = $public ? 'public/' : 'private/';
		
		$url = Self::$url.$base.$model.'/list';

		$c->setUrl($url);
		$res = Self::exec();
		return $res;
	}

	public static function get($model, $id, $public=true) {
		$c = Self::reset();

		$base = $public ? 'public/' : 'private/';
		
		$url = Self::$url.$base.$model.'/'.$id;

		$c->setUrl($url);

		Self::setData([
			'limit' => Self::$sqlLimit,
			'offset' => Self::$sqlLimit * Self::$curPage,
		]);

		$res = Self::exec();
		return $res;
	}

	private static function reset() {
		Self::$tmpData = [];

		Self::$curPage = 0;
		Self::$sqlLimit = 25;

		return Self::$curl->reset();
	} 

	private static function setData($data) {
		Self::$tmpData = $data;
	}

	public static function nextPage() {
		Self::$curPage++;
		
		Self::$tmpData['limit'] = Self::$sqlLimit;
		Self::$tmpData['offset'] = Self::$sqlLimit * Self::$curPage;

		$r = Self::exec('get', false);

		//var_dump(Self::$curl->getInfos());
		//var_dump(Self::$curl->getError());

		return $r;
	}

	public static function setLimit($limit) {
		$limit = (int)$limit;
		
		if ($limit < 0) {
			$limit = 1;
		}
		elseif ($limit > Self::$sqlMaxLimit) {
			$limit = Self::$sqlMaxLimit;
		}

		Self::$sqlLimit = $limit;
	}

	public static function setPage($page) {
		$page = (int)$page;
		
		if ($page < 0) {
			$page = 0;
		}

		Self::$sqlpage = $limit;
	}
}