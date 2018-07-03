$(document).ready(function() {
	/*var tabSelector = $(".tab-selector");
	var curLang = tabSelector.attr('target');*/

	// à l'envois du formulaire on ajoute la valeur du dorce lang
	$('#content form').submit(function(e) {
		var self = $(this);
		
		if (!self.find('input.lang-check').length) {
			console.log("SETTING LANG !!!");
			e.preventDefault();
			e.stopPropagation();

			var langTaget = tabSelector.attr('target');

			var inp = tabSelector.find('.lang-only[target="'+langTaget+'"] input');
			if (!inp.prop('checked')) {
				inp.val('');
			}

			inp.hide().appendTo(self);

			self.submit();
			return false;
		}
		
		console.log("Adding Ready !!!");
		self.attr('ready', true);
	});

	page.trigger('custom::pageReady');
});
