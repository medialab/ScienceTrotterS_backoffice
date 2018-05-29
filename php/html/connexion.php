<?php 

$aErrors = [];
if (fMethodIs('post')) {
	$res = ApiMgr::login('ouio@oui.com', $_POST['user_password']);

	if ($res) {
		header('location: /');
		exit;
	}

	$aErrors[] = 'Identifiant / Mot de passe invalides.';
}

[
	'showTopBar' => true,
	'showNavBar' => false,
	'aErrors' => $aErrors
]

$smarty->assign('hideTopBar', true);
$smarty->assign('aErrors', $aErrors);
$smarty->assign('showNavBar', false);