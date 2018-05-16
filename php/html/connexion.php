<?php 

if (fMethodIs('post')) {
	var_dump($_POST);

	$res = ApiMgr::login('ouio@oui.com', $_POST['user_password']);
	var_dump("API RESULT", $res);
	if ($res) {
		exit;
		header('location: /');
	}
	
	exit;
}