<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "round";
            $primaryKey = "RNDID";
            $where="RNDDeleted=0";
            $columns =  array(
                array( "db" => "RNDID", "dt" => 0 ),  
                array( "db" => "RNDNumber", "dt" => 1),  
                array( "db" => "RNDStartDate", "dt" => 2 ),  
                array( "db" => "RNDEndDate", "dt" => 3 ),  
                array( "db" => "RNDStartTime", "dt" => 4,"formatter"=>function($d,$row){
                    return date("H a",strtotime($d));
                }),  
                array( "db" => "RNDEndTime", "dt" => 5,"formatter"=>function($d,$row){
                    return date("h a",strtotime($d));
                }),  
                //array( "db" => "RNDActive", "dt" => 6 ),  
                array( "db" => "RNDDeleted", "dt" => 6 )  
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	

            $_POST["RNDStartTime_IDZN"]=date("G:i:s",strtotime($_POST["RNDStartTime_IDZN"]));
            $_POST["RNDEndTime_IDZN"]=date("G:i:s",strtotime($_POST["RNDEndTime_IDZN"]));
            $validation=new class_validation($_POST,"RND");
            $data=$validation->returnLastVersion();
            extract($data);
            /*$res = $database->return_data2(array(
                "tablesName"=>array("round"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"RNDNumber","operation"=>"=","value"=>$RNDNumber,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,1);
                exit;
            }
            $database->update_data("
                UPDATE
                    round
                SET
                RNDActive=1
                where 
                    RNDDeleted=0
            ");*/
            $res = $database->insert_data2("round",$data);
            if ($res) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            //testData($_POST,0);
            $_POST["RNDStartTime_UDRN"]=date("G:i:s",strtotime($_POST["RNDStartTime_UDRN"]));
            $_POST["RNDEndTime_UDRN"]=date("G:i:s",strtotime($_POST["RNDEndTime_UDRN"]));
            $validation=new class_validation($_POST,"RND");
            $data=$validation->returnLastVersion();
            extract($data);
            $res = $database->return_data2(array(
                "tablesName"=>array("round"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"RNDNumber","operation"=>"=","value"=>$RNDNumber,"link"=>"and"),
                    array("columnName"=>"RNDID","operation"=>"<>","value"=>$RNDID,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,1);
                exit;
            }
            
            $res = $database->update_data2(array(
                "tablesName"=>"round",
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
        if ($_POST["type"] == "delete"){
            $validation=new class_validation($_POST,"RND");
			$data=$validation->returnLastVersion();
            extract($data);	
            $res = $database->return_data2(array(
                "tablesName"=>array("donation"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"DOTDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"DOTRNDFORID","operation"=>"=","value"=>$RNDID,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,26);
                exit;
            }
            $delete_round = $database->delete_data3(array(
				"tablesName"=>"round",
				"userData"=>$data,
                "conditions"=>array(
                    array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"RND"
            ));
            if ($delete_round) {	
                echo jsonMessages(true,1);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
    }else{
        header("Location:../");
        exit;
    }
?>
    