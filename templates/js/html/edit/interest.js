
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

		if (oSelfInp.prop('files').length || oSelfInp.hasClass('.hasFile')) {
			console.log("Self Input Has Value");
			oSelfInp.click();
			return;
		}

		var oFound = false;
		aGalleryInp.each(function(i, e) {
			var oInp = $(e);

			console.log("Input #"+i, oInp, "Has File: ", oInp.hasClass('.hasFile'), "Value: ", !oInp.prop('files').length);
			if (!oInp.hasClass('.hasFile') && !oInp.prop('files').length) {
				oFound = oInp;
				return false;
			}
		});
	
		console.log("Input Found:", oFound);
		oFound.click();
	});
});
