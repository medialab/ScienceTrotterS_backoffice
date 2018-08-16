<?php
//ApiMgr::$debugMode = true;

// Récupération Des Villles
$aCities = Model\City::list(0, 0, ['title', 'state'], ['title', 'ASC']);
$smarty->assign('aCities', $aCities);
