$(document).ready(function() {
	// Auto-Correction des champs géoloc
	$('.geo-input').keyup(function() {
		var self = $(this);

		var val = self.val();
		val = val.replace(',', '.');
		val = val.replace(/[^0-9\.\-]+/, '');

		self.val(val);
	});

});
