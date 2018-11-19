<?php


$table = @$_GET['table'];
$id = @$_GET['id'];
$class = $model = @$_GET['model'];
$type = @$_GET['type'];

if (empty($id) || empty($table) || empty($model) || empty($type)) {
	header('location: /');
	exit;
}

$model = '\Model\\'.$model;
$oModel = new $model($id);
if (!$oModel->isLoaded()) {
	header('location: /');
	exit;
}

if (strpos($type, '@')) {
	$type = explode('@', $type);
	$index = $type[1];
	$type = $type[0];

	($oModel->gallery_image)->$index = null;
}
else{
	$oModel->$type = null;	
}

if (!empty($_GET['lang'])) {
	$oModel->setLang($_GET['lang']);
}

//$oModel->state = false;

//ApiMgr::$debugMode = true;
$oRes = $oModel->save();

if (!$oRes->success) {
	$_SESSION['session_msg']['warning'] = [
		'Impossible de supprimer le fichier sans dé-activer '.$oModel->sUserStr
	];
}
elseif (!empty($oRes->message)){
	$_SESSION['session_msg']['warning'] = [
		$oRes->message
	];
}


header('location: /edit/'.strtolower($class).'/'.$id.'.html');

exit;