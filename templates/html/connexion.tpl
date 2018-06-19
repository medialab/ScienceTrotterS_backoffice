<link rel="stylesheet" href="formulaire.css">

<div id="global">
	<div class="logo">
		<img src="/media/image/connexion/logo-science-trotters.png" width="517px" height="535" alt="Logo Science Trotters format Desktop" id="imgDesktop">
		<img src="/media/image/interface/leftbar/sts-full.svg" width="250px" height="14px" alt="Logo Science Trotters format Mobile" id="imgMobile">
	</div>

	<div class="all">
		<div class="form">	
			<form method="post">
				{include file="include/html/form-error.tpl"}

	        	<input type="password" id="password" name="user_password" placeholder="Mot de Passe">
				<p class="forget">
<!-- 
					<a href="#">Mot de passe oublié</a>
				-->
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