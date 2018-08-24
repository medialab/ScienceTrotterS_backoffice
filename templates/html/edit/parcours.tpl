{foreach $aLangs as $sIso => $sLang}
	{$oParc->setLang($sIso)}

	<div id="tab-{$sIso}" class="tab">

		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="lang" value="{$sIso}">
			<input type="hidden" name="id" value="{$oParc->id|default: 0}">
			
			<!-- TITRE -->
			<div class="box">
				<label for="parcours">
					Intitulé du Parcours *
					<i class="flag-ico"></i>
				</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum.</p>
				
				<input name="title" id="parcours" required placeholder="Ex: Le Chemin Vert" type="text" value="{$oParc->title|default: ''}" default="{$oParc->title|default: ''}" maxlength="90">
			</div>
			
			<!-- VILLE -->
			<div class="box" id="box-Ville">
				<label for="cities_id">Ville *</label>
				<p>La ville à la quelle le parcours appartient</p>
				
				{assign var="sCityID" value=$oParc->cities_id|default: false}
				<select name="cities_id" id="cities_id" default="{$oParc->cities_id}">
					<option value="">Choisir une ville</option>

					{foreach $aCities as $oCity}
						{$oCity->setLang('default')}
						<option value="{$oCity->id}" {if ($sCityID) == $oCity->id}selected{/if}>
							{$oCity->title}
						</option>
					{/foreach}
				</select>
			</div>

			<!-- COULEUR -->
			<div class="box box-small">
				<label>Couleur *</label>
				<p>Couleur du parcours</p>

				<div class="cust-color-selector" target='sel-color'>
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
									<span class="name">
										{$oColor->name}
									</span>
									<span class="taken" style="color: red;">
										Déjà Prise
									</span>
								</div>
							</div>
						{/foreach}
					</div>
					
					<input name="color" id="color" placeholder="Ex: #fff" type="text" value="{$oParc->color|default: ''}" default="{$oParc->color|default: ''}">
				</div>
			</div>
			
			<!-- DURÉE -->
			<div class="box box-small">
				<label for="time">
					Durée *
					<i class="flag-ico"></i>
				</label>
				<p>Durée du parcours.</p>
				
				<input name="time" id="time" placeholder="Ex: Entre 3h et 5h" type="text" value="{$oParc->time|default: ''}" default="{$oParc->time|default: ''}">
			</div>

			<!-- AUDIO -->
			<div class="box">
				<label for="audio-{$sIso}">
					Audio *
					<i class="flag-ico"></i>
				</label>
				{if $oParc->isSync() && !empty($oParc->audio)}
					<p class="audio-cnt">
						Écouté: 
						<b>
						</b> fois
					</p>
					<div class="spinner spinner-xs" style="display: inline-block; width: 15px; height: 15px">
						<div class="double-bounce1"></div>
						<div class="double-bounce2"></div>
					</div>
				{/if}
				<p>
					5min ou 10Mo max. format .mp3 ou .wav
				</p>
				
				<div class="borderGrey">
					<input type="file" name="audio" id="audio-{$sIso}" class="inputFile">
					
					<div class="blocInputFileName audio">
						<label class="btnInputFileName" for="audio-{$sIso}">
							{$audio = '/'|explode: ($oParc->audio|default: '')}
							{$audio = $audio[count($audio)-1]}
							{$audio = preg_replace('/_[0-9]+\.([^.]+)$/', '', $audio)}

							{$ext = []}
							{$i = preg_match('/(\.[a-z0-9]{2,5})$/i', $oParc->audio|default: '', $ext)}
							{$audio = $audio|cat: ($ext[0]|default: '')}

							<div class="audio-name" {if $oParc->audio|default:false}disabled{/if}>
								{if $audio|default: false}
									{$audio}
									
									<a href="{$_API_URL_}ressources/upload/{$oParc->audio}" target="blank" class="listen-file">
										<i title="modifier"></i>
									</a>

									<a class="delete" table="parcours" id="{$oParc->id}" model="Parcours" lang="{$sIso}"  type="audio" file="{$oParc->audio}" title="Supprimer le fichier">
										<i class="delete-file" ></i>
									</a>
								{/if}
							</div>
							<p></p>
						</label>
					</div>
				</div>

				{*<a href="" title="Supprimer l'audio'" class="item itemClick">Supprimer l'audio</a>*}
			</div>

			<!-- RÉSUMÉ -->
			<div class="box" id="box-Description">
				<label for="description">
					Résumé *
					<i class="flag-ico"></i>
				</label>
				<p>Tel qu'il apparaîtra sur l'application, 300 caractères maximum.</p>
				
				<textarea id="description" maxlength="300" name="description" default="{$oParc->description|default: ''}">{$oParc->description|default: ''}</textarea>
			</div>

			<!-- Audio Script -->
			<div class="box" id="box-Description">
				<label for="audio_script">
					Audio Script *
					<i class="flag-ico"></i>
				</label>
				<p>Tel qu'il apparaîtra sur l'application, 12 000 caractères maximum.</p>
				
				<textarea id="audio_script" maxlength="12000" name="audio_script" default="{$oParc->audio_script|default: ''}">{$oParc->audio_script|default: ''}</textarea>
			</div>

			<!-- Interêts -->
			<div id="box-interets" class="box box-interets">
				<label>Points d'interêts</label>
				{$count = count(($aInts|default: []))}

				<p class="cnt">
					{if !$oParc->isLoaded()}
						Veuillez enregister ce parcours<br>
						avant de pouvoir lui ajouter un point d'intérêt
					{elseif $count}
						<b>{$count}</b> points d'interêt associés à ce parcours
					{else}
						Aucun point d'interêt associé à ce parcours
					{/if}
				</p>

				{if $count}
					<p class="distance">
						Distance: <b><span></span></b>
					</p>
					
					<div class="spinner spinner-xs">
						<div class="double-bounce1"></div>
						<div class="double-bounce2"></div>
					</div>
					<p class="sep"> / </p>
					<p class="time">
						Temps Estimé: <b><span></span></b>
					</p>
					<div class="spinner spinner-xs">
						<div class="double-bounce1"></div>
						<div class="double-bounce2"></div>
					</div>
				{/if}
				
				<ul class="interest-list">
					{foreach $aInts|default: [] as $oInt}
						{$oInt->setLang($sIso)}
						<li {if $oInt->state}class="active"{/if}>
							<a href="/edit/interest/{$oInt->id}.html" target="_blank">{$oInt->title}</a>
						</li>
					{/foreach}
				</ul>
			</div>

			{if $oParc->isSync()}
				<div class="box" style="background-color: transparent; box-shadow: none; width: 100%">
					<div title="Preview" class="btn btn-lg">
						<a href="/edit/interest/@{$oParc->id}.html?force={$oParc->force_lang}" target="_blank">
							<img src="/media/image/interface/icons/icon_poi_blue.svg">
							Créer un nouveau Point d'Interêt
						</a>
					</div>
				</div>
			{/if}

			<div class="boolean {if $oParc->state|default: false}on{/if}">
				<input id="publie" type="checkbox" name="state" {if $oParc->state|default: false}checked{/if} default="{$oParc->state|default: ''}">
				<label for="publie" data="on">Public</label>

				<div class="style">
					<div></div>
				</div>

				<label for="publie" data='off'>Privé</label>
			</div>

			<button class="btn submit" type="submit">Envoyer</button>
		</form>

		<input id="curState" type="hidden" value="{if $oParc->state|default: false}1{else}0{/if}">
	</div>
{/foreach}