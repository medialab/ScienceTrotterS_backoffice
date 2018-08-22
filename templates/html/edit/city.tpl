{foreach $aLangs as $sIso => $sLang}
	{$oCity->setLang($sIso)}

	<div id="tab-{$sIso}" class="tab">
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="lang" value="{$sIso}">
			<input type="hidden" name="id" value="{$oCity->id|default: 0}">
			
			<div class="box box-large">
				<label for="ville-{$sIso}">
					Nom de la ville *
				</label>
				<p>Tel qu'il apparaîtra sur l'application</p>
				
				<input name="title" id="ville-{$sIso}" maxlength="90" required placeholder="Ex: Paris" type="text" value="{$oCity->title|default: ''}" default="{$oCity->title|default: ''}">
			</div>
			
			<div class="box" id="box-Localisation">
				<label for="latitude">Géolocation *</label>
				<p>Latitude, longitude</p>
				
				<input name="geo-n" id="latitude" type="text" class="geo-input" pattern="{$sGeoPat}" placeholder="ex: 48.856" value="{$oCity->geoN|default: ''}" default="{$oCity->geoN|default: ''}">
				<input name="geo-e" id="longitude" type="text" class="geo-input" pattern="{$sGeoPat}" placeholder="ex: 2.3522" value="{$oCity->geoE|default: ''}" default="{$oCity->geoE|default: ''}">

				<p>Avec <i>Coordonnées GPS</i>, recherchez une adresse pour récupérer les coordonées</p>
				<a id="localisation" href="https://www.coordonnees-gps.fr/" target="_blank" class="item itemClick">https://www.coordonnees-gps.fr/</a>
			</div>
			<div class="box">
				<label for="img-{$sIso}">Image *</label>
				<p>
					Choisir une image illustrant au mieux la ville.
					<br />Format 600x600px, png ou jpg, poids maximum 60k.
				</p>
				
				<div class="borderGrey">

					<div class="blocInputFileName">
						<input type="file" name="img" id="img-{$sIso}" target="img" class="inputFile" default="{$oCity->image|default: ''}">
						
						<label class="btnInputFileName" for="img-{$sIso}">
							{$image = '/'|explode: ($oCity->image|default: '')}
							{$image = $image[count($image)-1]}
							{$image = preg_replace('/_[0-9]+\.([^.]+)$/', '', $image)}

							{$ext = []}
							{$i = preg_match('/(\.[a-z0-9]{2,5})$/i', $oCity->image|default: '', $ext)}
							{$image = $image|cat: ($ext[0]|default: '')}

							{if $oCity->image|strlen}
								<img src="{$_API_URL_}ressources/upload/{$oCity->image}" style="max-width: 100%;">
							{else}
								<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
							{/if}

							<div class="audio-name" disabled="">
								<a href="{$_API_URL_}ressources/upload/{$oCity->image}" target="_blank">{$image}</a>

								{if $oCity->image|strlen}
									<a class="delete" table="cities" id="{$oCity->id}" model="City" lang="{$sIso}"  type="image" file="{$oCity->image}" title="Supprimer le fichier">
										<i class="delete-file" ></i>
									</a>
								{/if}
							</div>

							<p></p>
						</label>
					</div>
				</div>

				<p>
					Avec Jpeg.io, optimisez le poid de vos images
					<br />Attention: les photos doivent être libres de droits.
				</p>
				<a href="https://www.jpeg.io/" target="_blank" title="Optimiser vos images" class="item itemClick">https://www.jpeg.io/</a>
			</div>


			{if $oCity->isSync()}
				<!-- <div class="box" style="background-color: transparent; box-shadow: none">
					<div href="#" title="Preview" class="btn" style="margin-top: 0;">
						<a href="">
							<img src="/media/image/interface/icons/icon_edit_preview.svg">
							Preview
						</a>
					</div>
				</div> -->

				<div class="box" style="background-color: transparent; box-shadow: none; width: 100%">
					<div title="Preview" class="btn btn-lg" style="margin-top: 0;">
						<a href="/edit/parcours/@{$oCity->id}.html?force={$oCity->force_lang}" target="_blank">
							<img src="/media/image/interface/icons/icon_create_roadMap.svg">
							Créer un nouveau Parcours
						</a>
					</div>
				</div>
			{/if}
			
			<div class="boolean {if $oCity->state|default: false}on{/if}">
				<input id="publie" type="checkbox" name="state" {if $oCity->state|default: false}checked{/if} default="{$oCity->state|default: false}" default="{$oCity->state|default: ''}" />
				<label for="publie" data="on">Public</label>

				<div class="style">
					<div></div>
				</div>

				<label for="publie" data='off'>Privé</label>
			</div>

			<button class="btn submit" type="submit">Envoyer</button>
		</form>
	</div>
{/foreach}