<?php

var_dump("TEST");
$aCities = ApiMgr::list('cities', true, 5);
$smarty->assign('aCities', $aCities->data);
exit;