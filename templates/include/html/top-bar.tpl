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
			{assign var="sPath" value=''}
			{foreach $aFilDArianne as $sUrl => $sText}
				{assign var="sPath" value=$sPath|cat: '/'|cat: $sUrl}
				{$sPath|var_dump}

				<li class="pathName"><a class="faLink" href="{$sPath}">{$sText}</a></li>
				
			{/foreach}
			
		</ul>
	</div>
{/if}