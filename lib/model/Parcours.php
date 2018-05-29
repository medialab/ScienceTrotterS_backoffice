<?php

namespace Model;
/**
 * 
 */
class Parcours extends Model
{
	protected $sClass = 'Parcours';
	protected static $ssClass = 'Parcours';

	protected $aTranslateVars = ['title', 'time', 'audio', 'description', 'state'];

	protected $title;
	protected $time;
	protected $audio;
	protected $description;
	protected $state;
	protected $city_id;

	function __construct($id=false, $aData=[]) {
		$this->sTable = 'parcours';
		Parent::__construct($id, $aData);
	}
}