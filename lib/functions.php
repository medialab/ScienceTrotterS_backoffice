<?php

function fMethodIs($type='get') {
	return $_SERVER['REQUEST_METHOD'] === strtoupper($type);
}

/* INPUT FILTERS */
	function fRequiredValidator($sName, $aFormData, $bFile=false) {
		if ($bFile) {
			$bValid = !empty($_FILES) && !empty($_FILES[$sName]) && !empty($_FILES[$sName]['tmp_name']) && $_FILES[$sName]['error'] === UPLOAD_ERR_OK ;
		}
		else{
			$res = empty($aFormData[$sName]);
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

	function fFileZieValidator($sName, $size) {
		/* Si aucun Fichier */
		if (empty($_FILES) || empty($_FILES[$sName]) || !$_FILES[$sName]['size']) {
			return true;
		}

		/* Vérification du filtre */
		$aMatches = [];
		$sSize = $aParams['aApplyedFilters']['file-size'];
		
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

		/* Si aucun Fichier */
		if (empty($_FILES) || empty($_FILES[$sName]) || empty($_FILES[$sName]['tmp_name'])) {
			return true;
		}

		/* On récupère le fichier et son Mime */
		$sTmpPath = $_FILES[$sName]['tmp_name'];
		
		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		$mtype = finfo_file( $finfo,  $sTmpPath);
		finfo_close( $finfo );
		
		/* On récupère les Mimes Authorisés */
		if (is_string($aFileTypes)) {
			$aFileTypes = explode(', ', $aFileTypes);
		}

		$aAuthorizedMimes = fGetAuthorizedMimes($aFileTypes);

		return in_array($mtype, $aAuthorizedMimes);
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
								$aAuthorizedMimes[$sType] = $aMime.'/'.$sType;
								break;
							}
						}

						if (empty($aAuthorizedExtensions[$sType])) {
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
				'audio' => ['mpeg', 'x-wav', 'flac'],
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
				'm2a' => 'audio/mpeg',
				'mpa' => 'audio/mpeg',
				'mpg' => 'audio/mpeg',


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
