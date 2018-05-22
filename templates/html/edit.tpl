<div>
	<h2>Création d{$sCreation}</h2>
</div>

<div>
	<form method="post">
		<div>
			<label>
				Nom:
				<br>
				<input name="label" placeholder="Ex: Paris" type="text" value="{$smarty.post.label|default: $city.label: ''}">
			</label>
		</div>
		<div>
			<label>
				Active:
				<br>
				<input name="state" type="checkbox" checked="{if $smarty.post.state|default: $city.state:false}true{else}false{/if}">
			</label>
		</div>
		<div>
			<label>
				Geolocation:
				<br>
				<input name="geo-n" type="number" step=".001" placeholder="ex: 48.856">° N
				<input name="geo-e" type="number" step=".001" placeholder="ex: 2.3522" style="margin-left: 15px;">° E
			</label>
		</div>
		<div>
			<label>
				Image:
				<br>
				<input name="img" type="file">
			</label>
		</div>
	</form>
</div>