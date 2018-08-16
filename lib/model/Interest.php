<?php

namespace Model;

/**
 * 
 */
class Interest extends Model
{
	/**
	 * Class Du Model
	 */
	protected $sClass = 'Interest';

	/**
	 * Class Du Model Static
	 */
	public static $ssClass = 'Interest';
	
	/**
	 * Nom du Model pour un utilisateur
	 */
	protected $sUserStr = 'le point d\'intérêt';
	
	/**
	 * Variables à Traduire
	 */
	protected $aTranslateVars = [
		'title', 
		'transport', 
		'audio_script', 
		'description', 
		'bibliography', 
		'audio', 
		'price', 
		'schedule', 
		'address'
	];

	/**
	 * Latitude
	 */
	protected $geoN;
	
	/**
	 * Longitude
	 */
	protected $geoE;

	/**
	 * Géolocalisation
	 */
	protected $geoloc;

	/**
	 * Titre
	 */
	protected $title;
	
	/**
	 * Address
	 */
	protected $address;

	/**
	 * Transports à proximité
	 */
	protected $transport;

	/**
	 * Descriptions
	 */
	protected $description;

	/**
	 * Audios Scripts
	 */
	protected $audio_script;

	/**
	 * Bibliographies
	 */
	protected $bibliography;

	/**
	 * Image De Couverture
	 */
	protected $header_image;

	/**
	 * Gallerie D'Image
	 */
	protected $gallery_image;


	/**
	 * Fichiers Audios
	 */
	protected $audio;


	/**
	 * Prix
	 */
	protected $price;

	/**
	 * Id De La Ville Associée
	 */
	protected $cities_id;

	/**
	 * Id Du Parcours Associé
	 */
	protected $parcours_id;


	/**
	 * Status
	 */
	protected $state;


	/**
	 * Horraires
	 */
	protected $schedule;


	/**
	 * La Ville Associée
	 */
	protected $city;


	/**
	 * Parcours Associé
	 */
	protected $parcours;

	function __construct($id=false, $aData=[]) {
		$this->sTable = 'interests';
		$this->sqlIgnore = ['geoE','geoN', 'city', 'parcours'];
		Parent::__construct($id, $aData);
	}

	/**
	 * Réécritue Récupération de variable
	 * @param  String $sVar Nom de la variable
	 * @return Mixed       La variable ou NULL
	 */
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

	/**
	 * Réécritue Ecriture de variable
	 * @param String $sVar  Nom de la variable
	 * @param Mixed $value Valeur de la variable 
	 */
	function __set($sVar, $var) {
		//var_dump("=== SETTING: $sVar", $var);
		switch ($sVar) {
			case 'city':
				$this->setCity($var);
				break;

			case 'parcours':
				$this->setParcours($var);
				break;
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
				Parent::__set($sVar, $var);
				break;
		}
	}

	private function setParcours($oParc) {
		if ($oParc instanceOf Parcours) {
			$this->parcours = $oParc;
			$this->parcours_id = $oParc->id;
		}
		elseif ($oParc instanceOf StdClass) {
			$this->parcours = new Parcours(false, $oParc);
			$this->parcours_id = $oParc->id;
		}
	}

	private function setCity($oCity) {
		if ($oCity instanceOf Parcours) {
			$this->city = $oCity;
			$this->city_id = $oCity->id;
		}
		elseif ($oCity instanceOf StdClass) {
			$this->city = new City(false, $oCity);
			$this->city_id = $oCity->id;
		}
	}

	/**
	 * Chargement Des Données
	 * @param  Array $oData Les Données
	 * @return Bool        Success
	 */
	public function load($aData) {
		Parent::load($aData);

		// Mise à Jour De la Géoloc
		$this->setGeoloc($this->geoloc);
	}

	/**
	 * Séléctionne Une Langue
	 * @param boolean $lang [description]
	 */
	public function setLang($lang=false) {
		Parent::setLang($lang);

		// On Definis La Langue De la Ville
		if (!empty($this->city)) {
			$this->city->setLang($this->getLang());
		}

		// On Definis La Langue Du Parcours
		if (!empty($this->parcours)) {
			var_dump($this->parcours);
			$this->parcours->setLang($this->getLang());
		}
	}

	/**
	 * Récupération De La Ville
	 * @return City La Ville Associée
	 */
	public function getCity() {
		if (empty($this->city)) {
			var_dump("TEST-0");
			if (empty($this->cities_id)) {
				var_dump("TEST-1");
				$this->city = new City();
				return $this->city;
			}
			
			var_dump("TEST-2");
			$this->city = City::get($this->cities_id);
			$this->city->setLang($this->getLang());
		}
		elseif(is_object($this->city)) {
			var_dump("TEST-3");
			$this->city = new City(false, $this->city);
		}

		var_dump("TEST-4");
		return $this->city;
	}

	/**
	 * Récupération Du Parcours
	 * @return City Du Parcours Associé
	 */
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


	public static function listByParcours($parc_id) {
		if (!Model::validateID($id)) {
			return false;
		}
	}
}