<?php

namespace Model;
/**
 * 
 */
class Parcours extends Model
{

	protected $title;
	protected $time;
	protected $audio;
	protected $description;
	protected $state;
	protected $city_id;

	function __construct($id=false, Array $aData=[]) {
		$this->sTable = 'parcours';
		Parent::__construct($id, $aData);
	}
}