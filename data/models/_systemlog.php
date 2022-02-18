<?php

	include_once "../_general/backend/_header.php";
	if (isset($_POST['type']) || isset($_GET['type'])){
		//change by Niyaz
		if( isset($_GET['type']) && ($_GET['type'] == "load")){
			$table  =  '(
                SELECT 
                    system_log.*,
					ADMUsername,
					(SELECT count(*) FROM system_log_detail WHERE SLDForID=LOGID) as detail
                FROM 
                    system_log,
                    admin 
                WHERE 
                    LOGCreateBY=ADMID
					'.(isset($_GET['tables'])? ' AND LOGTable="'.$_GET['tables'].'"':'').' 
            ) AS ALLTBL';
			$primaryKey = 'LOGID';
			$columns = array(
                array( 'db' => 'LOGID', 'dt' => 0 ),
                array( 'db' => 'ADMUsername', 'dt' => 1 ),
                array( 'db' => 'LOGCreateAt', 'dt' => 2),
			    array( 'db' => 'LOGPage', 'dt' => 3 ),
			    array( 'db' => 'LOGTable', 'dt' => 4 ),
			    array( 'db' => 'LOGRowID', 'dt' => 5 ),
			    array( 'db' => 'LOGAction', 'dt' => 6 ),
				array( 'db' => 'LOGNote', 'dt' => 7 ),
				array( 'db' => 'detail', 'dt' => 8 )
			);
			echo json_encode(
			    SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, '' )
			);
			exit;
		}
		if( isset($_GET['type']) && ($_GET['type'] == "loadModule")){
			$table  =  '(
                SELECT 
                   *
                FROM 
					system_log_detail
                WHERE 
					SLDForID="'.$_GET['id'].'"
            ) AS ALLTBL';
			$primaryKey = 'SLDID';
			$columns = array(
                array( 'db' => 'SLDID', 'dt' => 0 ),
                array( 'db' => 'LogColumnName', 'dt' => 1 ),
                array( 'db' => 'SLDOldValue', 'dt' => 2),
                array( 'db' => 'SLDNewValue', 'dt' => 3),
			    array( 'db' => 'SLDCreateAt', 'dt' => 4 ),
			);
			echo json_encode(
			    SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, '' )
			);

			
			exit;
		}
		if ($_POST['type'] == "returnTablesNane") {         
            $res = $database->return_data("
                SELECT 
                    table_name
                FROM
                    information_schema.tables
                where 
                    table_schema='".$_SESSION['database_name']."' AND
                    SUBSTRING_INDEX(SUBSTRING_INDEX(table_comment, ',', 1), ',', -1)='active'
            ","key_all");
			echo json_encode($res);
		}
		if ($_POST['type'] == "returnLogDetail") {
            extract($_POST);
            $res = $database->return_data2(array(
				"tablesName"=>array("system_log_detail"),
				"columnsName"=>array("*"),
				"conditions"=>array(
					array("columnName"=>"SLDForID","operation"=>"=","value"=>$LOGID,"link"=>""),
				),
				"others"=>"",
				"returnType"=>"key_all"
            ));
            
            if ($res) {	
                echo jsonMessages2(true,$res);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
		}	
		if ($_POST['type'] == "changeDetail"){
			$check=0;
			$data=json_decode($_POST["data"],true);
			for($i=0;$i<count($data);++$i){
				$sql= "update system_log_detail set ";
				$res=$database->return_data("
					SELECT 
						* 
					FROM 
						system_log_detail 
					WHERE 
						SLDID='".$data[$i]["SLDID"]."'
				","key");
				$counter=0;
				foreach($data[$i] as $key=>$column){
					if($res[$key]!=$column){
						$sql.=$key." = '".$column."', ";
						++$counter;
					}
				}
				if($counter>0){
					$sql=strlen($sql)>0?substr($sql,0,strlen($sql)-2):$sql;
					$sql.=" where SLDID='".$data[$i]["SLDID"]."' ";
					$check=$database->update_data($sql);
				}
				
			}
			if($check){
				echo jsonMessages(true,1);
				exit;
			}else{
				echo jsonMessages(false,12);
				exit;
			}


		}
		if ($_POST['type'] == "changeSystemLog"){
			$check=0;
			$data=json_decode($_POST["data"],true);
			$sql= "update system_log set ";
			$res=$database->return_data("
				SELECT 
					* 
				FROM 
					system_log 
				WHERE 
					LOGID='".$data["LOGID"]."'
			","key");
			$counter=0;
			foreach($data as $key=>$column){
				if($res[$key]!=$column){
					$sql.=$key." = '".$column."', ";
					++$counter;
				}
			}
			if($counter>0){
				$sql=strlen($sql)>0?substr($sql,0,strlen($sql)-2):$sql;
				$sql.=" where LOGID='".$data["LOGID"]."' ";
				$check=$database->update_data($sql);
			}
				
			if($check){
				echo jsonMessages(true,1);
				exit;
			}else{
				echo jsonMessages(false,12);
				exit;
			}


		}
	}
	else{
		header("Location:../");
		exit;
	}
?>
