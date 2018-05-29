<?php

$curPage = explode('/', $sPage);
if (count($curPage) < 2) {
	header('location: /');
	exit;
}

$aErrors = [];
$smarty->assign('aLangs', [
	'fr' => 'français',
	'en' => 'anglais'
]);

$curPage = $curPage[1];
switch ($curPage) {
	case 'interret':
		$sFilPart = 'Édition d\'interrêt';
		break;

	case 'city':
		$sFilPart = 'Édition de ville';
		break;

	case 'parcours':
		$sFilPart = 'Édition de parcours';
		break;
}

$aFilDArianne['edit/'.$curPage] = $sFilPart;