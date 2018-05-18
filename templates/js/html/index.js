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

				if (typeof lists[id].req === 'undefined') {
					lists[id].req = ApiMgr.list(
						'cities', 0, 5, 
						function(result){
							var ul = list.find('ul');
							var base = list.find('li.item').first();

							if (!result.data.length) {
								console.log("Spinng Spinner");
								lists[id].spin.hide();
							}
							else{
								$.each(result.data, function(i,e) {
									console.log("=== "+i+" ===", e);

									var row = base.clone();
									row.find('.itemLabel').text(e.label);
									ul.append(row);
									ul.append(spin.parents('.item'));
								});
							}
						}, 
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
	})
})