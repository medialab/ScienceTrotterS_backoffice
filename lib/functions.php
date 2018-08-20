<?php
// Remplacement des caractères spéciaux
	function fRemoveSpecialChar( $sString, $sChar="-" ) {
		$fRemoveSpecialChar					=	$sString;
		$fRemoveSpecialChar					=	preg_replace( '`(\\r|\\n|\\t|\/\*(.+)\*\/)`Us', '', $fRemoveSpecialChar );
		$fRemoveSpecialChar					=	preg_replace( '`(\ +)`', ' ', $fRemoveSpecialChar );
		$fRemoveSpecialChar					=	preg_replace( '`(\’|\=|\^|\%|\$|\+|\-|\*|_|\@|\&|\(|\)|\!|\[|\]|\#|\ |\,|\.|\;|\/|\'|\:|\°|\?|\"|\\\\|\®|\™)`', $sChar, $fRemoveSpecialChar );
		$fRemoveSpecialChar					=	preg_replace( '`(\\{$sChar}+)`', $sChar, $fRemoveSpecialChar );
		$fRemoveSpecialChar					=	preg_replace( '`({$sChar}+)`', $sChar, $fRemoveSpecialChar );
		$fRemoveSpecialChar					=	trim( $fRemoveSpecialChar, $sChar );
		return $fRemoveSpecialChar;
	}
//---

// Suppression des accents
	function fRemoveAccents( $sString ) {
		$aChars					=	array(
			'Š'=>'S', 'š'=>'s', 'Ð'=>'D', 'd'=>'d', 'Ž'=>'Z', 'ž'=>'z', 'C'=>'C', 'c'=>'c', 'C'=>'C', 'c'=>'c','À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e','ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>"i", "ï"=>"i", "ð"=>"o", "ñ"=>"n", "ò"=>"o", "ó"=>"o","ô"=>"o", "õ"=>"o", "ö"=>"o", "ø"=>"o", "ù"=>"u", "ú"=>"u", "û"=>"u", "ý"=>"y", "ý"=>"y", "þ"=>"b","ÿ"=>"y", "R"=>"R", "r"=>"r"
		);
		return strtr( ($sString), $aChars );
	}
//---


// Création d'une chaine de caractère URL Friendly ( sans accents, ni caractère spécial, en minuscule )
	function fCreateFriendlyUrl( $sString ) {
		return preg_replace(['/([^a-z0-9.-]+)/', '/(\-)+/', '/^\-/', '/\-([^a-z0-9])/'], ['-', '-', '', '$1'], strtolower($sString));
		return str_replace('-.', '.', $sString);
	}
//---

function fMethodIs($type='get') {
	return $_SERVER['REQUEST_METHOD'] === strtoupper($type);
}

/* INPUT FILTERS */
	function fIdValidator($id) {
		return preg_match('/^[a-z0-9]{8}-([a-z0-9]{4}-){3}[a-z0-9]{12}$/', $id);
	}

	function fRequiredValidator($sName, $aFormData, $bFile=false) {
		if ($bFile) {
			$bValid = !empty($_FILES) && !empty($_FILES[$sName]) && !empty($_FILES[$sName]['tmp_name']) && $_FILES[$sName]['error'] === UPLOAD_ERR_OK ;
		}
		else{
			$res = !empty($aFormData[$sName]);
		}

		return $res;
	}

	function fDateValidator($sName, &$aFormData, $format=false) {
		$datePat = '/([0-9]{4})(-[0-9]{2})?(-[0-9]{2})?/';
		if (!preg_match($datePat, $aFormData[$sName])) {
			return false;
		}

		if ($format) {
			$aFormData[$sName] = date($format, strtotime($aFormData[$sName]));
		}

		return true;
	}

	function fDateGraterValidator($sName, $aFormData, $format, $dateMax, $bEqual=true) {
		$dateMax = $aParams['sFilterVal'];
		
		$matches = [];
		$datePat = '/([0-9]{4})(-[0-9]{2})?(-[0-9]{2})?/';
		if (preg_match_all($datePat, $dateMax, $matches, PREG_SET_ORDER)) {
			for ($i=0; $i < 4 - count($matches[0]); $i++) { 
				$dateMax .= '-01';
			}
		}
		else{
			trigger_error('Filter date-ls is not Valid');
			return;
		}

		if ($bEqual) {
			return strtotime($target) >= strtotime($aParams['sUsrVal']);
		}
		else {
			return strtotime($target) > strtotime($aParams['sUsrVal']);
		}
	}

	function fDateLesserValidator($sName, $aFormData, $format, $dateMax, $bEqual=true) {
		$dateMax = $aParams['sFilterVal'];
		
		$matches = [];
		$datePat = '/([0-9]{4})(-[0-9]{2})?(-[0-9]{2})?/';
		if (preg_match_all($datePat, $dateMax, $matches, PREG_SET_ORDER)) {
			for ($i=0; $i < 4 - count($matches[0]); $i++) { 
				$dateMax .= '-01';
			}
		}
		else{
			trigger_error('Filter date-ls is not Valid');
			return;
		}

		if ($bEqual) {
			return strtotime($target) >= strtotime($aParams['sUsrVal']);
		}
		else {
			return strtotime($target) > strtotime($aParams['sUsrVal']);
		}
	}

	function fFileZieValidator($sName, $sSize) {
		/* Si aucun Fichier */
		if (empty($_FILES) || empty($_FILES[$sName]) || !$_FILES[$sName]['size']) {
			return true;
		}

		/* Vérification du filtre */
		$aMatches = [];
		$aSizeFactors = [
			'o' => 1,
			'ko' => 1024,
			'mo' => 1024*1024,
			'go' => 1024*1024*1024
		];
		
		if (
			!preg_match('/([0-9]+(\.[0-9]{1,2})?)([KMGO]{1,2})/i', $sSize, $aMatches) 
			|| !array_key_exists(strtolower($aMatches[3]), $aSizeFactors)
		) {
			return true;
		}

		$dSize = (float)$aMatches[1] * $aSizeFactors[strtolower($aMatches[3])];
		return $dSize >= $_FILES[$sName]['size'];
	}

	function fFileExtensionValidator($sName, $aFileTypes) {
		//var_dump($_FILES);
		//var_dump("VALIDATE $sName");
		/* Si aucun Fichier */
		if (empty($_FILES) || empty($_FILES[$sName]) || empty($_FILES[$sName]['tmp_name'])) {
			//var_dump("File Empty");
			return true;
		}

		/* On récupère le fichier et son Mime */
		$sTmpPath = $_FILES[$sName]['tmp_name'];
		
		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		$mtype = finfo_file( $finfo,  $sTmpPath);
		finfo_close( $finfo );
		//var_dump("mType: $mtype");;
		
		/* On récupère les Mimes Authorisés */
		if (is_string($aFileTypes)) {
			$aFileTypes = explode(', ', $aFileTypes);
		}

		$aAuthorizedMimes = fGetAuthorizedMimes($aFileTypes);
		//var_dump("Autorized: ", $aAuthorizedMimes);;
		//var_dump("Result: ", in_array($mtype, $aAuthorizedMimes));;

		return in_array($mtype, $aAuthorizedMimes);
	}

	function fImageSize($name, $width, $height, $sComp='lessEq') {
		return true;
		if (!extension_loaded('imagick')) {
			trigger_error('Le Module PHP Imagick n\'est pas activé. Impossible de vérifier la taille en px des images.');
			return true;
		}

		$aF = &$_FILES[$name];
		if (empty($aF['name'])) {
			return true;
		}

		if (is_string($aF['name'])) {
			$aF['name'] = [$aF['name']];
			$aF['type'] = [$aF['type']];
			$aF['tmp_name'] = [$aF['tmp_name']];
			$aF['error'] = [$aF['error']];
			$aF['size'] = [$aF['size']];
		}

		foreach ($aF['name'] as $i => $fName) {			
			if (empty($fName)) {
				continue;
			}

			$image = new Imagick($aF['tmp_name'][$i]);
			switch ($sComp) {
				case 'less':
					return $image->getImageWidth() < $width &&  $image->getImageHeight() < $height;
					break;

				case 'lessEq':
					return $image->getImageWidth() <= $width && $image->getImageHeight() <= $height;
					break;

				case 'equal':
					return $image->getImageWidth() == $width && $image->getImageHeight() == $height;
					break;

				case 'great':
					return $image->getImageWidth() > $width &&  $image->getImageHeight() > $height;
					break;

				case 'greatEq':
					return $image->getImageWidth() >= $width && $image->getImageHeight() >= $height;
					break;
			}
		}

	}

	/**
	 * Retourne la liste des MimeTypes autorisée par le filtre FILE-EXT
	 * @param  Array $aFileTypes Tableau des Paramètres donnés au filtre
	 * @return Array             Tableau des MimeTypes Authorisés
	 */
	function fGetAuthorizedMimes($aFileTypes) {
		/* On récupère la Map des MimeTypes*/
		$aMimeMap = fGetMimeMap();

		$aAuthorizedMimes = [];
		/* Pour Chaques paramètres  */
		foreach ($aFileTypes as $sType) {
			/* si le paramètres est une extension  */
			if ($sType[0] == '.') {
				$ext = substr($sType, 1);

				if (!empty($aMimeMap['extensions'][$ext])) {
					$aAuthorizedMimes[$sType] = $aMimeMap['extensions'][$ext];
				}
				else{
					trigger_error("Filter File does not have {$ext} Extension");
				}
			}
			/* si le paramètres est un MimeType  */
			else{
				/* le paramètres est un MimeType complet  */
				if (strpos($sType, '/')) {
					$aType = explode('/', $sType, 1);
					$prefix = $aType[0];
					$suffix = $aType[1];
					
					if (!empty($aMimeMap['mimes'][$prefix]) && in_array($aMimeMap['mimes'][$prefix], $suffix)) {
						$aAuthorizedMimes[$sType] = $sType;
					}
					else{
						trigger_error("Filter File does not have {$sType} MimeType");
					}
				}

				/* le paramètres est un MimeType supposé incomplet  */
				else {
					/* on cherche le MimeType dans les Clés  */
					if (!empty($aMimeMap['mimes'][$sType])) {
						/* on regroupe récupères MimeTypes de la Clés  */
						foreach ($aMimeMap['mimes'][$sType] as $mime) {
							$aAuthorizedMimes[$sType.'/'.$mime] = $sType.'/'.$mime;
						}
					}
					else {
						/* on cherche le MimeType dans les Valeur  */
						foreach ($aMimeMap['mimes'] as $key => $aMimes) {
							if (in_array($sType, $aMimes)) {
								$aAuthorizedMimes[$sType] = $key.'/'.$sType;
								break;
							}
							
						}

						if (empty($aAuthorizedMimes[$sType])) {
							trigger_error("Filter File does not have {$sType} MimeType");
						}
					}
				}
			}
		}

		return $aAuthorizedMimes;
	}


	/* Liste des MimeTypes que gèrre le filtre FILEEXT */
	function fGetMimeMap() {
		static $aMimeMap = [
			'mimes' => [
				'audio' => ['mpeg', 'x-wav', 'flac', 'wav'],
				'video' => ['mpeg', 'mp4', 'quicktime', 'x-flv'],
				'text' => ['css', 'csv', 'html', 'javascript', 'plain', 'xml'],
				'image' => ['gif', 'jpg', 'jpeg', 'png', 'x-icon', 'svg', 'tiff'],

				'application' => [
					/* Application Basiques*/
					'javascript', 
					'json', 
					'pdf', 
					'xml', 
					'zip',

					/* Application Microsoft*/
					'msword', 
					'vnd.ms-excel',
					'vnd.ms-powerpoint',
					'vnd.openxmlformats-officedocument.spreadsheetml.sheet',
					'vnd.openxmlformats-officedocument.wordprocessingml.document', 
					'vnd.openxmlformats-officedocument.wordprocessingml.template',
					'vnd.openxmlformats-officedocument.presentationml.presentation'
				],
			],

			'extensions' => [
				/* TEXT */
				'css' => 'text/css',
				'csv' => 'text/csv',
				'xml' => 'text/xml',
				'txt' => 'text/plain',
				'html' => 'text/html',
				'js' => 'text/javascript',
				'pdf' => 'application/pdf',

				/* IMAGE */
				'gif' => 'image/gif',
				'png' => 'image/png',
				'jpg' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'tiff' => 'image/tiff',

				/* AUDIO */
				'flac' => 'audio/flac',
				'mp1' => 'audio/mpeg',
				'mp2' => 'audio/mpeg',
				'mp3' => 'audio/mpeg',
				'm2a' => 'audio/mpeg',
				'mpa' => 'audio/mpeg',
				'mpg' => 'audio/mpeg',
				'wav' => 'audio/wav',


				/* VIDEO */
				'mp4' => 'video/mp4',
				'flv' => 'video/x-flv',
				'mpeg' => 'video/mpeg',
				'mov' => 'video/quicktime',


				/* Applications Basiques */
				'pdf' => 'application/pdf',						
				'xml' => 'application/xml',						
				'zip' => 'application/zip',						
				'json' => 'application/json',						
				'js' => 'application/javascript',						

				
				/* Application Microsoft*/
				'doc' => 'application/msword',
				'dot' => 'application/msword',
				'docx' => 'vnd.openxmlformats-officedocument.wordprocessingml.document',
				'dotx' => 'vnd.openxmlformats-officedocument.wordprocessingml.template',

				'xls' => 'application/vnd.ms-excel',
				'xlt' => 'application/vnd.ms-excel',
				'xla' => 'application/vnd.ms-excel',
				'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

				'pptx' => 'vnd.openxmlformats-officedocument.presentationml.presentation'
			]
		];

		return $aMimeMap;
	}

/**
 * Gestion Des Uploads
 * @param  Sname  $name      Nom Input Du Fichier
 * @param  String  $directory Dossier D'enregistrement
 * @param  boolean $bArray    Est Un Tableau de Fichiers
 * @return String             Le Path relatif Du Fichier
 */
function handleUploadedFile($name, $directory, $bArray = false) {
	/* Sauvegarde Temporaire de l'image */
	if (empty($_FILES[$name])) {
		return null;
	}

	$aResult = [];
	$aF = &$_FILES[$name];
	if (!empty($aF['name'])) {

		if (is_string($aF['name'])) {
			$aF['name'] = [$aF['name']];
			$aF['type'] = [$aF['type']];
			$aF['tmp_name'] = [$aF['tmp_name']];
			$aF['error'] = [$aF['error']];
			$aF['size'] = [$aF['size']];
		}

		// Enregistrement Des Uploads
		foreach ($aF['name'] as $i => $fName) {
			if (empty($fName)) {
				continue;
			}

			/* Création du dossier */
			if (!is_dir(UPLOAD_PATH.$directory)) {
				mkdir(UPLOAD_PATH.$directory, 0775, true);
			}
			
			// Generation Du nom Du Fichier
			$imgPath = $directory.'/'.fCreateFriendlyUrl($aF['name'][$i]);
			$imgPath = preg_replace('/\.([^.]+)$/', '_'.time().'.$1', $imgPath);
			// 060820108flo
				$sOriginName = preg_replace( '`(.+)\.([a-z]+)`', '${1}', fCreateFriendlyUrl($aF['name'][$i]) );
				$sNewName = $sOriginName;
				$sExtensionName = preg_replace( '`(.+)\.([a-z]+)`', '${2}', fCreateFriendlyUrl($aF['name'][$i]) );
				$dNameSake=0;
				while( !empty( $aFileName[$sNewName] ) || file_exists( API_URL.'ressources/upload/'.$sNewName.'.'.$sExtensionName ) ){
					$dNameSake ++;
					$sNewName = $directory.'/'.fCreateFriendlyUrl($sOriginName).'-'.$dNameSake;
				}
				$aFileName[$sNewName]=1;
				$imgPath=$sNewName.'.'.$sExtensionName;
			// ---

			// Définition de la destination
			$dest = UPLOAD_PATH.$imgPath;
			$dest = str_replace('/', DIRECTORY_SEPARATOR, $dest);

			/* Si le fichier existe on le remplace */
			if (file_exists($dest)) {
				//var_dump("Deleting Old");
				unlink($dest);
			}

			/* Sauvegarde du fichier */
			$result = move_uploaded_file($aF['tmp_name'][$i], $dest);

			/* Si Sauvegarde a réussi */
			if ($result) {
				// Si Un Seul Fichier On Retourne Son Path
				if (!$bArray) {
					return $imgPath;
				}
				// Si Multiple Fichiers Garde Son Path
				else{
					$aResult[$i] = $imgPath;
				}
			}
			// Si Non On Définis Le Path à NULL
			else{
				if (!$bArray){
					return null;
				}
				else{
					$aResult[$i] = null;
				}
			}
		}

		// On Retourne La Liste Des Path
		return $aResult;
	}
	else{
		//var_dump("No File Uploaded");
	}

	return null;
}

/**
 * Définitions des JS à Charger
 */
global $_JS_FILES;
$_JS_FILES = [];
function addJs() {
	$aPaths = func_get_args();
	foreach ($aPaths as $sPath) {
		$sPath .= '.js';
		global $_JS_FILES;
		
		$sHtmlPath = '/lib/'.$sPath;
		$sRealPath = realpath('.').JS_PATH.$sPath;


		if (!file_exists($sRealPath)) {
			trigger_error('Can\'t add JS file: "'.$sRealPath.'". File Not found.');
			continue;
		}

		if (!in_array($sHtmlPath, $_JS_FILES)) {
			$_JS_FILES[] = $sHtmlPath;
		}

	}
}


/**
 * Définitions des CSS à Charger
 */
global $_CSS_FILES;
$_CSS_FILES = [];
function addCss($sPath) {
	$aPaths = func_get_args();
	foreach ($aPaths as $sPath) {
		$sPath .= '.css';
		global $_CSS_FILES;
		
		$sHtmlPath = '/lib/'.$sPath;
		$sRealPath = realpath('.').CSS_PATH.$sPath;


		if (!file_exists($sRealPath)) {
			trigger_error('Can\'t add CSS file: "'.$sRealPath.'". File Not found.');
			continue;
		}

		if (!in_array($sHtmlPath, $_CSS_FILES)) {
			$_CSS_FILES[] = $sHtmlPath;
		}

	}
	return true;
}