$(document).ready(function() {
	var lists = $('ul.itemList');
	console.log("LISTS: ", lists);

	lists.scroll(function() {
	  console.log("scrolled");
	})
})