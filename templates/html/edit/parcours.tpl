
{foreach $aLangs as $sIso => $sLang}
	{$oParc->setLang($sIso)}

	<div id="tab-{$sIso}" class="tab">

		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="lang" value="{$sIso}">
			<input type="hidden" name="id" value="{$oParc->id|default: 0}">
			
			<!-- TITRE -->
			<div class="box">
				<label for="parcours">Intitulé du Parcours *</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum.</p>
				
				<input name="title" id="parcours" required placeholder="Ex: Le Chemin Vert" type="text" value="{$smarty.post.title|default: $oParc->title: ''}">
			</div>
			
			<!-- VILLE -->
			<div class="box" id="box-Ville">
				<label for="cities_id">Ville *</label>
				<p>La ville à la quelle le parcours appartient</p>
				
				{assign var="sCityID" value=$smarty.post.cities_id|default: $oParc->cities_id:false}
				<select name="cities_id" id="cities_id">
					<option value="">Choisir une ville</option>

					{foreach $aCities as $oCity}
						<option value="{$oCity->id}" {if ($sCityID) == $oCity->id}selected{/if}>
							{$oCity->title}
						</option>
					{/foreach}
				</select>
			</div>

			<!-- COULEUR -->
			<div class="box box-small">
				<label for="color">Couleur *</label>
				<p>Couleur du parcours</p>

				<!-- <div class="cust-color-selector" target='sel-color'>
					<div class="sel-opt opt-selected">
						<div class="opt-color" style="background-color: transparent">
						</div>
				
						<div class="opt-text">
							Choisissez une Couleur
						</div>
					</div>
				
					<div class="option-container">
						{foreach $aColors as $oColor}
							<div class="sel-opt" value="{$oColor->color}">
								<div class="opt-color" style="background-color: {$oColor->color}">
									
								</div>
								<div class="opt-text">
									{$oColor->name}
								</div>
							</div>
						{/foreach}
					</div>
				</div> -->
				
				<select  name="color" id="sel-color">
					<option value="">Choisissez une Couleur</option>
					
					{foreach $aColors as $oColor}
						{$selected = $oColor->color == $oParc->color}
						<option value="{$oColor->color}" {if $selected}selected{/if}>{$oColor->name}</option>
					{/foreach}
				</select>
				<!-- <input name="color" id="color" placeholder="Ex: #fff" type="color" value="{$smarty.post.color|default: $oParc->color: ''}"> -->
			</div>
			
			<!-- DURÉE -->
			<div class="box box-small">
				<label for="time">Durée *</label>
				<p>Durée du parcours.</p>
				
				<input name="time" id="time" placeholder="Ex: Entre 3h et 5h" type="text" value="{$smarty.post.time|default: $oParc->time: ''}">
			</div>

			<!-- AUDIO -->
			<div class="box">
				<label for="audio">Audio *</label>
				<p>
					5min ou 6Mo max. format .mp3 ou .wav
				</p>
				
				<div class="borderGrey">
					<input type="file" name="audio" id="audio" class="inputFile">
					
					<div class="blocInputFileName audio">
						<label class="btnInputFileName" for="audio">
							{$audio = '/'|explode: ($oParc->audio|default: '')}
							{$audio = $audio[count($audio)-1]}

							<div class="audio-name" {if $oParc->audio|default:false}disabled{/if}>
								{($audio)|default: ''}
							</div>
							<p></p>
						</label>
					</div>
				</div>

				<a href="" title="Supprimer l'audio'" class="item itemClick">Supprimer l'audio</a>
			</div>

			<!-- RÉSUMÉ -->
			<div class="box" id="box-Description">
				<label for="description">Résumé</label>
				<p>Tel qu'il apparaîtra sur l'application, 600 caractères maximum.</p>
				
				<textarea id="description" name="description">{$smarty.post.description|default: $oParc->description: ''}</textarea>
			</div>

			<!-- Interêts -->
			<div class="box" id="box-Interets">
				<label >Points d'interêts</label>
				{$count = count($aInts)}
				<p>
					{if !$oParc->isLoaded()}
						Veuillez enregister ce parcours<br>
						avant de pouvoir lui ajouter un point d'intérêt
					{elseif $count}
						{$count} points d'interêt associés à ce parcours
					{else}
						Aucun point d'interêt associé à ce parcours
					{/if}
				</p>
				
				<ul class="interest-list">
					{foreach $aInts as $oInt}
						<li {if $oInt->state}class="active"{/if}>
							<a href="/edit/interest/{$oInt->id}.html">{$oInt->title}</a>
						</li>
					{/foreach}
				</ul>
			</div>

			{if $oParc->isSync()}
				<div class="box" style="background-color: transparent; box-shadow: none">
					<div href="#" title="Preview" class="btn">
						<a href="">
							<img src="/media/image/interface/icons/icon_edit_preview.svg">
							Preview
						</a>
					</div>
				</div>

				<div class="box" style="background-color: transparent; box-shadow: none">
					<div href="#" title="Preview" class="btn btn-lg">
						<a href="/edit/interest/@{$oParc->id}.html">
							<img src="/media/image/interface/icons/icon_create_roadMap.svg">
							Créer un nouveau Point d'Interêt
						</a>
					</div>
				</div>
			{/if}

			<div class="boolean {if $oParc->state|default: false}on{/if}">
				<input id="publie" type="checkbox" name="state" {if $oParc->state|default: false}checked{/if} />
				<label for="publie" data="on">Publié</label>

				<div class="style">
					<div></div>
				</div>

				<label for="publie" data='off'>Brouillon</label>
			</div>

			<button class="btn submit" type="submit">Envoyer</button>
		</form>
	</div>
{/foreach}