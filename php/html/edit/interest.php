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

//ApiMgr::$debugMode = true;
$aCities = [];
$oCities = ApiMgr::list('cities', false, 0, 0, ['id', 'title'], ['title', 'asc']);
foreach ($oCities->data as $oCity) {
	$aCities[$oCity->id] = $oCity->title;
}

$aoParcours = \Model\Parcours::list(false, false, ['id', 'title', 'cities_id'], [['cities_id', 'title'], 'asc']);

$aParcours = [];
$aParcoursOut = [];
//$oParcours = ApiMgr::list('parcours', false, 0, 0, ['id', 'title', 'cities_id']);
foreach ($aoParcours as $oPar) {
	//var_dump($oPar->title, $oPar->cities_id, strlen($oPar->cities_id));
	if (strlen($oPar->cities_id) && $oPar->city->isLoaded()) {
		$aParcoursOut[$oPar->id] = $oPar;
		continue;
	}

	//var_dump("Is Empty");
	$oPar->city = (object) ['id' => 0, 'title' => 'Sans Ville'];
	$aParcours[$oPar->id] = $oPar;
}

//var_dump($aParcours);
$aParcours = array_merge($aParcours, $aParcoursOut);
//var_dump($aParcours);
//exit;
ApiMgr::setLang(false);

/*ApiMgr::$debugMode = true;*/
$oInt = new \Model\Interest($id);
/*var_dump($oInt);
exit;*/

$oCity = null;
$curParc = false;
if (fMethodIs('post')  && fValidateModel($oInt, $aErrors)) {
	
	/* Validation De  la Ville */
		/*if(!fRequiredValidator('cities_id', $_POST)) {
			$aErrors['Ville'] = 'Ce champs est obligatoire';
		}*/

	/* Validation De  accroche */
		if (!empty($_POST['address'])) {
			$oInt->address = $_POST['address'];
		}

	/* Validation De  transports */
		if (!empty($_POST['transport'])) {
			$oInt->transport = $_POST['transport'];
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
		elseif ($_POST['parcours_id'] === '0') {
			$oInt->parcours_id = null;
		}

		$oInt->description = empty($_POST['description']) ? null : $_POST['description'];
		$oInt->audio_script = empty($_POST['audio_script']) ? null : $_POST['audio_script'];

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

			if (!empty($_FILES['imgs-interet'])) {
				//var_dump("TEST UPLOAD", $_FILES['imgs-interet']);
				$bDoUpload = in_array(0, $_FILES['imgs-interet']['error']);
				//var_dump($bDoUpload);

				if ($bDoUpload) {
					//var_dump("Fetching files");
					$aUploaded = handleUploadedFile('imgs-interet', 'interests/image', true);

					$oTmp = new Stdclass;
					//var_dump("Updating files");
					foreach ($aUploaded as $dIndex => $sFile) {
						if (!empty($sFile)) {
							$oTmp->$dIndex = $sFile;
							//var_dump("Updating $dIndex => $sFile");
							//var_dump("Current => ". $oInt->gallery_image->$dIndex);
							$oInt->gallery_image->$dIndex = $sFile;
							//var_dump("Vérif => ". $oInt->gallery_image->$dIndex);
						}
						else{
							$oTmp->$dIndex = $oInt->gallery_image->$dIndex;

						}
					}

					$oInt->gallery_image = $oTmp;
					//var_dump("Veruify: ", $oInt->gallery_image);

				}
			}

			//ApiMgr::$debugMode = true;
			$oSaveRes = $oInt->save();
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

				$oCity = \Model\City::get($aParc->cities_id);
			}
		}
		ApiMgr::setLang(false);
	}
}

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