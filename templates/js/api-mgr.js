var ApiMgr = {
	curRequest: null,
	
	sCurLang: false,
	apiUrl: _API_URL_,
	apiToken: _API_TOKEN_,

	queue: [],
	active: false,
	
	addRequest: function(req) {
		console.log("Adding Request: ", req);
		this.queue.push(req);
		console.log("Queue Len: ", this.queue.length);

		if (!this.active) {
			this.execute();
		}
	},

	execute: function() {
		this.active = true;

		console.log(this.queue);
		this.curRequest = this.queue.shift();
		console.log("Executing Request: ", this.curRequest);
		console.log(this.curRequest.data);

		$.ajax(this.curRequest);
	},

	call: function(method, url, data, success, error) {
		var self = this;

		var request = {
			crossDomain: true,
			url: this.apiUrl+url,
			method: method,
			data: data,
			dataType: 'jsonp',

			sCurLang: false,
			jsonpCallback: 'ApiResponse',

			success: function(result) {
				console.log("API SUCCESS", result);
				if (success) {
					success(result);
				}
			},

			error: function(result) {
				console.log("API FAILD", result);
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
		)
	},

	setLang: function(l) {
		this.sCurLang = l;
	}
}


function ApiResponse(result) {
	/*if (ApiMgr.curRequest.success) {
		ApiMgr.curRequest.success(result);
	}*/
}