function generateColorSelector(el) {
	el.hide();
	var selector = $('<div></div>');
	selector.addClass('cust-color-selector');
}

$(document).ready(function() {
	var tabSelector = $(".tab-selector")	
	var curLang = tabSelector.attr('target');

	$(".cust-checkbox")
		// MISE A JOUR DE L'APPARENCE
		.on('checkbox::update', function(e, check) {
			var self = $(this);
			
			if (check == -1) {
				var inp = self.find('input[type="checkbox"]');
				check = self.hasClass('on')// inp.prop('checked');
				inp.prop('checked', check);

				/*if (check) {
					$('.tab-selector').find()
				}*/
			}

			if (check) {
				self.addClass('on');
			}
			else{
				self.removeClass('on');
			}
		})
		// MISE A JOUR DES TABS
		.on('checkbox::update', function() {
			var self = $(this);
			var disabled = false;
			
			var target = self.parents('.lang-only').attr('target');

			// On Focus le tab francais
			if (self.find('input[type="checkbox"]').prop('checked')) {
				$("#trigger-"+target).click();
				disabled = true;
			}

			var container = tabSelector //self.parents('.tab-selector');			
			container.attr('target', target);

			// On Dé/Ré-Active les tabs
			var otherTabs = container.find('.tab-trigger').not('#trigger-'+target);

			if (disabled) {
				otherTabs.attr('disabled', disabled);
			}
			else{
				otherTabs.removeAttr('disabled');
			}
		})
		// MISE A JOUR DE L'INPUT
		.click(function(e) {
			e.preventDefault();
			e.stopPropagation();

			var self = $(this);
			var inp = self.find('input[type="checkbox"]');

			var check = !inp.prop('checked');
			inp.prop('checked', check);

			self.trigger('checkbox::update', check);
		})
	
		// On Appel la mise à jour initialle
		.trigger("checkbox::update", -1);
	;

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
	$("#trigger-"+curLang).click();

	// à l'envois du formulaire on ajoute la valeur du dorce lang
	$('#content form').submit(function(e) {
		var self = $(this);
		
		if (!self.find('input.lang-check').length) {
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

	});


	var aColorSelectors = $(".cust-color-selector");
	aColorSelectors.click(function() {
		var self = $(this);
		self.addClass("on");
		self.find('input').focus();
	});

	//console.log(aColorSelectors.find('.option-container .sel-opt'));

	aColorSelectors.find('.option-container .sel-opt').click(function(e) {
		//console.log("TEEEEEST");
		e.preventDefault();
		e.stopPropagation();

		var option = $(this);
		var selector = option.parents('.cust-color-selector');
		var input = selector.find('input');
		
		//console.log("Current Selector: ", selector);
		//console.log("Input: ", input);
		//console.log("Clicked Option: ", option);

		var name = option.text().trim();
		var color = option.attr('value');
		//console.log("Value: "+name+" => "+color);

		var currentOption = selector.find('.opt-selected');
		//console.log("Current", currentOption);
		
		currentOption.find('.opt-text').text(name);
		currentOption.find('.opt-color').css("background-color", color);

		input.val(color);

		//input.blur();
		return false;
	});
	
	aColorSelectors.each(function(i, e) {
		var selector = $(e);
		var input = selector.find('input');
		//console.log(selector, input);

		//console.log("Value", input.val());
		if (input.val().length) {
			var opt = selector.find('.option-container .sel-opt[value="'+input.val()+'"]');
			//console.log(opt)
			opt.click();
		}

		//input.trigger('cust-blur');
		input.blur();
	});

	/*aColorSelectors.find('input').on('cust-blur', function(e) {
		console.log("BLUR !!!!!!")
	});*/

	aColorSelectors.on('blur', 'input', function(e) {
		var self = $(this);
		setTimeout(function() {
			self.parents('.cust-color-selector').removeClass('on');
		}, 200)
	});

	// Auto-Correction des champs géoloc
	$('.geo-input').keyup(function() {
		var self = $(this);

		var val = self.val();
		val = val.replace(',', '.');
		val = val.replace(/[^0-9\.]+/, '');

		self.val(val);
	});

	$("input[target='img']").change(function() {
		var self = $(this);

		var oDisplay = self.parent().find('img');
		console.log("DISPLAY", oDisplay);
		
		if (!this.files || !this.files[0]) {
			oDisplay.attr('src', '/media/image/interface/icons/icon_photo.svg');
			console.log("updating SRC", oDisplay);
			return;
		}

		var oReader = new FileReader();

		oReader.onload = function(e) {
			oDisplay.attr('src', e.target.result);
		}

		oReader.readAsDataURL(this.files[0]);
	});
});
