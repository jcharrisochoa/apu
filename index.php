<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="" />

	<link rel="icon" href="libreria/neon/assets/images/favicon.ico">

	<title>AP-Atl&aacute;ntico | Iniciar Sessi&oacute;n</title>

	<link rel="stylesheet" href="libreria/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="libreria/neon/assets/css/bootstrap.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/neon-core.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/neon-theme.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/neon-forms.css">
	<link rel="stylesheet" href="libreria/neon/assets/css/skins/blue.css">
	<script src="libreria/jquery-3.5.1.min.js"></script>

	<!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>
<body class="page-body login-page login-form-fall">

<!-- This is needed when you send requests via Ajax -->
<script type="text/javascript">var baseurl = 'modulo/login/';</script>

<div class="login-container">	
	<div class="login-header login-caret">		
		<div class="login-content">			
			<a href="#" class="logo">
				<img src="libreria/neon/assets/images/logo@2x.png" width="120" alt="" />
			</a>			
			<p class="description">Alumbrado P&uacute;blico del Atl&aacute;ntico | Iniciar Sessi&oacute;n</p>
			
			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>43%</h3>
				<span>logging in...</span>
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
				<p>Enter <strong>demo</strong>/<strong>demo</strong> as login and password.</p>
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
			
			<div class="login-bottom-links">				
				<a href="extra-forgot-password.html" class="link">Forgot your password?</a>				
			</div>			
		</div>		
	</div>	
</div>


	<!-- Bottom scripts (common) -->
	<script src="libreria/neon/assets/js/gsap/TweenMax.min.js"></script>
	<script src="libreria/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="libreria/bootstrap/js/bootstrap.js"></script>
	<script src="libreria/neon/assets/js/joinable.js"></script>
	<script src="libreria/neon/assets/js/resizeable.js"></script>
	<script src="libreria/neon/assets/js/neon-api.js"></script>
	<script src="libreria/neon/assets/js/jquery.validate.min.js"></script>
	<script src="libreria/neon/assets/js/neon-login.js"></script>

	<!-- JavaScripts initializations and stuff -->
	<!--<script src="libreria/neon/assets/js/neon-custom.js"></script>-->
	<!-- Demo Settings -->
	<script src="libreria/neon/assets/js/neon-demo.js"></script>

</body>
</html>