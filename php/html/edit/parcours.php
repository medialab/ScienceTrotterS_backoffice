<?php

ApiMgr::setLang('fr');
$aCities = \Model\City::list(0, 0, ['id', 'title']);
ApiMgr::setLang(false);

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
$oParc = new \Model\Parcours($id);
//exit;


if (fMethodIs('post')) {
	$sLang = empty($_POST['lang']) ? false : $_POST['lang'];
	if (!$sLang) {
		$aErrors['lang'] = 'Aucune langue n\'a été sélectionnée';
	}
	
	$oParc->setLang($sLang);

	/* Validation Du Title */
		if(!fRequiredValidator('title', $_POST)) {
			$aErrors['Nom'] = 'Ce champs est obligatoire';
		}
		else{
			$oParc->title = $_POST['title'];
		}

	$oParc->time = $_POST['time'];
	$oParc->color = $_POST['color'];
	$oParc->cities_id = $_POST['cities_id'];
	$oParc->description = empty($_POST['description']) ? null : $_POST['description'];

	/* Validation de L'Audio */
		$aFileTypes = ['mp3', 'wav'];
		if (!fFileExtensionValidator('img', $aFileTypes)) {
			$mimes = fGetAuthorizedMimes($aFileTypes);
			
			if (count($mimes) > 1) {
				$aErrors['Audio'] = 'L\'Audio doit faire parti des types suivants: .'.join(', .', $aFileTypes);
			}
			else{
				$aErrors['Audio'] = 'L\'Audio doit être du type suivant: .'.join('', $aFileTypes);
			}
		}

		$maxSize = '6Mo';
		if (!fFileZieValidator('img', $maxSize)) {
			$aErrors['Audio'] = 'L\'Audio ne peut dépasser 6 Mo';
		}

		$state = (bool) (empty($_POST['state']) ? 0 : $_POST['state']);
		$oParc->state = $state;

	/* Si On a pad d'erreur on prepare L'object Parcours */
		if (empty($aErrors)) {
			$oParc->audio = handleUploadedFile('audio', 'parcours/audio');

			$oSaveRes = $oParc->save();
			if (!$oSaveRes->success) {
				$aErrors['Erreur'] = 'Une Erreur s\'est produit lors de l\'enregistrement';
			}
			elseif(!empty($oSaveRes->message)) {
				$aErrors['Erreur'] = $oSaveRes->message;
			}
			elseif (!$id && empty($aErrors)) {	// On redirige pour se mettre en modification
				header('location: /edit/parcours/'.$oParc->id.'.html');
				exit;
			}
		}
}
elseif (!empty($_GET['parent'])) {
	$oParc->cities_id = \Model\Model::validateID($_GET['parent']);
}

$smarty->assign('oParc', $oParc);
$smarty->assign("aCities", $aCities);


$smarty->assign("aErrors", $aErrors);
