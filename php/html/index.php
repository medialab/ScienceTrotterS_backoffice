<?php

ApiMgr::setLimit(10);
$aCities = ApiMgr::list('cities');
$smarty->assign('aCities', $aCities->data);