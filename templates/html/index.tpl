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
			<li class="pathName"><a class="faLink" href="#">Arborescence</a></li>
		</ul>
	</div>

	<div class="contentView">
		<div class="arbo">

			<!-- ROW -->
			<div class="arboItem">
				<!-- NAME -->
				<div class="columnName">
					<label>villes</label> 
				</div>

				<!-- DATA -->
				<div id="cities" class="columnData">

					<ul class="itemList">
						{foreach $aCities as $city}
							<!-- ITEM -->
							<li class="item">
								<div class="itemAction">
									<a class="delete-btn" href="/delete/city/{$city->id}.html">
										{$city->id}
										<i class="icon-pre icon-list-remove"></i>
									</a>
									<a class="edit-btn" href="/edit/city/{$city->id}.html">
										<i class="icon-pre icon-list-edit"></i>
									</a>
									<a class="preview-btn" href="https://science-trotters.actu.com/#/cities">
										<i class="icon-pre icon-list-preview"></i>
									</a>
								</div>
								<label class="itemLabel">{$city->label}</label>
							</li>
							<!-- .\ ITEM -->
						{/foreach}

						<!-- LOAD ITEM -->
						<li class="item">
							<div class="itemAction">
							</div>
							<label class="itemLabel">{include file="include/spinner.tpl" size='xs'}</label>
						</li>
						<!-- .\ LOAD ITEM -->
					</ul>

				</div>

				<!-- BTN LINK -->
				<div class="columnBtnLink">
					<a href="/edit/city.html">
						<i class="icon icon-create-city"></i>
						Créer une ville
					</a>
				</div>

			</div>
			<!-- .\ ROW -->

			<!-- ROW -->
			<div class="arboItem">
				<!-- NAME -->
				<div class="columnName">
					<label>parcours</label>
				</div>

				<!-- DATA -->
				<div class="columnData">
					<ul class="itemList">
						{foreach $aParcours as $parcours}
							<li class="item">
								<div class="itemAction">
									<a href="#">
										<i class="icon-pre icon-list-remove"></i>
									</a>
									<a href="#">
										<i class="icon-pre icon-list-edit"></i>
									</a>
									<a href="#">
										<i class="icon-pre icon-list-preview"></i>
									</a>
								</div>
								<label class="itemLabel">{$parcours->title}</label>
							</li>
						{/foreach}
					</ul>
				</div>

				<!-- BTN LINK -->
				<div class="columnBtnLink">
					<a href="#">
						<i class="icon icon-create-road"></i>
						Créer un parcours
					</a>
				</div>

			</div>
			<!-- .\ ROW -->

			<!-- ROW -->
			<div class="arboItem">
				<!-- NAME -->
				<div class="columnName">
					<label>point d'intérêt</label>
				</div>

				<!-- DATA -->
				<div class="columnData">
					<ul class="itemList">
						{foreach $aInterrests as $interrest}
							<li class="item">
								<div class="itemAction">
									<a href="#">
										<i class="icon-pre icon-list-remove"></i>
									</a>
									<a href="#">
										<i class="icon-pre icon-list-edit"></i>
									</a>
									<a href="#">
										<i class="icon-pre icon-list-preview"></i>
									</a>
								</div>
								<label class="itemLabel">{$interrest->title}</label>
							</li>
						{/foreach}
					</ul>
				</div>

				<!-- BTN LINK -->
				<div class="columnBtnLink">
					<a href="#">
						<i class="icon icon-create-roadMap"></i>
						Créer un point d'intérêt
					</a>
				</div>

			</div>
			<!-- .\ ROW -->



		</div>
	</div>
</div>