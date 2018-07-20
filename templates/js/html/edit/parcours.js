$(document).ready(function() {
	var oVilleList = $("select#cities_id");
	
	oVilleList.change(function() {
		var self = $(this);
		oVilleList.not(self).val(self.val());
	});

	var oIntBox = $(".box-interets");
	var oTime = oIntBox.find('.time');

	if (!oTime.length) {
		return;
	}
	

	var id = $("input[name=id]").first().val();

	ApiMgr.call('get', 'parcours/length/'+id, [], function(aDataSet) {
		var data = aDataSet.data;
		var oCnt = oIntBox.find('.cnt b').first();
		var oDistance = oIntBox.find('.distance');

		oIntBox.find('.spinner').remove();

		oTime.find('span').text(data.time.string);
		oCnt.text(data.pointCnt + ' / ' + oCnt.text());
		oDistance.find('span').text((data.distance.toString().replace('.', ','))+' Km');
	});
	
	var i = 0;
	var langs = ['fr', 'en'];

	function calcListen(res) {
		l = langs[i];
		i++;
		var cont = $('#tab-'+l+' .audio-cnt b');
		cont.text(''+res);
		cont.parent().next('.spinner').remove();

		if (typeof langs[i] !== 'undefined') {
			callListen();
		}
	}

	function callListen() {
		ApiMgr.call('get', 'parcours/listenCnt/'+id, {lang: langs[i]}, function(aDataSet) {
			calcListen(aDataSet.data);
		});
	}

	callListen();
})