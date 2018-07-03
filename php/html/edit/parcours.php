<?php

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;
if ($id && !fIdValidator($id)) {
	header('location: /cities.html');
}

/* Titre du formulaire */
if ($id) {
	$smarty->assign('sCreation', 'Mise à jour d\'un parcours');
}
else{
	$smarty->assign('sCreation', 'Création d\'un parcours');
}

//ApiMgr::$debugMode = true;
$aColors = ApiMgr::list('colors', true, 0, 0, false, ['name', 'DESC']);
$smarty->assign('aColors', $aColors->data);

//ApiMgr::$debugMode = true;
$oParc = new \Model\Parcours($id);
//exit;

if ($id && !$oParc->isLoaded()) {
	header('location: /');
}


$aInts = [];
ApiMgr::setLang('fr');
$aCities = \Model\City::list(0, 0, ['id', 'title'], ['title', 'asc']);

if ($oParc->isLoaded()) {
	$aInts = ApiMgr::listByParcours($id, false, 0, 0, ['id', 'title', 'state']);
	if ($aInts->success) {
		$aInts = $aInts->data;
	}

}
ApiMgr::setLang(false);

if (fMethodIs('post') && fValidateModel($oParc, $aErrors)) {
	$sLang = empty($_POST['lang']) ? false : $_POST['lang'];
	if (!$sLang) {
		$aErrors['lang'] = 'Aucune langue n\'a été sélectionnée';
	}

	
	/* Validation De  la Ville */
		/*if(!fRequiredValidator('cities_id', $_POST)) {
			$aErrors['Ville'] = 'Ce champs est obligatoire';
		}*/
	
	$oParc->setLang($sLang);

	$oParc->time = $_POST['time'];
	$oParc->color = $_POST['color'];
	$oParc->cities_id = $_POST['cities_id'];
	$oParc->description = empty($_POST['description']) ? null : $_POST['description'];
	$oParc->audio_script = empty($_POST['audio_script']) ? null : $_POST['audio_script'];

	$oParc->state = (bool) $_POST['state'];

	/* Si On a pad d'erreur on prepare L'object Parcours */
		if (empty($aErrors)) {
			if (!empty($_FILES['audio'])) {
				//var_dump($_FILES);
				$oParc->audio = handleUploadedFile('audio', 'parcours/audio');
			}

			//ApiMgr::$debugMode = true;
			$oSaveRes = $oParc->save();
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
						'Le parcours a bien été sauvegardé'
					]
				];

				if (!empty($oSaveRes->message)) {
					$_SESSION['session_msg']['warning'] = [
						$oSaveRes->message
					];
				}

				if (!$id && empty($aErrors)) {	// On redirige pour se mettre en modification
					header('location: /edit/parcours/'.$oParc->id.'.html');
					exit;
				}
			}
		}
}
elseif (!empty($_GET['parent'])) {
	$oParc->cities_id = \Model\Model::validateID($_GET['parent']);
}


addJs('color-selector');

$smarty->assign('oParc', $oParc);
$smarty->assign('oModel', $oParc);
$smarty->assign('aInts', $aInts);
$smarty->assign("aCities", $aCities);


$smarty->assign("aErrors", $aErrors);
