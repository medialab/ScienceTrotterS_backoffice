<?php

// Gestion des erreurs 
	ini_set( 'display_errors', true );
	error_reporting( E_ALL | E_NOTICE );
	ini_set('xdebug.var_display_max_data', -1);
	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);

	define( 'LIBRARY_PATH',        './lib/' );
	define( 'TEMPLATE_PATH',        './templates/' );

	define("API_URL", 'https://api-sts-stable.actu.com');



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
				require( realpath( $sPathClassName ) );

			} 
			else {

				$sPathClassName			=	( LIBRARY_PATH . $sDirectoryClassName .'.php' );
				// var_dump($sPathClassName);
				if ( file_exists( $sPathClassName ) ) {
					require( realpath( $sPathClassName ) );
				}
			}
		} spl_autoload_register( 'fAutoLoader' );
	//---nano .gitignore

// On appel la bonne page
	require_once('./bootstrap.php');


