/**
 * GESTION DES NOTIFICATION
 */
var Notify = {
	/**
	 * Conteneur Des Notifications
	 */
	cont: null,

	/**
	 * Conteneurs Des Types Notifications
	 */
	containers: null,

	init: function() {
		this.cont = $("#notify-container");

		this.containers = {
			error: this.cont.find('error-container'),
			success: this.cont.find('success-container'),
			warning: this.cont.find('warning-container'),
		};
	},

	/**
	 * Ajoute Une Notification
	 * @param {String} sType Success | Warning | Error
	 * @param {String} sKey  Titre De la Notif
	 * @param {String} sMsg  Le MEssage
	 */
	add: function (sType, sKey, sMsg) {
		var cont;
		var list;
		
		// Si Le Conteneur Nexiste pas On Le crée
		if (!this.containers[sType].length) {
			list = $("<ul></ul>");
			cont = $("<div></div>").addClass("msg-cont "+sType+'-container').append(list);
			this.containers[sType] = cont;
			
			this.cont.append(cont);
		}
		else{
			cont = this.containers[sType];
			list = cont.find('ul');
		}

		// On Ajoute Le Message
		var notif = $('<li></li>');
		notif.text(sMsg);

		if (sKey.length) {
			notif.prepend($("<b></b>").text(sKey+": "));
		}

		list.append(notif);
	},

	/**
	 * Ajoute Un Success
	 * @param  {String} sKey Titre de la Notif
	 * @param  {String} sMsg Le Message
	 */
	success: function(sKey, sMsg) {
		this.add('success', sKey, sMsg);
	},

	/**
	 * Ajoute Un Warning
	 * @param  {String} sKey Titre de la Notif
	 * @param  {String} sMsg Le Message
	 */
	warning: function(sKey, sMsg) {
		this.add('warning', sKey, sMsg);
	}, 

	/**
	 * Ajoute Une Erreur
	 * @param  {String} sKey Titre de la Notif
	 * @param  {String} sMsg Le Message
	 */
	error: function(sKey, sMsg) {
		this.add('error', sKey, sMsg);
	}
}

Notify.init();