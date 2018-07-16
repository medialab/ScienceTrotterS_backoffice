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

		<link rel="apple-touch-icon" sizes="57x57" href="/media/image/favicons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/media/image/favicons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/media/image/favicons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/media/image/favicons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/media/image/favicons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/media/image/favicons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/media/image/favicons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/media/image/favicons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/media/image/favicons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/media/image/favicons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/media/image/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/media/image/favicons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/media/image/favicons/favicon-16x16.png">
		<link rel="manifest" href="/media/image/favicons/manifest.json">

		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/media/image/favicons/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		
		<!-- CSS -->
			<link rel="stylesheet" href="/lib/reset.css" type="text/css" />
			<link href="/html.css" rel="stylesheet" type="text/css" />
			<link rel="stylesheet" href="/lib/navbar.css" type="text/css" />
			<link rel="stylesheet" href="/lib/icons.css" type="text/css" />
			<link rel="stylesheet" href="/lib/arbo.css" type="text/css" />
			
			{assign var='aFiles' value=('/'|explode: $sPage)}
			{assign var='sPath' value=''}

			{foreach $aFiles as $sFile}
					{assign var="sPath" value=$sPath|cat:'/':$sFile}

				{if file_exists( "./templates/css/html"|cat:$sPath:".css" )}
					<link rel="stylesheet" href="/html/{$sPath}.css" type="text/css" />
				{/if}
			{/foreach}
			<link rel="stylesheet" href="/html/topbar.css" type="text/css" />

			{*
				{if file_exists( "./templates/css/html/"|cat:$smarty.get.name:".css" )}
					<link rel="stylesheet" href="/html/{$smarty.get.name}.css" type="text/css" />
				{/if}
			*}

			{foreach $_CSS_FILES as $sFile}
				<link rel="stylesheet" href="{$sFile}" type="text/css" />
			{/foreach}

			<script type="text/javascript" src="/lib/jquery.min.js"></script>
			<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> -->
	</head>
	<body id="page{$smarty.get.name|default: 'index'|ucfirst}">
<!-- 
		{include file="include/html/header.tpl"}
 -->
		<div id="content" class="app">
			{if $showNavBar|default: true !== false}
				{include file="include/html/navBar.tpl"}
			{/if}

			<div class="content">
				{include file="include/html/top-bar.tpl"}

				<div class="mainContent">
					<div id="contentView" class="contentView">
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

		<script src="/functions.js"></script>
		<script src="/notify.js"></script>
		<script src="/api-mgr.js"></script>
		<script src="/html.js"></script>
		<script src="/html/topbar.js"></script>

		
		{assign var='sPath' value=''}
		{foreach $_JS_FILES as $sFile}
			<script src="{$sFile}"></script>
		{/foreach}


		{foreach $aFiles as $sFile}
			{assign var="sPath" value=$sPath|cat:'/':$sFile}
			{if file_exists( "./templates/js/html/"|cat:$sPath:".js" )}
				<script src="/html/{$sPath}.js"></script>
			{/if}
		{/foreach}
	</body>
</html>
