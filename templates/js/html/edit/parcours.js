$(document).ready(function() {
	var oVilleList = $("select#cities_id");
	
	oVilleList.change(function() {
		var self = $(this);
		oVilleList.not(self).val(self.val());
	})
})