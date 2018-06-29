<?php

namespace Model;
/**
 * 
 */
abstract class Model
{
	private $sCurLang = false;
	private $force_lang = false;
	
	protected $sClass = 'Model';
	protected $aTranslateVars = []; // les Variables à traduire

	protected $id;
	protected $created_at;
	protected $updated_at;


	protected $sqlVars = ['id', 'created_at', 'updated_at'];
	protected $aPublicVars = ['force_lang'];

	protected $sTable;

	protected $bSync = false;	// Est Synchronisé avec la DB
	protected $bLoaded = false;	// A des Donées Chargées

	protected $sqlIgnore = ['sqlIgnore', 'parcours', 'city'];	// variable à ignorer lors du Toarray

	function __construct($id=false, $aData=[]) {
		if ($id) {
			$this->loadById($id);
		}
		elseif(!empty($aData)) {
			$this->load($aData);
		}
	}

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
		if ($bSqlVars && in_array($sVar, $this->sqlVars)) {
			return true;
		}
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

	    $this->$sVar = $value;
	}


	function __set($sVar, $var) {
		if (is_string($var)) {
			$var = $this->decode($var);
		}

		$bAccess = $this->canAccessVar($sVar, false);
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
		    if ($this->sCurLang) {
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

	function __get($sVar) {
		if (property_exists($this, $sVar)) {

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

	public function load($oData) {
		//var_dump("LOADING", $oData);
		$this->bSync = false;
		$sCurLang = $oData->sCurLang;
		$this->setLang($sCurLang);

		foreach ($oData as $sProp => $sData) {
			if (property_exists($this, $sProp) || in_array($sProp, $this->aTranslateVars)) {
				if (in_array($sProp, $this->aTranslateVars)) {
					//var_dump("Translate Prop", $sProp);
					if ($sCurLang) {
						$this->setValueByLang($sProp, $sData);
					}
					else{
						$this->setValueAsJson($sProp, $sData);
					}
				}
				else{
					//var_dump("Prop", $sProp);
					//var_dump($sData);
					$this->$sProp = $sData;
				}
			}
			else {
				//var_dump("Faild Prop", $sProp, get_object_vars($this));
				//var_dump("Faild Prop", $sProp);
				return false;
			}
		}
		
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

	public function setLang($lang = false) {
		if ($lang !== 'default') {
			$this->sCurLang = $lang;
		}
		elseif(!empty($this->force_lang)){
			$this->sCurLang = $this->force_lang;
		}
		else {
			$this->sCurLang = 'fr';
		}
	}

	/**
	 * Transformation en Tableau
	 */
	public function toArray() {
		$aResult = [];
		$sLang = $this->sCurLang;

		foreach (get_object_vars($this) as $key => $value) {
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

	public function setState($bState) {
		$this->state = $bState;
	}

	public function getLang() {
		return $this->sCurLang;
	}
	
	public function setGeoloc(&$geoloc) {
		if ($geoloc === ';') {
			$geoloc = null;
		}

		if (is_string($geoloc)) {
			$geo = explode(';', $geoloc);
			$geoloc = (object) ['latitude' => (float)$geo[0], 'longitude' => (float)$geo[1]];
		}

		if (empty($geoloc)) {
			$this->geoN = $geoloc;
			$this->geoE = $geoloc;
			return;
		}

		$this->geoN = $geoloc->latitude;
		$this->geoE = $geoloc->longitude;
	}

	public function setGeoN($geoN) {
		//var_dump("Setting GeoN", $geoN);
		if (!empty($geoN) && !preg_match('/^[0-9]{1,2}(\.[0-9]{1,6})?$/', $geoN)) {
			throw new Exception('Error: Invalid Lattitude Value: '.$geoN, 1);
		}

		$this->geoN = $geoN;

		if (empty($geoN) && empty($this->geoE)) {
			$this->geoloc = null;
		}
		else {
			$this->geoloc = $geoN.';'.$this->geoE;
		}
	}

	public function setGeoE($geoE) {
		//var_dump("Setting GeoE", $geoE);
		if (!empty($geoE) && !preg_match('/^[0-9]{1,2}(\.[0-9]{1,6})?$/', $geoE)) {
			throw new Exception('Error: Invalid Longitude Value: '.$geoE, 1);
		}

		$this->geoE = $geoE;
		if (empty($geoE) && empty($this->geoN)) {
			$this->geoloc = null;
		}
		else {
			$this->geoloc = $this->geoN.';'.$geoE;
		}
	}

	public static function get($id=0, $aData=[], $sClass=false) {
		$sClass = 'Model\\'.$sClass;
		
		try {
			return new $sClass($id, $aData);
		} catch (\Exception $e) {
			exit;
		}

		return null;
	}

	public static function list($limit=0, $page=0, $columns=false, $aOptions=false, $sClass=false) {
		$sClass = 'Model\\'.$sClass;

		try {
			$base = new $sClass();

			if (is_array($columns)) {
				$aReqColumns = ['id', 'state', 'force_lang'];
				foreach ($aReqColumns as $sColumn) {
					if (!in_array($sColumn, $columns)) {
						$columns[] = $sColumn;
					}
				}
			}

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

	public static function validateID($id) {
		if (preg_match('/[a-z0-9]{8}-([a-z0-9]{4}-){3}[a-z0-9]{12}/', $id)) {
			return $id;
		}

		return false;
	}
}