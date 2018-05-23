<?php

namespace Model;
/**
 * 
 */
abstract class Model
{
	protected $id;
	protected $created_at;
	protected $updated_at;


	protected $sqlVars = ['id', 'created_at', 'updated_at'];

	protected $sTable;
	protected $bSync = false;
	protected $bLoaded = false;

	protected $sqlIgnore = ['sqlIgnore'];

	function __construct($id=false, Array $aData=[]) {
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

		$this->$sVar = $var;
	}

	function __get($sVar) {
		/*
			$bAccess = $this->canAccessVar($sVar);
			if ($bAccess === true) {
				return $this->$sVar;
			}
			elseif ($bAccess === -1) {
				trigger_error('Can\'t access Model Property "'.$sVar.'" due to Protection Level.');
			}
			else{
				trigger_error('Property "'.$sVar.'" does not exists in Class: '.get_class().'');
			}
		*/

		if (property_exists($this, $sVar)) {
			return $this->$sVar;
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
			return false;
		}

		$this->load($oData->data);
		$this->bSync = true;
		return true;
	}

	public function load($aData) {
		$this->bSync = false;
		foreach ($aData as $sProp => $sData) {
			if (property_exists($this, $sProp)) {
				$this->$sProp = $sData;
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
	 * Insère dans la DB
	 */
	public function add() {
		return \ApiMgr::insert($this);
	}

	/**
	 * Met à jour dans la DB
	 */
	public function save() {
		if (!$this->bLoaded) {
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

		return $oData;
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
		if (class_exists('Model\$sClass')) {
			$sClass = 'Model\\'.$sClass;
			return new $sClass($id, $aData);
		}

		return null;
	}
}