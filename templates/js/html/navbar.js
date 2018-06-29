$(document).ready(function() {
	var timer = false;
	var searchBar = $("#inputSearchBar");
	var tables = ['cities', 'parcours', 'interests'];

	console.log("Key Input");

	searchBar.keyup(function() {
		var self = $(this);
		var query = self.val();
		console.log("Query: "+query);
		
		if (query.length < 3) {
			console.log("Query Too Short Skipping ("+query.length+")");
			return;
		}

		if (timer) {
			console.log("Resetting Timer");
			clearTimeout(timer);
			timer = false;
		}

		console.log("Setting Timer");
		timer = setTimeout(function(){
			var i;
			timer = false;
			console.log("Running Query");

			for (i = 0; i < tables.length; i++) {
				console.log("Launching Table: "+tables[i]);

				ApiMgr.search(tables[i], query, 0, 0, function(aData) {

					console.log("Tables: ", tables);
					console.log("Result Table: "+ tables[i]);
					console.log("Search Success !!!", aData);
					i++;
				});
			}

			i = 0;
		}, 500);
	});
});