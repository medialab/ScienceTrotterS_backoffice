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
	</head>
	<body>
<!-- 
		{include file="include/html/header.tpl"}
 -->
		<div id="content">
			{if $showNavBar|default: true !== false}
				{include file="include/html/navBar.tpl"}
			{/if}

			{$sPageContent|default:'noCONTENT'}

			{include file="include/html/footer.tpl"}
		</div>
		<script src="/html.js"></script>
		{if file_exists( "./templates/js/html/"|cat:$smarty.get.name:".js" )}
			<script src="/html/{$smarty.get.name}.js"></script>
		{/if}
	</body>
</html>
