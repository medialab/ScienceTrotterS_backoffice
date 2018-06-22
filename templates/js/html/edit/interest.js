
$(document).ready(function() {
	var aGalleryBtn = $("#gallery-container .iconPreview");
	var aGalleryInp = $("#gallery-container input[type='file']");

	aGalleryBtn.click(function(e) {
		e.preventDefault();
		e.stopPropagation();

		var self = $(this);

		var index = parseInt(self.attr('index'));
		console.log("Index Selected : "+index);
		
		var oSelfInp = $(aGalleryInp.get(index));
		console.log("Self Input: ", oSelfInp);

		if (oSelfInp.prop('files').length || oSelfInp.hasClass('hasFile')) {
			console.log("Self Input Has Value");
			oSelfInp.click();
			return;
		}

		var oFound = false;
		aGalleryInp.each(function(i, e) {
			var oInp = $(e);

			console.log("Input #"+i, oInp, "Has File: ", oInp.hasClass('hasFile'), "Value: ", !oInp.prop('files').length);
			if (!oInp.hasClass('hasFile') && !oInp.prop('files').length) {
				oFound = oInp;
				return false;
			}
		});
	
		console.log("Input Found:", oFound);
		oFound.click();
	});

	var oVilleList = $("select#ville");
	var oParcoursList = $("select#parcours");

	oVilleList.first().change(function() {
		var self = $(this);
		var id = self.val();
		var parcs = self.parents('form').find('select#parcours');

		if (typeof id === 'undefined' || !id.length) {
			oParcoursList.find('optgroup').show();
			console.log("ID Is Empty");
			return;
		}

		console.log("Current ID: "+id);
		parcs.find('optgroup[target!='+id+']').hide();
		parcs.find('optgroup[target='+id+']').show();

		console.log(parcs.find('option:selected'));
		if (!parcs.find('option:selected').is(':visible')) {
			console.log("TEST VISIBLE");
			console.log(parcs.find('option:selected'));
			parcs.val('');
		}
	})
	.change()
	;

	oParcoursList.first().change(function() {
		if (oVilleList.val().length) {
			return;
		}

		var cities = self.parents('form').find('select#ville');
		var id = '';
		var self = $(this);
		var opt = self.find('option:selected');

		if (opt.length) {
			id = opt.parent().attr('target');
		}
		
		oVilleList.val(id);
		oVilleList.change();
	});
});
