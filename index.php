<?php
/*
var_dump($_SERVER['REQUEST_URI']);
var_dump($_GET);
exit;*/

// Gestion des erreurs 
	ini_set( 'display_errors', true );
	error_reporting( E_ALL | E_NOTICE );
	ini_set('xdebug.var_display_max_data', -1);
	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);

	define( 'LIBRARY_PATH',        './lib/' );
	define( 'TEMPLATE_PATH',        './templates/' );
	define( 'UPLOAD_PATH',      	realpath('.').'/media/upload/' );

	define("API_SSL", false);
	define("API_URL", 'http://api-sts-stable.actu.com');



	// Fonction de chargement dynamique des classes
		function fAutoLoader( $sClassName ){
			$aDirectoryClass			=	explode( '\\', $sClassName );
			$aDirectoryClass[0]			=	strtolower( $aDirectoryClass[0] );
			$sDirectoryClassName		=	implode( $aDirectoryClass, '/' );


			if ( count( $aDirectoryClass ) == 1 ) {
				$sDirectoryClassName		.=	'/'. $sClassName;
			}

			$sPathClassName			=	( LIBRARY_PATH . $sDirectoryClassName .'.class.php' );

			// var_dump($sPathClassName);
			if ( file_exists( $sPathClassName ) ) {
				require_once( realpath( $sPathClassName ) );

			} 
			else {

				$sPathClassName			=	( LIBRARY_PATH . $sDirectoryClassName .'.php' );
				// var_dump($sPathClassName);
				if ( file_exists( $sPathClassName ) ) {
					require_once( realpath( $sPathClassName ) );
				}
			}
		} spl_autoload_register( 'fAutoLoader' );
	//---nano .gitignore

// On appel la bonne page
	require_once('./bootstrap.php');


