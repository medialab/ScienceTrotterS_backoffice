<div class="leftBar">
	<!-- HEADER -->
	<div class="header">
		<button id="closeMenuBtn"></button>
	</div>

	<div class="content">
		<!-- ITEM LIST -->
		<ul class="itemList">
			<li class="item {if $sPage === 'index'}selected{/if}" title="Accueil">

				<a href="/" class="itemClick">
					<div class="itemIcon lb-icon-home"></div>
					<label class="itemLabel">Arborescence</label>
				</a>
				<!-- .\ SUB ITEMS -->
			</li>

			<li class="item {if $sPage === 'edit/city'}selected{/if}" title="Créer Une Ville">
				<a href="/edit/city.html" class="itemClick">
					<div class="itemIcon lb-icon-city"></div>
					<label class="itemLabel">Créer ville</label>
				</a>
			</li>
			<li class="item {if $sPage === 'edit/parcours'}selected{/if}" title="Créer un Parcours">
				<a href="/edit/parcours.html" class="itemClick">
					<div class="itemIcon lb-icon-road"></div>
					<label class="itemLabel">Créer parcours</label>
				</a>
			</li>
			<li class="item {if $sPage === 'edit/interest'}selected{/if}" title="Créer un Point d'Intérêt">
				<a href="/edit/interest.html" class="itemClick">
					<div class="itemIcon lb-icon-roadMap"></div>
					<label class="itemLabel">Créer points d'intérêts</label>
				</a>
			</li>
			<li class="item {if $sPage === 'credit'}selected{/if}" title="Crédits">
				<a href="/credit.html" class="itemClick">
					<div class="itemIcon lb-icon-iconInfo"></div>
					<label class="itemLabel">Crédit</label>
				</a>
			</li>
			<li class="item" title="Déconnexion">
				<a href="/logout.html" class="itemClick">
					<div class="itemIcon lb-icon-logout"></div>
					<label class="itemLabel">
						Déconnexion
					</label>
				</a>
			</li>
			{*
			<li class="item itemBySTS">
				<label class="itemLabel itemLogo">SCIENCE TROTTERS</label>
			</li>
			*}
			<li class="item itemByTNG">
				<a href="http://www.thenetgroup.net/" target="_blank">
					<label class="itemLabel itemLogo">TNG</label>
				</a>
			</li>
		</ul>

	</div>

</div>
