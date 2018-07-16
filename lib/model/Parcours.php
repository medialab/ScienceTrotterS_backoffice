<?php

namespace Model;
/**
 * 
 */
class Parcours extends Model
{
	/**
	 * Class Du Model
	 */
	protected $sClass = 'Parcours';

	/**
	 * Class Du Model Static
	 */
	protected static $ssClass = 'Parcours';

	/**
	 * Nom du Model pour un utilisateur
	 */
	protected $sUserStr = 'le parcours';

	/**
	 * Variables à Traduire
	 */
	protected $aTranslateVars = ['title', 'time', 'audio', 'description', 'audio_script'];

	/**
	 * Titre
	 */
	protected $title;

	/**
	 * Durée Du Parcours
	 */
	protected $time;

	/**
	 * Fichiers Audio
	 */
	protected $audio;

	/**
	 * Couleur
	 */
	protected $color;

	/**
	 * Descriptions
	 */
	protected $description;

	/**
	 * Scripts Audios
	 */
	protected $audio_script;

	/**
	 * Status
	 */
	protected $state;

	/**
	 * ID De LA Ville Associée
	 */
	protected $cities_id;
	
	/**
	 * LA Ville Associée
	 */
	protected $city;

	function __construct($id=false, $aData=[]) {
		$this->sTable = 'parcours';
		$this->sqlIgnore = ['city'];
		Parent::__construct($id, $aData);
	}

	/**
	 * [list description]
	 * @param  integer $limit    Limite
	 * @param  integer $page     Page
	 * @param  boolean $columns  Les Colones
	 * @param  boolean $aOptions Options
	 * @param  boolean $sClass   La Classe Du Modèle
	 * @return Array            Array Des Modèles
	 */
	public static function list($limit=0, $page=0, $columns=false, $aOptions=false, $sClass=false) {
		return Parent::list($limit, $page, $columns, $aOptions, self::$ssClass);
	}

	/**
	 * Recherche Par Id
	 * @param  integer $id     Id
	 * @param  array   $aData  Donée Par Défault
	 * @param  boolean $sClass Class Du Model
	 * @return Model          Le Model
	 */
	public static function get($id=0, $aData=[], $sClass=false) {
		return Parent::get($id, $aData, self::$ssClass);
	}
	
	/**
	 * Selectionne Un Lanque
	 * @param boolean $lang [description]
	 */
	public function setLang($lang=false) {
		Parent::setLang($lang);

		// On Definis La Langue De la Ville
		if (!empty($this->city)) {
			$this->city->setLang($this->getLang());
		}
	}

	/**
	 * Chargement De La Ville
	 * @return City La Ville
	 */
	public function getCity() {
		if (empty($this->city)) {
			if (empty($this->cities_id)) {
				$this->city = new City();
				return $this->city;
			}
			
			$this->city = new City($this->cities_id);
			$this->city->setLang($this->getLang());
		}

		return $this->city;
	}

	/**
	 * Mise à jour De la Couleur
	 * @param String $color Couleur En Hexa
	 */
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

	/**
	 * Mise à Jour De la Ville Associée
	 * @param [type] $city [description]
	 */
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

	/**
	 * Réécritue Ecriture de variable
	 * @param String $sVar  Nom de la variable
	 * @param Mixed $value Valeur de la variable 
	 */
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

	/**
	 * Réécritue Récupération de variable
	 * @param  String $sVar Nom de la variable
	 * @return Mixed       La variable ou NULL
	 */
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