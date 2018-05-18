<?php

ApiMgr::setLimit(5);
$aCities = ApiMgr::list('cities');
$smarty->assign('aCities', $aCities->data);