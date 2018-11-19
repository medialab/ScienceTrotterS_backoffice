<?php

namespace Model;

abstract class Model
{
	/**
	 * Langue Séléctionnée
	 * @var boolean
	 */
	private $sCurLang = false;

	/**
	 * Langue Forcée
	 * @var boolean
	 */
	private $force_lang = false;
	

	/**
	 * Class Du Model
	 */
	protected $sClass = 'Model';

	/**
	 * Nom du Model pour un utilisateur
	 */
	protected $sUserStr = 'le model';

	/**
	 * Variables à Traduire
	 */
	protected $aTranslateVars = []; 

	/**
	 * Variables De La BDD
	 */
	protected $sqlVars = ['id', 'created_at', 'updated_at'];


	/**
	 * Variable Accessibles Au Public
	 */
	protected $aPublicVars = ['force_lang'];

	/**
	 * Table Du Model
	 * @var [type]
	 */
	protected $sTable;

	/**
	 * Est Synchronisé avec la DB
	 */
	protected $bSync = false;


	/**
	 * Est Chargé
	 */
	protected $bLoaded = false;


	/**
	 * Variables à Ignorer lord du Save
	 */
	protected $sqlIgnore = ['sqlIgnore', 'parcours', 'city'];

	/**
	 * Colones Par Default
	 */
	protected $id;
	protected $created_at;
	protected $updated_at;

	function __construct($id=false, $aData=[]) {
		if ($id) {
			$this->loadById($id);
		}
		elseif(!empty($aData)) {
			$this->load($aData);
		}
	}

	/**
	 * Protège L'html avec Html Entities
	 * @param  String|Array $var Contenu
	 * @return String|Array      Html Protégé
	 */
	private function encode($var) {
		if (is_string($var)) {
			$var = htmlentities($var);
		}
		elseif (is_array($var) || is_object($var)) {
			foreach ($var as $sKey => &$val) {
				$val = $this->encode($val);
			}
		}

		return $var;
	}


	/**
	 * Décode L'Html
	 * @param  String|Array $var Contenu
	 * @return String|Array      Html Décodé
	 */
	private function decode($var) {
		if (is_string($var)) {
			$var = html_entity_decode($var);
		}
		elseif (is_array($var) || is_object($var)) {
			foreach ($var as $sKey => &$val) {
				$val = $this->decode($val);
			}
		}

		return $var;
	}

	/**
	 * Bloque l'accès aux variable prope à Model
	 * @param  String $sVar Nom de la variable
	 * @param  bool $bsqlVars Authorise ou non les variable id, created_at et updated_at
	 * @return bool|int       true, -1 Si Accès Interdit, false Si la variable n'existe pas
	 */
	private function canAccessVar($sVar, $bSqlVars=true) {
		// Si Les Données Sql Sont Incluses
		if ($bSqlVars && in_array($sVar, $this->sqlVars)) {
			return true;
		}
		// Si La Variable N'est Pas Accèssible Au Public
		elseif (!in_array($sVar, $this->aPublicVars) && property_exists('Model\Model', $sVar)) {
			return -1;
		} 
		elseif(property_exists($this, $sVar)) {
			return true;
		}

		return false;
	}

	/**
	 * Mets à jour une variable traductible pour une langue
	 * @param String $sVar  Nom de la variable
	 * @param mixed $value valeur de la variable
	 */
	private function setValueByLang($sVar, $value) {
	    $sLang = $this->sCurLang;
	    $var = $this->$sVar;

	    
	    if ($value !== false && empty($value)) {
	        $value = null;
	    }

	    // Si la valeur actuelle est une string on la décode
	    if(is_string($var)) {
	        $var = json_decode($var);
	    }

	    // Initialisation par défaut
	    if (empty($var)) {
	        $var = new \StdClass;
	    }


	    $var->$sLang = $value;
	    //$this->__set($sVar, $var);
	    $this->$sVar = $var;
	}

	/**
	 * Mets à jour une variable traductible pour toutes les langues
	 * @param String $sVar  Nom de la variable
	 * @param mixed $value Valeur de la variable
	 */
	private function setValueAsJson($sVar, $value) {        
	    $var = $this->$sVar;


	    if (empty($value)) {
	        $var = new \StdClass;
	    }
	    elseif (is_string($value)) {            
	        $var = json_decode($value);
	        
	        if (is_null($var)) {
	            throw new \Exception("Error: Can't Set Parcours::$sVar Due to Invalid Json: '$value'", 1);
	        }
	    }
	    elseif (is_array($value)) {
	        $var = (object) $value;
	    }
	    elseif(!is_object($value)) {
	        throw new \Exception("Error: Can't Set Parcours::$sVar Due to Invalid Data Type. Accepted StdClass, Array, String (json) OR null", 1);
	    }

	    //$this->__set($sVar, $value);
	    $this->$sVar = $value;
	}

	/**
	 * Réécritue Ecriture de variable
	 * @param String $sVar  Nom de la variable
	 * @param Mixed $value Valeur de la variable 
	 */
	function __set($sVar, $var) {
		if (is_string($var)) {
			$var = $this->decode($var);
		}

		$bAccess = $this->canAccessVar($sVar, false);
		// var_dump("======== $sVar ========", $var, $bAccess);
		if ($bAccess === -1) {
			trigger_error('Can\'t access Model Property "'.$sVar.'" due to Protection Level.');
			return;
		}
		elseif($bAccess === false){
			trigger_error('Property "'.$sVar.'" does not exists in Class: '.get_class().'');
			return;
		}

		// Si on modifie la variable on est dé-syncronisé de la DB
		if ($this->$sVar !== $var) {
			$this->bSync = false;
		}

		// Si il s'agit d'une variable à traduire
		if (in_array($sVar, $this->aTranslateVars)) {
		    
		    // Si une langue est choisie on met à jour que celle ci
		    if ($this->sCurLang && !is_object($var)) {
		        $this->setValueByLang($sVar, $var);
		    }
		    // Si aucune langue est choisie on les met toutes à jour
		    else{
		        $this->setValueAsJson($sVar, $var);
		    }
		}
		elseif ($sVar === 'state') {
			$this->setState($var);
		}
		else{
		    $this->$sVar = $var;
		}
	}

	/**
	 * Réécriture Logique empty()
	 * @param  String  $sVar Nom de la variable
	 * @return boolean       La variable sest vide
	 */
	public function __isset($sVar) {
		if (property_exists($this, $sVar)) {
			$v = $this->__get($sVar);

			if (in_array($v, $this->aTranslateVars)) {
				if (is_string($v)) {
					$v = json_decode($v);
				}
			}

			if (is_object($v)) {
				if ($this->sCurLang) {
					$lang = $this->sCurLang;
					return !empty($v->$lang);
				}
				else{
					return !empty(get_object_vars($this->$sVar));
				}
			}
			
			return !empty($this->$sVar);
		}
		
		return false;
	}

	/**
	 * Réécritue Récupération de variable
	 * @param  String $sVar Nom de la variable
	 * @return Mixed       La variable ou NULL
	 */
	function __get($sVar) {
		if (property_exists($this, $sVar)) {
			// Si La Variable Est à Traduire
			if (in_array($sVar, $this->aTranslateVars)) {
				$var = $this->$sVar;
				$sLang = $this->sCurLang;
				
				if (!$sLang) {
					$sLang = $this->force_lang;
				}

				// Si la valeur actuelle est une string on la décode
				if (is_string($var)) {
				    $var = json_decode($var);

				    // En cas d'échec on retourne NULL
				    if (is_null($var)) {
				        return null;
				    }
				}

				// Si une langue est séléctionnée
				if ($sLang) {
				    return empty($var->$sLang) ? null : $this->encode($var->$sLang);
				}

				return $this->encode($var);
			}
			else{
				return empty($this->$sVar) ? null : $this->encode($this->$sVar);
			}
		}
		
		trigger_error('Property "'.$sVar.'" does not exists in Class: '.get_class().'');
		return null;
	}

	/**
	 * Chargement Par ID
	 * @param  String $id Id
	 * @return Bool     Success
	 */
	public function loadById($id) {
		if (!fIdValidator($id)) {
			return false;
		}

		$oData = \ApiMgr::get($this->sTable, $id, false);	
		if (empty($oData) || !$oData->success) {
			$this->bSync = false;
			$this->bLoaded = false;
			return false;
		}

		$this->load($oData->data);
		$this->bSync = true;
		return true;
	}

	/**
	 * Chargement Des Données
	 * @param  Array $oData Les Données
	 * @return Bool        Success
	 */
	public function load($oData) {
		if (empty(get_object_vars($oData))) {
			return true;
		}

		// Le Model Est Dé-Synchronisé
		$this->bSync = false;
		
		$sCurLang = $oData->sCurLang;
		$this->setLang($sCurLang);
		// Mise à Jour Des Infos
		foreach ($oData as $sProp => $sData) {
			//var_dump("=== PROP: $sProp ===");
			if (property_exists($this, $sProp) || in_array($sProp, $this->aTranslateVars)) {
				if (in_array($sProp, $this->aTranslateVars)) {
					//var_dump("Translate Prop", $sProp);
					if ($sCurLang && !is_object($sData)) {
						$this->setValueByLang($sProp, $sData);
					}
					else{
						$this->setValueAsJson($sProp, $sData);
					}
				}
				else{
					if ($this->canAccessVar($sProp, false) !== -1) {
						$this->__set($sProp, $sData);
					}
					else{
						$this->$sProp = $sData;
					}
				}
			}
			else {
				//var_dump("Faild Prop", $sProp, get_object_vars($this));
				//var_dump("Faild Prop", $sProp);
				return false;
			}
		}
		
		// Le Model à Des Données Chargée
		$this->bLoaded = true;
		return true;
	}

	/**
	 * Est Syncrhonisé à la DB
	 */
	public function isSync() {
		return $this->bSync;
	}


	/**
	 * Content des données
	 */
	public function isLoaded() {
		return $this->bLoaded;
	}

	/**
	 * Insère / Met à jour dans la DB
	 */
	public function save() {
		$tmpLang = \ApiMgr::getLang();
		\ApiMgr::setLang($this->sCurLang);

		if (!$this->id) {
			$oData = \ApiMgr::insert($this);
		}
		else{
			$oData = \ApiMgr::update($this);
		}


		if ($oData->success) {
			$this->load($oData->data);
			$this->bSync = true;
		}
		else{
			$this->bSync = false;
		}


		\ApiMgr::getLang($tmpLang);
		return $oData;
	}

	/**
	 * Supprime De la BDD
	 */
	public function delete() {
		if (!$this->bLoaded) {
			return false;
		}

		$bdeleted = \ApiMgr::delete($this);
		if (!$bdeleted) {
			return false;
		}

		$this->id = 0;
		$this->bSync = false;
		return true;
	}

	/**
	 * Séléctionne Une Langue
	 * @param boolean $lang [description]
	 */
	public function setLang($lang = false) {
		// Si la Langue est Différent De défault
		if ($lang !== 'default') {
			$this->sCurLang = $lang;
			/*if (strlen($this->force_lang)) {
				$this->sCurLang = $this->force_lang;
			}
			else{
			}*/
		}
		// Si le Force Lang Est défini
		elseif(strlen($this->force_lang)){
			$this->sCurLang = $this->force_lang;
		}
		// Par Défaut La Langue Est Français
		else {
			if (!empty($this->title->fr)) {
				$this->sCurLang = 'fr';
			}
			else{
				$this->sCurLang = 'en';
			}
		}
	}

	/**
	 * Transformation en Tableau
	 */
	public function toArray() {
		$aResult = [];
		$sLang = $this->sCurLang;

		foreach (get_object_vars($this) as $key => $value) {

			// Est Ce Que La Variable Est à Ignorer
			$bIgnore = in_array($key, $this->sqlIgnore);
			$bModel = $this->canAccessVar($key) !== true;


			if (!$bIgnore && !$bModel) {
				if ($sLang && in_array($key, $this->aTranslateVars)) {
					$value = empty($value->$sLang) ? null : $value->$sLang;
				}

				$aResult[$key] = $value;
			}
		}

		return $aResult;
	}

	/**
	 * Définition Du Status
	 * @param Bool $bState Nouveau Status
	 */
	public function setState($bState) {
		$this->state = $bState;
	}

	/**
	 * Récupère La Langue
	 * @return String La Langue Séléctionnée
	 */
	public function getLang() {
		return $this->sCurLang;
	}
	
	/**
	 * écriture De la Géoloc
	 * @param [type] &$geoloc [description]
	 */
	public function setGeoloc(&$geoloc) {
		if ($geoloc === ';') {
			$geoloc = null;
		}

		// On Décode La Géoloc
		if (is_string($geoloc)) {
			$geo = explode(';', $geoloc);
			$geoloc = (object) ['latitude' => (float)$geo[0], 'longitude' => (float)$geo[1]];
		}

		//var_dump("Setting Geo: ", $geoloc);
		// Si LA Géoloc Est Vide
		if (is_null($geoloc) || empty(get_object_vars($geoloc))) {
			$this->geoN = null;
			$this->geoE = null;
			return;
		}

		// Si LA Géoloc Est OK
		$this->geoN = $geoloc->latitude;
		$this->geoE = $geoloc->longitude;
		$this->geoloc = $geoloc;
	}

	/**
	 * Définition de la Latitude
	 * @param float $geoN Latitude
	 */
	public function setGeoN($geoN) {
		// Si La Latitude Est Erronée
		if (!empty($geoN) && !preg_match('/^(-)?[0-9]{1,2}(\.[0-9]{1,16})?$/', $geoN)) {
			throw new Exception('Error: Invalid Lattitude Value: '.$geoN, 1);
		}

		$this->geoN = $geoN;

		// Mise à Jour De la Géoloc
		if (empty($geoN) && empty($this->geoE)) {
			$this->geoloc = null;
		}
		else {
			$this->geoloc = $geoN.';'.$this->geoE;
		}
	}


	/**
	 * Définition de la Latitude
	 * @param float $geoN Longitude
	 */
	public function setGeoE($geoE) {
		// Si La Longitude Est Erronée
		if (!empty($geoE) && !preg_match('/^(-)?[0-9]{1,2}(\.[0-9]{1,16})?$/', $geoE)) {
			throw new \Exception('Error: Invalid Longitude Value: '.$geoE, 1);
		}

		$this->geoE = $geoE;

		// Mise à Jour De la Géoloc
		if (empty($geoE) && empty($this->geoN)) {
			$this->geoloc = null;
		}
		else {
			$this->geoloc = $this->geoN.';'.$geoE;
		}
	}

	/**
	 * Recherche Par Id
	 * @param  integer $id     Id
	 * @param  array   $aData  Donée Par Défault
	 * @param  boolean $sClass Class Du Model
	 * @return Model          Le Model
	 */
	public static function get($id=0, $aData=[], $sClass=false) {
		$sClass = 'Model\\'.$sClass;
		
		try {
			return new $sClass($id, $aData);
		} catch (\Exception $e) {
			exit;
		}

		return null;
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
		$sClass = 'Model\\'.$sClass;

		try {
			$base = new $sClass();

			// Selection Des Colones
			if (is_array($columns)) {
				$aReqColumns = ['id', 'state', 'force_lang'];
				foreach ($aReqColumns as $sColumn) {
					if (!in_array($sColumn, $columns)) {
						$columns[] = $sColumn;
					}
				}
			}

			// Appel à L'Api
			$aResults = \ApiMgr::list($base->sTable, false, $limit, $page, $columns, $aOptions);
			if (!$aResults->success) {
				return [];
			}

			foreach ($aResults->data as &$aData) {
				$aData = new $sClass(0, $aData);
			}

		} catch (\Exception $e) {
			return [];
		}

		return $aResults->data;
	}

	/**
	 * Validation d'un uid_v4
	 * @param  String $id ID
	 * @return Bool     Success
	 */
	public static function validateID($id) {
		if (preg_match('/[a-z0-9]{8}-([a-z0-9]{4}-){3}[a-z0-9]{12}/', $id)) {
			return $id;
		}

		return false;
	}
}