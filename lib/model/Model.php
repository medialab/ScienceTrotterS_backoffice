<?php

namespace Model;
/**
 * 
 */
abstract class Model
{
	private $id;
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
		var_dump("LOAD CITY: $id");
		$aData = \ApiMgr::get($this->sTable, $id);	
		var_dump("RESULT", $aData);

		if (empty($aData)) {
			return false;
		}

		$this->load($aData);
		$this->$bSync = true;
		return true;
	}

	public function load($aData) {
		$this->bSync = false;
		foreach ($aData as $sProp => $sData) {
			//$sProp = str_replace($this->prefix, '', $sProp);
			if (property_exists($this, $sProp)) {
				$this->$sProp = $sData;
			}
			else {
				return false;
			}
		}
		
		$this->bLoded = true;
		return true;
	}

	function __set($sVar, $var) {
		if ($this->$sVar !== $var) {
			$this->bSync = false;
		}

		$this->$sVar = $var;
	}

	function __get($sVar) {
		if ($sVar === 'id') {
			return $this->id;
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