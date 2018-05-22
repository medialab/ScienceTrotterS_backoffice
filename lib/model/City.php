<?php

namespace Model;
/**
 * 
 */
class City extends Model
{

	public $geoN;
	public $geoE;
	public $label;
	public $active;
	public $imgPath;

	function __construct($id=false, Array $data=[]) {
		$this->sTable = 'cities';
		Parent::__construct($id, $aData);
	}
}