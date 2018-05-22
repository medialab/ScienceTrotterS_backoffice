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
		var_dump("LOADING", $aData);

		$this->bSync = false;
		foreach ($aData as $sProp => $sData) {
			var_dump($sProp);
			//$sProp = str_replace($this->prefix, '', $sProp);
			if (property_exists($this, $sProp)) {
				var_dump("OK");
				var_dump($sData);
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