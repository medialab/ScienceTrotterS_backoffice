<?php


//ApiMgr::setLang('fr');

//ApiMgr::$debugMode = true;
$aCities = Model\City::list(0, 0, ['title', 'state']);
$smarty->assign('aCities', $aCities);

$aParcours = Model\Parcours::list(0, 0, ['title', 'state']);
$smarty->assign('aParcours', $aParcours);

$aInterests = Model\Interest::list(0, 0, ['title', 'state']);
$smarty->assign('aInterests', $aInterests);
