{if $showTopBar|default: true}
	<!-- NAV BAR -->
	<div class="navBar">
		<div class="itemsNavBar">

			<div>
				<div class="item searchBar">
					<div class="spinner spinner-xs">
						<div class="double-bounce1"></div>
						<div class="double-bounce2"></div>
					</div>
					<label for="inputSearchBar" class="labelSearchBar"></label>
					
					<input type="text" id="inputSearchBar" placeholder="Ville, parcours, point d'intérêt..." />
				</div>
				<a href="https://mail.google.com" class="item itemClick">
					<div class="itemIcon iconMessage"></div>
				</a>
				<a href="/credit.html" class="item itemClick">
					<div class="itemIcon iconInfo"></div>
				</a>
				<a href="/logout.html" class="item itemClick clickOff">
					<div class="itemIcon iconOff"></div>
				</a>
			</div>

			<div style="" id="search-container">
				<ul>
					<li class="">
						<a href="">
							<span class="title"></span>
							<br>
							[
								<span class="tree-el tree-cities"></span>
								<sep> &gt; </sep>
								<span class="tree-el tree-parcours"></span>
								<sep> &gt; </sep>
								<span class="tree-el tree-interests"></span>
							]
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- .\ NAV BAR -->

	<div class="filDAriane">
		<ul class="pathList">
			{assign var="aPath" value=[]}


			{assign var="sPath" value=''}
			{assign var="i" value=0}
			{foreach $aFilDArianne as $sUrl => $sText}
				{if $i++ == 1}
					{assign var="sPath" value=''}
				{/if}

				{if $sUrl !== 0}
					{assign var="sPath" value=$sPath|cat: '/':$sUrl}
				{/if}

				<li class="pathName"><a class="faLink" href="{$sPath}.html">{$sText}</a></li>
				
			{/foreach}
			
		</ul>
	</div>
{/if}