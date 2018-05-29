<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="height=device-height,width=device-width,initial-scale=1,shrink-to-fit=no">
		<title>Science Trotters</title>
		<link rel="alternate" hreflang="fr" href="http://{$smarty.server.SERVER_NAME}"/>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- FONTS -->
		
		<!-- CSS -->
			<link rel="stylesheet" href="/lib/reset.css" type="text/css" />
			<link href="/html.css" rel="stylesheet" type="text/css" />
			<link rel="stylesheet" href="/lib/navbar.css" type="text/css" />
			<link rel="stylesheet" href="/lib/icons.css" type="text/css" />
			<link rel="stylesheet" href="/lib/arbo.css" type="text/css" />
			
			{assign var='aFiles' value=('/'|explode: $smarty.get.name)}
			{assign var='sPath' value=''}

			{foreach $aFiles as $sFile}
				{assign var="sPath" value=$sPath|cat:'/':$sFile}

				{if file_exists( "./templates/css/html/"|cat:$sFile:".css" )}
					<link rel="stylesheet" href="/html/{$sFile}.css" type="text/css" />
				{/if}
			{/foreach}

			{*
				{if file_exists( "./templates/css/html/"|cat:$smarty.get.name:".css" )}
					<link rel="stylesheet" href="/html/{$smarty.get.name}.css" type="text/css" />
				{/if}
			*}

			<script type="text/javascript" src="/lib/jquery-3.3.1.min.js"></script>
	</head>
	<body>
<!-- 
		{include file="include/html/header.tpl"}
 -->
		<div id="content" class="app">
			{if $showNavBar|default: true !== false}
				{include file="include/html/navBar.tpl"}
			{/if}

			<div class="content">

				<div class="mainContent">
					<div class="contentView">
					{include file="include/html/top-bar.tpl"}
					
					{$sPageContent|default:'noCONTENT'}
					</div>
				</div>
			</div>

			{include file="include/html/footer.tpl"}
		</div>

		<script>
			var _API_URL_ = '{$_API_URL_}private/';
			var _API_TOKEN_ = '{$_API_TOKEN_|default: ""}';
		</script>

		<script src="/api-mgr.js"></script>
		<script src="/html.js"></script>

		
		{assign var='sPath' value=''}
		{foreach $aFiles as $sFile}
			{assign var="sPath" value=$sPath|cat:'/':$sFile}
			
			{if file_exists( "./templates/js/html/"|cat:$sFile:".js" )}
				<script src="/html/{$sFile}.js"></script>
			{/if}
		{/foreach}

		{if file_exists( "./templates/js/html/"|cat:$smarty.get.name:".js" )}
		{/if}
	</body>
</html>
