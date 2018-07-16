var ApiMgr = {
	// La Requete En Train D'etre Executer
	curRequest: null,
	
	// Langue Séléctionnée
	sCurLang: false,

	// Url
	apiUrl: _API_URL_,

	// Token
	apiToken: _API_TOKEN_,

	// File D'attente des Requetes
	queue: [],
	// Execution En Cours
	active: false,
	
	// Order Par Defaut de la Recherche
	searchOrder: ['score', 'desc'],

	// Options Par Defaut
	defaultOpts: {
		skip: 0,
		limit: 0,
		columns: false,
		success: null,
		error: null,
	},
	
	/**
	 * Ajoute Une Requete à La Fille D'attente
	 * @param {Object} req La Requete
	 */
	addRequest: function(req) {
		console.log("Adding Request: ", req);
		this.queue.push(req);

		if (!this.active) {
			this.execute();
		}
	},

	/**
	 * Execute la File D'Attente
	 * @return {[type]} [description]
	 */
	execute: function() {
		this.active = true;

		this.curRequest = this.queue.shift();

		$.ajax(this.curRequest);
	},

	/**
	 * Prépare Une Nouvelle Requete
	 * @param  {String} method  Method HTTP
	 * @param  {String} url     Api EndPoint
	 * @param  {Object} data    Données
	 * @param  {CallBack} success Fonction En Cas de Succes
	 * @param  {CallBack} error Fonction En Cas d'Erreur
	 * @return {Object}         La Requete Générée
	 */
	call: function(method, url, data, success, error) {
		var self = this;

		// Ajout Du Token
		if (method === 'get') {
			url += url.indexOf('?') > -1 ? '&' : '?';
			url += 'token='+self.apiToken;
		}
		else{
			data['token'] = self.apiToken;
		}

		var usedToken = data.token;
		var request = {
			crossDomain: true,
			url: this.apiUrl+url,
			method: method,
			data: data,
			dataType: 'json',

			sCurLang: false,

			success: function(result) {
				if (success) {
					success(result);
				}
			},

			error: function(result) {
				console.error("Request Error", result);

				if (typeof result.responseJSON !== 'undefined') {
					result = result.responseJSON;
					console.log(usedToken);

					var bIsExpired = result.code === 4;

					// Si le Token Est Expiré
					if (bIsExpired) {
						console.log("Veol Expired", this);
						// Le Token Est Identique, On le regénère
						if (usedToken === self.apiToken) {
							var oReq = this;
							console.log("Updating");

							self.refreshToken(
								function() {
									oReq.backup.data.token = self.apiToken;
									console.log("### RESEND REQUEST: ", oReq);
									self.addRequest(oReq.backup);
								},
								function() {
									error(result);
								}
							);
						}
						else{
							console.error('Token Expired !');
						}
					}
				}

				if (error && !bIsExpired) {
					error(result);
				}
			},

			complete: function() {
				if (self.queue.length) {
					self.execute();
				}
				else{
					self.active = false;
					self.curRequest = null;
				}
			},

			nextPage: function(success, error) {
				this.data.skip += this.data.limit;
				
				if (success) {
					this.success = success;
				}

				if (error) {
					this.error = error;
				}

				self.addRequest(this);
			},

			setLang: function(l) {
				this.sCurLang = l;
				this.data.lang = l;
			}
		};

		request.backup = Object.assign({}, request);
		request.setLang(self.sCurLang);
		this.addRequest(request);

		return request;
	},

	/**
	 * Regénère un Token
	 */
	refreshToken: function(success, error) {
		console.log("==== Refreshing Token ====");
		var self = this;

		$.ajax({
			method: 'post',
			data: {token: self.apiToken},
			url: '/refresh-token.ajax',
			dataType: 'json',

			success: function(aData) {
				console.log("==== Refresh Result ====", aData);

				if (aData.success) {
					console.log("success");
					_API_TOKEN_ = self.apiToken = aData.data;
				}
				else{
					Notify.error('Session', aData.message);
					console.error('Fail To Refresh Token', aData);
					window.location = '/connexion.html';
					return;
				}

				success();
			},

			error: function(aData) {
				console.error(aData);
				error();
			}
		});
	},

	/**
	 * Liste Un Modele
	 * @param  {String} table   Table Du Model
	 * @param  {Int} page    Le Numéro de la Page
	 * @param  {Int} limit   Limite De résultats
	 * @param  {CallBack} success Fonction En Cas De Succes
	 * @param  {CallBack} error Fonction En Cas D'Erreur
	 * @param  {Array} columns Colones à récupérer
	 * @return {Object}         Retour Api
	 */
	list: function(table, page, limit, success, error, columns) {
		page = page || 0;
		columns = columns || false;

		return this.call(
			'get',
			table+'/list',
			{
				token: _API_TOKEN_, 
				limit: limit, 
				skip: page*limit
			},
			success,
			error
		);
	},

	/**
	 * Met à Jour un Mode
	 * @param  {String} table   Table Du Model
	 * @param  {Object} aData   Données
	 * @param  {CallBack} success Fonction En Cas De Succes
	 * @param  {CallBack} error Fonction En Cas D'Erreur
	 * @return {Object}         La Requete
	 */
	update: function(table, aData, success, error) {
		aData['token'] = _API_TOKEN_;
		return this.call(
			'post',
			table+'/update',
			aData,
			success,
			error
		);
	},

	/**
	 * Définition de la langue
	 * @param {String} l la Langue
	 */
	setLang: function(l) {
		this.sCurLang = l;
	},

	/**
	 * Recherche Dans Un Model
	 * @param  {String} table   Table Du Model
	 * @param  {String} query   Phrase à Rechercher
	 * @param  {Int} page    Le Numéro de la Page
	 * @param  {Int} limit   Limite De résultats
	 * @param  {CallBack} success Fonction En Cas De Succes
	 * @param  {CallBack} error Fonction En Cas D'Erreur
	 * @param  {Array} columns Colones à récupérer
	 * 
	 * @param  {Bool} parents Chargement Des Parents
	 * @return {Object}         La Requete
	 */
	search: function(table, query, page, limit, success, error, columns, parents) {
		columns = columns || [];
		parents = parents || false;

		if (typeof columns === 'object') {
			if (columns.indexOf('id') == -1) {
				columns.push('id');
			}

			if (columns.indexOf('title') == -1) {
				columns.push('title');
			}

			if (columns.indexOf('force_lang') == -1) {
				columns.push('force_lang');
			}

			if (columns.indexOf('state') == -1) {
				columns.push('state');
			}
		}

		aData = {
			token: _API_TOKEN_,
			search: query,
			limit: limit,
			skip: page*limit,
			columns: columns,
			parents: parents,
			order: this.searchOrder
		};

		return this.call(
			'post',
			table+'/search',
			aData,
			success,
			error
		);
	},

	/**
	 * Search Model By CityId || ParcoursId
	 * @param  {object} options {
	 *     'by': (string) CityId || ParcoursId,
	 *     'table': (string) parcours || interests,
	 *     'id': (string) parent Id,
	 *     'skip': (int),
	 *     'limit': (int),
	 *     'columns': (Array),
	 *     'success': (Function),
	 *     'error': (Function),
	 * } [Request Data]
	 * 
	 */
	by: function(options) {
		if (typeof options !== "object") {
			console.error("Invalid Params For ApiMgr.by()");
		}

		// Paramètres Obligatoires
		var reqParams = [
			"id",
			'by'
		];

		// Genère Un Warning En Cas D'erreur
		var missing = [];
		for (var i in reqParams) {
			var p = reqParams[i];
			var t = typeof options[p];
			
			if (t === "undefined") {
				missing.push(p);
			}
		}

		if (missing.length) {
			console.error("Missing Params: "+ missing.join(', ') +" For ApiMgr.by()");
		}


		var id = options.id;
		var by = options.by.ucfirst();
		var table = options.table;

		// Preparation DesOptions
		options = Object.assign(ApiMgr.defaultOpts, options);

		var order = options.order || false;
		var parents = options.parents || false;
		var columns = options.columns || false;

		if (typeof columns === 'object') {
			if (columns.indexOf('id') == -1) {
				columns.push('id');
			}

			if (columns.indexOf('title') == -1) {
				columns.push('title');
			}

			if (columns.indexOf('force_lang') == -1) {
				columns.push('force_lang');
			}

			if (columns.indexOf('state') == -1) {
				columns.push('state');
			}
		}

		// Preparation Des Données
		aData = {
			token: _API_TOKEN_,
			limit: options.limit,
			skip: options.page * options.limit,
			columns: columns,
			parents: parents,
			order: order
		};

		// Mise à Jour de L'url
		if (by !== "NoCity") {
			var url = options.table+'/by'+by+'/'+id;
		}
		else{
			var url = options.table+'/by'+by;
		}

		// Appel à L'api
		return this.call(
			'get',
			url,
			aData,
			options.success,
			options.error
		);
	}
}