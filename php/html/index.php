<?php

ApiMgr::setLimit(3);
$aCities = ApiMgr::list('cities');
$smarty->assign('aCities', $aCities->data);