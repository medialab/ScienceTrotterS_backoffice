<div>
	<h2>{$sCreation}</h2>
</div>

<div class="tab-selector" target="{$smarty.post.sLang|default: 'fr'}">
	<div class="tab-triggers">
		{assign var="selected" value=$smarty.post.sLang|default: 'fr'}
		{foreach $aLangs as $sIso => $sLang}
			<div id="trigger-{$sIso}" class="tab-trigger {if $selected === $sIso}on{/if}" target="tab-{$sIso}">
				{$sLang}
			</div>
		{/foreach}
	</div>

	<div class="lang-only" target="fr">
		<label class="cust-checkbox">
			<div class="check">
				<div></div>
				<input type="checkbox" name="french_only">
			</div>
			Français uniquement
		</label>
	</div>

	<div class="lang-only" target="en">
		<label class="cust-checkbox"> 
			<div class="check">
				<div></div>
				<input type="checkbox" name="english_only">
			</div>
			Anglais uniquement
		</label>
	</div>
</div>

{include file="include/html/form-error.tpl" aErrors=$aErrors|default:false}
