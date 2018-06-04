<?php 


/* Récupération de l'ID de la ville s'il existe */
	$id = !empty($_GET['id']) ? $_GET['id'] : false;
	if ($id && !fIdValidator($id)) {
		header('location: /cities.html');
	}

// Titre du formulaire 
	if ( empty( $id ) ) {
		$smarty->assign('sCreation', 'Création d\'un point d\'intérêt');
	} else {
		$smarty->assign('sCreation', 'Mise à jour d\'un point d\'intérêt');
	}


ApiMgr::setLang('fr');
$oCities = ApiMgr::list('cities', false, 0, 0, ['id', 'title']);
$aCities = [];
foreach ($oCities->data as $oCity) {
	$aCities[$oCity->id] = $oCity->title;
}


$oParcours = ApiMgr::list('parcours', false, 0, 0, ['id', 'title']);
$aParcours = [];
foreach ($oParcours->data as $aPar) {
	$aParcours[$aPar->id] = $aPar->title;
}

ApiMgr::setLang(false);


$oCity = null;

$oInt = new \Model\Interest($id);
/*var_dump($oInt);
exit;*/

$curParc = false;
if (fMethodIs('post')) {
	//var_dump($_POST);
	$sLang = empty($_POST['lang']) ? false : $_POST['lang'];
	/*var_dump($sLang);
	exit;*/
	if (!$sLang) {
		$aErrors['lang'] = 'Aucune langue n\'a été sélectionnée';
	}

	$oInt->setLang($sLang);

	/* Validation Du Title */
		if(!fRequiredValidator('title', $_POST)) {
			$aErrors['Nom'] = 'Ce champs est obligatoire';
		}
		else{
			$oInt->title = $_POST['title'];
		}

		/* Validation Du Status */
			$_POST['state'] = (bool) (empty($_POST['state']) ? 0 : $_POST['state']);
			$oInt->state = $_POST['state'];

		/* Validation De la Géolocalisation */
			/* Validation De la Latitude */
			if (!empty($_POST['geo-n'])) {
				$geoN = &$_POST['geo-n'];

				if (empty($geoN)) {
					$aErrors['Longitude'] = 'Ce champs est obligatoire';
				}
				else{
					if (!is_numeric($geoN) || $geoN < -90 || $geoN > 90) {
						$aErrors['Latitude'] = 'Ce Champs doit être compris entre -90° et 90°';
					}
				}
			}

			/* Validation De la Longitude */
			if (!empty($_POST['geo-e'])) {
				$geoE = &$_POST['geo-e'];

				if (empty($_POST['geo-n'])) {
					$aErrors['Latitude'] = 'Ce champs est obligatoire';
				}
				else{
					if (!is_numeric($geoE) || $geoE < -180 || $geoE > 180) {
						$aErrors['Latitude'] = 'Ce Champs doit être compris entre -180° et 180°';
					}
				}
			}

			/* Validation De  accroche */
			if (!empty($_POST['address'])) {
				$oInt->address = $_POST['address'];
			}

			/* Validation De  transports */
			if (!empty($_POST['transport'])) {
				$oInt->transport = $_POST['transport'];
			}

			/* Validation De  audio_script */
			if (!empty($_POST['audio_script'])) {
				$oInt->audio_script = $_POST['audio_script'];
			}

			/* Validation De  bibliography */
			if (!empty($_POST['bibliography'])) {
				$oInt->bibliography = $_POST['bibliography'];
			}

			/* Validation De  schedule */
			if (!empty($_POST['schedule'])) {
				$oInt->schedule = $_POST['schedule'];
			}

			/* Validation De price */
			if (!empty($_POST['price'])) {
				$oInt->price = $_POST['price'];
			}

			if (empty($aErrors['Latitude']) && empty($aErrors['Latitude'])) {		
				$oInt->geoloc = $_POST['geo-n'].';'.$_POST['geo-e'];
			}

		/* Validation de L'image */
			$aFileTypes = ['png', 'jpg', 'jpeg'];
			if (!fFileExtensionValidator('img', $aFileTypes)) {
				$mimes = fGetAuthorizedMimes($aFileTypes);
				
				if (count($mimes) > 1) {
					$aErrors['Image'] = 'L\'image doit faire parti des types suivants: .'.join(', .', $aFileTypes);
				}
				else{
					$aErrors['Image'] = 'L\'image doit être du type suivant: .'.join('', $aFileTypes);
				}
			}

			$maxSize = '500Mo';
			if (!fFileZieValidator('img', $maxSize)) {
				$aErrors['Image'] = 'L\'image ne peut dépasser 500 Mo';
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

		/* Validation de  Parcours */
		if (!empty($_POST['parcours_id'])) {
			if (array_key_exists($_POST['parcours_id'], $aParcours)) {
				$oInt->parcours_id = $_POST['parcours_id'];
			}
			else{
				$aErrors['Parcours'] = "Le parcours sélectionné n'existe pas.";
			}
		}

		/* Si On a pad d'erreur on prepare L'object ville */
		if (empty($aErrors)) {
			/* Sauvegarde Temporaire de l'image */
			$oInt->header_image = handleUploadedFile('img', 'interests');
			/*
			ApiMgr::$debugMode = true;
			var_dump($oInt);*/
			$oSaveRes = $oInt->save();
			//exit;
			if (!$oSaveRes->success) {
				$aErrors['Erreur'] = 'Une Erreur s\'est produit lors de l\'enregistrement';
			}
			elseif(!empty($oSaveRes->message)) {
				$aErrors['Erreur'] = $oSaveRes->message;
			}
			elseif (!$id && empty($aErrors)) {	// On redirige pour se mettre en modification
				header('location: /edit/interest/'.$oInt->id.'.html');
				exit;
			}
		}
}
elseif (!empty($_GET['parent'])) {
	$oInt->par_id = \Model\Model::validateID($_GET['parent']);

	if ($oInt->par_id) {
		ApiMgr::setLang('fr');
		foreach ($aParcours as $oParc) {
			if ($oParc->id == $oInt->par_id) {
				$curParc = $oParc;
				$oCity = \Model\City::get($oParc->cities_id);
			}
		}
		ApiMgr::setLang(false);
	}
}

$smarty->assign([
	'aErros' => $aErrors,
	'oInt' => $oInt,
	'oCity' => $oCity,
	'oParc' => $curParc,
	'aCities' => $aCities,
	'aParcours' => $aParcours
]);