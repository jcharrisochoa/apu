<?php
session_start();
$url = file_get_contents("../conexion/credencial.json");
$credencial= json_decode($url, true);
/*
echo $credencial['driver'];
"host,uid,pwd,database*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="" />

	<link rel="icon" href="../libreria/neon/assets/images/favicon.ico">

	<title>AP-Atl&aacute;ntico | Tablero Inicial</title>

    <link rel="stylesheet" href="../libreria/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" href="../libreria/neon/assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="../libreria/neon/assets/css/bootstrap.css">
	<link rel="stylesheet" href="../libreria/neon/assets/css/neon-core.css">
	<link rel="stylesheet" href="../libreria/neon/assets/css/neon-theme.css">
	<link rel="stylesheet" href="../libreria/neon/assets/css/neon-forms.css">
	<link rel="stylesheet" href="../libreria/neon/assets/css/skins/blue.css">
	<script src="../libreria/jquery-3.5.1.min.js"></script>


	<!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>
<body class="page-body  page-fade">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
	
	<div class="sidebar-menu">

		<div class="sidebar-menu-inner">
			
			<header class="logo-env">

				<!-- logo -->
				<div class="logo">
					<a href="index.html">
						<img src="../libreria/neon/assets/images/logo@2x.png" width="120" alt="" />
					</a>
				</div>

				<!-- logo collapse icon -->
				<div class="sidebar-collapse">
					<a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
						<i class="entypo-menu"></i>
					</a>
				</div>

								
				<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
				<div class="sidebar-mobile-menu visible-xs">
					<a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
						<i class="entypo-menu"></i>
					</a>
				</div>

			</header>
			
									
			<ul id="main-menu" class="main-menu">
				<!-- add class "multiple-expanded" to allow multiple submenus to open -->
				<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
				<li class="active opened active has-sub">
					<a href="index.php">
						<i class="entypo-gauge"></i>
						<span class="title">Operaciones</span>
					</a>
					<ul class="visible">
						<li class="active">
							<a href="luminaria/luminaria.php">
								<span class="title">Luminarias</span>
							</a>
						</li>
						<li>
							<a href="georegerencia/georegerencia.php">
								<span class="title">Georeferencia</span>
							</a>
						</li>
						<li>
							<a href="actividad/actividad.php">
								<span class="title">Actividades</span>
							</a>
						</li>						
					</ul>
				</li>
			</ul>
			
		</div>

	</div>

	<div class="main-content">
				
		<div class="row">
		
			<!-- Profile Info and Notifications -->
			<div class="col-md-6 col-sm-8 clearfix">
		
				<ul class="user-info pull-left pull-none-xsm">
		
					<!-- Profile Info -->
					<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
		
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="../libreria/neon/assets/images/thumb-1@2x.png" alt="" class="img-circle" width="44" />
							Johan Charris
						</a>
		
						<ul class="dropdown-menu">
		
							<!-- Reverse Caret -->
							<li class="caret"></li>
		
							<!-- Profile sub-links -->
							<li>
								<a href="extra-timeline.html">
									<i class="entypo-user"></i>
									Edit Profile
								</a>
							</li>
		
							<li>
								<a href="mailbox.html">
									<i class="entypo-mail"></i>
									Inbox
								</a>
							</li>
		
							<li>
								<a href="extra-calendar.html">
									<i class="entypo-calendar"></i>
									Calendar
								</a>
							</li>
		
							<li>
								<a href="#">
									<i class="entypo-clipboard"></i>
									Tasks
								</a>
							</li>
						</ul>
					</li>
		
				</ul>
		
			</div>
		
		
			<!-- Raw Links -->
			<div class="col-md-6 col-sm-4 clearfix hidden-xs">		
				<ul class="list-inline links-list pull-right">		
					<li>
						<a href="../index.php">
							Log Out <i class="entypo-logout right"></i>
						</a>
					</li>
				</ul>		
			</div>		
		</div>		
		<hr />	
		
		<script type="text/javascript">
			// Code used to add Todo Tasks
			jQuery(document).ready(function($)
			{
				var $todo_tasks = $("#todo_tasks");
		
				$todo_tasks.find('input[type="text"]').on('keydown', function(ev)
				{
					if(ev.keyCode == 13)
					{
						ev.preventDefault();
		
						if($.trim($(this).val()).length)
						{
							var $todo_entry = $('<li><div class="checkbox checkbox-replace color-white"><input type="checkbox" /><label>'+$(this).val()+'</label></div></li>');
							$(this).val('');
		
							$todo_entry.appendTo($todo_tasks.find('.todo-list'));
							$todo_entry.hide().slideDown('fast');
							replaceCheckboxes();
						}
					}
				});
			});
		</script>
		
	
		
		<!-- Footer -->
		<footer class="main">			
			&copy; 2020 Admin:jcharris</a>		
		</footer>
	</div>
		
</div>


	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="../libreria/neon/assets/js/jvectormap/jquery-jvectormap-1.2.2.css">
	<link rel="stylesheet" href="../libreria/neon/assets/js/rickshaw/rickshaw.min.css">

	<!-- Bottom scripts (common) -->
	<script src="../libreria/neon/assets/js/gsap/TweenMax.min.js"></script>
	<script src="../libreria/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="../libreria/bootstrap/js/bootstrap.js"></script>
	<script src="../libreria/neon/assets/js/joinable.js"></script>
	<script src="../libreria/neon/assets/js/resizeable.js"></script>
	<script src="../libreria/neon/assets/js/neon-api.js"></script>
	<script src="../libreria/neon/assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>




	<!-- JavaScripts initializations and stuff -->
	<script src="../libreria/neon/assets/js/neon-custom.js"></script>
	<!-- Demo Settings -->
	<script src="../libreria/neon/assets/js/neon-demo.js"></script>

</body>
</html>