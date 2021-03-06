<?php

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;
if ($id && !fIdValidator($id)) {
	header('location: /cities.html');
	exit;
}

// Titre du formulaire 
if ($id) {
	$bIsCreate = false;
	$smarty->assign('sCreation', 'Mise à jour d\'un point d\'intérêt');
} 
else {
	$bIsCreate = true;
	$smarty->assign('sCreation', 'Création d\'un point d\'intérêt');
}


// Chargement Des Villes
$aCities = [];
//$oCities = ApiMgr::list('cities', false, 0, 0, ['id', 'title'], ['title', 'asc']);
$oCities = \Model\City::list(0, 0, ['id', 'title'], ['title', 'asc']);
foreach ($oCities as $oCity) {
	$aCities[$oCity->id] = $oCity;
}


// Chargement Des Parcours
$aoParcours = \Model\Parcours::list(false, false, ['id', 'title', 'cities_id'], [['cities_id', 'title'], 'asc']);

$aParcours = [];
$aParcoursOut = [];	// Parcours Sans Ville
foreach ($aoParcours as $oPar) {
	// Si Le Parcours a Une Ville Associée
	if (strlen($oPar->cities_id) && $oPar->city->isLoaded()) {
		$oPar->city->setLang('default');
		$aParcoursOut[$oPar->id] = $oPar;
		continue;
	}

	// Si Le Parcours n'a PAS Une Ville Associée
	$oPar->city = new \Model\City();
	$oPar->city->setLang('fr');
	$oPar->city->force_lang = 'fr';
	$oPar->city->title = 'Sans Ville';

	//$oPar->city = (object) ['id' => 0, 'title' => 'Sans Ville'];
	$aParcours[$oPar->id] = $oPar;
}

$aParcours = array_merge($aParcours, $aParcoursOut);
/*var_dump($aParcours);
exit;
*/

/*ApiMgr::$debugMode = true;*/
// Chargement Du Point
$oInt = new \Model\Interest($id);
if (empty($id) && !empty($_GET['force'])) {
	$oInt->force_lang = $_GET['force'];
}

// Si Le Parcours Est Introuvable
if ($id && !$oInt->isLoaded()) {
	$_SESSION['session_msg']['error'] = [
		'Impossible de trouver le point d\'intérêt demandée'
	];

	header('location: /');
	exit;
}
elseif($id) {
	$oInt->setLang('default');
	$aFilDArianne[] = $oInt->title;
}

$oCity = null;
$curParc = false;

/* Validation du formulaire */
if (fMethodIs('post')  && fValidateModel($oInt, $aErrors)) {
	// Récupération Des Informations Propres Aux Parcours

	/* Validation De  accroche */
		if (!empty($_POST['address'])) {
			if (mb_strlen($_POST['address']) > 70) {
				$aErrors['lieu'] = 'Le lieu ne peut dépasser 70 caractères';
			}
			else {
				$oInt->address = $_POST['address'];
			}
		}
		else{
			$oInt->address = null;
		}

	/* Validation De  transports */
		/*if (!empty($_POST['transport'])) {
			$oInt->transport = $_POST['transport'];
		}
		else{
			$oInt->transport = null;
		}*/

	/* Validation De  bibliography */
		if (!empty($_POST['bibliography'])) {
			foreach ($_POST['bibliography'] as $i => $sData) {
				if (mb_strlen($sData) > 200) {
					$aErrors['Bibliographie'] = 'Les lignes du champs Bibliographie ne peuvent dépasser 200 caractères chaqu\'unes.';
				}
			}

			$oInt->bibliography = $_POST['bibliography'];
		}
		else{
			$oInt->bibliography = null;
		}

	/* Validation De  schedule */
		if (!empty($_POST['schedule'])) {
			if (mb_strlen($_POST['schedule']) > 20) {
				$aErrors['Horaire'] = 'Le champs horaire ne peut dépasser 20 caractères.';
			}
			$oInt->schedule = $_POST['schedule'];
		}
		else{
			$oInt->schedule = null;
		}

	/* Validation De price */
		if (!empty($_POST['price'])) {
			if (mb_strlen($_POST['price']) > 50) {
				$aErrors['Difficultés'] = 'Les difficultés ne peuvent dépasser 50 caractères.';
			}
			
			$oInt->price = $_POST['price'];
		}
		else{
			$oInt->price = null;
		}

	/* Validation de  ville */
		if (!empty($_POST['cities_id'])) {
			if (array_key_exists($_POST['cities_id'], $aCities)) {
				$oInt->cities_id = $_POST['cities_id'];
			}
			else{
				$aErrors['Ville'] = "La ville sélectionnée n'existe pas.";
			}
		}
		else{
			$oInt->cities_id = null;
		}

	/* Validation de  Parcours */
		if (!empty($_POST['parcours_id'])) {
			if (array_key_exists($_POST['parcours_id'], $aParcours)) {
				$oInt->parcours_id = $_POST['parcours_id'];
			}
			else{
				$aErrors['Parcours'] = "Le parcours sélectionné n'existe pas.";
			}
		}
		else {
			$oInt->parcours_id = null;
		}


		/*if (!empty($_POST['description'])) {
			if (mb_strlen($_POST['description']) > 300) {
				$aErrors['Description'] = 'La description ne peut dépasser 300 caractères';
			}
			else {
				$oInt->description = $_POST['description'];
			}
		}
		else {
			$oInt->description = null;
		}*/


		if (!empty($_POST['audio_script'])) {
			if (mb_strlen($_POST['audio_script']) > 12000) {
				$aErrors['Script Audio'] = 'Le script audio ne peut dépasser 12 000 caractères';
			}
			else {
				$oInt->audio_script = preg_replace('/[\n\r]{1,2}/i', '<br />', $_POST['audio_script']);
			}
		}
		else {
			$oInt->audio_script = null;
		}

		/* Sauvegarde de la Gallerie D'Image */
		if (!empty($_FILES['imgs-interet'])) {
			/* Vérification D'erreurs */
			$bDoUpload = in_array(0, $_FILES['imgs-interet']['error']);

			$bGalleryUpdated = false;
			if ($bDoUpload) {
				$bGalleryUpdated = true;
				//var_dump("Fetching files");
				// Téléchargement des Images
				$aUploaded = handleUploadedFile('imgs-interet', 'interests/image', true, ['png', 'jpg', 'jpeg']);
				if (!$aUploaded) {
					$aErrors["Gallerie"] = "Attention La gallerie ne peut contenir que des images.";
				}
				else{
					// Mise à jour de la Gallerie
					$oTmp = new Stdclass;
					foreach ($aUploaded as $dIndex => $sFile) {
						if (!empty($sFile)) {
							$oTmp->$dIndex = $sFile;
							$oInt->gallery_image->$dIndex = $sFile;
						}
						else{
							$oTmp->$dIndex = $oInt->gallery_image->$dIndex;
						}
					}
				
					$sPrevGallery = $oInt->gallery_image;
					$oInt->gallery_image = $oTmp;
					
				}
			}
		}

	/* Si On a pas d'erreur on prepare L'object ville */
		if (empty($aErrors)) {
			$oInt->state = $_POST['state'];

			/* Sauvegarde Temporaire de l'image */
			$bImgUpdated = false;
			if (!empty($_FILES['img']) && !$_FILES['img']['error'] && !empty($_FILES['img']['name'])) {

				//$bSize = fImageSize('img', 300, 300);
				$bSize = true;
				if (!$bSize) {
					$aErrors['Image'] = 'L\'image ne peut dépasser les 600px de large et 600px de haut.';
				}
				else{
					$bImgUpdated = true;
					$sPrevImg = $oInt->header_image;
					$oInt->header_image = handleUploadedFile('img', 'interests');
				}
			}
			
			/* Sauvegarde du Fichier Audio */
			$bAudioUpdated = false;
			if (!empty($_FILES['audio'])  && !$_FILES['audio']['error'] && !empty($_FILES['audio']['name'])) {
				$bAudioUpdated = true;
				$sPrevAudio = $oInt->audio;
				$oInt->audio = handleUploadedFile('audio', 'interests/audio');
			}

			//ApiMgr::$debugMode = true;
			$oSaveRes = $oInt->save();
			//exit;

			// Si La Sauvegarde a Echoué
			if (!$oSaveRes->success) {
				if ($bAudioUpdated) {
					$oParc->audio = $sPrevAudio;
				}
				if ($bImgUpdated) {
					$oCity->image = $sPrevImg;
				}
				if ($bGalleryUpdated) {
					$oInt->gallery_image = $sPrevGallery;
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
						'Le point d\'intérêt a bien été sauvegardé'
					]
				];

				// Si Un Message a été Envoyé
				if (!empty($oSaveRes->message)) {
					if (is_string($oSaveRes->message)) {
						$_SESSION['session_msg']['warning'] = [
							$oSaveRes->message
						];
					}
					else{
						$_SESSION['session_msg']['warning'] = $oSaveRes->message;

					}
				}

				if (!$id && $bIsCreate && empty($aErrors)) {	// On redirige pour se mettre en modification
					header('location: /edit/interest/'.$oInt->id.'.html');
					exit;
				}
			}
		}
		else {
			logFormError(empty($id), 'Interest', $oInt->title, $aErrors);
		}
}
// Si Un Parent Est Pré-Séléctionné
elseif (!empty($_GET['parent'])) {
	$oInt->parcours_id = \Model\Model::validateID($_GET['parent']);

	// Si Le Parcours Est Chargé, On Charge la ville Associée
	if ($oInt->parcours_id) {
		ApiMgr::setLang('fr');
		foreach ($aParcours as $id => $aParc) {
			if ($id == $oInt->parcours_id) {
				$curParc = $aParc;

				$oCity = \Model\City::get($aParc->cities_id);
			}
		}
		ApiMgr::setLang(false);
	}
}


// Chargement Des Js Supplémentaires
addJs(
	'geo-input',
	'img-upload'
);

$smarty->assign([
	'aErrors' => $aErrors,
	'oInt' => $oInt,
	'oModel' => $oInt,
	'oCity' => $oCity,
	'curParc' => $curParc,
	'aCities' => $aCities,
	'aParcours' => $aParcours
]);