{include file="include/html/form-error.tpl" aErrors=$aErrors|default:false}

<div class="arbo">

	<!-- ROW -->
	<div class="arboItem">
		<!-- NAME -->
		<div class="columnName">
			<label>villes</label> 
		</div>

		<!-- DATA -->
		<div id="cities" class="columnData" limit="{$aCities|count}" target="la ville">
			<ul class="itemList">
				{foreach $aCities as $model}
					{$model->setLang('default')}
					<!-- ITEM -->
					<li id="{$model->id}" class="item {if !$model->state}disabled{/if}" title="{$model->title}">
						<div class="itemAction">
							<a class="delete-btn" href="/delete/city/{$model->id}.html">
								<i class="icon-pre icon-list-remove"></i>
							</a>
							<a class="edit-btn" href="/edit/city/{$model->id}.html">
								<i class="icon-pre icon-list-edit"></i>
							</a>
							<a class="preview-btn">
								<i class="icon-pre icon-list-preview"></i>
							</a>
						</div>
						<label class="itemLabel">{$model->title}</label>

						<div>
							{if $model->force_lang === null ||$model->force_lang === 'fr'}
								<i class="icon-pre icon-flag-fr"></i>
							{/if}
							{if $model->force_lang === null ||$model->force_lang === 'en'}
								<i class="icon-pre icon-flag-en"></i>
							{/if}
						</div>
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
		<div id="parcours"  class="columnData" limit="{$aParcours|count}" target="le parcours">
			<ul class="itemList">
				{foreach $aParcours as $model}
					{$model->setLang('default')}

					<li id="{$model->id}" class="item {if !$model->state}disabled{/if}" title="{$model->title}">
						<div class="itemAction">
							<a class="delete-btn" href="/delete/parcours/{$model->id}.html">
								<i class="icon-pre icon-list-remove"></i>
							</a>
							<a href="/edit/parcours/{$model->id}.html">
								<i class="icon-pre icon-list-edit"></i>
							</a>
							<a  class="preview-btn">
								<i class="icon-pre icon-list-preview"></i>
							</a>
						</div>
						<label class="itemLabel">{$model->title}</label>
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
		<div id="interests"  class="columnData" limit="{$aInterests|count}" target="cl point d'interêt">
			<ul class="itemList">
				{foreach $aInterests as $model}
					{$model->setLang('default')}

					<li id="{$model->id}" class="item {if !$model->state}disabled{/if}" title="{$model->title}">
						<div class="itemAction">
							<a class="delete-btn" href="/delete/interest/{$model->id}.html">
								<i class="icon-pre icon-list-remove"></i>
							</a>
							<a href="/edit/interest/{$model->id}.html">
								<i class="icon-pre icon-list-edit"></i>
							</a>
							<a  class="preview-btn">
								<i class="icon-pre icon-list-preview"></i>
							</a>
						</div>
						<label class="itemLabel">{$model->title}</label>
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