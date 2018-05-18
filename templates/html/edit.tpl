<div>
	<h2>Création d{$sCreation}</h2>
</div>

<div>
	<form method="post">
		<div>
			<input name="label" placeholder="Nom de la Ville" type="text" value="{$smarty.post.label|default: $city.label: ''}">
		</div>
		<div>
			<label>
				Active
				<input name="state" type="checkbox" checked="{if $smarty.post.state|default: $city.state:false}true{else}false{/if}">
			</label>
		</div>
		<div>
			<label>
				Image
				<input name="img" type="file">
			</label>
		</div>
	</form>
</div>