<?php


ApiMgr::setLang('fr');
$aCities = Model\City::list(5);
$smarty->assign('aCities', $aCities);

$aParcours = Model\Parcours::list(5);
$smarty->assign('aParcours', $aParcours);

ApiMgr::$debugMode = true;
$aInterrests = ApiMgr::list('interrests', false, 5);
exit;
$smarty->assign('aInterrests', $aInterrests->data);