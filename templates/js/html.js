$.scrollElementVisible = function (el) {
	var list = el.parent();
	var listFound = false;

	while(true) {
		if (!list.length) {
			return false;
		}
		else if (list.css('overflow') === 'scroll') {
			break;
		}

		list = list.parent():
	}

	var docViewTop = list.scrollTop();
    var docViewBottom = docViewTop + list.height();

    var elemTop = el.offset().top;
    var elemBottom = elemTop + el.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}