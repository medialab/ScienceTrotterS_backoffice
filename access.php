<?php


// Les extensions controlées 
$aAccess = [];
$aRestrictExtension	= [
	'html',
	'admin'
];

// Les pages autorisées
	// aux VISITEURS 
		$aAccess["VISITOR"]	=	[
			'logout.html',
			'upload.html',
			'upload/cities.html',
			'connexion.html',
		];
	

	// ADMIN 
		$aAccess["ADMIN"]	=	[
			'index.html',
			'test.html',
			'edit.html',
			'edit/city.html'
		];

		$aAccess['HTML'] = &$aAccess['ADMIN'];

		$aAccess["ADMIN"]["redirection"] = "/connexion.html";
		$aAccess["ADMIN"]	=	array_merge( $aAccess["ADMIN"], $aAccess["VISITOR"] );

// Quel accès dispose l'utilisateur? 
	$aAccessUtilisateur				=	[];


if ( empty( $_SESSION["user"]["aut_access"] ) ){
	$sDroitUser					=	"VISITOR";
} else {
	$sDroitUser					=	$_SESSION["user"]["aut_access"];
}
if( !empty( $aAccess[$sDroitUser] ) ){
	$aAccessUtilisateur			=	$aAccess[$sDroitUser];
}
else{
	$_SESSION["user"]["aut_access"] = 'VISITOR';
	$aAccessUtilisateur			=	$aAccess['VISITOR'];
}


$smarty->assign( "aAccess", $aAccess );
$smarty->assign( "sDroitUser", $sDroitUser );
$smarty->assign( "aAccessUtilisateur", $aAccessUtilisateur );