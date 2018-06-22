// Load Automatique Des Listes
$(document).ready(function() {
	/* On alerte avant de supprimer */
	console.log($(".arboItem .columnData"));

	$(".arboItem .columnData a.delete-btn").on('click', function(e) {		
		var self = $(this);
		var oParent = self.parents(".item");
		var oCont = oParent.parents(".columnData");
		var sTitle = oParent.attr("title");
		
		if (!confirm("Êtes vous sûr de vouloir supprimer "+oCont.attr('target')+" "+oParent.attr('title')+" ?")) {
			e.preventDefault();
			return false;
		}

		return false;
	});

	$(".arboItem .columnData a.preview-btn").on('click', function(e) {
		var self = $(this);
		var oParent = self.parents(".item");
		var oCont = oParent.parents(".columnData");
		var sTitle = oParent.attr("title");


		var bEnable = oParent.hasClass('disabled');
		var sEnable = bEnable ? "activer" : "déactiver";

		if (!confirm("Êtes vous sûr de vouloir "+sEnable+" "+oCont.attr('target')+" "+oParent.attr('title')+" ?")) {
			e.preventDefault();
			return false;
		}

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
				oParent.removelass('disabled');
			}

			console.log(aModel);
			if (result.success) {
				if (result.message === null || !result.message.length) {
					Notify.success(aModel.title, 'Mise à jour réussie.');
				}
				else{
					Notify.warning(aModel.title, result.message);
				}
			}
			else{
				Notify.error(aModel.title, result.message);
			}
		}, function(error) {
			console.error("Modale Update Faild: ", error);

		});
	});

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
								console.log("=== "+i+" ===", e);

								var row = base.clone(true);
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
							console.log("ERROR: ", result)
						},

						['id', 'title']
					);
				}
				else{
					console.log("CallingNextPage");
					lists[id].req.nextPage();
				}
			}

		}, 75);
	}).scroll();
});