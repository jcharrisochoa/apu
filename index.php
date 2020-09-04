<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Asociacion de Alumbrado Público del Atlántico" />
	<meta name="author" content="jcharris" />

	<link rel="icon" href="libreria/neon/assets/images/asoatlantico.ico">

	<title>AP-Atl&aacute;ntico | Iniciar Sessi&oacute;n</title>

	<link rel="stylesheet" href="libreria/neon/assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="libreria/neon/assets/css/bootstrap.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/neon-core.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/neon-theme.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/neon-forms.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/custom.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/skins/white.css">

	<script src="../libreria/neon/assets/js/jquery-1.11.3.min.js"></script>
	<!-- Bottom scripts (common) -->
	<script src="libreria/neon/assets/js/gsap/TweenMax.min.js"></script>
	<script src="libreria/neon/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="libreria/neon/assets/js/bootstrap.js"></script>
	<script src="libreria/neon/assets/js/joinable.js"></script>
	<script src="libreria/neon/assets/js/resizeable.js"></script>
	<script src="libreria/neon/assets/js/neon-api.js"></script>

    <script src="libreria/neon/assets/js/jquery.validate.min.js"></script>
	<script src="modulo/login/js/neon-login.js"></script>
    <!--<script src="libreria/neon/assets/js/neon-demo.js"></script>-->


</head>
<body class="page-body login-page login-form-fall">

<!-- This is needed when you send requests via Ajax -->
<script type="text/javascript">var baseurl = 'modulo/login/';</script>

<div class="login-container">	
	<div class="login-header login-caret">		
		<div class="login-content">			
			<a href="#" class="logo">
				<img src="libreria/img/logo.png" width="220" alt="" />
			</a>			
			<p class="description">Iniciar Sessi&oacute;n</p>
			
			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>43%</h3>
				<span>Ingresando</span>
			</div>
		</div>	
	</div>
	
	<div class="login-progressbar">
		<div></div>
	</div>	
	<div class="login-form">		
		<div class="login-content">			
			<div class="form-login-error">
				<h3>Usuario Inv&aacute;lido</h3>
			</div>
			
			<form method="post" role="form" id="form_login">				
				<div class="form-group">					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>						
						<input type="text" class="form-control" name="username" id="username" placeholder="Usuario" autocomplete="off" />
					</div>					
				</div>
				
				<div class="form-group">					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>						
						<input type="password" class="form-control" name="password" id="password" placeholder="Clave" autocomplete="off" />
					</div>				
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btn-login">
						<i class="entypo-login"></i>
						Ingresar
					</button>
				</div>			
			</form>			
			
			<!--<div class="login-bottom-links">				
				<a href="#" class="link">Olvid&oacute; su clave?</a>				
			</div>-->

		</div>		
	</div>	
</div>
</body>
</html>