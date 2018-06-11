<div>
	<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="id" value="{$oParc->id|default: 0}">
		
		<!-- TITRE -->
		<div class="box">
			<label for="parcours">Intitulé du Parcours *</label>
			<p>Tel qu'il apparaîtra sur l'application, 90 caractères maximum.</p>
			
			<input name="title" id="parcours" required placeholder="Ex: Le Chemin Vert" type="text" value="{$smarty.post.title|default: $oParc->title: ''}">
		</div>
		
		<!-- VILLE -->
		<div class="box" id="box-Ville">
			<label for="city_id">Ville *</label>
			<p>La ville à la quelle le parcours appartient</p>
			
			<select name="city_id" id="city_id">
				{assign var="sCityID" value=$smarty.post.city_id|default: $oParc->city_id:false}

				{foreach $aCities as $oCity}
					<option value="{$oCity->id}" {if ($sCityID) == $oCity->id}selected{/if}>
						{$oCity->label}
					</option>
				{/foreach}
			</select>
		</div>

		<!-- COULEUR -->
		<div class="box">
			<label for="color">Couleur *</label>
			<p>Couleur du parcours</p>
			
			<input name="color" id="color" placeholder="Ex: #fff" type="color" value="{$smarty.post.color|default: $oParc->color: ''}">
		</div>
		
		<!-- DURÉE -->
		<div class="box">
			<label for="time">Durée *</label>
			<p>Durée du parcours.</p>
			
			<input name="time" id="time" placeholder="Ex: Entre 3h et 5h" type="text" value="{$smarty.post.time|default: $oParc->time: ''}">
		</div>

		<!-- AUDIO -->
		<div class="box">
			<label for="img">Audio *</label>
			<p>
				5min ou 6Mo max. format .mp3 ou .wav
			</p>
			
			<input type="file" name="audio" id="audio" class="inputFile">
			
			<div class="borderGrey">
				
			</div>

			<p>Avec Jpeg.io, optimisez le poid de vos images</p>
			<a href="" title="Supprimer l'audio'" class="item itemClick">Supprimer l'audio</a>
		</div>

		<!-- RÉSUMÉ -->
		<div class="box" id="box-Description">
			<label for="description">Résumé</label>
			<p>Tel qu'il apparaîtra sur l'application, 600 caractères maximum.</p>
			
			<textarea id="description" name="description">
				{$smarty.post.description|default: $oParc->description: ''}
			</textarea>
		</div>

		<!-- RÉSUMÉ -->
		<div class="box" id="box-Description">
			<label for="description">Points d'interêts</label>
			<p>Aucun point d'interêt associé à ce parcours</p>
			
			<div>
			</div>
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