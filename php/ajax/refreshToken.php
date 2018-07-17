<?php

var_dump("test", $_POST);
//ApiMgr::$debugMode = true;

if (ApiMgr::refreshToken()) {
	fRespond(true, ApiMgr::getToken());
}

fRespond(false, false, 'Impossible de renouveler la session.', 400);