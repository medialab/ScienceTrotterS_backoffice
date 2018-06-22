<?php

namespace Model;
/**
 * 
 */
class Parcours extends Model
{
	protected $sClass = 'Parcours';
	protected static $ssClass = 'Parcours';

	protected $aTranslateVars = ['title', 'time', 'audio', 'description'];

	protected $title;
	protected $time;
	protected $audio;
	protected $color;
	protected $description;
	protected $state;
	protected $cities_id;
	
	protected $city;

	function __construct($id=false, $aData=[]) {
		$this->sTable = 'parcours';
		$this->sqlIgnore = ['city'];
		Parent::__construct($id, $aData);
	}

	public static function list($limit=0, $page=0, $columns=false, $aOptions=false, $sClass=false) {
		return Parent::list($limit, $page, $columns, $aOptions, self::$ssClass);
	}

	public static function get($id=0, $aData=[], $sClass=false) {
		return Parent::get($id, $aData, self::$ssClass);
	}
	

	public function setLang($lang=false) {
		Parent::setLang($lang);

		if (!empty($this->city)) {
			$this->city->setLang($this->getLang());
		}
	}

	public function getCity() {
		if (empty($this->city)) {
			if (empty($this->cities_id)) {
				return null;
			}
			
			$this->city = City::get($this->cities_id);
			$this->city->setLang($this->getLang());
		}

		return $this->city;
	}

	public function setColor($color) {
		if (!preg_match('/^#[a-z0-9]{3,6}$/i', $color)) {
			$this->color = '#0000';

			if (!empty($color)) {
				trigger_error('Fail To Set Parcours::$color due to Invalid Color Value: '.$color);
			}
		}
		else{
			$this->color = $color;
		}
	}

	public function setCity($city) {
		if (is_string($city) && Model::validateID($city)) {
			$this->cities_id = $city;
		}
		elseif($city instanceOf City) {
			$this->cities_id = Model::validateID($city->id);
		}
		else {
			$this->cities_id = null;
			if (!empty($city)) {
				trigger_error('Fail To Set Parcours::$cities_id due to Invalid City Value: ', $city);
			}
		}
	}

	function __set($sVar, $value) {
		if ($sVar === 'color') {
			$this->setColor($value);
			return;
		}
		if ($sVar == 'cities_id') {
			$this->setCity($value);
			return;
		}

		Parent::__set($sVar, $value);
	}



	function __get($sVar) {
		switch ($sVar) {
			case 'city':
				return $this->getCity();
				break;			
		}

		return Parent::__get($sVar);
	}

	
	public function setGeoloc(&$geoloc) {
		return;
	}

	public function setGeoN($geoN) {
		return;
	}

	public function setGeoE($geoE) {
		return;
	}
}