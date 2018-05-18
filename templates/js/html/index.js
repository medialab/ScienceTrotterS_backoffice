function fetchNext(list) {
	$.ajax({

	});
}

$(document).ready(function() {
	var lists = {};
	var timers = {};
	var spinners = {};

	$('div.columnData').scroll(function() {
		var list = $(this);
		var id = list.attr('id');

		if (typeof lists[id] === 'undefined') {
			lists[id] = {
				el: list
			}
		}
		else if(lists[id].timer){
			clearTimeout(lists[id].timer);
		}

		lists[id].timer = setTimeout(function() {
			if (typeof lists[id].spin === 'undefined') {
				lists[id].spin = list.find('.spinner');
			}

			var spin = lists[id].spin;
			if (spin.is(':visible') && $.scrollElementVisible(spin)) {
				console.log("SPINNER VISIBLE");
			}

		}, 75);
	})
})