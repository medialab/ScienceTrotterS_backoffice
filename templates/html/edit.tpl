<div>
	<h2>{$sCreation}</h2>
</div>

{assign var="lang" value=$smarty.post.sLang|default: 'fr'}

<div class="tab-selector" target="{$lang}">
	<div class="tab-triggers">
		{foreach $aLangs as $sIso => $sLang}
			<div id="trigger-{$sIso}" class="tab-trigger {if $lang === $sIso}on{/if}" target="{$sIso}">
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
