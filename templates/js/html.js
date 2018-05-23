$.scrollElementVisible = function (el) {
	var list = el.parent();
	var listFound = false;

	while(true) {
		if (!list.attr('id') === 'content') {
			return false;
		}
		else if (list.hasClass('columnData')) {
			break;
		}

		list = list.parent();
	}

	var docViewTop = list.offset().top;
    var docViewBottom = docViewTop + list.height();

    var elemTop = el.offset().top;
    var elemBottom = elemTop + el.height();

    console.log((elemTop +">="+ docViewTop), (elemTop >= docViewTop));
    console.log((elemBottom +"<="+ docViewBottom), (elemBottom <= docViewBottom));

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}



let currentItem = '';



const fCloseMenuBtn = function fCloseMenuBtn (event) {
  document.querySelector('.leftBar').classList.toggle('close');
}

/**
  * Handle action menu.
  */
document.querySelector('#closeMenuBtn')
  .addEventListener('click', fCloseMenuBtn);

/**
  * On Sub item click.
  */

const handlerItemClick = function handlerItemClick (item) {
  item.addEventListener('click', function (event) {
    const target = event.currentTarget.dataset.target;
    const _res = document.querySelector('.item[data-target="'+target+'"]');

    document.querySelector('.item.selected').classList.remove('selected');
    currentItem = target;
    _res.classList.toggle('selected');
  });

}

const itemClick = document.querySelectorAll('.itemClick.withSubItem');

itemClick.forEach(handlerItemClick);




var ApiMgr = {
	curRequest: null,
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

		console.log("PREPARING REQUEST");
		var request = {
			crossDomain: true,
			url: this.apiUrl+url,
			method: method,
			data: data,
			dataType: 'jsonp',

			jsonpCallback: 'ApiResponse',

			beforeSend: function(xhr) {
				console.log("TEST: "+this.apiToken);
		        xhr.setRequestHeader('Authorization', this.apiToken);
		        xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
		    },

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
				this.data.offset += this.data.limit;
				
				if (success) {
					this.success = success;
				}

				if (error) {
					this.error = error;
				}

				self.addRequest(this);
			}
		};

		this.addRequest(request);

		return request;
	},

	list: function(table, page, limit, success, error) {
		page = page || 0;

		return this.call(
			'get',
			table+'/list',
			{
				limit: limit, 
				offset: page*limit
			},
			success,
			error
		)
	}
}


function ApiResponse(result) {
	/*if (ApiMgr.curRequest.success) {
		ApiMgr.curRequest.success(result);
	}*/
}