<?php

$aCities = ApiMgr::list('cities');
var_dump($aCities);
$aCities = ApiMgr::get('cities', 'ca3e834d-c717-4832-ab8b-c50ebd1bd3d6');
var_dump($aCities);
var_dump("END");
exit;