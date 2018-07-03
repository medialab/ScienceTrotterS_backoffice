$(document).ready(function() {
	//var tabSelector = $(".tab-selector");

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
				//$("#trigger-"+target).click();
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
});