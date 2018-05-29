<?php 

// Titre du formulaire 
	if ( empty( $id ) ) {
		$smarty->assign('sCreation', 'Création d\'une ville');
	} else {
		$smarty->assign('sCreation', 'Mise à jour d\'une ville');
	}
