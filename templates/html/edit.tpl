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
				<input class="lang-check" type="checkbox" name="lang_only" value='fr'>
			</div>
			Français uniquement
		</label>
	</div>

	<div class="lang-only" target="en">
		<label class="cust-checkbox"> 
			<div class="check">
				<div></div>
				<input class="lang-check" type="checkbox" name="lang_only" value='en'>
			</div>
			Anglais uniquement
		</label>
	</div>
</div>

{include file="include/html/form-error.tpl" aErrors=$aErrors|default:false}