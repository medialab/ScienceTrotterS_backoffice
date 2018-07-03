$(document).ready(function() {
	var timer = false;
	var topBar = $(".navBar .searchBar");
	var searchBar = $("#inputSearchBar");
	var tables = ['cities', 'parcours', 'interests'];

	//console.log("Key Input");

	var overlay = false;

	var container = $("#search-container");
	var list = container.find('ul');
	
	var tmpRow = list.find('li');
	var emptyRow = tmpRow.clone();

	tmpRow.remove();

	function showSearch() {
		showOverLay();
		topBar.removeClass('loading');
		container.addClass("on");
	}

	function hideSearch() {
		if (overlay) {
			overlay.hide();
		}

		container.removeClass("on");
	}

	function showOverLay() {		
		if (!overlay) {
			overlay = $("<div></div>");
			overlay.attr("id", "search-overlay");
			
			overlay.click(function() {
				hideSearch();
			});

			$("body").append(overlay);
		}
		
		topBar.addClass('loading');
		overlay.show();
	}

	function addResult(aData, model) {
		for (var i in aData) {
			var data = aData[i];

			var row = tmpRow.clone();
			var modelUrl = model;

			switch (model) {
				case "cities":
					modelUrl = "city";
					break;

				case "interests":
					modelUrl = "interest";
					break;
			}

			row.find("a").attr('href', '/edit/'+modelUrl+'/'+data.id+'.html');

			row.addClass(model);
			row.find('.title').text(data.title);

			if (typeof data.city !== "undefined" && data.city) {
				console.log("Parent City: "+data.city.title);
				row.find('.tree-cities').html(data.city.title);
			}
			else{
				console.log("Parent No City: ");
				row.find('.tree-cities').html("<i>Sans Ville</i>");
			}

			if (typeof data.parcours !== "undefined" && data.parcours) {
				console.log("Parent Parcours: "+data.parcours.title);
				row.find('.tree-parcours').html(data.parcours.title);
			}
			else{
				console.log("Parent No Parcours: ");
				row.find('.tree-parcours').html("<i>Hors Parcours</i>");
			}

			row.find('.tree-'+model).text(data.title);

			list.append(row);
		}
	}

	searchBar.keyup(function(e) {
		e.keyCode = e.keyCode || e.witch || e.charCode;

		// Si la touche est Echap On Cache la recherche
		if (e.keyCode == 27) {
			console.log("Hiding And Returning");
			hideSearch();
			return false;
		}

		var self = $(this);
		var query = self.val();
		
		if (!query.length || (query.length < 3 && e.keyCode != 13)) {
			return;
		}

		if (timer) {
			clearTimeout(timer);
			timer = false;
		}

		list.empty();

		timer = setTimeout(function(){
			var i;
			timer = false;
			showOverLay();

			var columns = ['id', 'title'];
			for (i = 0; i < tables.length; i++) {
				
				switch(i) {
					case 1:
						columns.push('cities_id');
						break;

					case 2:
						columns.push('parcours_id');
						break;
				}

				ApiMgr.search(tables[i], query, 0, 0, 
					function(aData) {
						addResult(aData.data, tables[i]);

						if (i == tables.length -1) {
							
							if (!list.find('li').length) {
								var row = emptyRow.clone();
								row.html("Aucun résultat trouvé.");
								row.appendTo(list);
							}

							showSearch();
						}
						i++;
					}, 
					function(error) {
						hideSearch();
					}, 
					columns.slice(), true
				);
			}

			i = 0;
		}, 500);
	});
});