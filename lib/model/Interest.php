<?php

namespace Model;

/**
 * 
 */
class Interest extends Model
{
	protected $sClass = 'Interest';
	public static $ssClass = 'Interest';
	
	protected $aTranslateVars = ['title', 'transport', 'audio_script', 'description', 'bibliography', 'audio', 'price', 'schedule', 'address']; // les Variables à traduire

	protected $geoloc;
	protected $geoN;
	protected $geoE;

	protected $title;
	
	protected $address;
	protected $transport;
	protected $description;
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

	protected $city;
	protected $parcours;

	function __construct($id=false, $aData=[]) {
		$this->sTable = 'interests';
		$this->sqlIgnore = ['geoE','geoN', 'city', 'parcours'];
		Parent::__construct($id, $aData);
	}

	function __get($sVar) {
		switch ($sVar) {
			case 'gallery_image':
				if (is_null($this->gallery_image)) {
					$this->gallery_image = new \StdClass;
				}

				return (object)$this->gallery_image;
				break;

			case 'city':
				return $this->getCity();
				break;			
		}

		return Parent::__get($sVar);
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

	public function setLang($lang=false) {
		Parent::setLang($lang);

		if (!empty($this->city)) {
			$this->city->setLang($this->getLang());
		}
	}

	public function getCity() {
		if (empty($this->city)) {
			if (empty($this->cities_id)) {
				$this->city = new City();
				return $this->city;
			}
			
			$this->city = City::get($this->cities_id);
			$this->city->setLang($this->getLang());
		}

		return $this->city;
	}

	public function getParcours() {
		if (empty($this->parcours)) {
			if (empty($this->parcours_id)) {
				$this->parcours = new Parcours();
				return $this->parcours;
			}
			
			$this->parcours = Parcours::get($this->parcours_id);
			$this->parcours->setLang($this->getLang());
		}

		return $this->parcours;
	}

	public static function list($limit=0, $page=0, $columns=false, $aOptions=false, $sClass=false) {
		return Parent::list($limit, $page, $columns, $aOptions, self::$ssClass);
	}

	public static function get($id=0, $aData=[], $sClass=false) {
		return Parent::get($id, $aData, self::$ssClass);
	}

	public static function listByParcours($parc_id) {
		if (!Model::validateID($id)) {
			return false;
		}

	}
}