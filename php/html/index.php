<?php

$aCities = ApiMgr::list('cities', true, 5);
$smarty->assign('aCities', $aCities->data);



$aParcours = ApiMgr::list('parcours', true, 5);
$smarty->assign('aParcours', $aParcours->data);


$aParcours = ApiMgr::list('interrests', true, 5);
$smarty->assign('ainterrests', $aInterrests->data);
