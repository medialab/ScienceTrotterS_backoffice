<?php

// Gestion des erreurs 
	ini_set( 'display_errors', true );
	error_reporting( E_ALL | E_NOTICE );
	ini_set('xdebug.var_display_max_data', -1);
	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);

	define("API_URL", 'https://api-sts.actu.com');

// On appel la bonne page
	require_once('./bootstrap.php');


