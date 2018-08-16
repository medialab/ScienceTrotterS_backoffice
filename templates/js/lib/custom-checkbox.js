// GESTION DES CHECKBOX
$(document).ready(function() {
	$(".cust-checkbox")
		// MISE A JOUR DE L'APPARENCE
		.on('checkbox::update', function(e, check) {
			var self = $(this);
			
			// Si initialisation on prend la valeur de l'input
			if (check == -1) {
				var inp = self.find('input[type="checkbox"]');
				check = self.hasClass('on');
				inp.prop('checked', check);
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

			// Récupération du status
			if (self.find('input[type="checkbox"]').prop('checked')) {
				disabled = true;
			}

			var container = tabSelector;
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