// Load Automatique Des Listes
$(document).ready(function() {
	var emptyRow = null;
	var scrollList = $(".arboItem .columnData");

	scrollList.find("li.item").click(function(e) {
		ApiMgr.setLang('fr');

		var self = $(this);
		var pId = self.attr('id');

		var container = self.parents('.columnData');
		if (container.attr('id') === 'interests') {
			return;
		}

		self.parent().find('li').removeClass("active");
		self.addClass("active");

		var type = container.attr('id');
		//console.log("Type: "+type);

		var childType = container.attr('child');
		//console.log("ChildType: "+childType);

		var sModel = childType;
		if (sModel === 'cities') {
			sModel = 'city';
		}
		else if(sModel === 'interests') {
			sModel = 'interest';
		}

		var childContainer = $("#"+childType+" ul.itemList");
		var by = container.attr('id') === 'cities' ? 'CityId' : 'ParcoursId';
		
		if (type === 'cities') {
			if (pId !== "no-city") {
				by = 'cityId';
			}
			else{
				by = "NoCity";
			}
		}
		else if(type === 'parcours') {
			if (pId !== "no-prcours") {
				by = "parcoursId";
			}
			else{
				by = "NoParcours";
				pId = self.attr('parent'); // City Id
			}
		}

		childContainer.empty();
		self.addClass("loading");

		ApiMgr.by({
			by: by,
			id: pId,
			table: childType,
			order: [childType+'.title', 'ASC'],
			columns: ['id', 'title', 'state'],

			success: function(aData) {
				self.removeClass("loading");
				if (childType === "parcours") {
					$("#interests ul.itemList").empty();
				}

				if (aData.data.length) {
					for(var i in aData.data) {
						var data = aData.data[i];

						//console.log("Row: ", data);
						//console.log("ADDING: #"+ data.id +" => "+data.title);

						var row = emptyRow.clone(true, true);
						row.attr('id', data.id);
						row.attr('title', data.title);

						row.attr('parent', pId);
						
						row.find('label.itemLabel').text(data.title);
						
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
						row.find("a.delete-btn").attr('href', "/delete/"+sModel+"/"+data.id+".html");

						if (data.force_lang) {
							row.find('flag-cont').addClass(data.force_lang);
						}

						childContainer.append(row);
					}
				}
				else if(sModel === 'interest') {
					var row = emptyRow.clone(false);
					row.attr('id', '');
					row.attr('parent', '');

					row.find('label.itemLabel').text("Aucun Résultat Trouvé !");
					row.addClass("item-notif");

					childContainer.append(row);
				}

				if (childType === 'parcours') {
					var row = emptyRow.clone(true, true);
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

	scrollList.find("a.delete-btn").click(function(e) {		
		e.preventDefault();
		e.stopPropagation();
		
		var self = $(this);
		var oParent = self.parents(".item");
		var oCont = oParent.parents(".columnData");
		var sTitle = oParent.attr("title");
		console.log("Deleting: "+oParent.attr('title'));
		
		var bCity = oCont.attr("id") === "cities";
		var msg = 'Êtes vous sûr de vouloir supprimer '+oCont.attr('target')+': "'+oParent.attr('title')+'" ?';
		
		if (bCity) {
			msg += "\nAttention, les parcours et points d'intérêt liés à cette ville deviendront inaccessibles";
		}

		//console.log("Msg: "+msg);

		if (!confirm(msg)) {
			e.preventDefault();
			return false;
		}

		return false;
	});

	scrollList.find("a.preview-btn").click(function(e) {
		e.stopPropagation();

		var self = $(this);
		var oParent = self.parents(".item");
		var oCont = oParent.parents(".columnData");
		var sTitle = oParent.attr("title");


		var bEnable = oParent.hasClass('disabled');
		var sEnable = bEnable ? "activer" : "désactiver";

		var bCity = oCont.attr("id") === "cities";
		var msg = "Êtes vous sûr de vouloir "+sEnable+" "+oCont.attr('target')+': "'+oParent.attr('title')+'" ?';
		
		if (bCity) {
			msg += "\nAttention, les parcours et points d'intérêt liés à cette ville deviendront inaccessibles";
		}

		if (!confirm(msg)) {
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
			}
			else{
				Notify.error(aModel.title, result.message);
			}
		}, function(error) {
			//console.error("Modale Update Faild: ", error);

		});
	});

	emptyRow = scrollList.first().find('li').first().clone(true, true);
	emptyRow.attr('id', '');
	emptyRow.attr('title', '');
	emptyRow.attr('parent', '');
	emptyRow.removeClass('disabled');

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