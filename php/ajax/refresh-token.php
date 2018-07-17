<?php

if (ApiMgr::refreshToken()) {
	fRespond(true, ApiMgr::getToken());
}

fRespond(false, false, 'Impossible de renouveler la session avec le motif suivant:<br>'.ApiMgr::getMessage(), 400);