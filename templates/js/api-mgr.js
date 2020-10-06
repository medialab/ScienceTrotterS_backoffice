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
		this.queue.push(req);

		if (!this.active) {
			this.execute();
		}
	},

	/**
	 * Ajoute Une Requete à La Fille D'attente
	 * @param {Object} req La Requete
	 */
	addFirstRequest: function(req) {
		this.queue.unshift(req);

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

		var url = this.curRequest.url;
		// Ajout Du Token
		if (this.curRequest.method === 'get') {
			url += url.indexOf('?') > -1 ? '&' : '?';
			url += 'token='+this.apiToken;

			this.curRequest.url = url;
			// make sure data.token is updated
			if (this.curRequest.data['token'] !== this.apiToken) {
				this.curRequest.data['token'] = this.apiToken;
			}
		}
		else{
			this.curRequest.data['token'] = this.apiToken;
		}

		$.ajax(this.curRequest);
	},

	updateToken: function (token) {
		//console.log("Updating Token", token);
		this.apiToken = token;
		_API_TOKEN_ = token;
	},

	tokenExpired: function(usedToken, oReq) {
		var self = this;
		// Le Token Est Identique, On le regénère
		if (usedToken === self.apiToken) {
			self.refreshToken(
				function() {
					oReq.backup.data.token = self.apiToken;
					self.addFirstRequest(oReq.backup);
				},
				function() {
					error(result, err);
				}
			);
		}
		else{
			console.error('Token Expired !');
		}
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


		var usedToken = data.token;
		var request = {
			crossDomain: true,
			url: this.apiUrl+url,
			method: method,
			data: data,
			dataType: 'json',

			sCurLang: false,

			success: function(result) {
				if (typeof result.token != 'undefined') {
					self.updateToken(result.token);
				}

				if (success) {
					success(result);
				}
			},

			error: function(result) {
				console.error("Request Error", result);
				if (typeof result.token != 'undefined') {
					self.updateToken(result.token);
				}

				var err = result;
				if (typeof result.responseJSON !== 'undefined') {
					result = result.responseJSON;

					var bIsExpired = [2,4].indexOf(result.code) >= 0;

					// Si le Token Est Expiré
					if (bIsExpired) {
						self.tokenExpired(usedToken, this);
					}
				}
				// catch 401 if xhr status is 0
				if (result.status === 0) {
					self.tokenExpired(usedToken, this);
				}

				if (error) {
					error(result, err);
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
		if (typeof data.lang === 'undefined') {
			request.setLang(self.sCurLang);
		}
		else{
			if (data.lang == false) {
				delete data.lang;
			}
			else{
				request.setLang(data.lang);
			}
		}

		this.addRequest(request);

		return request;
	},

	/**
	 * Regénère un Token
	 */
	refreshToken: function(success, error) {
		var self = this;

		$.ajax({
			method: 'post',
			data: {token: self.apiToken},
			url: '/refresh-token.ajax',
			dataType: 'json',

			success: function(aData) {

				if (aData.success) {
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

		var lang = options.lang || false;
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
			order: order,
			lang: lang
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
	},

	delete: function(table, id, success, error) {
		return this.call(
			'post',
			table+'/delete',
			{id: id},
			success,
			error
		)
	}
}