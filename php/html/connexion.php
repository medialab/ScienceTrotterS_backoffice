<?php 

if (fMethodIs('post')) {
	var_dump($_POST);

	$res = ApiMgr::login('oui@oui.com', $_POST['user_password']);
	exit;
}