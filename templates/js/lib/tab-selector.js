var curLang = null;
var tabSelector = null;

$(document).ready(function() {
	page = $("body");
	var curTab = null;
	tabSelector = $(".tab-selector");
	curLang = tabSelector.attr('target');

	// Changement de Tab
	$(".tab-trigger").click(function(e) {
		var self = $(this);
		if (self.attr('disabled')) {
			return;
		}

		// VÉRIFICATION DES INPUTS / S'IL Y A UN CHANGEMLENT ON DEMANDE AU USER S'IL VEUT CHANGER DE LANGUE
		if (curTab) {
			var bSaved = true;
			curTab.find('input, select, textarea').each(function(i, e) {
				e = $(e);
				var val = e.val();
				var type = e.attr('type');

				var tag = e.prop('tagName');
				var def = e.attr('default');

				if (typeof def === 'undefined') {
					return true;
				}

				console.log(def, ' ||| ', val)

				switch (type) {
					case 'file':
						if (e.attr('updated')) {
							bSaved = false;
							console.log(e.attr('name'))
						}
						break;

					case 'checkbox':
						if (def != e.prop('checked')) {
							bSaved = false;
							console.log(e.attr('name'))
						}
						break;

					case 'textarea':
						if (def != e.text()) {
							bSaved = false;
							console.log(e.attr('name'))
						}
						break;

					default:
						if (tag === 'SELECT') {
						}

						if (val !== def) {
							bSaved = false;
							console.log(e.attr('name'))
						}
						break;
				}

				return bSaved;
			});

			if (!bSaved) {
				if (!confirm("Attention les modifications saisies n'ont pas été enregistrés, êtes-vous sûr de vouloir switcher de langue?")) {
					return false;
				}
			}
		}

		var container = self.parent();
		
		// CHANGEMENT DE TAB
		container.find('.tab-trigger').removeClass('on');
		self.addClass('on');
		
		var tabID = self.attr('target');
		$(".tab").removeClass('on');
		
		curTab = $("#tab-"+tabID)
		curTab.addClass('on');

		container.parent().attr('target', tabID);
	});

	// Selection automatique du Tab de la langue selectionnée
	page.on('custom::pageReady', function() {
		$("#trigger-"+curLang).click();
	});
});