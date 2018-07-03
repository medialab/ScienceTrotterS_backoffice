var ApiMgr = {
	curRequest: null,
	
	sCurLang: false,
	apiUrl: _API_URL_,
	apiToken: _API_TOKEN_,

	queue: [],
	active: false,
	
	searchOrder: ['score', 'desc'],
	defaultOpts: {
		skip: 0,
		limit: 0,
		columns: false,
		success: null,
		error: null,
	},
	
	addRequest: function(req) {
		//console.log("Adding Request: ", req);
		this.queue.push(req);
		//console.log("Queue Len: ", this.queue.length);

		if (!this.active) {
			this.execute();
		}
	},

	execute: function() {
		this.active = true;

		this.curRequest = this.queue.shift();

		$.ajax(this.curRequest);
	},

	call: function(method, url, data, success, error) {
		var self = this;
		//console.log("Calling:", data);

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
				//console.log("API FAILD", result);
				if (error) {
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

		request.setLang(self.sCurLang);
		this.addRequest(request);

		return request;
	},

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

	setLang: function(l) {
		this.sCurLang = l;
	},

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

		var reqParams = [
			"id",
			'by'
		];

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

		//console.log("#### DO LOAD PARENT: ", parents);
		aData = {
			token: _API_TOKEN_,
			limit: options.limit,
			skip: options.page * options.limit,
			columns: columns,
			parents: parents,
			order: order
		};
		//console.log("data: ", aData);

		if (by !== "NoCity") {
			var url = options.table+'/by'+by+'/'+id;
		}
		else{
			var url = options.table+'/by'+by;
		}

		return this.call(
			'get',
			url,
			aData,
			options.success,
			options.error
		);
	}
}