<?php

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;
if ($id && !fIdValidator($id)) {
	$_SESSION['session_msg']['error'] = [
		'Impossible de trouver la ville demandée'
	];

	header('location: /');
}

// Définition Du Titre Du Formulaire
if ($id) {
	$bIsCreate = false;
	$smarty->assign('sCreation', 'Mise à jour d\'un parcours');
}
else{
	$bIsCreate = true;
	$smarty->assign('sCreation', 'Création d\'un parcours');
}


// Chargement Des Couleurs
$aColors = ApiMgr::list('colors', true, 0, 0, false, ['name', 'ASC']);
$smarty->assign('aColors', $aColors->data);

//ApiMgr::$debugMode = true;
// Chargement Du Parcours
$oParc = new \Model\Parcours($id);
if (empty($id) && !empty($_GET['force'])) {
	$oParc->force_lang = $_GET['force'];
}
//exit;

// Si Le Parcours Est Introuvable
if ($id && !$oParc->isLoaded()) {
	header('location: /');
}
elseif($id) {
	$oParc->setLang('default');
	$aFilDArianne[] = $oParc->title;
}

// Chargement Des Villes
//ApiMgr::setLang('fr');
$aCities = \Model\City::list(0, 0, ['id', 'title'], ['title', 'asc']);

// Chargement Des Points D'interets
$aInts = [];
if ($oParc->isLoaded()) {
	$aInts = ApiMgr::listByParcours($id, false, 0, 0, ['id', 'title', 'state'], ['title', 'ASC']);
	if ($aInts->success) {
		$aInts = $aInts->data;

		foreach ($aInts as &$oInt) {
			$oInt = new \Model\Interest(false, $oInt);
		}
	}
}

/* Validation du formulaire */
if (fMethodIs('post') && fValidateModel($oParc, $aErrors)) {

	// Récupération Des Informations Propres Aux Parcours
	if (!empty($_POST['time'])) {
		$oParc->time = $_POST['time'];
		if (mb_strlen($_POST['time']) > 5) {
			$aErrors['Durée'] = 'La durée ne peut dépasser 5 caractères';
		}
	}
	else{
		$oParc->time = null;
	}

	if (!empty($_POST['color'])) {
		$oParc->color = $_POST['color'];
	}
	else{
		$oParc->color = null;
	}
	
	if (!empty($_POST['cities_id'])) {
		$oParc->cities_id = $_POST['cities_id'];
	}
	else{
		$oParc->cities_id = null;
	}



	if (!empty($_POST['description'])) {
		if (mb_strlen($_POST['description']) > 300) {
			$aErrors['Description'] = 'La description ne peut dépasser 300 caractères';
		}
		else {
			$oParc->description = preg_replace('/[\n\r]{1,2}/i', '<br />', $_POST['description']);
		}
	}
	else {
		$oParc->description = null;
	}


	if (!empty($_POST['audio_script'])) {
		if (mb_strlen($_POST['audio_script']) > 12000) {
			$aErrors['Script Audio'] = 'Le script audio ne peut dépasser 12000 caractères';
		}
		else {
			$oParc->audio_script = preg_replace('/[\n\r]{1,2}/i', '<br />', $_POST['audio_script']);
		}
	}
	else {
		$oParc->audio_script = null;
	}

	$oParc->state = (bool)$_POST['state'];

	/* Si On a pad d'erreur on prepare L'object Parcours */
		if (empty($aErrors)) {
			// Téléchargement Du Fichier Audio
			$bAudioUpdated = false;
			if (!empty($_FILES['audio']) && !empty($_FILES['audio']['name'])) {
				$bAudioUpdated = true;
				$sPrevAudio = $oParc->audio;
				$oParc->audio = handleUploadedFile('audio', 'parcours/audio');
			}

			
			//ApiMgr::$debugMode = true;
			$oSaveRes = $oParc->save();
			//exit;

			// Si La Sauvegarde a Echoué
			if (!$oSaveRes->success) {
				if ($bAudioUpdated) {
					$oParc->audio = $sPrevAudio;
				}
				
				if(!empty($oSaveRes->message)) {
					$aErrors['Erreur'] = $oSaveRes->message;
				}
				else{
					$aErrors['Erreur'] = 'Une Erreur s\'est produite lors de l\'enregistrement';
				}
			}
			// Si La Sauvegarde a Réussi
			else {
				$_SESSION['session_msg'] = [
					'success' => [
						'Le parcours a bien été sauvegardé'
					]
				];

				// Si Un Message a été Envoyé
				if (!empty($oSaveRes->message)) {
					$_SESSION['session_msg']['warning'] = [
						$oSaveRes->message
					];
				}

				if (!$id && $bIsCreate && empty($aErrors)) {	// On redirige pour se mettre en modification
					header('location: /edit/parcours/'.$oParc->id.'.html?lang='.$oParc->getLang());
					exit;
				}
			}
		}
}
// Si Un Parent Est Pré-Séléctionné
elseif (!empty($_GET['parent'])) {
	$oParc->cities_id = \Model\Model::validateID($_GET['parent']);
}

// Chargement Des Js Supplémentaires
addJs('color-selector');

$smarty->assign('oParc', $oParc);
$smarty->assign('oModel', $oParc);
$smarty->assign('aInts', $aInts);
$smarty->assign("aCities", $aCities);


$smarty->assign("aErrors", $aErrors);
