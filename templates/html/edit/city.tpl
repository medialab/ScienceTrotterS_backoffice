{$oCity|var_dump}
<div>
	<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="id" value="{$oCity->id|default: 0}">
		<div class="inline">
			<label>
				Nom:
				<br>
				<input name="label" required placeholder="Ex: Paris" type="text" value="{$smarty.post.label|default: $oCity->label: ''}">
			</label>
		</div>
		<div class="inline">
			<label>
				Active:
				<br>
				{$oCity->state|var_dump}
				{if $oCity->state|default: false}checked{else}NAIN{/if}
				<input name="state" type="checkbox" {if $oCity->state|default: false}checked{/if}">
			</label>
		</div>
		<div class="inline">
			<label>
				Geolocation:
				<br>
				<input name="geo-n" type="number" step=".0001" placeholder="ex: 48.856" value="{$smarty.post['geo-n']|default: $oCity->geoN: ''}">° N
				<input name="geo-e" type="number" step=".0001" placeholder="ex: 2.3522" value="{$smarty.post['geo-e']|default: $oCity->geoE: ''}" style="margin-left: 15px;">° E
			</label>
		</div>
		<div class="inline">
			<label>
				Image: {($oCity->image|default: '')|basename}
				<br>
				<input name="img" type="file">
			</label>
		</div>

		<div class="inline">
			{if $oCity->image|strlen}
				<img src="{$_API_URL_}/ressources/upload/{$oCity->image}" class="inline" style="max-width: 350px;">
			{/if}
		</div>

		<button class="btn" type="submit">Envoyer</div>
	</form>
</div>