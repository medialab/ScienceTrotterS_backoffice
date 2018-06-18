{foreach $aLangs as $sIso => $sLang}
	{$oInt->setLang($sIso)}

	<div id="tab-{$sIso}" class="tab">
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="lang" value="{$sIso}">
			<input type="hidden" name="id" value="{$oInt->id}">

			<div class="box">
				<label for="intitule">Intitulé du point d'intérêt *</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<input name="title" id="intitule" required type="text" value="{$oInt->title}">
			</div>

			<div id="box-Ville" class="box">
				<label for="ville">Ville *</label>
				<select name="cities_id" id="ville">
					<option value="">Choisir une ville</option>
					{assign var="sCityID" value=$smarty.post.cities_id|default: $oInt->cities_id:$oCity->id:false}

					{foreach $aCities as $city_id => $sCity}
						<option value="{$city_id}" {if ($sCityID) == $city_id}selected{/if}>
							{$sCity}
						</option>
					{/foreach}
				</select>
			</div>

			<div class="box">
				<label for="address">Accroche du point d'intérêt *</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<input name="address" id="address" type="text" value="{$oInt->address}">
			</div>

			{assign var="sParcID" value=$smarty.post.par_id|default: $oInt->parcours_id:$curParc['id']:false}
			<div class="box">
				<label for="parcours">Parcours *</label>

				<select name="parcours_id" id="parcours">
					<option value="">Choisir un parcours</option>

					{foreach $aParcours as $par_id => $parc}
						<option value="{$par_id}" {if ($sParcID) == $par_id}selected{/if}>
							{$parc['title']}
						</option>
					{/foreach}
				</select>
			</div>

			<div class="box" id="box-Localisation">
				<label for="latitude">Géolocation</label>
				<p>Latitude, longitude du point d'intérêt</p>
				
				<input name="geo-n" id="latitude" type="text" pattern="{$sGeoPat}" placeholder="ex: 48.856" value="{$oInt->geoN}">
				<input name="geo-e" id="longitude" type="text" pattern="{$sGeoPat}" placeholder="ex: 2.3522" value="{$oInt->geoE}">
				
				<p>Avec Google Map, cliquez sur une adresse pour récupérer les coordonées GPS</p>
				<a id="localisation" href="https://www.google.com/maps" target="_blank" title="" class="item itemClick">https://www.google.com/maps</a>
			</div>

			<div class="box">
				<label for="img">Image</label>
				<p>
					Choisir une image illustrant le point d'intérêt.
					<br />Format 600x600px, png ou jpg, poids maximum 60ko
				</p>
				
				<div class="borderGrey">
					<input type="file" name="img" id="img" class="inputFile">
					<div class="blocInputFileName">
						<label class="btnInputFileName" for="img">
							{if $oInt->header_image|strlen}
								<img src="{$_API_URL_}ressources/upload/{$oInt->header_image}" style="max-width: 100%;">
							{else}
								<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
							{/if}
							<p></p>
						</label>
					</div>
				</div>

				<p>Avec Jpeg.io, optimisez le poid de vos images</p>
				<a href="https://www.jpeg.io/" target="_blank" title="Optimiser vos images" class="item itemClick">https://www.jpeg.io/</a>
			</div>

			<div class="box">
				<label for="horaires">Horaires</label>
				<p>ex. "mer-sam, 13h-17h"</p>

				<input name="schedule" id="horaires"  type="text" value="{$oInt->schedule}">
			</div>

			<!-- AUDIO -->
			<div class="box">
				<label for="audio-{$sIso}">Audio *</label>
				<p>
					5min ou 6Mo max. format .mp3 ou .wav
				</p>
				
				<div class="borderGrey">
					<input type="file" name="audio" id="audio-{$sIso}" class="inputFile">
					
					<div class="blocInputFileName audio">
						<label class="btnInputFileName" for="audio">
							{$audio = '/'|explode: ($oInt->audio|default: '')}
							{$audio = $audio[count($audio)-1]}

							<div class="audio-name" {if $oInt->audio|default:false}disabled{/if}>
								{$audio|default: ''}
							</div>
							<p></p>
						</label>
					</div>
				</div>

				<a href="" title="Supprimer l'audio'" class="item itemClick">Supprimer l'audio</a>
			</div>

			<div class="box">
				<label for="difficultes">Difficulté(s)</label>
				<p>ex. "payant (5€ tarif étudiant)"</p>

				<input name="price" id="difficultes"  type="text" value="{$oInt->price}">
			</div>

			<div class="box">
				<label for="transport">Transport à proximité</label>
				<p>ex. "RER B Luxembourg"</p>

				<input name="transport" id="transport"  type="text" value="{$oInt->transport}">
			</div>

			<div class="box">
				<label for="description">Résumé du point d'intérêt *</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<textarea name="audio_script" id="description" value="">{$oInt->audio_script}</textarea>
			</div>

			<div class="box">
				<label for="bibliographie">Bibliographie</label>
				<p>5 maximum</p>

				<input name="bibliography[]" id="bibliography1"  type="text" value="{$oInt->bibliography[0]|default: ''}">
				<input name="bibliography[]" id="bibliography2"  type="text" value="{$oInt->bibliography[1]|default: ''}">
				<input name="bibliography[]" id="bibliography3"  type="text" value="{$oInt->bibliography[2]|default: ''}">
				<input name="bibliography[]" id="bibliography4"  type="text" value="{$oInt->bibliography[3]|default: ''}">
				<input name="bibliography[]" id="bibliography5"  type="text" value="{$oInt->bibliography[4]|default: ''}">
			</div>


			<div class="box box-large" id="box-imgs-interest">
				<label for="imgs-interet">Image(s) du point d'intérêt</label>
				<p>
					5 maximum, format 600x600px, png ou jpg, poids maximum 60ko
				</p>

				<div class="borderGrey flexInputFile">
					{for $index=0 to 4}
						{$sImg = $oInt->gallery_image[$index]|default: false}
						{if $sImg}
							<input type="file" name="imgs-interet-{$index}" id="imgs-interet-{$index}" class="inputFile">
							<div class="blocInputFileName">
								<label class="btnInputFileName" for="imgs-interet-{$index}">
									<img class="iconPreview" src="{$_API_URL_}ressources/upload/{$sImg}" alt="" width="56" height="50">
									<p>{$sImg}</p>
								</label>
							</div>
						{else}
							<input type="file" name="imgs-interet[{$index}]" id="imgs-interet-{$index}" class="inputFile">
							<div class="blocInputFileName">
								<label class="btnInputFileName" for="imgs-interet-{$index}">
									<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
									<p></p>
								</label>
							</div>
						{/if}
					{/for}

				</div>

				<p>Avec Jpeg.io, optimisez le poid de vos images</p>
				<a href="https://www.jpeg.io/" target="_blank" title="Optimiser vos images" class="item itemClick">https://www.jpeg.io/</a>
			</div>

			<div class="box box-large" style="background-color: transparent; box-shadow: none">
				<div href="#" title="Preview" class="btn">
					<a href="">
						<img src="/media/image/interface/icons/icon_edit_preview.svg">
						Preview
					</a>
				</div>
			</div>
			
			<div class="boolean {if $oInt->state|default: false}on{/if}">
				<input id="publie" type="checkbox" name="state" {if $oInt->state|default: false}checked{/if}/>
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