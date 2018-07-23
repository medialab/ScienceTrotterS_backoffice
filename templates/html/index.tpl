{include file="include/html/form-error.tpl" aErrors=$aErrors|default:false}

<div class="arbo">

	<!-- ROW -->
	<div class="arboItem">
		<!-- NAME -->
		<div class="columnName">
			<label>villes</label> 
		</div>

		<!-- DATA -->
		<div id="cities" class="columnData" limit="{$aCities|count}" child="parcours" target="la ville">
			<ul class="itemList">

				{foreach $aCities as $model}
					{$model->setLang('default')}
					{if !$model->force_lang && empty($model->title)}
						{$model->setLang('en')}
					{/if}
					<!-- ITEM -->
					<li id="{$model->id}" class="item {if !$model->state}disabled{/if}" title="{$model->title}">
						<!-- ACTIONS -->
						<div class="itemAction">
							<a class="delete-btn" href="/delete/city/{$model->id}.html">
								<i class="icon-pre icon-list-remove"></i>
							</a>
							<a class="edit-btn" href="/edit/city/{$model->id}.html">
								<i class="icon-pre icon-list-edit"></i>
							</a>
							<a class="preview-btn">
								<i class="icon-pre icon-list-check"></i>
							</a>
						</div>
						<!-- / ACTION -->

						<!-- TITLE -->
						<label class="itemLabel">{$model->title}</label>
						<!-- /TITLE -->

						<!-- LANG FLAGS -->
						<div class="flag-cont {$model->force_lang|default: ''}">
							<i class="icon-pre icon-flag-fr"></i>
							<i class="icon-pre icon-flag-en"></i>
						</div>
						<!-- /LANG FLAGS -->

						<!-- LOAD SPINNER -->
						<div class="spinner spinner-xs">
							<div class="double-bounce1"></div>
							<div class="double-bounce2"></div>
						</div>
						<!-- /LOAD SPINNER -->
					</li>
					<!-- /ITEM -->
				{/foreach}

				<!-- SANS VILLES -->
				<li id="no-city" class="item item-notif">
					<div class="itemAction">
					</div>
					<label class="itemLabel">Sans Ville</label>
					<div class="spinner spinner-xs">
						<div class="double-bounce1"></div>
						<div class="double-bounce2"></div>
					</div>
				</li>
				<!-- / SANS VILLES -->
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
	<!-- /ROW -->

	<!-- ROW -->
	<div class="arboItem">
		<!-- NAME -->
		<div class="columnName">
			<label>parcours</label>
		</div>

		<!-- DATA -->
		<div id="parcours"  class="columnData" limit="" child="interests" target="le parcours">
			<ul class="itemList">
				<li id="" class="item item-notif">
					<label class="itemLabel">Cliquez sur une ville<br>pour voir les parcours associés.</label>
				</li>
			</ul>
		</div>

		<!-- BTN LINK -->
		<div class="columnBtnLink">
			<a href="/edit/parcours.html">
				<i class="icon icon-create-roadMap"></i>
				Créer un parcours
			</a>
		</div>

	</div>
	<!-- /ROW -->

	<!-- ROW -->
	<div class="arboItem">
		<!-- NAME -->
		<div class="columnName">
			<label>point d'intérêt</label>
		</div>

		<!-- DATA -->
		<div id="interests"  class="columnData" limit="" target="le point d'interêt">
			<ul class="itemList">

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
	<!-- /ROW -->
</div>