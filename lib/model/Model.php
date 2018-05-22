<?php

/**
 * 
 */
class Model
{
	private $sTable;
	private $bLoded = false;

	function __construct($id=0, Array $data=[]) {
		
	}

	public function getById($id) {
		$aData = ApiMgr::get($this->sTable, $id);
	}
}