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
	public $image;

	function __construct($id=false, Array $aData=[]) {
		$this->sTable = 'cities';
		Parent::__construct($id, $aData);
	}
}