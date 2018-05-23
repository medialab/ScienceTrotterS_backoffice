<?php

var_dump($sPage);
$aPage = explode('/', $sPage);
if (count($aPage) !== 2) {
	header('location: /');
	exit;
}

$sClass = $aPage[1];
var_dump("FETCH MODEL $sClass");
$mdl = Model\Model::get(ucfirst($sClass));
var_dump($mdl);

exit;