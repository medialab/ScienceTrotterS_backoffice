$(document).ready(function(){
	
	$("input[target='img']").change(function() {
		var self = $(this);

		var oDisplay = self.parent().find('img');
		console.log("DISPLAY", oDisplay);
		
		if (!this.files || !this.files[0]) {
			oDisplay.attr('src', '/media/image/interface/icons/icon_photo.svg');
			console.log("updating SRC", oDisplay);
			return;
		}

		var oReader = new FileReader();

		oReader.onload = function(e) {
			oDisplay.attr('src', e.target.result);
		}

		oReader.readAsDataURL(this.files[0]);
	});
});