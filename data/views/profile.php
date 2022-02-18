<?php 
    $fileName=__FILE__;
	include_once "header.php";
	$userPermission=json_decode(html_entity_decode($_SESSION["userPermission"]),true)[4];
?>
<div class="navbar navbar-default navbar-xs navbar-component no-border-radius-top">
	<ul class="nav navbar-nav visible-xs-block">
		<li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
	</ul>
	<div class="navbar-collapse collapse" id="navbar-filter">
		<ul class="nav navbar-nav">
		<?php
			$profileActive=$passwordActive="";
			if($_SESSION["ADMProfileType"]==1 || $_SESSION["ADMProfileType"]==2 ||($userPermission["buttons"]["PRFIP"]==1 && $userPermission["buttons"]["PRFCP"]==1)){
				echo '
					<li class="active">
						<a href="#activity" data-toggle="tab">
							<i class="icon-menu7 position-left"></i>
							<span class="multi_lang">PersonalInformation </span>
						</a>
					</li>
					<li>
						<a href="#schedule" data-toggle="tab">
							<i class="icon-key position-left"></i> 
							<span class="multi_lang">Change Password</span>
						</a>
					</li>
				';
				$profileActive="active in";
			}else if($userPermission["buttons"]["PRFIP"]==1){
				echo '
					<li class="active">
						<a href="#activity" data-toggle="tab">
							<i class="icon-menu7 position-left"></i>
							<span class="multi_lang">PersonalInformation </span>
						</a>
					</li>
				';
				$profileActive="active in";
			}else if($userPermission["buttons"]["PRFCP"]==1){
				echo '
					<li class="active">
						<a href="#schedule" data-toggle="tab">
							<i class="icon-key position-left"></i> 
							<span class="multi_lang">Change Password</span>
						</a>
					</li>
				';
				$passwordActive="active in";
			}
		?>
		</ul>
		
	</div>
</div>

<div class="tabbable">
	<div class="tab-content">
		<div class="tab-pane fade <?php echo $profileActive;?>" id="activity">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title multi_lang">Personal Information</h6>
				</div>
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
					<input type="hidden" name="language_id" id="language_id" value="<?php echo $_SESSION["language_id"];?>">
						<form id="profileform">
							<input type="hidden" name="selectedLanguage" id="selectedLanguage" value="<?php echo $_SESSION['language_id'];?>">
							<?php 
								echo input1("","text","User Name","ADMUsername_USZ","required","icon-user",$_SESSION['username']);
								echo input1("","text","Full Name","ADMFullname_USZ","required","icon-vcard",$_SESSION['full_name']);
								echo input1("","text","Phone No.","ADMPhoneNumber_UPZ","","icon-phone2",$_SESSION['phone_number']);
								echo input1("","email","E-Mail","ADMEmail_UEZ","","icon-mail5",$_SESSION['email']);
								echo button1("updateprofile","submit","Update","icon-circle-right2");
							?>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade <?php echo $passwordActive;?>" id="schedule">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title multi_lang">Change Password</h6>
				</div>
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<form id="profileformpass">
							<?php 
								echo input1("","password","New Password","ADMPassword_UWZ","required","icon-key");
								echo input1("","password","Re-Type Password","ADMPassword_UWW","required","icon-key");
								echo button1("updatePassword","submit","Password","icon-circle-right2");
							?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="controllers/profile.js?random=<?php echo uniqid(); ?>"></script>