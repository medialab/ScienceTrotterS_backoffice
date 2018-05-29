<?php


ApiMgr::setLang('fr');
$aCities = Model\City::list(5);
$smarty->assign('aCities', $aCities);

$aParcours = Parcours::list(5);
$smarty->assign('aParcours', $aParcours);

$aInterrests = ApiMgr::list('interrests', false, 5);
$smarty->assign('aInterrests', $aInterrests->data);