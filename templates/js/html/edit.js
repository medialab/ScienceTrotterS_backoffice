$(document).ready(function() {
	/*var tabSelector = $(".tab-selector");
	var curLang = tabSelector.attr('target');*/

	// à l'envois du formulaire on ajoute la valeur du dorce lang
	$('#content form').submit(function(e) {
		var self = $(this);
		
		// ON EVITE LE DOUBLE ENVOIS
		if (self.hasClass('ready')) {
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
		
		// ON AJOUTE LA CHECKBOX FORCE LANG
		if (!self.find('input.lang-check').length) {
			e.preventDefault();
			e.stopPropagation();

			var langTaget = tabSelector.attr('target');

			var inp = tabSelector.find('.lang-only[target="'+langTaget+'"] input');
			if (!inp.prop('checked')) {
				inp.val('');
			}

			inp.hide().appendTo(self);

			self.submit();
			return false;
		}

		self.attr('ready', true);
		self.addClass('ready')

	});

	page.trigger('custom::pageReady');


});

function countListen(sModelType, id) {
	var listenCnt = $('form .audio-cnt');
	
	console.log(listenCnt.length);
	if (listenCnt.length) {
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
			ApiMgr.call('get', sModelType+'/listenCnt/'+id, {lang: langs[i]}, function(aDataSet) {
				calcListen(aDataSet.data);
			});
		}

		callListen();
	}
}
