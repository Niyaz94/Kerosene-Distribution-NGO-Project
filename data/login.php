<?php
	if(!isset($_SESSION)) { 
		session_start(); 
	} 
	if(isset($_SESSION['is_login']) ){
		header("location: starter.php");
		echo '<script>window.location="starter.php";</script>';
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Login to System</title>
		<link href="../API/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
		<link href="../API/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="../API/assets/css/core.css" rel="stylesheet" type="text/css">
		<link href="../API/assets/css/components.css" rel="stylesheet" type="text/css">
		<link href="../API/assets/css/colors.css" rel="stylesheet" type="text/css">
		<noscript>
			<META HTTP-EQUIV="Refresh" CONTENT="0;URL=java_disabled.html">
		</noscript>
		<script type="text/javascript" src="../API/assets/js/plugins/loaders/pace.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/core/libraries/jquery.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/core/libraries/bootstrap.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/loaders/blockui.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/ui/nicescroll.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/ui/drilldown.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/forms/styling/uniform.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/notifications/pnotify.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/notifications/noty.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/notifications/jgrowl.min.js"></script>
		<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
		<script type="text/javascript" src="../API/assets/js/pages/login.js"></script>
		<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>
	</head>
	<body class="login-container">
		<div class="page-container">
			<div class="page-content">
				<div class="content-wrapper">
					<form id="loginform">
						<div class="panel panel-body login-form">
							<div class="text-center">
								<div class="icon-object border-slate-300 text-slate-300"><i class="icon-user-tie"></i></div>
								<h5 class="content-group-lg">Login to your account <small class="display-block">Enter your credentials</small></h5>
							</div>
							<div class="form-group has-feedback has-feedback-left form-group-material">
								<label class="control-label">User Name</label>
								<input type="text" id="username" name="username" class="form-control" placeholder="User Name" required="required">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>
							<div class="form-group has-feedback has-feedback-left form-group-material">
								<label class="control-label">Password</label>
								<input type="password" id="password" name="password" class="form-control" placeholder="Password" required="required">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>
							<div class="form-group login-options">
								<div class="row">
									<div class="col-sm-6">
										<label class="checkbox-inline">
											<input type="checkbox" class="styled" checked="checked">
											Remember
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<button  class="btn bg-pink-400 btn-block" id="login_btn" name="login_btn">Login <i class="icon-arrow-right14 position-right"></i></button>
							</div>	
						</div>
					</form>
				</div>
			</div>
		</div>
		<footer id="footer" >
				<div class="text-center  clearfix">
					<p> <small><a href="https://www.keentech.co" target="_blank" >KeenTech</a> for IT Services and Solutions
					<br>&copy; <?php echo date("Y"); ?></small> </p>
				</div>
		</footer>
		<script type="text/javascript" src="controllers/login.js?random=<?php echo uniqid(); ?>"></script>
	</body>
</html>
