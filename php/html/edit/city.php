<?php

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;

// Si l'Id de la Ville n'est pas Valide
if ($id && !fIdValidator($id)) {
	$_SESSION['session_msg']['error'] = [
		'Impossible de trouver la ville demandée'
	];

	header('location: /');
}

// Définition Du Titre Du Formulaire
if ($id) {
	$bIsCreate = false;
	$smarty->assign('sCreation', 'Mise à jour d\'une ville');
}
else{
	$bIsCreate = true;
	$smarty->assign('sCreation', 'Création d\'une ville');
}

//ApiMgr::$debugMode = true;
// Chargement De la Ville
$oCity = new \Model\City($id);
//exit;

// Si La Ville Est Introuvable
if ($id && !$oCity->isLoaded()) {
	$_SESSION['session_msg']['error'] = [
		'Impossible de trouver la ville demandée'
	];

	header('location: /');
}

/* Validation du formulaire */
if (fMethodIs('post') && fValidateModel($oCity, $aErrors)) {
	// var_dump("===== VALIDATING =====", $_POST);

	/* Si On a pas d'erreur on prepare L'object ville */
	if (empty($aErrors)) {

		/* Sauvegarde Temporaire de l'image */
		if (!empty($_FILES['img']) && !$_FILES['img']['error']) {
			$oCity->image = handleUploadedFile('img', 'cities/image');
		}
		
		//ApiMgr::$debugMode = true;
		$oSaveRes = $oCity->save();
		//exit;

		// Si La Sauvegarde a Echoué
		if (!$oSaveRes->success) {
			if(!empty($oSaveRes->message)) {
				$aErrors['Erreur'] = $oSaveRes->message;
			}
			else{
				$aErrors['Erreur'] = 'Une Erreur s\'est produit lors de l\'enregistrement';
			}
		}
		// Si La Sauvegarde a Réussi
		else {
			$_SESSION['session_msg'] = [
				'success' => [
					'La ville a bien été sauvegardée'
				]
			];

			// Si Un Message a été Envoyé
			if (!empty($oSaveRes->message)) {
				$_SESSION['session_msg']['warning'] = [
					$oSaveRes->message
				];
			}

			// Si la Ville à été Insérée On Redirige Sur la page de modification
			if (!$id && $bIsCreate && empty($aErrors)) {
				header('location: /edit/city/'.$oCity->id.'.html');
				exit;
			}
		}
	}
}

// Chargement Des Js Supplémentaires
addJs(
	'geo-input',
	'img-upload'
);

$smarty->assign('oCity', $oCity);
$smarty->assign('oModel', $oCity);
$smarty->assign("aErrors", $aErrors);
