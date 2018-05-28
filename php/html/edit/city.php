<?php

/* Titre du formulaire */
$smarty->assign('sCreation', 'une ville');

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;
if ($id && !fIdValidator($id)) {
	header('location: /cities.html');
}

$oCity = new \Model\City($id);

/* Validation du formulaire */
if (fMethodIs('post')) {

	/* Validation Du Label */
		if(!fRequiredValidator('label', $_POST)) {
			$aErrors['Nom'] = 'Ce champs est obligatoire';
		}
		else{
			$oCity->label = $_POST['label'];
		}

	/* Validation Du Status */
		$_POST['state'] = (bool) (empty($_POST['state']) ? 0 : $_POST['state']);
		$oCity->state = $_POST['state'];
		var_dump($_POST);

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

		if (empty($aErrors['Latitude']) && empty($aErrors['Latitude'])) {		
			$oCity->geoloc = $_POST['geo-n'].';'.$_POST['geo-e'];
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

	/* Si On a pad d'erreur on prepare L'object ville */
	if (empty($aErrors)) {
		/* La ville ne peut être active que si tout les champs sont remplis */

		if (!strlen($oCity->geoloc) || !strlen($oCity->image)) {
			var_dump("SETTING STATE TO FALSE");
			$oCity->state = false;
			exit;
		}

		/* Sauvegarde Temporaire de l'image */
		if (!empty($_FILES['img']) && !empty($_FILES['img']['name'])) {
			/* Création du dossier */
			if (!is_dir(UPLOAD_PATH.'cities')) {
				mkdir(UPLOAD_PATH.'cities', 0775, true);
			}

			$imgPath = 'cities/'.fCreateFriendlyUrl($_FILES['img']['name']);
			$dest = UPLOAD_PATH.$imgPath;

			/* Si le fichier existe on le remplace */
			if (file_exists($dest)) {
				unlink($dest);
			}

			move_uploaded_file($_FILES['img']['tmp_name'], $dest);

			/* On Sauvegarde le nouveau path de l'image*/
			$oCity->image = $imgPath;
		}

		if (!$oCity->save()) {
			$aErrors['Erreur'] = 'Une Erreur s\'est produit lors de l\'enregistrement';
		}
		elseif (!$id) {	// On redirige pour se mettre en modification
			header('location: /edit/city/'.$oCity->id.'.html');
			exit;
		}
	}
}

$smarty->assign('oCity', $oCity);
$smarty->assign("aErrors", $aErrors);
