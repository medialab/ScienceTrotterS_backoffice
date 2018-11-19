<?php 
$aErrors = [];
if (fMethodIs('post')) {
	//ApiMgr::$debugMode = true;
	//$pass = password_hash( $_POST['user_password'], PASSWORD_BCRYPT );

	$res = ApiMgr::login('admin@admin.com', $_POST['user_password']);

	if ($res) {
		header('location: /');
		exit;
	}

	$aErrors[''] = ApiMgr::getMessage();
}

$smarty->assign([
	'showTopBar' => false,
	'showNavBar' => false,
	'aErrors' => $aErrors
]);