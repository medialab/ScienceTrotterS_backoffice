<?php

var_dump($sPage);
$aPage = explode('/', $sPage);
if (count($aPage) !== 2) {
	header('location: /');
	exit;
}

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;
if ($id && !fIdValidator($id)) {
	header('location: /cities.html');
}

$sClass = $aPage[1];
var_dump("FETCH MODEL $sClass");
var_dump("ID: $id");
$mdl = Model\Model::get(ucfirst($sClass), $id);
var_dump($mdl);

exit;