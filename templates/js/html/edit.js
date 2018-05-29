$(document).ready(function() {
	
	$(".cust-checkbox")
	.on('checkbox::update', function(e, check) {
		console.log("UPDATING CHECKBOX", $(this));
		var self = $(this);
		
		if (check == -1) {
			var inp = self.find('input[type="checkbox"]');
			check = inp.prop('checked');
		}

		if (check) {
			self.addClass('on');
		}
		else{
			self.removeClass('on');
		}
	})
	.click(function(e) {
		console.log("CKLICKED");
		
		e.preventDefault();
		e.stopPropagation();

		var self = $(this);
		var inp = self.find('input[type="checkbox"]');
		console.log("inp", inp);

		var check = !inp.prop('checked');
		inp.prop('checked', check);

		console.log("New State", check);
		self.trigger('checkbox::update', check);
	})
	.trigger("checkbox::update");
	;
})