<div>
	<h2>{$sCreation}</h2>
</div>

{assign var="lang" value=$smarty.post.lang|default: $oModel->force_lang:'fr'}

<div class="tab-selector" target="{$lang}">
	<div class="tab-triggers">
		{foreach $aLangs as $sIso => $sLang}
			<div id="trigger-{$sIso}" class="tab-trigger {if $lang === $sIso}on{/if}" target="{$sIso}">
				{$sLang}
			</div>
		{/foreach}
	</div>

	{foreach $aLangs as $iso => $sLang}
		{assign var="checked" value=($oModel->force_lang|default: '') === $iso}

		<div class="lang-only" target="{$iso}">
			<label class="cust-checkbox {if $checked}on{/if}">
				<div class="check">
					<div></div>
					<input class="lang-check" type="checkbox" name="force_lang" value='{$iso}'>
				</div>
				{$sLang} uniquement
			</label>
		</div>
		
	{/foreach}
</div>

{include file="include/html/form-error.tpl" aErrors=$aErrors|default:false}