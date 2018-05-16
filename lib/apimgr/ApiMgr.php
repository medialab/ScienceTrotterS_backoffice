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
	
	private static $tmpData = [];

	public static function init() {
		if (Self::$bInit) {
			return;
		}

		Self::$url = API_URL.'/';
		Self::$curl = new CurlMgr();

		//var_dump("INIT API", $_SESSION);
		if (!empty($_SESSION['user']['token'])) {
			Self::$token = $_SESSION['user']['token'];
		}

		//var_dump("TOKEN: ", Self::$token);

		Self::$bInit = true;
	}

	private static function exec() {
		if (Self::$token) {
			Self::$curl->setHeader('Authorization: '.Self::$token);
		}

		Self::$curl->setData(Self::$tmpData);
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
		;

		Self::setData([
			'email' => $mail,
			'password' => $pass
		]);

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
		Self::$curPage = 0;
		Self::$tmpData = [];
		return Self::$curl->reset();
	} 

	private static function setData($data) {
		Self::$tmpData = $data;
	}

	public static function nextPage() {
		/*if (empty(Self::$tmpData)) {
			return false;
		}
		*/
		Self::$curPage++;
		Self::$tmpData['limit'] = Self::$sqlLimit;
		Self::$tmpData['offet'] = Self::$sqlLimit * Self::$curPage;

		return Self::exec();
	} 
}