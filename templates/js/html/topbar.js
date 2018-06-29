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
		// console.log("################################################");
		// console.log("Adding Résult For: "+model);

		for (var i in aData) {
			// console.log("================================================");
			var data = aData[i];
			// console.log("type: "+model);
			// console.log("Adding: "+data.title);
			
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

			// console.log("title: "+data.title);
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
			// console.log("#### Model: " + model);
			// console.log("Class: " + '.tree-'+model);
			// console.log("container: ", row.find('.tree-'+model));

			// console.log("Adding to List");
			list.append(row);
		}
	}

	searchBar.keyup(function(e) {
		e.keyCode = e.keyCode || e.witch || e.charCode;

		console.log("KeyCode: "+ e.keyCode);
		// Si la touche est Echap On Cache la recherche
		if (e.keyCode == 27) {
			console.log("Hiding And Returning");
			hideSearch();
			return false;
		}

		var self = $(this);
		var query = self.val();
		//console.log("Query: "+query);
		
		query = query.trim();
		if (!query.length || (query.length < 3 && e.keyCode != 13)) {
			//console.log("Query Too Short Skipping ("+query.length+")");
			return;
		}

		if (timer) {
			//console.log("Resetting Timer");
			clearTimeout(timer);
			timer = false;
		}

		list.empty();
		//console.log("Setting Timer");
		timer = setTimeout(function(){
			var i;
			timer = false;
			showOverLay();

			//console.log("Running Query");

			var columns = ['id', 'title'];
			for (i = 0; i < tables.length; i++) {
				
				//console.log("Launching Table #"+i+": "+tables[i]);
				
				switch(i) {
					case 1:
						columns.push('cities_id');
						break;

					case 2:
						columns.push('parcours_id');
						break;
				}

				//console.log("Columns: ", columns.slice());

				ApiMgr.search(tables[i], query, 0, 0, 
					function(aData) {
						addResult(aData.data, tables[i]);
						console.log("#i: "+i +" / "+tables.length -1);

						if (i == tables.length -1) {
							console.log("Last Table");
							console.log(list.find('li'));
							
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
						console.error("Fail To Search Data", error);
					}, 
					columns.slice(), true
				);
			}

			i = 0;
		}, 500);
	});


});