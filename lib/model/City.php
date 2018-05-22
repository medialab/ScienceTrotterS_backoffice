<?php

namespace Model;
/**
 * 
 */
class City extends Model
{

	public $geoN;
	public $geoE;
	public $geoloc;
	public $label;
	public $state;
	public $image;

	function __construct($id=false, Array $aData=[]) {
		$this->sTable = 'cities';
		Parent::__construct($id, $aData);
	}

	public function load($aData) {
		Parent::load($aData);
		$this->setGeoloc($this->geoloc);
	}

	public function setGeoloc($geoloc) {
		if (empty($geoloc)) {
			$this->geoloc = $geoloc;
			$this->geoloc = $geoloc;
			return;
		}

		$aMatches = [];
		if (!preg_match_all('/^([0-9]{2}\.[0-9]{4});([0-9]{2}\.[0-9]{4})$/', $geoloc, $aMatches)) {
			trigger_error("Faild to Set City::geoloc propoerty. Value is Invalid");
			return;
		}

		var_dump($aMatches);
		exit;
		$this->geoloc = $geoloc;
		$this->geoloc = $geoloc;

		return;
	}

	function __set($sVar, $var) {
		var_dump("SETTING CITY VAR", $sVar, $var);
		if ($sVar === 'geoloc') {
			$this->setGeoloc($var);
		}

		Parent::_set($sVar, $var);
	}
}