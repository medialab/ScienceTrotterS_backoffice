<?php

namespace Model;

/**
 * 
 */
class Interest extends Model
{
	protected $sClass = 'Interest';
	public static $ssClass = 'Interest';
	
	protected $aTranslateVars = ['title', 'transport', 'audio_script', 'bibliography', 'audio', 'price', 'schedule', 'address']; // les Variables à traduire

	protected $geoloc;
	protected $geoN;
	protected $geoE;

	protected $title;
	
	protected $address;
	protected $transport;
	protected $audio_script;
	protected $bibliography;
	protected $header_image;
	protected $gallery_image;
	protected $audio;
	protected $price;
	protected $cities_id;
	protected $parcours_id;
	protected $state;
	protected $schedule;

	function __construct($id=false, $aData=[]) {
		$this->sTable = 'interests';
		$this->sqlIgnore = ['geoE','geoN'];
		Parent::__construct($id, $aData);
	}

	function __get($sVar) {
		if ($sVar === 'gallery_image') {
			if (is_null($this->gallery_image)) {
				$this->gallery_image = [];
			}

			return $this->gallery_image;
		}

		return Parent::__get($sVar);
	}

	public function load($aData) {
		Parent::load($aData);
		$this->setGeoloc($this->geoloc);
	}

	public function setGeoloc(&$geoloc) {
		if ($geoloc === ';') {
			$geoloc = null;
		}
		if (empty($geoloc)) {
			$this->geoN = $geoloc;
			$this->geoE = $geoloc;
			return;
		}

		$aMatches = [];
		if (!preg_match_all('/^(-?[0-9]{1,2}\.?[0-9]{0,4});(-?[0-9]{1,3}\.?[0-9]{0,4})$/', $geoloc, $aMatches)) {
			trigger_error("Faild to Set Interest::geoloc propoerty. Value is Invalid");
			return;
		}

		/*$this->geoloc = $geoloc;*/
		$this->geoN = $aMatches[1][0];
		$this->geoE = $aMatches[2][0];
	}

	public function setGeoN($geoN) {
		var_dump("Setting GeoN", $geoN);
		if (!empty($geoN) && !preg_match('/^[0-9]{1,2}\.?[0-9]{0,4}$/', $geoN)) {
			throw new Exception('Error: Invalid Lattitude Value: '.$geoN, 1);
		}

		if (empty($geoN) && empty($this->$geoE)) {
			$this->geoloc = null;
		}
		else {
			$this->geoloc = $geoN.';'.$this->geoE;
		}
	}

	public function setGeoE($geoE) {
		var_dump("Setting GeoE", $geoE);
		if (!empty($geoE) && !preg_match('/^[0-9]{1,3}\.?[0-9]{0,4}$/', $geoE)) {
			throw new Exception('Error: Invalid Longitude Value: '.$geoE, 1);
		}

		if (empty($geoE) && empty($this->$geoN)) {
			$this->geoloc = null;
		}
		else {
			$this->geoloc = $this->geoN.';'.$geoE;
		}
	}

	function __set($sVar, $var) {
		switch ($sVar) {
			case 'geoloc':
				$this->setGeoloc($var);
				break;

			case 'geoN':
				$this->setGeoN($var);
				break;

			case 'geoE':
				$this->setGeoE($var);
				break;
			
			default:
				break;
		}

		Parent::__set($sVar, $var);
	}

	public static function list($limit=0, $page=0, $columns=false, $sClass=false) {
		return Parent::list($limit, $page, $columns, self::$ssClass);
	}

	public static function get($id=0, $aData=[], $sClass=false) {
		return Parent::get($id, $aData, self::$ssClass);
	}
}