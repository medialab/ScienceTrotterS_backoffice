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

	public static function listByParcours($parc_id) {
		if (!Model::validateID($id)) {
			return false;
		}

	}
}