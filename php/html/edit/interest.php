<?php 

// Titre du formulaire 
	if ( empty( $id ) ) {
		$smarty->assign('sCreation', 'Création d\'un point d\'intérêt');
	} else {
		$smarty->assign('sCreation', 'Mise à jour d\'un point d\'intérêt');
	}
