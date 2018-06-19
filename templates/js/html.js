/* DEFINITION DE LA LANGUE PAR DEFAUT */
ApiMgr.setLang('fr');


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


/* Stylisation du bouton d'importation d'une image */

$( '.inputFile' ).click(function() {

	var oThis	 = $( this );

	// Firefox bug fix
	oThis
	.on( 'focus', function(){ oThis.addClass( 'has-focus' ); })
	.on( 'blur', function(){ oThis.removeClass( 'has-focus' ); });
})
.change(function( e ){
	console.log("Add File");
	var fileName = '';
	var oThis	 = $( this );

	if( e.target.value ) {
		fileName = e.target.value.split( '\\' ).pop();
		console.log("File Name: "+fileName);
	}


	if( fileName ) {
		console.log("p: ", oThis.parent().find('.blocInputFileName p'));
		oThis.parent().find('p').text(fileName);
	}
	else{
		console.log("No File Name");
	}

});

/***/

function updateSwitch(el) {
	if (el.find('input').prop('checked')) {
		el.addClass('on');
	}
	else{
		el.removeClass('on');
	}
}

function toggleSwitch(el) {
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

		if (label.attr('data') === 'on') {
			cont.find('input').prop("checked", true);
		}
		else{
			cont.find('input').prop("checked", false);
		}
		

		updateSwitch(cont);
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