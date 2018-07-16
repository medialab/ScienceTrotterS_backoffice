var page = $("#contentView");

/* DEFINITION DE LA LANGUE PAR DEFAUT */
ApiMgr.setLang('fr');

/**
 * Est ce qu'un Element Scrollable Est Visible
 * @param  {JqueryObj} el L'element
 * @return {Bool}    Visible
 */
$.scrollElementVisible = function (el) {
	var list = el.parent();

	// On Cherche Le Conteneur
	list = el.parents('.columnData');
	if (!list.length) {
		list = el.parents('.content');
	}

	// On Vérifie Que La liste Est Visible
	var docViewTop = list.offset().top;
    var docViewBottom = docViewTop + list.height();

	// On Vérifie Que L'élément Est Visible
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


/**
 * Gestion Des Switch D'activation De Model
 * @param  {JqueryObj} el L'element
 */
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