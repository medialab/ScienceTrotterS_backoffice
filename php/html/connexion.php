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

$smarty->assign('aErrors', $aErrors);