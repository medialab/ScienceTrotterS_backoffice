
<div class="arbo">

	<!-- ROW -->
	<div class="arboItem">
		<!-- NAME -->
		<div class="columnName">
			<label>villes</label> 
		</div>

		<!-- DATA -->
		<div id="cities" class="columnData" limit="{$aCities|count}" target="cette ville">
			<ul class="itemList">
				{foreach $aCities as $city}
					{$city->setLang('default')}
					<!-- ITEM -->
					<li class="item">
						<div class="itemAction">
							<a class="delete-btn" href="/delete/city/{$city->id}.html">
								<i class="icon-pre icon-list-remove"></i>
							</a>
							<a class="edit-btn" href="/edit/city/{$city->id}.html">
								<i class="icon-pre icon-list-edit"></i>
							</a>
							<a class="preview-btn" href="https://science-trotters.actu.com/#/cities">
								<i class="icon-pre icon-list-preview"></i>
							</a>
						</div>
						<label class="itemLabel">{$city->title}</label>
					</li>
					<!-- .\ ITEM -->
				{/foreach}

				{*
					<!-- LOAD ITEM -->
					<li class="item">
						<div class="itemAction">
						</div>
						<label class="itemLabel">{include file="include/spinner.tpl" size='xs'}</label>
					</li>
					<!-- .\ LOAD ITEM -->
				*}
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
		<div id="parcours"  class="columnData" limit="{$aParcours|count}" target="ce parcours">
			<ul class="itemList">
				{foreach $aParcours as $parcours}
					{$parcours->setLang('default')}
					<li class="item">
						<div class="itemAction">
							<a class="delete-btn" href="/delete/parcours/{$parcours->id}.html">
								<i class="icon-pre icon-list-remove"></i>
							</a>
							<a href="/edit/parcours/{$parcours->id}.html">
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
				<i class="icon icon-create-roadMap"></i>
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
		<div id="interests"  class="columnData" limit="{$aInterests|count}" target="ce point d'interêt">
			<ul class="itemList">
				{foreach $aInterests as $interest}
					{$interest->setLang('default')}

					<li class="item">
						<div class="itemAction">
							<a class="delete-btn" href="/delete/interest/{$interest->id}.html">
								<i class="icon-pre icon-list-remove"></i>
							</a>
							<a href="/edit/interest/{$interest->id}.html">
								<i class="icon-pre icon-list-edit"></i>
							</a>
							<a href="#">
								<i class="icon-pre icon-list-preview"></i>
							</a>
						</div>
						<label class="itemLabel">{$interest->title}</label>
					</li>
				{/foreach}
			</ul>
		</div>

		<!-- BTN LINK -->
		<div class="columnBtnLink">
			<a href="/edit/interest.html">
				<i class="icon icon-poi"></i>
				Créer un point d'intérêt
			</a>
		</div>

	</div>
	<!-- .\ ROW -->



</div>