<?php

/* On récupère le Model à supprimer*/
$aPage = explode('/', $sPage);
if (count($aPage) !== 2) {
	header('location: /');
	exit;
}

/* Récupération de l'ID de la ville s'il existe */
$id = !empty($_GET['id']) ? $_GET['id'] : false;
if ($id && !fIdValidator($id)) {
	header('location: /cities.html');
}

$sClass = $aPage[1];
$oMdl = Model\Model::get($id, null, ucfirst($sClass));


//ApiMgr::$debugMode = true;
if ($oMdl->isLoaded()) {
	$oMdl->setLang('fr');

	if ($oMdl->delete()) {
		$_SESSION['session_msg'] = [
			'success' => [
				'Suppression de '.$oMdl->title.' réussie.'
			]
		];
	}
	else{
		$_SESSION['session_msg'] = [
			'error' => [
				'Impossible de supprimer '.$oMdl->sUserStr.' "'.$oMdl->title.'"',
				ApiMgr::getMessage()
			]
		];
	}
}
else{
	$_SESSION['session_msg'] = [
		'error' => [
			'Impossible de trouver '.$oMdl->sUserStr
		]
	];
}



header('location: /');
exit;