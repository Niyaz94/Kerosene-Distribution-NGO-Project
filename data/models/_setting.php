<?php
	include_once "../_general/backend/_header.php";
	if($_POST['type']=='updateProfileImage'){
		$fileUpload=uploadingImageFileV2($_FILES,"../_general/image/sys/");
		//testData($fileUpload,0);
        if(!is_array($fileUpload)){
            echo $fileUpload;
            exit;
        }else{
            $_POST=array_merge($fileUpload,$_POST);
        } 
		$validation=new class_validation($_POST,"SYS");
		$data=$validation->returnLastVersion();
		$res = $database->update_data2(array(
			"tablesName"=>"system_settings",
			"userData"=>$data,
			"conditions"=>array()
		));
		if ($res) {
			updateSession($database);
			echo jsonMessages(true,1);
		}else{
			echo jsonMessages(false,1);
		}
	}
	if($_POST['type']=='updateSystemInfo'){
		$validation=new class_validation($_POST,"SYS");
		$data=$validation->returnLastVersion();
		$res = $database->update_data2(array(
			"tablesName"=>"system_settings",
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
	if($_POST['type']=='updateCompanyInfo'){
		$validation=new class_validation($_POST,"SYS");
		$data=$validation->returnLastVersion();
		$res = $database->update_data2(array(
			"tablesName"=>"system_settings",
			"userData"=>$data,
			"conditions"=>array()
		));
		if ($res) {
			updateSession($database);
			echo jsonMessages2(true,$_SESSION);
			exit;
		}else{
			echo jsonMessages(false,1);
			exit;
		}	
	}
	function updateSession($database){
		$sysinfo = $database->return_data2(array(
			"tablesName"=>array("system_settings"),
			"columnsName"=>array("*"),
			"conditions"=>array(
				array("columnName"=>"SYSID","operation"=>"=","value"=>1,"link"=>"")
			),
			"others"=>"",
			"returnType"=>"key"
		));
		$_SESSION['company_logo']    = $sysinfo['SYSCompanyLogo'];
		$_SESSION['company_name']    = $sysinfo['SYSCompanyName'];
		$_SESSION['company_mngr'] = $sysinfo['SYSCompanyManager'];
		$_SESSION['company_address'] = $sysinfo['SYSCompanyAddress'];
		$_SESSION['company_phone']   = $sysinfo['SYSCompanyPhone'];
		$_SESSION['company_email']   = $sysinfo['SYSCompanyEmail'];
		$_SESSION['SYSName']   			 = $sysinfo['SYSName'];
		$_SESSION['SYSVersion']   	 = $sysinfo['SYSVersion']; 
		$_SESSION['SYSSetupDate']    = date('d-M-Y',strtotime($sysinfo['SYSSetupDate'])) ;
		$_SESSION['SYSTimezone']     = $sysinfo['SYSTimezone'];
	}
?>