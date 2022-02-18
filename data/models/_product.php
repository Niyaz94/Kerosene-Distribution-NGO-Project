<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "(
                select
                    product.*
                from
                    product
                where
                    PDUDeleted=0
            ) as table1";
            $primaryKey = "PDUID";
            $where="PDUDeleted=0";
            $columns =  array(
                array( "db" => "PDUID", "dt" => 0 ),  
                array( "db" => "PDUName", "dt" => 1 ),  
                array( "db" => "PDUType", "dt" => 2 ),  
                array( "db" => "PDUActive", "dt" => 3 )  
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            //testData($_POST,0);
            $validation=new class_validation($_POST,"PDU");
            $data=$validation->returnLastVersion();
            extract($data);
            //testData($data,0);
            $res = $database->return_data2(array(
                "tablesName"=>array("product"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"PDUDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"PDUName","operation"=>"=","value"=>$PDUName,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,1);
                exit;
            }
            $product_id = $database->insert_data2("product",$data);
            for($i=0;$i<count($_POST["PDDCMPFORID_INZN"]);$i++){
                $database->insert_data2("product_detail",array(
                    "PageName"=>$PageName,
                    "PDDPDUFORID"=>$product_id,
                    "PDDCMPFORID"=>$_POST["PDDCMPFORID_INZN"][$i]
                ));
            }
            $family = $database->return_data2(array(
                "tablesName"=>array("family"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>"")
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            //testData($product);
            for($i=0;$i<count($family);$i++){
                $database->insert_data2("family_product_max",array(
                    "PageName"=>$PageName,
                    "FPMFMYFORID"=>$family[$i]["FMYID"],
                    "FPMPDUFORID"=>$product_id
                ));
            }
            if ($product_id) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            $oldCampID=$database->return_data("
                SELECT
                    PDDID,PDDCMPFORID
                FROM
                    product,
                    product_detail
                WHERE
                    PDUID=".$_POST["PDUID_UIZP"]." AND
                    PDUID=PDDPDUFORID AND
                    PDUDeleted=0 AND
                    PDDDeleted=0
            ","key_all");
            $validation=new class_validation($_POST,"PDU");
            $data=$validation->returnLastVersion();
            extract($data);
            $res = $database->return_data2(array(
                "tablesName"=>array("product"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"PDUDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"PDUName","operation"=>"=","value"=>$PDUName,"link"=>"and"),
                    array("columnName"=>"PDUID","operation"=>"<>","value"=>$PDUID,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,1);
                exit;
            }
            $deleteID=array_values(array_diff(array_column($oldCampID,"PDDCMPFORID"),$_POST["PDDCMPFORID_UNRN"]));
            $insertID=array_values(array_diff($_POST["PDDCMPFORID_UNRN"],array_column($oldCampID,"PDDCMPFORID")));
            //testData($insertID,0);
            for ($i=0; $i < count($deleteID); $i++) { 
                $deletedID=0;
                for ($j=0; $j < count($oldCampID); $j++) { 
                    if($oldCampID[$j]["PDDCMPFORID"]==$deleteID[$i]){
                        $deletedID=$oldCampID[$j]["PDDID"];
                        break;
                    }
                }
                $database->delete_data3(array(
                    "tablesName"=>"product_detail",
                    "userData"=>array(
                        "PageName"=>$PageName,
                        "primaryKey"=>array("key"=>"PDDID","value"=>$deletedID)
                    ),
                    "conditions"=>array(
                        array("columnName"=>"PDDDeleted","operation"=>"=","value"=>0,"link"=>"and")
                    ),
                    "symbol"=>"PDD"
                ));
            }
            for ($i=0; $i < count($insertID); $i++) { 
                $database->insert_data2("product_detail",array(
                    "PageName"=>$PageName,
                    "PDDPDUFORID"=>$PDUID,
                    "PDDCMPFORID"=>$insertID[$i]
                ));
            }
            $res = $database->update_data2(array(
                "tablesName"=>"product",
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
            $validation=new class_validation($_POST,"PDU");
			$data=$validation->returnLastVersion();
            extract($data);	

            $res = $database->return_data2(array(
                "tablesName"=>array("donation_detail"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"DDTDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"DDTPDUFORID","operation"=>"=","value"=>$PDUID,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,24);
                exit;
            }

            $delete_product = $database->delete_data3(array(
				"tablesName"=>"product",
				"userData"=>$data,
                "conditions"=>array(
                    array("columnName"=>"PDUDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"PDU"
            ));
            $database->delete_data3(array(
                "tablesName"=>"family_product_max",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "foreignKey"=>array("key"=>"FPMPDUFORID","value"=>$PDUID)
                ),
                "symbol"=>"FPM",
                "conditions"=>array(
                    array("columnName"=>"FPMDeleted","operation"=>"=","value"=>0,"link"=>"and")
                )
            ));
            $database->delete_data3(array(
                "tablesName"=>"product_detail",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "foreignKey"=>array("key"=>"PDDPDUFORID","value"=>$PDUID)
                ),
                "symbol"=>"PDD",
                "conditions"=>array(
                    array("columnName"=>"PDDDeleted","operation"=>"=","value"=>0,"link"=>"and")
                )
            ));
            if ($delete_product) {	
                echo jsonMessages(true,1);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "getAmount"){
            echo jsonMessages2(true,$database->return_data3(array(
                "tablesName"=>array("'product_detail'","'camp'"),
                "columnsName"=>array("CMPName","PDDID","PDDItemNumber"),
                "conditions"=>array(
                    array("columnName"=>"PDDDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"PDDPDUFORID","operation"=>"=","value"=>$_POST["PDUID"],"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            )));
        }
        if ($_POST["type"] == "setAmount") {
            foreach ($_POST as $key => $value) {
                $id=explode("_",$key);
                if(count($id)==2){
                    $database->update_data2(array(
                        "tablesName"=>"product_detail",
                        "userData"=>array(
                            "PageName"=>$_POST["PageName"],
                            "PDDItemNumber"=>$value,
                            "primaryKey"=>array("key"=>"PDDID","value"=>$id[1])
                        ),
                        "conditions"=>array(),
                    ));
                }
            }
            echo jsonMessages(true,1);
            exit;
        }
    }else{
        header("Location:../");
        exit;
    }
?>
    