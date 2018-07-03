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
								<i class="icon-pre icon-list-check"></i>
							</a>
						</div>
						<label class="itemLabel">{$model->title}</label>

						<div class="flag-cont {$model->force_lang|default: ''}">
							<i class="icon-pre icon-flag-fr"></i>
							<i class="icon-pre icon-flag-en"></i>
						</div>

						<div class="spinner spinner-xs">
							<div class="double-bounce1"></div>
							<div class="double-bounce2"></div>
						</div>
					</li>
					<!-- .\ ITEM -->
				{/foreach}

				
				<li id="no-city" class="item item-notif">
					<div class="itemAction">
					</div>
					<label class="itemLabel">Sans Ville</label>
					<div class="spinner spinner-xs">
						<div class="double-bounce1"></div>
						<div class="double-bounce2"></div>
					</div>
				</li>
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

		<!-- <li id="empty" class="item" title="">
			<div class="itemAction">
				<a class="delete-btn" href="/delete/">
					<i class="icon-pre icon-list-remove"></i>
				</a>
		
				<a href="/edit/">
					<i class="icon-pre icon-list-edit"></i>
				</a>
				
				<a  class="preview-btn">
					<i class="icon-pre icon-list-preview"></i>
				</a>
		
			</div>
		
			<label class="itemLabel"></label>
		
			<div class="flag-cont">	
				<i class="icon-pre icon-flag-fr"></i>
				<i class="icon-pre icon-flag-en"></i>
			</div>
		</li> -->

		<!-- DATA -->
		<div id="parcours"  class="columnData" limit="{$aParcours|count}" child="interests" target="le parcours">
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
	<!-- .\ ROW -->

	<!-- ROW -->
	<div class="arboItem">
		<!-- NAME -->
		<div class="columnName">
			<label>point d'intérêt</label>
		</div>

		<!-- DATA -->
		<div id="interests"  class="columnData" limit="{$aInterests|count}" target="le point d'interêt">
			<ul class="itemList">
				<!-- <li id="" class="item item-notif">
					<label class="itemLabel">Cliquez sur un parcours<br>pour voir les points d'intérêts associés.</label>
				</li> -->
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