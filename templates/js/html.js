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
				token: _API_TOKEN_, 
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



/* Stylisation du bouton d'importation d'une image */

$( '.inputFile' ).each( function() {


	var $this	 = $( this ),
		$label	 = $this.next( 'label' ),
		labelVal = $label.html();

	$this.on( 'change', function( e )
	{
		var fileName = '';

		if( this.files && this.files.length > 1 )
			fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
		else if( e.target.value )
			fileName = e.target.value.split( '\\' ).pop();

		if( fileName ) {
			$('#btnInputFileName p').html(fileName);
		}

	});


	// Firefox bug fix
	$this
	.on( 'focus', function(){ $this.addClass( 'has-focus' ); })
	.on( 'blur', function(){ $this.removeClass( 'has-focus' ); });
});

/***/

function updateSwitch(el) {
	console.log("UPDATE: ", el);
	
	if (el.find('input').prop('checked')) {
		el.addClass('on');
	}
	else{
		el.removeClass('on');
	}
}

function toggleSwitch(el) {
	console.log("Toggle: ", el);

	var inp = el.find('input');
	inp.prop('checked', !inp.prop('checked'));
	
	updateSwitch(el);
}

$(document).ready(function() {
	var switches = $(".boolean");

	switches.on('click', 'label', function(e) {
		e.preventDefault();
		e.stopPropagation();

		var label = $(e.currentTarget);
		var cont = $(this).parents('.boolean');

		console.log("DATA: "+label.attr('data'));
		console.log(cont.find('input'));
		if (label.attr('data') === 'on') {
			console.log("Setting to True");

			cont.find('input').prop("checked", true);
		}
		else{
			console.log("Setting to False");
			cont.find('input').prop("checked", false);
		}
		

		updateSwitch($(this));
	});


	switches.on('click', '.style', function(e) {
		e.preventDefault();
		e.stopPropagation();


		toggleSwitch($(this).parents('.boolean'));
	});
});


/* Switch */
/*$(document).on( 'click', '.boolean label', function(){
	var bOn				=	$('input#' + $(this).attr('for') ).val();
	if( bOn == 1 ){
		$(this).parents('.boolean').addClass( 'on' );
	} else {
		$(this).parents('.boolean').removeClass( 'on' );
	}
});

$(document).on( 'style', '.boolean label', function(){
	$(this).parents('.boolean').find('input:not-check').click();
});
*/