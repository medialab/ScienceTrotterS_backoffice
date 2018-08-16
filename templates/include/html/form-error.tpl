<div id="notify-container">
	{if !empty($smarty.session.session_msg)}
		{assign var="aSession" value=$smarty.session.session_msg}
		
		{if !empty($aSession.success)}
			
			<div class="msg-cont success-container">
				<ul>
					{foreach $aSession.success as $k => $msg}
						<li>
							{if !empty($k)}
								<b>{$k}: </b> 
							{/if}
							{$msg}
						</li>
					{/foreach}
				</ul>
			</div>
		{/if}


		{if !empty($aSession.warning)}
			<div class="msg-cont warning-container">
				<ul>
					{foreach $aSession.warning as $k => $msg}
						<li>
							{if is_string($k) && !empty($k)}
								<b>{$k}: </b> 
							{/if}
							{$msg}
						</li>
					{/foreach}
				</ul>
			</div>
			
		{/if}

	{/if}


	{if !empty($aErrors)}
		<div class="msg-cont error-container">
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
	
</div>
