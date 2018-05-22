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


	private $sqlVars = ['id', 'created_at', 'updated_at'];

	protected $sTable;
	private $bSync = false;
	private $bLoaded = false;

	protected $sqlIgnore = ['sqlIgnore'];

	function __construct($id=false, Array $aData=[]) {
		if ($id) {
			$this->loadById($id);
		}
		elseif(!empty($aData)) {
			$this->load($aData);
		}
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

	public function set($sVar, $var) {
	}

	function __set($sVar, $var) {
		if ($this->$sVar !== $var) {
			$this->bSync = false;
		}

		$this->$sVar = $var;
	}

	function __get($sVar) {
		if (in_array($sVar, $this->sqlVars)) {
			return $this->$sVar;
		}
		elseif (property_exists('Model', $sVar)) {
			trigger_error('Can\'t access Model Properties due to Protection Level.');
			return null;
		} 
		elseif(property_exists($this, $sVar)) {
			return $this->$sVar;
		}

		trigger_error('Property  '.$sVar.' does not exists in Class: '.get_class().'');
		return null;
	}

	public function isSync() {
		return $this->bSync;
	}

	public function isLoaded() {
		return $this->bLoaded;
	}

	public function add() {

	}

	public function save() {
		if (!$this->bLoaded) {
			return $this->add();
		}

		return \ApiMgr::update($this);
	}

	public function toArray() {
		var_dump("To Array", get_object_vars($this));
		$aResult = [];
		foreach (get_object_vars($this) as $key => $value) {
			if (!in_array($key, $this->sqlIgnore) && (in_array($key, $this->sqlVars) || !property_exists('Model', $key))) {
				$aResult[$key] = $value;
			}
		}

		var_dump("Done", $aResult);
		exit;
	}
}