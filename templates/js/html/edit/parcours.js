$(document).ready(function() {
	var oVilleList = $("select#cities_id");
	
	oVilleList.change(function() {
		var self = $(this);
		oVilleList.not(self).val(self.val());
	});

	var oIntBox = $(".box-interets");
	var oTime = oIntBox.find('.time');

	var id = $("input[name=id]").first().val();

	if (!id) {
		return;
	}

	if (oTime.length) {
		ApiMgr.call('get', 'parcours/length/'+id, [], function(aDataSet) {
			var data = aDataSet.data;
			var oCnt = oIntBox.find('.cnt b').first();
			var oDistance = oIntBox.find('.distance');

			oIntBox.find('.spinner').remove();

			oTime.find('span').text(data.time.string);
			oCnt.text(data.pointCnt + ' / ' + oCnt.text());
			oDistance.find('span').text((data.distance.toString().replace('.', ','))+' Km');
		});
	}
	
	console.log("Prepare Count");
	countListen('parcours', id);
})