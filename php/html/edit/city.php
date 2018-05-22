<?php

$smarty->assign('sCreation', '\'une ville');

if (fMethodIs('post')) {
	if(!fRequiredValidator('label', $_POST)) {
		$aErrors['Nom'] = 'Ce champs est obligatoire';
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
		//$oCity = new Model\City();
	}
}

$smarty->assign("aErrors", $aErrors);

$oCity = new Model\City('ca3e834d-c717-4832-ab8b-c50ebd1bd3d6');
var_dump($oCity);
exit;