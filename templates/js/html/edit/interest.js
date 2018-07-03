
$(document).ready(function() {
	var aGalleryBtn = $("#gallery-container .iconPreview");
	var aGalleryInp = $("#gallery-container input[type='file']");

	aGalleryBtn.click(function(e) {
		e.preventDefault();
		e.stopPropagation();

		var self = $(this);
		var index = parseInt(self.attr('index'));		
		var oSelfInp = $(aGalleryInp.get(index));

		if (oSelfInp.prop('files').length || oSelfInp.hasClass('hasFile')) {
			oSelfInp.click();
			return;
		}

		var oFound = false;
		aGalleryInp.each(function(i, e) {
			var oInp = $(e);

			if (!oInp.hasClass('hasFile') && !oInp.prop('files').length) {
				oFound = oInp;
				return false;
			}
		});
	
		oFound.click();
	});

	var oVilleList = $("select[id^='ville-']");
	var oParcoursList = $("select[id^='parcours-']");
	oParcoursList.find('.notfound').hide();

	oVilleList.change(function() {
		var self = $(this);
		var id = self.val();
		var parcs = self.parents('form').find('select#parcours');

		oVilleList.not(self).val(self.val());
		if (typeof id === 'undefined' || !id || !id.length) {
			oParcoursList.find('optgroup').show();
			oParcoursList.find('.notfound').hide();
			return;
		}

		oParcoursList.find('optgroup[target!='+id+']').hide();
		oParcoursList.find('optgroup[target='+id+']').show();

		if (!oParcoursList.find('option:selected').is(':visible')) {
			oParcoursList.val('');
		}

		if (self.is(':visible')) {
			console.log("VISIBLE: ", parcs.find('optgroup:visible'));
			if (!parcs.find('optgroup:visible').length) {
				oParcoursList.find('.notfound').show();
			}
			else{
				oParcoursList.find('.notfound').hide();
			}
		}
	})
	.change()
	;

	oParcoursList.change(function() {
		var self = $(this);
		oParcoursList.not(self).val(self.val());
		if (oVilleList.val().length) {
			return;
		}

		var cities = self.parents('form').find('select#ville');
		var id = '';
		var opt = self.find('option:selected');

		if (opt.length) {
			id = opt.parent().attr('target');
		}
		
		oVilleList.val(id);
		oVilleList.change();
	});

	oParcoursList.each(function(i, e) {
		var list = $(e);
		list.val(list.find('option[selected]').val());
	})
});
