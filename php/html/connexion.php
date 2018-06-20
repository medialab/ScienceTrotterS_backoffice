<?php 

$aErrors = [];
if (fMethodIs('post')) {
	$res = ApiMgr::login('admin@admin.com', $_POST['user_password']);

	if ($res) {
		header('location: /');
		exit;
	}

	$aErrors[''] = 'Identifiant / Mot de passe invalides.';
}

$smarty->assign([
	'showTopBar' => false,
	'showNavBar' => false,
	'aErrors' => $aErrors
]);
