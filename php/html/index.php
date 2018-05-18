<?php

ApiMgr::setLimit(10);
$aCities = ApiMgr::list('cities');
var_dump($aCities);
exit;
$smarty->assign('aCities', $aCities->data);