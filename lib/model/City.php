<?php

namespace Model;
/**
 * 
 */
class City extends Model
{

	protected $geoN;
	protected $geoE;
	protected $geoloc;
	protected $label;
	protected $state;
	protected $image;

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
			$this->geoN = $geoloc;
			$this->geoE = $geoloc;
			return;
		}

		$aMatches = [];
		if (!preg_match_all('/^(-?[0-9]{1,2}\.[0-9]{1,4});(-?[0-9]{1,3}\.[0-9]{1,4})$/', $geoloc, $aMatches)) {
			trigger_error("Faild to Set City::geoloc propoerty. Value is Invalid");
			return;
		}

		$this->geoloc = $geoloc;
		$this->geoN = $aMatches[1];
		$this->geoE = $aMatches[2];
	}

	function __set($sVar, $var) {
		var_dump("City Update: $sVar", $var);
		if ($sVar === 'geoloc') {
			$this->setGeoloc($var);
		}

		Parent::__set($sVar, $var);
	}
}