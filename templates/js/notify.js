var Notify = {
	cont: null,

	containers: null,

	init: function() {
		this.cont = $("#notify-container");

		this.containers = {
			error: this.cont.find('error-container'),
			success: this.cont.find('success-container'),
			warning: this.cont.find('warning-container'),
		};
		

		console.log(this.containers);
	},

	add: function (sType, sKey, sMsg) {
		var cont;
		var list;
		console.log("Adding Notify: "+sType, this.containers[sType]);
		
		if (!this.containers[sType].length) {
			list = $("<ul></ul>");
			cont = $("<div></div>").addClass("msg-cont "+sType+'-container').append(list);
			this.containers[sType] = cont;
			
			console.log("Adding Notify: "+sType, this.containers[sType]);

			this.cont.append(cont);
		}
		else{
			cont = this.containers[sType];
			list = cont.find('ul');
		}

		var notif = $('<li></li>');
		notif.text(sMsg);

		if (sKey.length) {
			notif.prepend($("<b></b>").text(sKey+": "));
		}

		list.append(notif);
	},

	success: function(sKey, sMsg) {
		this.add('success', sKey, sMsg);
	},

	warning: function(sKey, sMsg) {
		this.add('warning', sKey, sMsg);
	}, 

	error: function(sKey, sMsg) {
		this.add('error', sKey, sMsg);
	}
}

Notify.init();