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

	function __construct($id=false, $aData=[]) {
		$this->sTable = 'parcours';
		Parent::__construct($id, $aData);
	}

	public static function list($limit=0, $page=0, $columns=false, $sClass=false) {
		return Parent::list($limit, $page, $columns, self::$ssClass);
	}

	public static function get($id=0, $aData=[], $sClass=false) {
		return Parent::get($id, $aData, self::$ssClass);
	}

	public function setColor($color) {
		if (!preg_match('/^#[a-z0-9]{3,6}$/', $color)) {
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
}