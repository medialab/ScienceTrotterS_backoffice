$(document).ready(function() {
	
	$(".cust-checkbox")
	.on('checkbox::update', function(e, check) {
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
		
		e.preventDefault();
		e.stopPropagation();

		var self = $(this);
		var inp = self.find('input[type="checkbox"]');

		var check = !inp.prop('checked');
		inp.prop('checked', check);

		self.trigger('checkbox::update', check);
	})
	.trigger("checkbox::update");
	;

	$(".tab-trigger").click(function(e) {
		var self = $(this);
		var container = self.parent();

		console.log("Parent: ", container);


		container.find('.tab-trigger').removeClass('on');
		
		self.addClass('on');
		var tabID = self.attr('target');

		console.log("Test: ", $(".tab").is("#"+tabID));
		$(".tab").removeClass('on').is("#"+tabID).addClass('on');
		///var tab = $("#"+tabID);

	});
})