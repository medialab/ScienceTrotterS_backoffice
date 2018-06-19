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


$oParcours = ApiMgr::list('parcours', false, 0, 0, ['id', 'title', 'cities_id']);
$aParcours = [];
foreach ($oParcours->data as $oPar) {
	$aParcours[$oPar->id] = ['id' => $oPar->id, 'title' => $oPar->title, 'city_id' => $oPar->cities_id];
}

ApiMgr::setLang(false);


$oCity = null;

$oInt = new \Model\Interest($id);
/*var_dump($oInt);
exit;
*/
$curParc = false;
if (fMethodIs('post')  && fValidateModel($oInt, $aErrors)) {
	
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
			$oInt->state = $_POST['state'];
			/* Sauvegarde Temporaire de l'image */
			if (!empty($_FILES['img'])  && !$_FILES['img']['error']) {
				$oInt->header_image = handleUploadedFile('img', 'interests');
			}
			
			if (!empty($_FILES['audio'])  && !$_FILES['audio']['error']) {
				$oInt->audio = handleUploadedFile('audio', 'interests/audio');
			}

			var_dump($_FILES['imgs-interet']);
			if (!empty($_FILES['imgs-interet'])) {
				$bDoUpload = false;

				foreach ($_FILES['imgs-interet']['error'] as $bIsValid) {
					if ($bIsValid) {
						$bDoUpload = true;
						break;
					}
				}

				if ($bDoUpload) {
					$oInt->gallery_image = handleUploadedFile('imgs-interet', 'interests/image', true);
				}
			}

			/*ApiMgr::$debugMode = true;
			var_dump($oInt);
			exit;*/
			$oSaveRes = $oInt->save();


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
						'Le point d\'intérêt a bien été sauvegardé'
					]
				];

				if (!empty($oSaveRes->message)) {
					$_SESSION['session_msg']['warning'] = [
						$oSaveRes->message
					];
				}

				if (!$id && empty($aErrors)) {	// On redirige pour se mettre en modification
					header('location: /edit/interest/'.$oInt->id.'.html');
					exit;
				}
			}
		}
}
elseif (!empty($_GET['parent'])) {
	$oInt->parcours_id = \Model\Model::validateID($_GET['parent']);

	if ($oInt->parcours_id) {
		ApiMgr::setLang('fr');
		foreach ($aParcours as $id => $aParc) {
			if ($id == $oInt->parcours_id) {
				$curParc = $aParc;

				$oCity = \Model\City::get($aParc['city_id']);
			}
		}
		ApiMgr::setLang(false);
	}
}

/*var_dump($curParc);
var_dump($oCity);
exit;*/

$smarty->assign([
	'aErros' => $aErrors,
	'oInt' => $oInt,
	'oModel' => $oInt,
	'oCity' => $oCity,
	'curParc' => $curParc,
	'aCities' => $aCities,
	'aParcours' => $aParcours
]);