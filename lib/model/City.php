<?php

namespace Model;
/**
 * 
 */
class City extends Model
{
	protected $sClass = 'City';
	protected static $ssClass = 'City';
	
	protected $aTranslateVars = ['label', 'state']; // les Variables à traduire

	protected $geoN;
	protected $geoE;
	protected $geoloc;
	protected $label;
	protected $state;
	protected $image;


	function __construct($id=false, Array $aData=[]) {
		$this->sTable = 'cities';
		$this->sqlIgnore = ['geoE','geoN'];
		Parent::__construct($id, $aData);
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
		if (!preg_match_all('/^(-?[0-9]{1,2}\.[0-9]{1,4});(-?[0-9]{1,3}\.[0-9]{1,4})$/', $geoloc, $aMatches)) {
			trigger_error("Faild to Set City::geoloc propoerty. Value is Invalid");
			return;
		}

		/*$this->geoloc = $geoloc;*/
		$this->geoN = $aMatches[1][0];
		$this->geoE = $aMatches[2][0];
	}

	public function setGeoN($geoN) {
		var_dump("Setting GeoN", $geoN);
		if (!empty($geoN) && !preg_match('/^[0-9]{1,2}\.[0-9]{1,4}$/', $geoN)) {
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
		if (!empty($geoE) && !preg_match('/^[0-9]{1,3}\.[0-9]{1,4}$/', $geoE)) {
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
}