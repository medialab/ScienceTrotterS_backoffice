<?php
/* Smarty version 3.1.30, created on 2018-05-15 11:18:36
  from "/data/vhosts/science_trotters/admin/templates/html.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5afaa5ec9dadb8_67866333',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bb16605f2a3ad972f13d0c44780df6866988c5e6' => 
    array (
      0 => '/data/vhosts/science_trotters/admin/templates/html.tpl',
      1 => 1526375908,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:include/html/header.tpl' => 1,
    'file:include/html/footer.tpl' => 1,
  ),
),false)) {
function content_5afaa5ec9dadb8_67866333 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="height=device-height,width=device-width,initial-scale=1,shrink-to-fit=no">
		<title>Science Trotters</title>
		<link rel="alternate" hreflang="fr" href="http://<?php echo $_SERVER['SERVER_NAME'];?>
"/>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- FONTS -->
		
		<!-- CSS -->
			<link href="/html.css" rel="stylesheet" type="text/css" />
			<link rel="stylesheet" href="/lib/navbar.css" type="text/css" />
			<link rel="stylesheet" href="/lib/icons.css" type="text/css" />
			<link rel="stylesheet" href="/lib/arbo.css" type="text/css" />
			<?php if (file_exists(("./templates/css/html/").($_GET['name']).(".css"))) {?>
				<link rel="stylesheet" href="/html/<?php echo $_GET['name'];?>
.css" type="text/css" />
			<?php }?>
	</head>
	<body>
<!-- 
		<?php $_smarty_tpl->_subTemplateRender("file:include/html/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

 -->
		<div id="content">
			<?php echo (($tmp = @$_smarty_tpl->tpl_vars['sPageContent']->value)===null||$tmp==='' ? 'noCONTENT' : $tmp);?>

			<?php $_smarty_tpl->_subTemplateRender("file:include/html/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		</div>
		<?php echo '<script'; ?>
 src="/html.js"><?php echo '</script'; ?>
>
		<?php if (file_exists(("./templates/js/html/").($_GET['name']).(".js"))) {?>
			<?php echo '<script'; ?>
 src="/html/<?php echo $_GET['name'];?>
.js"><?php echo '</script'; ?>
>
		<?php }?>
	</body>
</html>
<?php }
}
