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

<style>
	.lang-selector {
		width: 1040px;
		max-width: 70%;
		position: relative;
		margin: 0px auto 24px auto;
		border-bottom: 2px solid #3156A6;
	}

	.lang-selector .lang-triggers{
		display: inline-block;
	}

	.lang-selector .tab-trigger{
		color: #3156A6;
		margin-right: 0;
		cursor: pointer;
		padding: 3px 15px;
		display: inline-block;
		
		border: 2px solid #3156A6;
		border-bottom: none;
	}

	.lang-selector .lang-triggers div.tab-trigger:first-of-type{
		border-radius: 4px 0px 0 0;
	}

	.lang-selector .lang-triggers div.tab-trigger:last-of-type{
		border-radius: 0px 4px 0 0;
	}

	.lang-selector .tab-trigger.on{
		color: white;
		background-color: #3156A6;
	}

	.lang-selector .french-only{
		right: 0;
		bottom: 0;
		position: absolute;
		display: inline-block;
	}

	.cust-checkbox {
		cursor: pointer;
	}

	.cust-checkbox .check {
		width: 18px;
		height: 18px;
		border: 2px solid #3156A6;
		position: relative;
		vertical-align: sub;
		display: inline-block;
	}

	.cust-checkbox input[type="checkbox"] {
		display: none;
	}

	.cust-checkbox > div {
		top: 1px;
		left: 1px;
		width: 12px;
		height: 12px;
		position: absolute;
		display: inline-block;
	}

	.cust-checkbox.on > div {
		background: #3156A6;
	}
</style>

{include file="include/html/form-error.tpl" aErrors=$aErrors}
