<?php

/* On récupère le Model à supprimer*/
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
$oMdl = Model\Model::get(ucfirst($sClass), $id);

if ($oMdl->isLoaded()) {
	$b = $oMdl->delete();
}

header('location: /');
exit;