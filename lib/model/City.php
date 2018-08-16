<?php

namespace Model;
/**
 * 
 */
class City extends Model
{
	/**
	 * Class Du Model
	 */
	protected $sClass = 'City';

	/**
	 * Class Du Model En Static
	 */
	public static $ssClass = 'City';

	/**
	 * Nom du Model pour un utilisateur
	 */
	protected $sUserStr = 'la ville';
	
	/**
	 * Variables à Traduire
	 */
	protected $aTranslateVars = ['title']; // les Variables à traduire

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
	 * Status
	 */
	protected $state;

	/**
	 * Image De Couverture
	 */
	protected $image;


	function __construct($id=false, $aData=[]) {
		$this->sTable = 'cities';
		$this->sqlIgnore = ['geoE','geoN'];
		Parent::__construct($id, $aData);
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
	 * Réécritue Ecriture de variable
	 * @param String $sVar  Nom de la variable
	 * @param Mixed $value Valeur de la variable 
	 */
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
}