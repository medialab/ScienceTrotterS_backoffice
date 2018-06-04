{if !empty($aErrors)}
	<div class="error-container">
		<ul>
			{foreach $aErrors as $k => $err}
				<li>
					<b>{$k}: </b> {$err}
				</li>
			{/foreach}
		</ul>
	</div>
{/if}