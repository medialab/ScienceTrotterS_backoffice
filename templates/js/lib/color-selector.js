$(document).ready(function() {
	var timer = false;
	var aColorSelectors = $(".cust-color-selector");

	aColorSelectors.parents('.box').find('label').click(function(e) {
		var self = $(this);
		self.parent().find('.cust-color-selector').click();

		e.preventDefault();
		e.stopPropagation();
		return false;
	})

	aColorSelectors.click(function() {
		var self = $(this);

		if (self.hasClass('on')) {
			//console.log("Select Already Enabled !!");
			return;
		}

		//console.log("Enable Select !!");
		//console.log("Add: On");
		self.addClass("on");

		//console.log("Focus: input");
		var inp = self.find('input');
		if (!inp.is(':focus')) {
			inp.focus();
		}
	});

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

		//console.log("Blurring Input");
		//input.blur();
	});

	aColorSelectors.on('blur', 'input', function(e) {
		var self = $(this);
		//console.log("Input Is Blured !!!");
		if (timer) {
			clearTimeout(timer);
		}

		timer = setTimeout(function() {
			//console.log("Closing Select");
			self.parents('.cust-color-selector').removeClass('on');
		}, 200)
	});

	aColorSelectors.on('focus', 'input', function(e) {
		//console.log("Input Is Focus !!!");
	});
})