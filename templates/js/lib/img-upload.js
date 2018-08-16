// AFFICHAGE DE L'IMAGE LORS D'UN UPDATE input FILE
$(document).ready(function(){
	$("input[target='img']").change(function() {
		var self = $(this);

		var oDisplay = self.parent().find('img');
		
		if (!this.files || !this.files[0]) {
			oDisplay.attr('src', '/media/image/interface/icons/icon_photo.svg');
			return;
		}

		var oReader = new FileReader();

		oReader.onload = function(e) {
			oDisplay.attr('src', e.target.result);
			self.attr('updated', true);
		}

		oReader.readAsDataURL(this.files[0]);
	});
});