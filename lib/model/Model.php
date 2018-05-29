<?php

namespace Model;
/**
 * 
 */
abstract class Model
{
	private $sCurLang = false;
	protected $sClass = 'Model';

	protected $aTranslateVars = []; // les Variables à traduire

	protected $id;
	protected $created_at;
	protected $updated_at;


	protected $sqlVars = ['id', 'created_at', 'updated_at'];

	protected $sTable;

	protected $bSync = false;	// Est Synchronisé avec la DB
	protected $bLoaded = false;	// A des Donées Chargées

	protected $sqlIgnore = ['sqlIgnore'];	// variable à ignorer lors du Toarray

	function __construct($id=false, $aData=[]) {
		if ($id) {
			$this->loadById($id);
		}
		elseif(!empty($aData)) {
			$this->load($aData);
		}
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
		elseif (property_exists('Model\Model', $sVar)) {
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
	    
	    if (empty($value)) {
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
	        $var = null;
	    }
	    elseif (is_string($value)) {            
	        $var = json_decode($value);
	        
	        if (is_null($var)) {
	            throw new Exception("Error: Can't Set Parcours::$sVar Due to Invalid Json: '$value'", 1);
	        }
	    }
	    elseif (is_array($value)) {
	        $var = (object) $value;
	    }
	    elseif(!is_object($value)) {
	        throw new Exception("Error: Can't Set Parcours::$sVar Due to Invalid Data Type. Accepted StdClass, Array, String (json) OR null", 1);
	    }

	    $this->$sVar = $var;
	}


	function __set($sVar, $var) {
		$bAccess = $this->canAccessVar($sVar, false);
		if ($bAccess === -1) {
			trigger_error('Can\'t access Model Property "'.$sVar.'" due to Protection Level.');
			return;
		}
		elseif($bAccess === false){
			trigger_error('Property "'.$sVar.'" does not exists in Class: '.get_class().'');
			return;
		}

		// Si on modifie la variavle on est dé-syncronisé de la DB
		if ($this->$sVar !== $var) {
			$this->bSync = false;
		}

		// Si il s'agit d'une variable à traduire
		if (in_array($sVar, $this->aTranslateVars)) {
		    $var = $this->$sVar;
		    
		    // Si une langue est choisie on met à jour que celle ci
		    if ($this->sCurLang) {
		        $this->setValueByLang($sVar, $var);
		    }
		    // Si aucune langue est choisie on les met toutes à jour
		    else{
		        $this->setValueAsJson($sVar, $var);
		    }
		}
		else{
		    $this->$sVar = $var;
		}
	}

	function __get($sVar) {
		if (property_exists($this, $sVar)) {

			if (in_array($sVar, $this->aTranslateVars)) {
				$sLang = $this->sCurLang;
				$var = $this->$sVar;

				// Si la valeur actuelle est une string on la décode
				if (is_string($var)) {
				    $var = json_decode($var);

				    // En cas d'échec on retourne NULL
				    if (is_null($var)) {
				        return null;
				    }

				    $this->$sVar = $var;
				}

				// Si une langue est séléctionnée
				$sLang = $this->sCurLang;
				if ($sLang) {
				    return empty($var->$sLang) ? null : $var->$sLang;
				}

				return $var;
			}
			else{
				return empty($this->$sVar) ? null : $this->$sVar;
			}
		}
		
		trigger_error('Property "'.$sVar.'" does not exists in Class: '.get_class().'');
		return null;
	}

	public function loadById($id) {
		if (!fIdValidator($id)) {
			return false;
		}

		$oData = \ApiMgr::get($this->sTable, $id);	

		if (empty($oData) || !$oData->success) {
			$this->bSync = false;
			$this->bLoaded = false;
			return false;
		}

		$this->load($oData->data);
		$this->bSync = true;
		return true;
	}

	public function load($aData) {
		$this->bSync = false;
		$sCurLang = $aData->sCurLang;
		foreach ($aData as $sProp => $sData) {
			if (property_exists($this, $sProp)) {

				if ($sCurLang && in_array($sProp, $this->aTranslateVars)) {
					$this->$sProp = (object) [$sCurLang => $sData];
				}
				else{
					$this->$sProp = $sData;
				}
			}
			else {
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
		var_dump("SAVING", $this);
		if (!$this->id) {
			$oData = \ApiMgr::insert($this);
		}
		else{
			$oData = \ApiMgr::update($this);
		}

		var_dump($oData);
		if ($oData->success) {
			$this->load($oData->data);
			$this->bSync = true;
		}
		else{
			$this->bSync = false;
		}

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
		$this->sCurLang = $lang;
	}

	/**
	 * Transformation en Tableau
	 */
	public function toArray() {
		$aResult = [];
		foreach (get_object_vars($this) as $key => $value) {
			$bIgnore = in_array($key, $this->sqlIgnore);

			$bModel = $this->canAccessVar($key) !== true;

			if (!$bIgnore && !$bModel) {
				$aResult[$key] = $value;
			}
		}

		return $aResult;
	}

	public static function get($sClass, $id=0, $aData=[]) {
		$sClass = 'Model\\'.$sClass;
		
		try {
			return new $sClass($id, $aData);

		} catch (Exception $e) {}

		return null;
	}

	public static function list($limit=0, $page=0, $sClass=false) {
		$sClass = 'Model\\'.$sClass;

		try {
			$base = new $sClass();

			$aResults = \ApiMgr::list($base->sTable, false, $limit, $page);
			if (!$aResults->success) {
				return [];
			}

			foreach ($aResults->data as &$aData) {
				$aData = new $sClass(0, $aData);
			}

		} catch (Exception $e) {	
			return [];
		}

		return $aResults->data;
	}


}