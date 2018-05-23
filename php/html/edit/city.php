<?php

/* Titre du formulaire */
$smarty->assign('sCreation', '\'une ville');

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;
if ($id && !fIdValidator($id)) {
	header('location: /cities.html');
}

$oCity = new \model\City($id);

/* Validation du formulaire */
if (fMethodIs('post')) {

	/*  */
	if(!fRequiredValidator('label', $_POST)) {
		$aErrors['Nom'] = 'Ce champs est obligatoire';
	}
	else{
		$oCity->label = $_POST['label'];
	}

	$_POST['state'] = (bool) (empty($_POST['state']) ? 0 : $_POST['state']);

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

	if (empty($aErrors['Latitude']) && empty($aErrors['Latitude'])) {		
		$oCity->geoloc = $_POST['geo-n'].';'.$_POST['geo-e'];
	}

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

	if (empty($aErrors)) {
		if (empty($oCity->geoloc) || empty($oCity->image)) {
			$oCity->state = false;
		}
		if (!empty($_FILES['img']) && !empty($_FILES['img']['name'])) {

			if (!is_dir(UPLOAD_PATH.'cities')) {
				mkdir(UPLOAD_PATH.'cities', 0775, true);
			}

			$imgPath = 'cities/'.fCreateFriendlyUrl($_FILES['img']['name']);
			$dest = UPLOAD_PATH.$imgPath;

			var_dump($dest);
			if (file_exists($dest)) {
				unlink($dest);
			}

			move_uploaded_file($_FILES['img']['tmp_name'], $dest);

			$oCity->image = $imgPath;
		}

		if (!$oCity->save()) {
			$aErrors['Erreur'] = 'Une Erreur s\'est produit lors de l\'enregistrement';
		}
		elseif (!$id) {
			var_dump($oCity);
			exit;
			header('location: /edit/city/'.$oCity->id.'.html');
			exit;
		}

	}

	exit;
}

var_dump(fMethodIs('post'));
var_dump($aErrors);
var_dump($id, $oCity);

$smarty->assign('oCity', $oCity);
$smarty->assign("aErrors", $aErrors);
