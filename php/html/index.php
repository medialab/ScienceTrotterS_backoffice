<?php


var_dump("GET CITIES");
$aCities = ApiMgr::list('cities', false, 5);
$smarty->assign('aCities', $aCities->data);
var_dump($aCities);

ApiMgr::setLang('fr');

ApiMgr::$debugMode = true;
var_dump("GET CITIES 2");
$aCities = ApiMgr::list('cities', false, 5);
$smarty->assign('aCities', $aCities->data);
var_dump($aCities);


var_dump("GET PARCOURS");
$aParcours = ApiMgr::list('parcours', false, 5);
$smarty->assign('aParcours', $aParcours->data);
var_dump($aParcours);

$aInterrests = ApiMgr::list('interrests', false, 5);
$smarty->assign('aInterrests', $aInterrests->data);

exit;