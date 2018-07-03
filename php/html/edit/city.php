<?php

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;

if ($id && !fIdValidator($id)) {
	header('location: /');
}

if ($id) {
	/* Titre du formulaire */
	$smarty->assign('sCreation', 'Mise à jour d\'une ville');
}
else{
	$smarty->assign('sCreation', 'Création d\'une ville');
}

//ApiMgr::$debugMode = true;
$oCity = new \Model\City($id);
//exit;

/* Validation du formulaire */
if (fMethodIs('post') && fValidateModel($oCity, $aErrors)) {
//	var_dump("===== VALIDATING =====", $_POST);
	/* Si On a pad d'erreur on prepare L'object ville */
	if (empty($aErrors)) {
		/* Sauvegarde Temporaire de l'image */
		if (!empty($_FILES['img']) && !$_FILES['img']['error']) {
			$oCity->image = handleUploadedFile('img', 'cities/image');
		}
		
		//ApiMgr::$debugMode = true;
		$oSaveRes = $oCity->save();
		//exit;

		if (!$oSaveRes->success) {
			if(!empty($oSaveRes->message)) {
				$aErrors['Erreur'] = $oSaveRes->message;
			}
			else{
				$aErrors['Erreur'] = 'Une Erreur s\'est produit lors de l\'enregistrement';
			}
		}
		else {
			$_SESSION['session_msg'] = [
				'success' => [
					'La ville a bien été sauvegardée'
				]
			];

			if (!empty($oSaveRes->message)) {
				$_SESSION['session_msg']['warning'] = [
					$oSaveRes->message
				];
			}

			if (!$id && empty($aErrors)) {	// On redirige pour se mettre en modification
				header('location: /edit/city/'.$oCity->id.'.html');
				exit;
			}
		}
	}
}

addJs(
	'geo-input',
	'img-upload'
);

$smarty->assign('oCity', $oCity);
$smarty->assign('oModel', $oCity);
$smarty->assign("aErrors", $aErrors);
