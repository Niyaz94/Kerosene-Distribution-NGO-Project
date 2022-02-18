<?php
	if(!isset($_SESSION)){
		session_start();
	}
	$out  = array();
	$Validator = array('is_success'=>false, 'data'=>array());
	date_default_timezone_set('Asia/Baghdad');

	if(empty($_POST['username']) || empty($_POST['password'])){

		$error=" Please Fill User Name and Password!";
        $State=false;
        $out  = array(
			'status'  => 'false',
			'data'  => $error
		);
	}else{
			require_once "../_general/backend/db_connect.php";

			$username=$_POST['username'];
			$password=$_POST['password'];

			$username = stripslashes($username);

			$password=hash('sha256',$password);
	    	$password= stripslashes($password);

			$Records = $database->return_data2(array(
				"tablesName"=>array("admin"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"ADMUsername","operation"=>"=","value"=>$username,"link"=>"And"),
					array("columnName"=>"ADMPassword","operation"=>"=","value"=>$password,"link"=>"And"),
					array("columnName"=>"ADMDeleted","operation"=>"=","value"=>0,"link"=>""),
				),
				"others"=>"",
				"returnType"=>"key"
            ));

			if (!$Records) {
		        $error= "Some thing went wrong. Please try again";
		        $State=false;

		  	}else{
				if($Records){
					$UserID 					= $Records['ADMID'];
					$_SESSION['is_login'] 		= true;
					$_SESSION['user_id'] 		= $Records['ADMID'];
					$_SESSION['ADMProfileType'] 	= $Records['ADMProfileType'];
					$_SESSION['access_token'] 	= md5(uniqid(rand(), true));
					$_SESSION['image_path'] 	= 'image/profile/';
					$_SESSION['username'] 		= $Records['ADMUsername'];
					$_SESSION['full_name'] 		= $Records['ADMFullname'];
					$_SESSION['email'] 			= $Records['ADMEmail'];
					$_SESSION['profile_image'] 	= $Records['ADMProfileImg'];
					$_SESSION['cover_image'] 	= $Records['ADMCoverImg'];
					$_SESSION['phone_number'] 	= $Records['ADMPhoneNumber'];
					$_SESSION['language_id'] 	= $Records['ADMLANFORID'];
					$_SESSION['userPermission'] 	= $Records['ADMProfilePermission'];
					$_SESSION['ADMCampID'] 	= $Records['ADMCampID'];

					$sysinfo = $database->return_data("SELECT * FROM system_settings","key");
							
					$_SESSION['company_logo']    = $sysinfo['SYSCompanyLogo'];
					$_SESSION['company_name']    = $sysinfo['SYSCompanyName'];
					$_SESSION['company_mngr'] = $sysinfo['SYSCompanyManager'];
					$_SESSION['company_address'] = $sysinfo['SYSCompanyAddress'];
					$_SESSION['company_phone']   = $sysinfo['SYSCompanyPhone'];
					$_SESSION['company_email']   = $sysinfo['SYSCompanyEmail'];
					$_SESSION['SYSName']   		 = $sysinfo['SYSName'];
					$_SESSION['SYSVersion']   	 = $sysinfo['SYSVersion']; 
					$_SESSION['SYSSetupDate']    = date('d-M-Y',strtotime($sysinfo['SYSSetupDate'])) ;
					$_SESSION['SYSTimezone']     = $sysinfo['SYSTimezone']; 

			        $error= "Login Success";
			        $State= true;
			        $LoginCount = $Records['ADMLoginCount'] +1;

			        $query = $database->update_data("UPDATE admin SET ADMLoginCount='$LoginCount' WHERE ADMID = '".$UserID."'");

				}else{
			        $error= " User Name or Password Invalid ";
			        $State=false;
				} // if records
			} // if query
		} // if empty
			$Validator['is_success'] = $State;
      		$Validator['data'] = $error;
			echo json_encode($Validator);
?>
