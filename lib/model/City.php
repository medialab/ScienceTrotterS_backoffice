<?php

namespace Model;
/**
 * 
 */
class City extends Model
{
	protected $sClass = 'City';
	public static $ssClass = 'City';
	
	protected $aTranslateVars = ['title']; // les Variables à traduire

	protected $geoN;
	protected $geoE;
	protected $geoloc;
	protected $title;
	protected $state;
	protected $image;


	function __construct($id=false, $aData=[]) {
		$this->sTable = 'cities';
		$this->sqlIgnore = ['geoE','geoN'];
		Parent::__construct($id, $aData);
	}

	public function load($aData) {
		Parent::load($aData);
		$this->setGeoloc($this->geoloc);
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

	public static function list($limit=0, $page=0, $columns=false, $aOptions=false, $sClass=false) {
		return Parent::list($limit, $page, $columns, $aOptions, self::$ssClass);
	}

	public static function get($id=0, $aData=[], $sClass=false) {
		return Parent::get($id, $aData, self::$ssClass);
	}
}