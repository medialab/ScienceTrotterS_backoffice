<?php

ApiMgr::setLimit(1);
$aCities = ApiMgr::list('cities');
$smarty->assign('aCities', $aCities->data);