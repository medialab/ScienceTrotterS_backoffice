<?php
/* Smarty version 3.1.30, created on 2018-05-16 12:03:42
  from "/data/vhosts/science_trotters/admin/templates/html/connexion.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5afc01fed48e75_94640240',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '37e2c96857ba1e17f2110d59990bb629eb339818' => 
    array (
      0 => '/data/vhosts/science_trotters/admin/templates/html/connexion.tpl',
      1 => 1526464854,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5afc01fed48e75_94640240 (Smarty_Internal_Template $_smarty_tpl) {
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Formulaire Science Trotters</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link rel="stylesheet" href="formulaire.css">
</head>

<body>
	<div id="global">
		<div class="logo">
			<img src="/media/image/interface/logo-science-trotters.png" width="517px" height="535" alt="Logo Science Trotters format Desktop" id="imgDesktop">
			<img src="/media/image/interface/sts-full.svg" width="250px" height="14px" alt="Logo Science Trotters format Mobile" id="imgMobile">
		</div>
	
		<div class="all">
			<div class="form">	
				<form method="post">
		        	<input type="password" id="password" name="user_password" placeholder="Mot de Passe">

					<p class="forget">
						<a href="#">Mot de passe oublié</a>
					</p>	
					
					<p class="login">
						<button type="submit">Connexion</button>	
					</p>
		   			
				</form>
			</div>

			<div class="site">
				<p class="link">
					Allez sur le site
					<a href="#"> FORCCAST</a>
				</p>
			</div>

		</div>
	</div>	
</body>

</html>
<?php }
}
