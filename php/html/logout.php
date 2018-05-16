<?php 
var_dump("LOGIN OUT");
ApiMgr::logout();
session_destroy();

exit;