<div>
	<h2>{$sCreation}</h2>
</div>

<div class="lang-selector">
	<div class="lang-triggers">
		<div class="tab-trigger on" target="">
			Français
		</div>
		<div class="tab-trigger" target="">
			Anglais
		</div>
	</div>

	<div class="french-only">
		<label class="cust-checkbox">
			<div class="check">
				<div></div>
				<input type="checkbox" name="french_only">
			</div>
			Français uniquement
		</label>
	</div>
</div>

{include file="include/html/form-error.tpl" aErrors=$aErrors}
