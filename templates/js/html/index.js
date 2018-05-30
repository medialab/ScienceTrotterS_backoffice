// Load Automatique Des Listes
$(document).ready(function() {
	/* On alerte avant de supprimer */
	$("#cities").on('click', 'a.delete-btn', function(e) {
		if (!confirm("Êtes vous sûr de vouloir supprimer cette ville ?")) {
			e.preventDefault();
			return false;
		}
	});

	var lists = {};		// Taleau ID => [jquery el, timer, requete]

	$('div.columnData').scroll(function() {
		console.log("SCROLL");
		var list = $(this);
		var id = list.attr('id');

		if (typeof lists[id] === 'undefined') {	// Ajout de la liste si elle n'existe pas
			lists[id] = {
				el: list
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
						'cities', 1, 5, 

						// On Success
						function(result){
							// Récupération de la liste
							var ul = list.find('ul');
							var base = list.find('li.item').first();

							// Si aucun résultat on suprime le spinner
							if (!result.data.length) {
								lists[id].spin.hide();
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
						}
					);
				}
				else{
					console.log("CallingNextPage");
					lists[id].req.nextPage();
				}
			}

		}, 75);
	}).scroll();
})