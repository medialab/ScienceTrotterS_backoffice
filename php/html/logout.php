<?php 

ApiMgr::logout();
session_destroy();

header('location: /connexion.html');
exit;