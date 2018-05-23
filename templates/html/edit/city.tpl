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
				<input name="state" type="checkbox" checked="{if $smarty.post.state|default: $oCity->state:false}true{else}false{/if}">
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

			{if $oCity->image|strlen}
				<img src="{$oCity->image}" class="inline">
			{/if}
		</div>

		<button class="btn" type="submit">Envoyer</div>
	</form>
</div>