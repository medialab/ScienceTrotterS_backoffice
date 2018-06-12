<?php


ApiMgr::setLang('fr');

$aCities = Model\City::list(0, 0, ['title']);
$smarty->assign('aCities', $aCities);


$aParcours = Model\Parcours::list(0, 0, ['title']);
$smarty->assign('aParcours', $aParcours);

$aInterests = Model\Interest::list(0, 0, ['title']);
$smarty->assign('aInterests', $aInterests);
