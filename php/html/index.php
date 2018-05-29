<?php


ApiMgr::setLang('fr');
$aCities = ApiMgr::list('cities', false, 5);
$smarty->assign('aCities', $aCities->data);

var_dump($aCities);

$aParcours = ApiMgr::list('parcours', false, 5);
$smarty->assign('aParcours', $aParcours->data);

$aInterrests = ApiMgr::list('interrests', false, 5);
$smarty->assign('aInterrests', $aInterrests->data);