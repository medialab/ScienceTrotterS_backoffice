var oVilleList = $("select#cities_id");
var oColorList = $(".cust-color-selector");

function updateColorList(aColors) {
	console.log(aColors);

	var options = $(oColorList.find('.sel-opt'));
	console.log(options);

	options.each(function(i, e) {
		e = $(e);

		if (aColors.indexOf(e.attr('value')) >= 0) {

		console.log(e) 
		console.log(e.attr('value')) 
		console.log(aColors.indexOf(e.attr('value')));
			e.addClass('taken');
		}
		else {
			e.removeClass('taken');
		}
	});
}

$(document).ready(function() {
	oVilleList = $("select#cities_id");
	oColorList = $(".cust-color-selector");

	oVilleList.change(function() {
		var self = $(this);
		oVilleList.not(self).val(self.val());

		if (self.val()) {
			ApiMgr.by({
				by: 'city',
				id: self.val(),
				table: 'parcours',
				order: ['parcours.title'],

				success: function(aResult) {
					if (!aResult.success) {
						updateColorList([]);
					}else{
						var aColors = [];
						for (var i in aResult.data) {

							if (aResult.data[i].color && aResult.data[i].state && aResult.data[i].id !== id) {
								aColors.push(aResult.data[i].color);
							}
						}
						
						updateColorList(aColors);
					}
				}
			});
		}
		else {
			updateColorList([]);
		}
	});

	oVilleList.first().change();

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