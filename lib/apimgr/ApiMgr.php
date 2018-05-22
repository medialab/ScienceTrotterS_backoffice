<?php

/**
 * 
 */
class ApiMgr {
	private static $url;				// Base API URL
	private static $curl;				// CurlMgr
	private static $token = false;		// Api Token
	private static $bInit = false;		// Is Init
	
	private static $curPage = 0;		// Current Page
	private static $sqlLimit = 25;		// Request Return Limit
	private static $sqlMaxLimit = 200;	// Max Reuest Return Limit
	
	private static $tmpData = [];		// Temporary Request

	public static function init() {
		if (Self::$bInit) {
			return;
		}

		Self::setUrl();

		Self::$curl = new CurlMgr();
		Self::$curl->setTimeout(3);

		if (!empty($_SESSION['user']['token'])) {
			Self::setToken($_SESSION['user']['token']);
		}

		Self::$bInit = true;
	}

	/**
	 * Defini L'url et L'ajoute à Smarty pour le ApiMgr js
	 */
	private static function setUrl() {
		global $smarty;

		Self::$url = API_URL.'/';
		$smarty->assign("_API_URL_", Self::$url);
	}

	/**
	 * Execute Une REquete
	 * @param  string  $method       (get, postn put...)
	 * @param  boolean $applyHeaders Ajoute ou non le header Authorization 
	 * @return Array                Résultats
	 */
	private static function exec($method='get', $applyHeaders=true) {
		if (Self::$token && $applyHeaders) {
			Self::$curl->setHeader('Authorization: '.Self::$token);
		}

		Self::$tmpData['limit'] = Self::$sqlLimit;
		Self::$tmpData['offset'] = Self::$sqlLimit * Self::$curPage;

		//var_dump("Request DATA", Self::$tmpData);
		
		Self::$curl->setData(Self::$tmpData)->setMethod($method);

		$r = Self::$curl->exec();
		var_dump(Self::$curl->getInfos());
		var_dump(Self::$curl->getError());
		var_dump($r);

		return json_decode($r);
	}

	/**
	 * Logout
	 */
	public static function logout() {
		if (!Self::$token) {
			return;
		}

		$c = Self::reset();
		$c->setUrl(Self::$url.'logout');

		$res = Self::exec();
	}

	/**
	 * Execute un login
	 * @param  String $mail Email
	 * @param  String $pass Password
	 * @return bool       Succès
	 */
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

	/**
	 * Defini le Token Et l'ajoute à Smarty pour ApiMgr Js
	 * @param [type] $token [description]
	 */
	private static function setToken($token) {
		global $smarty;
		Self::$token = $token;
		$smarty->assign("_API_TOKEN_", Self::$token);
	}

	/**
	 * Récupère Un Array d'une Table
	 * @param  String  $model  Table
	 * @param  boolean $public Public ou Private Url
	 * @param  integer $limit  Limit
	 * @param  integer $page   Page
	 * @return Array          List d'elements
	 */
	public static function list($model, $public=true, $limit=0, $page=0) {
		$c = Self::reset();

		Self::setLimit($limit);
		Self::setPage($page);

		$base = $public ? 'public/' : 'private/';
		
		$url = Self::$url.$base.$model.'/list';

		$c->setUrl($url);
		$res = Self::exec();
		return $res;
	}

	/**
	 * Récupère Un Element par ID
	 * @param  String  $model  Table
	 * @param  [type]  $id     ID
	 * @param  boolean $public Public ou Private Url
	 * @return Array          Data
	 */
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

	/**
	 * Reset La Requete
	 * @return CurlMgr
	 */
	private static function reset() {
		Self::$tmpData = [];

		Self::$curPage = 0;
		Self::$sqlLimit = 25;
		Self::$curl
			->reset()
			->verifySSL(API_SSL)
		;

		return Self::$curl;
	}

	private static function setData($data) {
		Self::$tmpData = $data;
	}

	/**
	 * Page Suivante De la Requete
	 * @return Array Liste des Elements
	 */
	public static function nextPage() {
		Self::$curPage++;
		
		Self::$tmpData['limit'] = Self::$sqlLimit;
		Self::$tmpData['offset'] = Self::$sqlLimit * Self::$curPage;

		$r = Self::exec('get', false);

		//var_dump(Self::$curl->getInfos());
		//var_dump(Self::$curl->getError());

		return $r;
	}

	/**
	 * definis la limite
	 * @param int $limit
	 */
	public static function setLimit($limit) {
		$limit = (int)$limit;
		
		if ($limit <= 0) {
			$limit = 1;
		}
		elseif ($limit > Self::$sqlMaxLimit) {
			$limit = Self::$sqlMaxLimit;
		}

		Self::$sqlLimit = $limit;
	}

	/**
	 * Défini la Page
	 * @param int $page page
	 */
	public static function setPage($page) {
		$page = (int)$page;
		
		if ($page < 0) {
			$page = 0;
		}

		Self::$curPage = $page;
	}

	public static function update(Model\Model $oModel) {
		$c = Self::reset();
		
		$url = Self::$url.'private/'.$oModel->sTable.'/update';
		$c->setUrl($url);

		$this->setData($oModel->toArray());

		$res = Self::exec('post');
		return $res;
	}
}