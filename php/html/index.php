<?php


ApiMgr::setLang('fr');
$aCities = Model\City::list(5);
$smarty->assign('aCities', $aCities);

$aParcours = Model\Parcours::list(5);
$smarty->assign('aParcours', $aParcours);

$aInterrests = ApiMgr::list('interrests', false, 5);
var_dump($aInterrests);
$smarty->assign('aInterrests', $aInterrests->data);