<?php


ApiMgr::setLang('fr');
$aCities = Model\City::list(5);
$smarty->assign('aCities', $aCities);

var_dump($aCities);
exit;

$aParcours = ApiMgr::list('parcours', false, 5);
$smarty->assign('aParcours', $aParcours->data);

$aInterrests = ApiMgr::list('interrests', false, 5);
$smarty->assign('aInterrests', $aInterrests->data);