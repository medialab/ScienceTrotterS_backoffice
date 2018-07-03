var curLang = null;
var tabSelector = null;

$(document).ready(function() {
	page = $("body");
	tabSelector = $(".tab-selector");
	curLang = tabSelector.attr('target');

	// Changement de Tab
	$(".tab-trigger").click(function(e) {
		var self = $(this);
		if (self.attr('disabled')) {
			return;
		}

		var container = self.parent();
		
		container.find('.tab-trigger').removeClass('on');
		self.addClass('on');
		
		var tabID = self.attr('target');
		$(".tab").removeClass('on');
		$("#tab-"+tabID).addClass('on');

		container.parent().attr('target', tabID);
	});

	// Selection automatique du Tab de la langue selectionnée
	page.on('custom::pageReady', function() {
		$("#trigger-"+curLang).click();
	});
});