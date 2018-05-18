$(document).ready(function() {
	var lists = $('div.columnData');
	var timers = {};
	console.log("LISTS: ", lists);

	lists.scroll(function() {
		var list = $(this);
		var id = list.attr('id');

		if (typeof timers[id] !== 'undefined') {
			clearTimeout(timers[id]);
		}

		timers[id] = setTimeout(function() {
			console.log("scrolled");
		}, 75);
	})
})