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
	public $geoloc;
	public $state;
	public $image;

	function __construct($id=false, Array $aData=[]) {
		$this->sTable = 'cities';
		Parent::__construct($id, $aData);
	}

	function __set($sVar, $var) {
		if ($sVar === 'geoloc') {
			if (empty($var)) {
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

		Parent::_set($sVar, $var);
	}
}