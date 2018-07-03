<?php

function fValidateModel(Model\Model $oModel, &$aErrors) {
	/* Validation Du Status */
		$_POST['state'] = (bool) (empty($_POST['state']) ? false : $_POST['state']);

	/* Validation De la langue */
		$sLang = empty($_POST['lang']) ? false : $_POST['lang'];
		if (!$sLang) {
			$aErrors['lang'] = 'Aucune langue n\'a été sélectionnée';
		}

		$oModel->setLang($sLang);

		if (!empty($_POST['force_lang'])) {
			$oModel->force_lang = $_POST['force_lang'];
		}
		else{
			$oModel->force_lang = null;
		}

	/* Validation Du Title */
		if(!fRequiredValidator('title', $_POST)) {
			$aErrors['Nom'] = 'Ce champs est obligatoire';
		}
		elseif($sLang){
			$oModel->title = $_POST['title'];
		}

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
		else{
			$oModel->setGeoN(null);
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
			
			if (empty($aErrors['Latitude']) && empty($aErrors['Latitude'])) {		
				$oModel->geoloc = $_POST['geo-n'].';'.$_POST['geo-e'];
			}
		}
		else{
			$oModel->setGeoE(null);
		}


	/* Validation de L'image Principale */
		$aFileTypes = ['png', 'jpg', 'jpeg'];
		if (!fFileExtensionValidator('img', $aFileTypes)) {
			$mimes = fGetAuthorizedMimes($aFileTypes);
			
			if (count($mimes) > 1) {
				$aErrors['Image'] = 'L\'image doit faire parti des types suivants: '.join(', ', $aFileTypes);
			}
			else{
				$aErrors['Image'] = 'L\'image doit être du type suivant: '.join('', $aFileTypes);
			}
		}

		$maxSize = '500Mo';
		if (!fFileZieValidator('img', $maxSize)) {
			$aErrors['Image'] = 'L\'image ne peut dépasser 500 Mo';
		}

	/* Validation de L'Audio */
		$aFileTypes = ['.mp3', '.wav'];
		if (!fFileExtensionValidator('audio', $aFileTypes)) {
			$mimes = fGetAuthorizedMimes($aFileTypes);
			
			if (count($mimes) > 1) {
				$aErrors['Audio'] = 'L\'Audio doit faire parti des types suivants: .'.join(', .', $aFileTypes);
			}
			else{
				$aErrors['Audio'] = 'L\'Audio doit être du type suivant: .'.join('', $aFileTypes);
			}
		}

		$maxSize = '20Mo';
		if (!fFileZieValidator('audio', $maxSize)) {
			$aErrors['Audio'] = 'L\'Audio ne peut dépasser 20 Mo';
		}


	/* Validation De  audio_script */
		/*if (!empty($_POST['audio_script'])) {
			$oModel->audio_script = $_POST['audio_script'];
		}*/

	$oModel->setState($_POST['state']);
	return empty($aErrors);
}

//ApiMgr::$debugMode = true;
$curPage = explode('/', $sPage);
if (count($curPage) < 2) {
	header('location: /');
	exit;
}

$aErrors = [];
$smarty->assign('aErrors', $aErrors );
$smarty->assign('sGeoPat', '^[0-9]{1,2}(\.[0-9]{1,6})?$');

$smarty->assign('aLangs', [
	'fr' => 'français',
	'en' => 'anglais'
]);

$curPage = $curPage[1];
switch ($curPage) {
	case 'interest':
		$sFilPart = 'Édition d\'interêt';
		break;

	case 'city':
		$sFilPart = 'Édition de ville';
		break;

	case 'parcours':
		$sFilPart = 'Édition de parcours';
		break;
}

$aFilDArianne['edit/'.$curPage] = $sFilPart;

addJs(
	'tab-selector',
	'custom-checkbox'
);