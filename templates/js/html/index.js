$(document).ready(function() {
	var lists = $('div.columnData');
	var timers = {};
	var spinners = {};
	console.log("LISTS: ", lists);

	lists.scroll(function() {
		var list = $(this);
		var id = list.attr('id');

		if (typeof timers[id] !== 'undefined') {
			clearTimeout(timers[id]);
		}

		timers[id] = setTimeout(function() {
			if (typeof spinners[id] === 'undefined') {
				spinners[id] = list.find('.spinner');
			}

			var spin = spinners[id];
			if (spin.is(':visible') && $.scrollElementVisible(spin)) {
				console.log("SPINNER VISIBLE");
			}
			
			console.log("scrolled");
		}, 75);
	})
})