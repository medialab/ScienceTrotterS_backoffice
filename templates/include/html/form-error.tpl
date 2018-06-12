{if !empty($aErrors)}
	<div class="error-container">
		<ul>
			{foreach $aErrors as $k => $err}
				<li>
					{if !empty($k)}
						<b>{$k}: </b> 
					{/if}
					{$err}
				</li>
			{/foreach}
		</ul>
	</div>
{/if}