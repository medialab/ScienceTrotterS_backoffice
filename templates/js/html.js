$.scrollElementVisible = function (el) {
	var list = el.parent();
	var listFound = false;

	while(true) {
		console.log(list);
		if (!list.attr('id') == 'content') {
			return false;
		}
		else if (list.css('overflow') === 'scroll') {
			console.log("LIST FOUND");
			break;
		}

		list = list.parent();
	}

	var docViewTop = list.scrollTop();
    var docViewBottom = docViewTop + list.height();

    var elemTop = el.offset().top;
    var elemBottom = elemTop + el.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}



let currentItem = '';



const fCloseMenuBtn = function fCloseMenuBtn (event) {
  console.log(event);
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


