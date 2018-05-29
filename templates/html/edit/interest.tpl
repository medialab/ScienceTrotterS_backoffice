	<div id="tab-FR" class="tab" style="display:block!important">
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="lang" value="">
			<input type="hidden" name="id" value="">
			

			<div class="box box-large">
				<label for="lieu">Nom du lieu</label>

				<input name="lieu" id="lieu" type="text" value="">
			</div>

			<div class="box">
				<label for="intitule">Intitulé du point d'intérêt *</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<input name="intitule" id="intitule" required type="text" value="">
			</div>

			<div class="box">
				<label for="ville">Ville *</label>

				<select name="ville" id="ville" required>
					<option value=""></option>
				</select>
			</div>

			<div class="box">
				<label for="intitule">Accroche du point d'intérêt *</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<input name="intitule" id="intitule" required type="text" value="">
			</div>

			<div class="box">
				<label for="parcours">Parcours *</label>

				<select name="parcours" id="parcours" required>
					<option value=""></option>
				</select>
			</div>

			<div class="box" id="box-Localisation">
				<label for="latitude">Géolocation</label>
				<p>Latitude, longitude du point d'intérêt</p>
				
				<input name="geo-n" id="latitude" type="number" step=".0001" placeholder="ex: 48.856" value="">
				<input name="geo-e" id="longitude" type="number" step=".0001" placeholder="ex: 2.3522" value="">
				
				<p>Avec Google Map, cliquez sur une adresse pour récupérer les coordonées GPS</p>
				<a id="localisation" href="https://www.google.com/maps" target="_blank" title="" class="item itemClick">https://www.google.com/maps</a>
			</div>

			<div class="box">
				<label for="img">Image</label>
				<p>
					Choisir une image illustrant le point d'intérêt.
					<br />Format 600x600px, png ou jpg, poids maximum 60ko
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

			<div class="box">
				<label for="horaires">Horaires</label>
				<p>ex. "mer-sam, 13h-17h"</p>

				<input name="horaires" id="horaires"  type="text" value="">
			</div>

<div class="box">
	<label for="audio">Audio</label>
	<p>5min ou 6Mo max, format .mp3 ou .wav</p>

	<input name="audio" id="audio"  type="text" value="">
</div>

			<div class="box">
				<label for="difficultes">Difficulté(s)</label>
				<p>ex. "payant (5€ tarif étudiant)"</p>

				<input name="difficultes" id="difficultes"  type="text" value="">
			</div>

			<div class="box">
				<label for="transport">Transport à proximité</label>
				<p>ex. "RER B Luxembourg"</p>

				<input name="transport" id="transport"  type="text" value="">
			</div>

			<div class="box">
				<label for="resume">Résumé du point d'intérêt *</label>
				<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum</p>

				<textarea name="resume" id="resume" required value=""></textarea>
			</div>

			<div class="box">
				<label for="bibliographie">Bibliographie</label>
				<p>5 maximum</p>

				<input name="bibliographie1" id="bibliographie1"  type="text" value="">
				<input name="bibliographie2" id="bibliographie2"  type="text" value="">
				<input name="bibliographie3" id="bibliographie3"  type="text" value="">
				<input name="bibliographie4" id="bibliographie4"  type="text" value="">
				<input name="bibliographie5" id="bibliographie5"  type="text" value="">
			</div>








<div class="box box-large">
	<label for="imgs-interet">Image(s) du point d'intérêt</label>
	<p>
		5 maximum, format 600x600px, png ou jpg, poids maximum 60ko
	</p>

	<div class="borderGrey flexInputFile">
		
		<input type="file" name="imgs-interet-1" id="imgs-interet-1" class="inputFile">
		<div class="blocInputFileName">
			<label class="btnInputFileName" for="imgs-interet-1">
				<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
				<p></p>
			</label>
		</div>

		<input type="file" name="imgs-interet-2" id="imgs-interet-2" class="inputFile">
		<div class="blocInputFileName">
			<label class="btnInputFileName" for="imgs-interet-2">
				<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
				<p></p>
			</label>
		</div>

		<input type="file" name="imgs-interet-3" id="imgs-interet-3" class="inputFile">
		<div class="blocInputFileName">
			<label class="btnInputFileName" for="imgs-interet-3">
				<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
				<p></p>
			</label>
		</div>

		<input type="file" name="imgs-interet-4" id="imgs-interet-4" class="inputFile">
		<div class="blocInputFileName">
			<label class="btnInputFileName" for="imgs-interet-4">
				<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
				<p></p>
			</label>
		</div>

		<input type="file" name="imgs-interet-5" id="imgs-interet-5" class="inputFile">
		<div class="blocInputFileName">
			<label class="btnInputFileName" for="imgs-interet-5">
				<img class="iconPreview" src="/media/image/interface/icons/icon_photo.svg" alt="" width="56" height="50">
				<p></p>
			</label>
		</div>

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