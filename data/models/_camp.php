<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "(
                select
                    camp.*,
                    (
                        SELECT
                            RNDNumber
                        FROM
                            round
                        WHERE
                            RNDID=CMPRNDFORID AND
                            RNDDeleted=0
                    ) as activeRound,
                    (
                        select
                            count(*)
                        from
                            family
                        where
                            FMYCMPFORID=CMPID and
                            FMYDeleted=0
                    ) as familyTotal
                from
                    camp
                where
                    CMPDeleted=0 
            ) as table1";
            $primaryKey = "CMPID";
            $where="";
            $columns =  array(
                array( "db" => "CMPID", "dt" => 0 ),  
                array( "db" => "CMPName", "dt" => 1 ),  
                array( "db" => "activeRound", "dt" => 2 , "formatter"=>function($d){
                    return $d;
                }),  
                array( "db" => "CMPNote", "dt" => 3, "formatter"=>function($d){
                    return html_entity_decode($d);
                }),  
                array( "db" => "familyTotal", "dt" => 4),  
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            //testData($_POST,0);
            $validation=new class_validation($_POST,"CMP");
            $data=$validation->returnLastVersion();
            extract($data);
            $res = $database->insert_data2("camp",$data);
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
            $validation=new class_validation($_POST,"CMP");
            $data=$validation->returnLastVersion();
            extract($data);
            $res = $database->return_data2(array(
                "tablesName"=>array("camp"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"CMPName","operation"=>"=","value"=>$CMPName,"link"=>"and"),
                    array("columnName"=>"CMPID","operation"=>"!=","value"=>$CMPID,"link"=>"")
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,1);
                exit;
            }
            $res = $database->update_data2(array(
                "tablesName"=>"camp",
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
            $validation=new class_validation($_POST,"CMP");
			$data=$validation->returnLastVersion();
            extract($data);	
            $res = $database->return_data2(array(
                "tablesName"=>array("family"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"FMYCMPFORID","operation"=>"=","value"=>$CMPID,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,25);
                exit;
            }
            $delete_camp = $database->delete_data3(array(
				"tablesName"=>"camp",
				"userData"=>$data,
                "conditions"=>array(
                    array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"CMP"
            ));
            if ($delete_camp) {	
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
    