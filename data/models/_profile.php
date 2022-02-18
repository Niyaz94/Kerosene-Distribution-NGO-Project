<?php
	include_once "../_general/backend/_header.php";

	if($_POST['type']=='updateProfile'){
		$_POST["ADMID_UIZP"]=$_SESSION['user_id'];
		$validation=new class_validation($_POST,"ADM");
		$data=$validation->returnLastVersion();
		extract($data);
		$res = $database->return_data2(array(
			"tablesName"=>array("admin"),
			"columnsName"=>array("*"),
			"conditions"=>array(
				array("columnName"=>"ADMUsername","operation"=>"=","value"=>$ADMUsername,"link"=>"and"),
				array("columnName"=>"ADMDeleted","operation"=>"=","value"=>0,"link"=>"and"),
				array("columnName"=>"ADMID","operation"=>"!=","value"=>$ADMID,"link"=>"")
			),
			"others"=>"",
			"returnType"=>"row_count"
		));
		if($res>0){
			echo jsonMessages(false,7);
			exit;
		}
		$res = $database->update_data2(array(
			"tablesName"=>"admin",
			"userData"=>$data,
			"conditions"=>array()
		));
		if ($res) {
			updateSession($database);
			echo jsonMessages(true,1);
			exit;
		}else{
			echo jsonMessages(false,1);
			exit;
		}
	}
	if($_POST['type']=='updatePassword'){
		//testData($_POST,0);
		if(valatador::checkForTheSame($_POST)=="false"){
			echo jsonMessages(false,4);
			exit;
		}
		$_POST["ADMID_UIZP"]=$_SESSION['user_id'];
		$validation=new class_validation($_POST,"ADM");
		$data=$validation->returnLastVersion();
		extract($data);

		$res = $database->update_data2(array(
			"tablesName"=>"admin",
			"userData"=>$data,
			"conditions"=>array()
		));
		if ($res) {
			echo jsonMessages(true,1);
			exit;
		}else{
			echo jsonMessages(false,1);
			exit;
		}
	}
	function updateSession($database){
		$Records = $database->return_data2(array(
			"tablesName"=>array("admin"),
			"columnsName"=>array("*"),
			"conditions"=>array(
				array("columnName"=>"ADMID","operation"=>"=","value"=>$_SESSION['user_id'],"link"=>"")
			),
			"others"=>"",
			"returnType"=>"key"
		));
		$_SESSION['username'] 		= $Records['ADMUsername'];
		$_SESSION['full_name'] 		= $Records['ADMFullname'];
		$_SESSION['email'] 			= $Records['ADMEmail'];
		$_SESSION['profile_image'] 	= $Records['ADMProfileImg'];
		$_SESSION['cover_image'] 	= $Records['ADMCoverImg'];
		$_SESSION['phone_number'] 	= $Records['ADMPhoneNumber'];
		//$_SESSION['language_id'] 	= $Records['ADMLANFORID'];
	}
?>
