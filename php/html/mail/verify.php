<?php

$aData = [
	'code' => $_GET['code'],
	'client_id' => '846957372411-01hsuhtmsqiriaq3qee1g4f8s9ch83nu.apps.googleusercontent.com',
	'client_secret' => 'GWkTpjFagR1R314XekjPJ03W',
	'grant_type' => 'authorization_code',
	'redirect_uri' => 'https://admin-sts-dev.actu.com/mail/test.html',
	'access_type' => 'offline',
];

$c = new CurlMgr();
var_dump($aData);

$c->setUrl('https://www.googleapis.com/oauth2/v4/token');
$c->setData($aData, false);
$c->isPost();

var_dump("Lauch Request");
var_dump($c->exec());

exit();