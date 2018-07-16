$(document).ready(function() {
	var oVilleList = $("select#cities_id");
	
	oVilleList.change(function() {
		var self = $(this);
		oVilleList.not(self).val(self.val());
	});

	var oIntBox = $("#box-Interets");
	var oTime = oIntBox.find('.time');

	if (!oTime.length) {
		return;
	}
	

	var id = $("input[name=id]").first().val();

	ApiMgr.call('get', 'parcours/length/'+id, [], function(aDataSet) {
		var data = aDataSet.data;
		var oCnt = oIntBox.find('.cnt b');
		var oDistance = oIntBox.find('.distance');

		oIntBox.find('.spinner').remove();

		oTime.find('span').text(data.time.string);
		oCnt.text(data.pointCnt + ' / ' + oCnt.text());
		oDistance.find('span').text((data.distance.toString().replace('.', ','))+' Km');
	});
})