<?php
	include_once "../_general/backend/_header.php";
	if (isset($_POST['type']) || isset($_GET['type'])){
		//change by niyaz
		if( isset($_GET['type']) && ($_GET['type'] == "load")){
			$table = 'admin';
			$primaryKey = 'ADMID';
			$columns =  array(
				array( 'db' => 'ADMID', 'dt' => 0 ),
				array( 'db' => 'ADMUsername', 'dt' => 1 ),
				array( 'db' => 'ADMFullname',  'dt' => 2 ),
				array( 'db' => 'ADMEmail',   'dt' => 3 ),
				array( 'db' => 'ADMPhoneNumber',   'dt' => 4 ),
				array( 'db' => 'ADMProfileType',   'dt' => 5 ),
				array( 'db' => 'ADMLoginCount',   'dt' => 6 ),
				array( 'db' => 'ADMRegisterDate', 'dt' => 7, 'formatter'=> function($d){
					return date('Y-m-d',strtotime($d));
				}),
				array( 'db' => 'ADMDeleted',   'dt' => 8 )
			);
			echo json_encode(
			    SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, " ADMDeleted !=1 and ADMProfileType<2" )
			);
			exit;
		}
		//change by niyaz
		if ($_POST['type'] == "update") {
			//testData($_POST,0);
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
				echo jsonMessages(true,1);
				exit;
			}else{
				echo jsonMessages(false,1);
				exit;
			}
		}
		//change by niyaz
		if ($_POST['type']=="delete"){
			if($_SESSION['ADMProfileType'] != 2 && $_SESSION['ADMProfileType'] != 1){
				echo jsonMessages(false,5);
				exit;
			}
			$validation=new class_validation($_POST,"ADM");
			$data=$validation->returnLastVersion();
			extract($data);	

			$oldData = $database->return_data2(array(
				"tablesName"=>array("admin"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"ADMID","operation"=>"=","value"=>$ADMID,"link"=>"And"),
					array("columnName"=>"ADMDeleted","operation"=>"=","value"=>0,"link"=>""),
				),
				"others"=>"",
				"returnType"=>"key"
			));
			if($oldData['ADMProfileType'] == 1){//if the user admin
				$res = $database->return_data2(array(
					"tablesName"=>array("admin"),
					"columnsName"=>array("*"),
					"conditions"=>array(
						array("columnName"=>"ADMID","operation"=>"<>","value"=>$ADMID,"link"=>"And"),
						array("columnName"=>"ADMProfileType","operation"=>"=","value"=>1,"link"=>"And"),
						array("columnName"=>"ADMDeleted","operation"=>"=","value"=>0,"link"=>""),
					),
					"others"=>"",
					"returnType"=>"row_count"
				));
				if($res<1){
					echo jsonMessages(false,6);
					exit;
				}
			}
			$res = $database->delete_data2(array(
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
		//change by niyaz
		if ($_POST['type']=="deactive") {
			if($_SESSION['ADMProfileType'] != 2 && $_SESSION['ADMProfileType'] != 1){
				echo jsonMessages(false,5);
				exit;
			}
			$validation=new class_validation($_POST,"ADM");
			$data=$validation->returnLastVersion();
			extract($data);	
			$oldData = $database->return_data2(array(
				"tablesName"=>array("admin"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"ADMID","operation"=>"=","value"=>$ADMID,"link"=>"And"),
					array("columnName"=>"ADMDeleted","operation"=>"=","value"=>0,"link"=>""),
				),
				"others"=>"",
				"returnType"=>"key"
			));
			if($oldData['ADMProfileType'] == 1){//if the user admin
				$res = $database->return_data2(array(
					"tablesName"=>array("admin"),
					"columnsName"=>array("*"),
					"conditions"=>array(
						array("columnName"=>"ADMID","operation"=>"=","value"=>$ADMID,"link"=>"And"),
						array("columnName"=>"ADMProfileType","operation"=>"=","value"=>1,"link"=>"And"),
						array("columnName"=>"ADMDeleted","operation"=>"=","value"=>0,"link"=>""),
					),
					"others"=>"",
					"returnType"=>"row_count"
				));
				if($res<=1){
					echo jsonMessages(false,6);
					exit;
				}
			}
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
		//change by niyaz
		if ($_POST['type']=="active") {
			if($_SESSION['ADMProfileType'] != 2 && $_SESSION['ADMProfileType'] != 1){
				echo jsonMessages(false,5);
				exit;
			}
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
		//change by niyaz
		if ($_POST['type'] == "create") {	
			if(valatador::checkForTheSame($_POST)=="false"){
				echo jsonMessages(false,4);
				exit;
			}
			$validation=new class_validation($_POST,"ADM");
			$data=$validation->returnLastVersion();
			extract($data);

			$res = $database->return_data2(array(
				"tablesName"=>array("admin"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"ADMUsername","operation"=>"=","value"=>$ADMUsername,"link"=>"and"),
					array("columnName"=>"ADMDeleted","operation"=>"=","value"=>0,"link"=>"")
				),
				"others"=>"",
				"returnType"=>"row_count"
			));
			if($res>0){
				echo jsonMessages(false,7);
				exit;
			}

			$res = $database->insert_data2("admin",$data);
			if ($res) {	
				echo jsonMessages(true,2);
				exit;
			}else{
				echo jsonMessages(false,1);
				exit;
			}
		}
		//change by niyaz
		if ($_POST['type']=="role") {
			//testData($_POST);
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
		if ($_POST['type']=="getRoles") {
			//testData($_POST,0);
			extract($_POST);
			$res = $database->return_data2(array(
				"tablesName"=>array("admin"),
				"columnsName"=>array("ADMProfilePermission"),
				"conditions"=>array(
					array("columnName"=>"ADMID","operation"=>"=","value"=>$ADMID,"link"=>"and"),
					array("columnName"=>"ADMDeleted","operation"=>"=","value"=>0,"link"=>"")
				),
				"others"=>"",
				"returnType"=>"key"
			));
			if(!$res){
				echo jsonMessages(false,7);
				exit;
			}else{
				echo jsonMessages2(true,strlen($res["ADMProfilePermission"])>0?html_entity_decode($res["ADMProfilePermission"]):"{}");
				exit;
			}
		}
		if ($_POST['type']=="campAdd"){
			extract($_POST);
			if(!isset($ADMCampID)){
				$ADMCampID="";
			}else{
				$ADMCampID=implode(",",$ADMCampID);
			}
			$res = $database->update_data2(array(
				"tablesName"=>"admin",
				"userData"=>array(
					"ADMCampID"=>$ADMCampID,
					"PageName"=>$PageName,
					"primaryKey"=>array("key"=>"ADMID","value"=>$ADMID_UIZP
				)),
				"conditions"=>array()
			));
			if (!$res){
				echo jsonMessages(false,1);
				exit;
			}else{
				echo jsonMessages(true,1);
				exit;
			}
		}
	}
	else{
		header("Location:../");
		exit;
	}
?>
