<?php


$aCities = ApiMgr::list('cities', false, 5);
$smarty->assign('aCities', $aCities->data);
var_dump($aCities);

ApiMgr::setLang('fr');

$aCities = ApiMgr::list('cities', false, 5);
$smarty->assign('aCities', $aCities->data);
var_dump($aParcours);


$aParcours = ApiMgr::list('parcours', false, 5);
$smarty->assign('aParcours', $aParcours->data);
var_dump($aParcours);

$aInterrests = ApiMgr::list('interrests', false, 5);
$smarty->assign('aInterrests', $aInterrests->data);

exit;