<?php

$aCities = ApiMgr::list('cities', true, 5);
$smarty->assign('aCities', $aCities->data);



$aParcours = ApiMgr::list('parcours', true, 5);
var_dump($aParcours);
$smarty->assign('aParcours', $aParcours->data);
