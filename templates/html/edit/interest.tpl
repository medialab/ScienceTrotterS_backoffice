	<div id="tab-FR" class="tab" style="display:block!important">
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="lang" value="">
			<input type="hidden" name="id" value="">
			
			<div class="box">
				<label for="ville">Nom de la ville *</label>
				<p>Tel qu'il apparaîtra sur l'application</p>
				
				<input name="label" id="ville" required placeholder="Ex: Paris" type="text" value="">
			</div>
			
			<div class="box" id="box-Localisation">
				<label for="latitude">Géolocation *</label>
				<p>Latitude, longitude</p>
				
				<input name="geo-n" id="latitude" type="number" step=".0001" placeholder="ex: 48.856" value="">
				<input name="geo-e" id="longitude" type="number" step=".0001" placeholder="ex: 2.3522" value="" />
				
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
						<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
						<p></p>
					</label>
				</div>

				<p>Avec Jpeg.io, optimisez le poid de vos images</p>
				<a href="https://www.jpeg.io/" target="_blank" title="Optimiser vos images" class="item itemClick">https://www.jpeg.io/</a>
			</div>

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
					<a href="">
						<img src="/media/image/interface/icons/icon_create_roadMap.svg">
						Créer un nouveau Parcours
					</a>
				</div>
			</div>
			
			<div class="boolean">
				<input id="publie" type="checkbox" name="state" />
				<label for="publie" data="on">Publié</label>

				<div class="style">
					<div></div>
				</div>

				<label for="publie" data='off'>Brouillon</label>
			</div>

			<button class="btn submit" type="submit">Envoyer</button>
		</form>
	</div>