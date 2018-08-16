<?php

function fRespond($success, $data, $msg=null, $code=200) {
	echo json_encode([
		'success' => $success,
		'data' => $data,
		'code' => $code
	]);
	
	exit;
}

if (!ApiMgr::getToken()) {
	fRespond(false, false, 'Aucune Session Trouvée', 401);	
}