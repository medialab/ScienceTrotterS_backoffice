$(document).ready(function() {
	$('input[maxlength], textarea[maxlength]').keyup(function(e) {
		var self = $(this);
		var len = parseInt(self.attr('maxlength'));
				
		var curLen = self.val().split("\n").length - 1;
		curLen += self.val().length;
		
		if(curLen > len) {
			var substring = self.val().substring(0, len-1);
			var cnt =  (substring.match(/\n/g) ||[]).length;
			substring = substring.substring(0, (curLen - cnt) - (curLen - len));

			self.val(substring);
			
		}
	});
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

	$(".btnInputFileName a.delete").click(function(e) {
		e.stopPropagation();
		e.preventDefault();

		var t;
		var self = $(this);
		var table = self.attr('table');

		if (self.attr('type').indexOf('image') >= 0) {
			t = 'cette image';
		}
		else{
			t = 'ce fichier audio';

		}

		var name;
		switch(table) {
			case "cities": 
				name = "la ville";
				break;

			case "parcours": 
				name = "le parcours";
				break;

			case "interests": 
				name = "le point d'intérêt";
				break;
		}

		if ($("#curState").val() === '1') {
			alert("Attention: Vous ne pouvez supprimer "+t+" sans désactiver "+name+".");
		}
		else if(confirm("Attention: Vous êtes sur le point de supprimer "+t+"")) {
			var loc = '/delete-file.html?table='+table+"&id="+self.attr('id')+"&type="+self.attr('type')+"&model="+self.attr('model')+"&lang="+self.attr('lang')
			console.log(loc);
			window.location = loc;
		}
	})
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
