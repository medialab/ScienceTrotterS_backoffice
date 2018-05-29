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

		console.log("Calling UPDATE");
		self.trigger('checkbox::update', check);
	})
	.trigger("checkbox::update");
	;

	$(".tab-trigger").click(function(e) {
		var self = $(this);
		if (self.prop('disabled')) {
			return;
		}

		var container = self.parent();
		
		container.find('.tab-trigger').removeClass('on');
		self.addClass('on');
		
		var tabID = self.attr('target');
		$(".tab").removeClass('on');
		$("#"+tabID).addClass('on');
	});

	$(".tab-selector .cust-checkbox").on('checkbox::update', function() {
		console.log("TEST UPDATE", $(this));
		var self = $(this);
		var disabled = false;
		
		console.log('input: ', self.find('input[type="checkbox"]'));
		console.log('Test: ', self.find('input[type="checkbox"]').prop('checked'));

		if (self.find('input[type="checkbox"]').prop('checked')) {
			console.log("IS CHECKED");
			$("#trigger-fr").click();
			disabled = true;
		}
		
		console.log("Disable: ", disabled);
		self.parents('.tab-selector').find('.tab-trigger').not('#trigger-fr').prop('disabled', disabled);
	});
})