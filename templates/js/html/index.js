// Load Automatique Des Listes
$(document).ready(function() {
	var emptyRow = null;
	var scrollList = $(".arboItem .columnData");

	function createRow(sModel, data, pId) {
		// Création d'une ligne
		var row = emptyRow.clone(true, true);
		row.attr('id', data.id);

		var l = data.force_lang;
		var title;
		if (l) {
			title = data.title[l];
		}
		else{
			if (typeof data.title.fr !== 'undefined') {
				title = data.title.fr;
			}
			else{
				title = data.title.en;
			}
		}
		row.attr('title', title);

		row.attr('parent', pId);
		
		row.find('label.itemLabel').text(title);
		
		if (!data.state) {
			row.addClass('disabled');
		}

		var flags = row.find('.flag-cont');
		flags.removeClass('en');
		flags.removeClass('fr');
		if (data.force_lang) {
			flags.addClass(data.force_lang);
		}
		
		row.find("a.edit-btn").attr('href', "/edit/"+sModel+"/"+data.id+".html");
		//row.find("a.delete-btn").attr('href', "/delete/"+sModel+"/"+data.id+".html");
		row.find("a.delete-btn").attr('target', data.id);

		if (data.force_lang) {
			row.find('flag-cont').addClass(data.force_lang);
		}

		return row;
	}

	// CHARGEMENT DES MODELS ENFANTS
	scrollList.find("li.item").click(function(e) {
		ApiMgr.setLang('fr');

		// RECUPERARTION DES INFORMATION DU MODEL
		var self = $(this);
		var pId = self.attr('id');


		var container = self.parents('.columnData');
		if (container.attr('id') === 'interests') {
			return;
		}

		self.parent().find('li').removeClass("active");
		self.addClass("active");

		// TYPE DE MODEL
		var type = container.attr('id');

		// TYPE DE MODEL ENFANT
		var childType = container.attr('child');

		var sModel = childType;
		if (sModel === 'cities') {
			sModel = 'city';
		}
		else if(sModel === 'interests') {
			sModel = 'interest';
		}


		// LISTE DES MODELS ENFANTS
		var childContainer = $("#"+childType+" ul.itemList");
		var by = container.attr('id') === 'cities' ? 'CityId' : 'ParcoursId';
		
		if (type === 'cities') {
			if (pId !== "no-city") { // Recherche par ville
				by = 'cityId';
			}
			else{ // Recherche Hors Ville
				by = "NoCity";
			}
		}
		else if(type === 'parcours') {
			if (pId !== "no-prcours") { // RECHERCHE PAR PARCOURS
				by = "parcoursId";
			}
			else{ // RECHERCHE HORS PARCOURS
				by = "NoParcours"; 
				pId = self.attr('parent'); // City Id
			}
		}

		// ON VIDE LA LISTE
		childContainer.empty();
		self.addClass("loading");

		ApiMgr.by({
			by: by,
			id: pId,
			table: childType,
			order: [childType+'.title', 'ASC'],
			columns: ['id', 'title', 'state'],

			success: function(aData) {
				// ON VIDE LA LISTE
				childContainer.empty();

				self.removeClass("loading");
				if (childType === "parcours") { // ON VIDE LA LISTE DES POINTS D INTEREST
					$("#interests ul.itemList").empty();
				}

				var row = null;
				var rows = [];
				if (aData.data.length) {
					var indexes = [];
					for(var i in aData.data) { // POUR CHAQU'UN DES RESULTATS
						row = createRow(sModel, aData.data[i], pId);
						indexes.push(i);

						if (childType === 'parcours') {  // SI ON LISTE PARCOURS
							rows.push(row);
							ApiMgr.by({
								by: 'parcours',
								id: aData.data[i].id,
								table: 'interests',
								columns: ['id', 'title', 'state'],
								order: ['interests.title'],

								success: function(aResult) {
									var r = rows.shift();
									var i = indexes.shift();
									if (!aResult.success || !aResult.data.length) {
										r.find('.itemLabel').append('<i class="ico-warning" title="Attention ce Parcours n\'est pas affichée dans l\'application car il ne possède aucun point d\'intérêt"></i>');
									}

									childContainer.append(r);
								},
								error: function(){
									var r = rows.shift();
									var i = indexes.shift();

									r.find('.itemLabel').append('<i class="ico-warning" title="Attention ce Parcours n\'est pas affichée dans l\'application car il ne possède aucun point d\'intérêt"></i>');

									childContainer.append(r);
								}
							});
						}
						else{
							childContainer.append(row);
						}

					}
				}
				else if(sModel === 'interest') { // SI AUCUNT POINT D'INTERET
					row = emptyRow.clone(false);
					row.attr('id', '');
					row.attr('parent', '');

					row.find('label.itemLabel').text("Aucun Résultat Trouvé !");
					row.addClass("item-notif");

					childContainer.append(row);
				}

				if (childType === 'parcours') {  // SI AUCUNT PARCOURS
					row = emptyRow.clone(true, true);
					row.attr('id', 'no-prcours');
					row.addClass('item-notif');
					row.attr('parent', pId);

					row.addClass("item-notif");
					row.find('label.itemLabel').text("Hors Parcours");
					
					childContainer.append(row);
				}
			},

			error: function(data) {
				self.removeClass("loading");
			}
		});
	});

	// SUPPRESSION D'UN MODEL
	scrollList.find("a.delete-btn").click(function(e) {		
		e.preventDefault();
		e.stopPropagation();
		
		var self = $(this);
		var oParent = self.parents(".item");
		var oCont = oParent.parents(".columnData");
		var sTitle = oParent.attr("title");
		var sType = oCont.attr('target');

		// MESSAGE DE CONFIRMATION
		var bCity = oCont.attr("id") === "cities";
		var msg = 'Êtes vous sûr de vouloir supprimer '+sType+': "'+oParent.attr('title')+'" ?';
		
		if (bCity) {
			msg += "\nAttention, les parcours et points d'intérêt liés à cette ville deviendront inaccessibles";
		}

		if (!confirm(msg)) {
			return false;
		}

		console.log('Deleteing: ',self.attr('target'));
		ApiMgr.delete(oCont.attr('id'), self.attr('target'), function(data) {
			msg = ' a été supprimé'+ ((bCity) ? 'e' : '');
			msg += ' avec succès.';

			Notify.success(sTitle, msg);
			oParent.remove();
		}, function(data, error) {
			if (error.status === 404) {
				msg = 'est introuvable.'
			}
			else{
				msg = 'Une Erreur s\'est produite lors de la suppresion.';
			}

			Notify.error(sTitle, msg)
			//Notify.error(sType.ucfirst(), msg);
		});
	});

	// ACTIVATION MODEL
	scrollList.find("a.preview-btn").click(function(e) {
		e.stopPropagation();

		var self = $(this);
		var oParent = self.parents(".item");
		var oCont = oParent.parents(".columnData");
		var sTitle = oParent.attr("title");


		var bEnable = oParent.hasClass('disabled');
		var sEnable = bEnable ? "activer" : "désactiver";

		var bCity = oCont.attr("id") === "cities";
		
		// MESSAGE DE CONFIRMATION
		var msg = "Êtes vous sûr de vouloir "+sEnable+" "+oCont.attr('target')+': "'+oParent.attr('title')+'" ?';
		if (bCity && !bEnable) {
			msg += "\nAttention, les parcours et points d'intérêt liés à cette ville deviendront inaccessibles";
		}

		if (!confirm(msg)) {
			e.preventDefault();
			return false;
		}

		// APPEL DE L'API EN AJAX
		var sModel = oCont.attr("id");
		aUpdateData = {
			id: oParent.attr("id"),
			data: {
				state: bEnable
			}
		}

		ApiMgr.update(sModel, aUpdateData, function(result){
			var aModel = result.data;

			if (!aModel.state) {
				oParent.addClass('disabled');
			}
			else {
				oParent.removeClass('disabled');
			}

			//console.log(aModel);
			if (result.success) {
				if (result.message === null || !result.message.length) {
					Notify.success(aModel.title, 'Mise à jour réussie.');
				}
				else{
					Notify.warning(aModel.title, result.message);
				}

				if (bEnable) {
					oParent.removeClass("disable");
				}
				else{
					oParent.addClass("disable");
				}
			}
			else{
				Notify.error(aModel.title, result.message);
			}
		}, function(error) {
		});
	});

	// MISE EN MEMOIRE D'UNE LIGNE VIDE
	emptyRow = scrollList.first().find('li').first().clone(true, true);
	emptyRow.attr('id', '');
	emptyRow.attr('title', '');
	emptyRow.attr('parent', '');
	emptyRow.removeClass('disabled');
	emptyRow.removeClass('warning');

	var flagCont = emptyRow.find("flag-cont");
	flagCont.removeClass('fr');
	flagCont.removeClass('en');

	var lists = {};		// Taleau ID => [jquery el, timer, requete]
	$('div.columnData').scroll(function() {
		var list = $(this);
		var id = list.attr('id');

		if (typeof lists[id] === 'undefined') {	// Ajout de la liste si elle n'existe pas
			lists[id] = {
				el: list,
				limit: parseInt(list.attr('limit'))
			}
		}
		else if(lists[id].timer){	// Remise à Zero Tu Timer
			clearTimeout(lists[id].timer);
		}

		// Timer Pour eviter le flood dû à l'event Scroll
		lists[id].timer = setTimeout(function() {
			// Récupération du spinner
			if (typeof lists[id].spin === 'undefined') {
				lists[id].spin = list.find('.spinner');
			}

			var spin = lists[id].spin;

			// Si le spinner est visible, on effectue une Rêquête
			if (spin.is(':visible') && $.scrollElementVisible(spin)) {

				// Création de la requête faite pour cette liste
				if (typeof lists[id].req === 'undefined') {

					// Appel à L'api 
					lists[id].req = ApiMgr.list(
						'cities', 1, lists[id].limit, 

						// On Success
						function(result){
							// Récupération de la liste
							var ul = list.find('ul');
							var base = list.find('li.item').first();

							// Si aucun résultat on suprime le spinner
							if (!result.data.length) {
								lists[id].spin.parents('.item').hide();
								return;
							}

							// On Ajoute les Résultats dans la liste
							$.each(result.data, function(i,e) {
								//console.log("=== "+i+" ===", e);

								var row = base.clone(true, true);
								row.find('.itemLabel').text(e.label);
								row.find('.delete-btn').attr('href', '/delete/city/'+e.id+'.html');
								row.find('.edit-btn').attr('href', '/edit/city/'+e.id+'.html');
								row.find('.preview-btn').attr('href', '/preview/city/'+e.id+'.html');

								ul.append(row);
								ul.append(spin.parents('.item'));
							});
						}, 

						// On Error
						function(result){
							//console.log("ERROR: ", result)
						},

						['id', 'title']
					);
				}
				else{
					//console.log("CallingNextPage");
					lists[id].req.nextPage();
				}
			}

		}, 75);
	}).scroll();
});