<?php 

if (fMethodIs('post')) {
	var_dump($_POST);

	$res = ApiMgr::login('ouio@oui.com', $_POST['user_password']);
	if ($res) {
		header('location: /');
		exit;
	}
}