// GESTION DES SELECT DE COULEUR
$(document).ready(function() {
	var timer = false;
	// Le Select
	var aColorSelectors = $(".cust-color-selector");

	// On transmet le click de l'input au custom select
	aColorSelectors.parents('.box').find('label').click(function(e) {
		var self = $(this);
		self.parent().find('.cust-color-selector').click();

		e.preventDefault();
		e.stopPropagation();
		return false;
	})

	// On ouvre le select
	aColorSelectors.click(function() {
		var self = $(this);

		if (self.hasClass('on')) {
			return;
		}

		self.addClass("on");

		var inp = self.find('input');
		if (!inp.is(':focus')) {
			inp.focus();
		}
	});

	// Au Click D'une couleur On Met à jour l'affichage et l'input
	aColorSelectors.find('.option-container .sel-opt').click(function(e) {
		e.preventDefault();
		e.stopPropagation();

		var option = $(this);
		var selector = option.parents('.cust-color-selector');
		var input = selector.find('input');
		
		var name = option.text().trim();
		var color = option.attr('value');

		var currentOption = selector.find('.opt-selected');
		
		currentOption.find('.opt-text').text(name);
		currentOption.find('.opt-color').css("background-color", color);

		input.val(color);

		return false;
	});
	
	// Pré-selection de la couleur
	aColorSelectors.each(function(i, e) {
		var selector = $(e);
		var input = selector.find('input');

		if (input.val().length) {
			var opt = selector.find('.option-container .sel-opt[value="'+input.val()+'"]');
			opt.click();
		}
	});

	// On Masque le select
	aColorSelectors.on('blur', 'input', function(e) {
		var self = $(this);
		if (timer) {
			clearTimeout(timer);
		}

		timer = setTimeout(function() {
			self.parents('.cust-color-selector').removeClass('on');
		}, 200)
	});
	
	/*
	aColorSelectors.on('focus', 'input', function(e) {
		//console.log("Input Is Focus !!!");
	});
	*/
})