$(document).ready(function() {
	
	$(".cust-checkbox").click(function(e) {
		e.preventDefault();
		e.stopPropagation();

		var self = $(this);
		var inp = self.find('input[type="checkbox"]');
		
		var check = !inp.prop('checked');
		inp.prop('checked', check);

		self.trigger('checkbox::update')
	})
	.on('checkbox::update', function(e) {
		console.log("UPDATING CHECKBOX", $(this));
		var self = $(this);
		var inp = self.find('input[type="checkbox"]');
		
		var check = inp.prop('checked');

		if (check) {
			self.addClass('on');
		}
		else{
			self.removeClass('on');
		}
	});
	;
})