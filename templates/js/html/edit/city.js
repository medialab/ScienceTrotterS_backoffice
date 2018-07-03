$(document).ready(function() {
	$("form").submit(function(e) {
		var self = $(this);

		if (!self.attr('ready')) {
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
		
		var inp = self.find('.boolean input');

		var cur = inp.attr('default');
		var value = inp.prop('checked');

		if (cur && !value) {
			var resp = confirm("Attention en désactivant cette ville, les parcours et points d'intérêt liés deviendront inaccessibles");
			
			if (!resp) {
				e.preventDefault();
				e.stopPropagation();
				return false;
			}
		}

		self.attr('ready', null);
	})
});