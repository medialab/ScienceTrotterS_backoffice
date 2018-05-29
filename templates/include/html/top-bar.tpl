{if $showTopBar|default: true}
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

	<div class="filDAriane">
		<ul class="pathList">
			{assign var="aPath" value=[]}


			{assign var="sPath" value='/'}
			{assign var="i" value=0}
			{foreach $aFilDArianne as $sUrl => $sText}
				{assign var="aPath[$i]" value=$sUrl}
				{assign var="sPath" value=$sPath|cat: '/':$sUrl}

				<li class="pathName"><a class="faLink" href="{$sPath}.html">{$sText}</a></li>
				
			{/foreach}
			
		</ul>
	</div>
{/if}