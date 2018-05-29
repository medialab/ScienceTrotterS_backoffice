<?php

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;
if ($id && !fIdValidator($id)) {
	header('location: /cities.html');
}

if ($id) {
	/* Titre du formulaire */
	$smarty->assign('sCreation', 'Mise à jour d\'un parcours');
}
else{
	$smarty->assign('sCreation', 'Création d\'un parcours');
}

$smarty->assign('oCity', $oCity);
$smarty->assign("aErrors", $aErrors);
