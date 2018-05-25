<!-- NAV BAR -->
<div class="navBar">
	<div class="itemsNavBar">

		<div class="item searchBar">
			<label for="inputSearchBar" class="labelSearchBar"></label>
			<input type="text" id="inputSearchBar" placeholder="Ville, parcours, point d'intérêt..." />
		</div>
		<a href="#" class="item itemClick">
			<div class="itemIcon iconMessage"></div>
		</a>
		<a href="#" class="item itemClick">
			<div class="itemIcon iconInfo"></div>
		</a>
	</div>
</div>
<!-- .\ NAV BAR -->

<div class="mainContent">

	<div class="filDAriane">
		<ul class="pathList">
			<li class="pathName"><a class="faLink" href="#">Arborescence</a></li>
			<li class="pathName"><a class="faLink" href="#">Arborescence</a></li>
		</ul>
	</div>

	<div class="contentView">


		<div>
			<h2>Créer {$sCreation}</h2>
		</div>

		{include file="include/html/form-error.tpl" aErrors=$aErrors}
