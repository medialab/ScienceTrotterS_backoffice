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
			$this->geoN = $geoloc;
			$this->geoE = $geoloc;
			return;
		}

		$aMatches = [];
		if (!preg_match_all('/^(-?[0-9]{1,2}\.[0-9]{1,4});(-?[0-9]{1,2}\.[0-9]{1,4})$/', $geoloc, $aMatches)) {
			trigger_error("Faild to Set City::geoloc propoerty. Value is Invalid");
			return;
		}

		$this->geoN = $aMatches[1];
		$this->geoE = $aMatches[2];
	}

	function __set($sVar, $var) {
		var_dump("City Update: $sVar");
		if ($sVar === 'geoloc') {
			$this->setGeoloc($var);
		}

		Parent::_set($sVar, $var);
	}
}