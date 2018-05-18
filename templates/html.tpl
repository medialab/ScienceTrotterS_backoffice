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
			<link href="/html.css" rel="stylesheet" type="text/css" />
			<link rel="stylesheet" href="/lib/navbar.css" type="text/css" />
			<link rel="stylesheet" href="/lib/icons.css" type="text/css" />
			<link rel="stylesheet" href="/lib/arbo.css" type="text/css" />
			{if file_exists( "./templates/css/html/"|cat:$smarty.get.name:".css" )}
				<link rel="stylesheet" href="/html/{$smarty.get.name}.css" type="text/css" />
			{/if}

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
				{$sPageContent|default:'noCONTENT'}
			</div>

			{include file="include/html/footer.tpl"}
		</div>

		<script>
			var _API_TOKEN_ = {$_API_TOKEN_}
		</script>

		<script src="/html.js"></script>
		{if file_exists( "./templates/js/html/"|cat:$smarty.get.name:".js" )}
			<script src="/html/{$smarty.get.name}.js"></script>
		{/if}
	</body>
</html>
