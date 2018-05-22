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


	protected $sTable;
	private $bSync = false;
	private $bLoded = false;

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
				$this->set($sProp, $sData);
			}
			else {
				return false;
			}
		}
		
		$this->bLoded = true;
		return true;
	}

	public function set($sVar, $var) {
		var_dump("Update: $sVar");
		if ($this->$sVar !== $var) {
			var_dump("New");
			$this->bSync = false;
		}

		$this->$sVar = $var;
	}

	function __set($sVar, $var) {
		$this->et($sVar, $var);
	}

	function __get($sVar) {
		if (in_array($sVar, ['id', 'created_at', 'updated_at'])) {
			return $this->$sVar;
		}
		elseif (property_exists('Model', $sVar)) {
			trigger_error('Can\'t access Model Properties due to Protection Level.');
			return null;
		} 
		elseif(property_exists($this, $sVar)) {
			return $this->$var;
		}

		trigger_error('Property  '.$sVar.' does not exists in Class: '.get_class().'');
		return null;
	}
}