
		<div>
			<form method="post" enctype="multipart/form-data">
				<input type="hidden" name="id" value="{$oCity->id|default: 0}">
				<div class="box">
					<label for="ville">Nom de la ville *</label>
					<p>Tel qu'il apparaîtra sur l'application</p>
					<input name="label" id="ville" required placeholder="Ex: Paris" type="text" value="{$smarty.post.label|default: $oCity->label: ''}">
				</div>
				<div class="box" id="box-Localisation">
					<label for="latitude">Géolocation *</label>
					<p>Latitude, longitude</p>
					<input name="geo-n" id="latitude" type="number" step=".0001" placeholder="ex: 48.856" value="{$smarty.post['geo-n']|default: $oCity->geoN: ''}">
					<input name="geo-e" id="longitude" type="number" step=".0001" placeholder="ex: 2.3522" value="{$smarty.post['geo-e']|default: $oCity->geoE: ''}">
					<p>Avec Google Map, cliquez sur une adresse pour récupérer les coordonées GPS</p>
					<a id="localisation" href="https://www.google.com/maps" target="_blank" title="" class="item itemClick">https://www.google.com/maps</a>
				</div>
				<div class="box">
					<label for="img">Image *</label>
					<p>
						Choisir une image illustrant au mieux la ville.
						<br />Format 600x600px, png ou jpg, poids maximum 60k
					</p>
					<input type="file" name="img" id="img" class="inputFile">
					<div class="borderGrey">
						<label id="btnInputFileName" for="img">
							{if $oCity->image|strlen}
								<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
							{else}
								<img src="{$_API_URL_}/ressources/upload/{$oCity->image}" style="max-width: 100%;">
							{/if}
							<p></p>
						</label>
					</div>
					<p>Avec Jpeg.io, optimisez le poid de vos images</p>
					<a href="https://www.jpeg.io/" target="_blank" title="Optimiser vos images" class="item itemClick">https://www.jpeg.io/</a>
				</div>
				<a href="#" title="Preview" class="btn">
					<img src="/media/image/interface/icons/icon_edit_preview.svg" alt="" width="18px" height="10px">
					Preview
				</a>
				<div class="boolean {if $oCity->state|default: false}on{/if}">
					<input type="radio" name="state" id="publie" value="1" />
					<label for="publie">Publié</label>
					<div class="style">
						<div></div>
					</div>
					<label for="brouillon" style="color:grey">Brouillon</label>
					<input type="radio" name="state" id="brouillon" value="0" />
				</div>

				<button class="btn submit" type="submit">Envoyer</button>
			</form>
		</div>





	</div>
</div>