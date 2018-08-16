$(document).ready(function() {
	// GESTION DE LA GALLERIE
	var aGalleryBtn = $("#gallery-container .btnInputFileName");
	var aGalleryInp = $("#gallery-container input[type='file']");

	aGalleryBtn.click(function(e) {
		e.preventDefault();
		e.stopPropagation();

		var self = $(this);
		var index = parseInt(self.attr('index'));
		var cont = self.parents("#gallery-container");

		var oInputs = cont.find("input[type='file']");
		var oSelfInp = $(oInputs.get(index));

		// SI UNE IMAGE EXISTE POUR CE BOUTON ON OUVRE KE SELECTEUR D'IMAGE
		if (oSelfInp.prop('files').length || oSelfInp.hasClass('hasFile')) {
			oSelfInp.click();
			return;
		}

		// SI NON ON RECHERCHE LE PREMIER BOUTON SANS IMAGE
		var oFound = false;
		oInputs.each(function(i, e) {
			var oInp = $(e);

			if (!oInp.hasClass('hasFile') && !oInp.prop('files').length) {
				oFound = oInp;
				return false;
			}
		});
	
		oFound.click();
	});


	// GESTION DES SELECTS VILLE / PARCOURS
	var oVilleList = $("select[id^='ville-']");
	var oParcoursList = $("select[id^='parcours-']");
	oParcoursList.find('.notfound').hide();

	// AU CHANGEMENT D'UNE VILLE
	oVilleList.change(function() {
		var self = $(this);
		var id = self.val();
		var parcs = self.parents('form').find('select#parcours');

		// MISE à JOUR DU SELECT DE L'AUTRE LANGUE
		oVilleList.not(self).val(self.val());
		
		// Si AUCUNE VILLE CHOISIE ON AFFICHE TOUT LES PARCOURS
		if (typeof id === 'undefined' || !id || !id.length) {
			oParcoursList.find('optgroup').show();
			oParcoursList.find('.notfound').hide();
			return;
		}

		// ON MASQUE TOUT LES PARCOURS N'APPARTENANT PAS à LA VILLE
		oParcoursList.find('optgroup[target!='+id+']').hide();
		var oParcGroup = oParcoursList.find('optgroup[target='+id+']');
		oParcGroup.show();

		if (!oParcoursList.find('option:selected').is(':visible')) {
			oParcoursList.val('');
		}

		if (self.is(':visible')) {
			if (!oParcGroup.length) {
				oParcoursList.find('.notfound').show();
			}
			else{
				oParcoursList.find('.notfound').hide();
			}
		}
	})
	.change()
	;

	// AU CHANGEMENT D'UN PARCOURS
	oParcoursList.change(function() {
		var self = $(this);

		// ON MET A JOUR LE SELECT DE l'AUTRE LANGUE
		oParcoursList.not(self).val(self.val());
		if (oVilleList.val().length) {
			return;
		}

		var cities = self.parents('form').find('select#ville');
		var id = '';
		var opt = self.find('option:selected');

		// ON SELECTIONNE LA VILLE CORRESPONDANTE
		if (opt.length) {
			id = opt.parent().attr('target');
		}
		
		oVilleList.val(id);
		oVilleList.change();
	});

	// PRE-SELECTION DES SELECTS
	oParcoursList.each(function(i, e) {
		var list = $(e);
		list.val(list.find('option[selected]').val());
	})

	var id = $("input[name=id]").first().val();
	countListen('interests', id);
});
