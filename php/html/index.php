<?php


ApiMgr::setLang('fr');
$aCities = Cities::list(5);
$smarty->assign('aCities', $aCities->data);

var_dump($aCities);
exit;

$aParcours = ApiMgr::list('parcours', false, 5);
$smarty->assign('aParcours', $aParcours->data);

$aInterrests = ApiMgr::list('interrests', false, 5);
$smarty->assign('aInterrests', $aInterrests->data);