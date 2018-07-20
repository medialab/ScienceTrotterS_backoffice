{foreach $aLangs as $sIso => $sLang}
	{$oInt->setLang($sIso)}

	<div id="tab-{$sIso}" class="tab">
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="lang" value="{$sIso}">
			<input type="hidden" name="id" value="{$oInt->id}">

			<div class="box">
				<label for="intitule-{$sIso}">
					Intitulé du point d'intérêt *
					<i class="flag-ico"></i>
				</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<input name="title" id="intitule-{$sIso}" required type="text" value="{$oInt->title}">
			</div>

			<div id="box-Ville" class="box">
				<label for="ville-{$sIso}">Ville *</label>
				<select name="cities_id" id="ville-{$sIso}">
					<option value="">Choisir une ville</option>
					{assign var="sCityID" value=$smarty.post.cities_id|default: $oInt->cities_id:$oCity->id:false}

					{foreach $aCities as $city_id => $oCity}
						{if $oCity->force_lang|default: false}
							{$oCity->setLang($oCity->force_lang)}
						{else}
							{$oCity->setLang($sIso)}
						{/if}
						<option value="{$city_id}" {if ($sCityID) == $city_id}selected{/if}>
							{$oCity->title}
						</option>
					{/foreach}
					
					{assign var="sCityID" value=false}
				</select>
			</div>

			<div class="box">
				<label for="address-{$sIso}">
					Accroche du point d'intérêt *
					<i class="flag-ico"></i>
				</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<input name="address" id="address-{$sIso}" type="text" value="{$oInt->address}">
			</div>

			{assign var="sParcID" value=$smarty.post.par_id|default: $oInt->parcours_id:$curParc['id']:false}
			<div class="box">
				<label for="parcours-{$sIso}">Parcours</label>
				<select name="parcours_id" id="parcours-{$sIso}">
					<option value="0" {if !strlen($oInt->parcours_id)} selected {/if}>
						Hors Parcours
					</option>

					{$sLastCity = false}
					{foreach $aParcours as $par_id => $parc}
						{if $parc->force_lang|default: false}
							{$parc->setLang($parc->force_lang)}
						{else}
							{$parc->setLang($sIso)}
						{/if}
						{$bGroup = $sLastCity !== $parc->city->id}

						{if $bGroup}
							{if $sLastCity !== false}
								</optgroup>
							{/if}

							<optgroup target="{$parc->city->id}" label="{$parc->city->title}">
							{$sLastCity = $parc->city->id}
						{/if}
								
						<option value="{$par_id}" {if ($sParcID) == $par_id}selected{/if}>
							{$parc->title}
						</option>
					{/foreach}
						</optgroup>

						<option disabled class="notfound">
							Aucun Parcours Trouvé.
						</option>
				</select>
			</div>

			<div class="box" id="box-Localisation">
				<label for="latitude-{$sIso}">Géolocation *</label>
				<p>Latitude, longitude du point d'intérêt</p>
				
				<input name="geo-n" id="latitude-{$sIso}" type="text" class="geo-input" pattern="{$sGeoPat}" placeholder="ex: 48.856" value="{$oInt->geoN}">

				<input name="geo-e" id="longitude" type="text" class="geo-input" pattern="{$sGeoPat}" placeholder="ex: 2.3522" value="{$oInt->geoE}">
				
				<p>Avec <i>Coordonnées GPS</i>, recherchez une adresse pour récupérer les coordonées</p>
				<a id="localisation" href="https://www.coordonnees-gps.fr/" target="_blank" class="item itemClick">https://www.coordonnees-gps.fr/</a>
			</div>

			<div class="box">
				<label for="img-{$sIso}">Image *</label>
				<p>
					Choisir une image illustrant le point d'intérêt.
					<br />Format 600x600px, png ou jpg, poids maximum 60ko
				</p>
				
				<div class="borderGrey">
					<input type="file" name="img" id="img-{$sIso}" target="img" class="inputFile">
					<div class="blocInputFileName">
						<label class="btnInputFileName" for="img-{$sIso}">
							{$image = '/'|explode: ($oInt->header_image|default: '')}
							{$image = $image[count($image)-1]}
							{$image = preg_replace('/_[0-9]+\.([^.]+)$/', '', $image)}

							{$ext = []}
							{$i = preg_match('/(\.[a-z0-9]{2,5})$/i', $oInt->header_image|default: '', $ext)}
							{$image = $image|cat: ($ext[0]|default: '')}

							{if $oInt->header_image|strlen}
								<img src="{$_API_URL_}ressources/upload/{$oInt->header_image}" style="max-width: 100%;">
							{else}
								<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
							{/if}

							<div class="audio-name" disabled="">
								<a href="{$_API_URL_}ressources/upload/{$oInt->header_image}" target="_blank">{$image}</a>
							</div>

							<p></p>
						</label>
					</div>
				</div>

				<p>Avec Jpeg.io, optimisez le poid de vos images</p>
				<a href="https://www.jpeg.io/" target="_blank" title="Optimiser vos images" class="item itemClick">https://www.jpeg.io/</a>
			</div>

			<div class="box">
				<label for="horaires-{$sIso}">
					Horaires *
					<i class="flag-ico"></i>
				</label>
				<p>ex. "mer-sam, 13h-17h"</p>

				<input name="schedule" id="horaires-{$sIso}" type="text" value="{$oInt->schedule}">
			</div>

			<!-- AUDIO -->
			<div class="box">
				<label for="audio-{$sIso}">
					Audio *
					<i class="flag-ico"></i>
				</label>
				<p>
					5min ou 20Mo max. format .mp3 ou .wav
				</p>
				
				<div class="borderGrey">
					<input type="file" name="audio" id="audio-{$sIso}" class="inputFile">
					
					<div class="blocInputFileName audio">
						<label class="btnInputFileName" for="audio-{$sIso}">
							{$audio = '/'|explode: ($oInt->audio|default: '')}
							{$audio = $audio[count($audio)-1]}
							{$audio = preg_replace('/_[0-9]+\.([^.]+)$/', '', $audio)}


							{$ext = []}
							{$i = preg_match('/(\.[a-z0-9]{2,5})$/i', $oInt->audio|default: '', $ext)}
							{$audio = $audio|cat: ($ext[0]|default: '')}

							<div class="audio-name" {if $oInt->audio|default:false}disabled{/if}>
								{$audio|default: ''}
							</div>
							<p></p>
						</label>
					</div>
				</div>

				{*<a href="" title="Supprimer l'audio'" class="item itemClick">Supprimer l'audio</a>*}
			</div>

			<div class="box">
				<label for="difficultes-{$sIso}">
					Difficulté(s) *
					<i class="flag-ico"></i>
				</label>
				<p>ex. "payant (5€ tarif étudiant)"</p>

				<input name="price" id="difficultes-{$sIso}"  type="text" value="{$oInt->price}">
			</div>

			<div class="box">
				<label for="transport-{$sIso}">
					Transport à proximité *
					<i class="flag-ico"></i>
				</label>
				<p>ex. "RER B Luxembourg"</p>

				<input name="transport" id="transport-{$sIso}"  type="text" value="{$oInt->transport}">
			</div>

			<div class="box">
				<label for="description-{$sIso}">
					Description du point d'intérêt *
					<i class="flag-ico"></i>
				</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<textarea name="description" id="description-{$sIso}" value="">{$oInt->description}</textarea>
			</div>

			<div class="box">
				<label for="audio_script-{$sIso}">
					Script Audio *
					<i class="flag-ico"></i>
				</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<textarea name="audio_script" id="audio_script-{$sIso}" value="">{$oInt->audio_script}</textarea>
			</div>

			<div class="box">
				<label for="bibliographie">
					Bibliographie
					<i class="flag-ico"></i>
				</label>
				<p>5 maximum</p>

				<input name="bibliography[]" id="bibliography1"  type="text" value="{$oInt->bibliography[0]|default: ''}">
				<input name="bibliography[]" id="bibliography2"  type="text" value="{$oInt->bibliography[1]|default: ''}">
				<input name="bibliography[]" id="bibliography3"  type="text" value="{$oInt->bibliography[2]|default: ''}">
				<input name="bibliography[]" id="bibliography4"  type="text" value="{$oInt->bibliography[3]|default: ''}">
				<input name="bibliography[]" id="bibliography5"  type="text" value="{$oInt->bibliography[4]|default: ''}">
			</div>


			<div class="box box-large" id="box-imgs-interest">
				<label for="imgs-interet">Image(s) du point d'intérêt *</label>
				<p>
					5 maximum, format 600x600px, png ou jpg, poids maximum 60ko
				</p>

				<div id="gallery-container" class="borderGrey flexInputFile">
					{for $index=0 to 4}
						{$sImg = $oInt->gallery_image->$index|default: ''}
						{$sName = '/'|explode: ($sImg|default: '')}
						{$sName = $sName[count($sName)-1]}
						{$sName = preg_replace('/_[0-9]+\.([^.]+)$/', '', $sName)}

						{if $sImg}
							<div class="blocInputFileName">
								<input type="file" name="imgs-interet[{$index}]" id="imgs-interet-{$index}" target="img" class="inputFile {if $sImg}hasFile{/if}">

								<label class="btnInputFileName" index="{$index}">
									<img class="iconPreview" src="{$_API_URL_}ressources/upload/{$sImg}" index="{$index}" alt="" width="56" height="50">
									<p>{preg_replace('/_[0-9]+$/', '', $sName)}</p>
								</label>
							</div>
						{else}
							<div class="blocInputFileName">
								<input type="file" name="imgs-interet[{$index}]" id="imgs-interet-{$index}" target="img" class="inputFile {if $sImg}hasFile{/if}">
								<label class="btnInputFileName" index="{$index}">
									<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" index="{$index}" alt="" width="56" height="50">
									<p></p>
								</label>
							</div>
						{/if}
					{/for}

				</div>

				<p>Avec Jpeg.io, optimisez le poid de vos images</p>
				<a href="https://www.jpeg.io/" target="_blank" title="Optimiser vos images" class="item itemClick">https://www.jpeg.io/</a>
			</div>

			{if $oInt->isSync()}
				<div class="box box-large" style="background-color: transparent; box-shadow: none">
					<div href="#" title="Preview" class="btn">
						<a href="">
							<img src="/media/image/interface/icons/icon_edit_preview.svg">
							Preview
						</a>
					</div>
				</div>
			{/if}
			
			<div class="boolean {if $oInt->state|default: false}on{/if}">
				<input id="publie" type="checkbox" name="state" {if $oInt->state|default: false}checked{/if}/>
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