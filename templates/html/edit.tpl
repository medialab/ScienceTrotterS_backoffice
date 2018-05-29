<div>
	<h2>{$sCreation}</h2>
</div>

<div class="lang-selector">
	<div class="lang-triggers">
		<div class="tab-trigger" target="">
			Français
		</div>
		<div class="tab-trigger" target="">
			Anglais
		</div>
	</div>

	<div class="french-only">
		<label>
			<input type="checkbox">
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

	.lang-selector .triggers{
		display: inline-block;
	}

	.lang-selector .tab-trigger{
		color: #3156A6;
		margin-right: 0;
		cursor: pointer;
		padding: 3px 15px;
		display: inline-block;
		border-radius: 4px 0px 0 0;
		
		border: 2px solid #3156A6;
		border-bottom: none;
	}

	.lang-selector .tab-trigger .on{
		color: white;
		background-color: #3156A6;
	}

	.lang-selector .french-only{
		right: 0;
		bottom: 0;
		position: absolute;
		display: inline-block;
	}
</style>

{include file="include/html/form-error.tpl" aErrors=$aErrors}
