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

	var docViewTop = list.offset().top + list.scrollTop();
    var docViewBottom = docViewTop + list.height();

    var elemTop = el.offset().top;
    var elemBottom = elemTop + el.height();


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
	apiUrl: _API_TOKEN_,
	apiToken: _API_TOKEN_,

	queue: [],
	active: false,
	
	addRequest: function(req) {
		console.log("Adding Request: ", req);
		this.queue.push(this.queue);
		console.log("Queue Len: ", this.queue.length);

		if (!this.active) {
			this.execute();
		}
	},

	execute: function() {
		console.log("Executing Request: ", req);
		this.active = true;
		this.curRequest = this.queue.shift();
		
		$.ajax(this.curRequest);
	},

	call: function(method, url, data, success, error) {
		var self = this;

		console.log("PREPARING REQUEST");
		var request = {
			url: this.apiUrl+url,
			method: method,
			data: data,
			headers: {
				Authorization: this.apiToken
			},

			success: function(result) {
				if (success) {
					success(result);
				}
			},

			error: function(result) {
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
			table,
			{
				limit: limit, 
				offset: page*limit
			},
			success,
			error
		)
	}
}