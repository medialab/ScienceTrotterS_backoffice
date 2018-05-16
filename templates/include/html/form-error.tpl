{if !empty($aErrors)}
	<div class="error-container">
		<ul>
			{foreach $aErrors as $err}
				<li>
					{$err}
				</li>
			{/foreach}
		</ul>
	</div>
{/if}