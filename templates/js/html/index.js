$(document).ready(function() {
	var lists = $('div.columnData');
	console.log("LISTS: ", lists);

	lists.scroll(function() {
	  console.log("scrolled");
	})
})