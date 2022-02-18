<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $extraCondition="";
            if($_GET["ADMCampID"]!=0){
                $extraCondition=" CMPID IN(".$_GET["ADMCampID"].") and ";
            }
            $table = "(
                select
                    family.*,
                    CMPName
                from
                    family,
                    camp
                where
                    $extraCondition
                    FMYDeleted=0 and
                    CMPID=FMYCMPFORID and
                    CMPDeleted=0
            ) as table1";
            $primaryKey = "FMYID";
            $where="FMYDeleted=0";
            $columns =  array(
                array( "db" => "FMYID", "dt" => 0 ),  
                array( "db" => "FMYFamilyName", "dt" => 1 ),  
                array( "db" => "FMYFamilyCaseID", "dt" => 2 ),  
                array( "db" => "CMPName", "dt" => 3 ),  
                array( "db" => "FMYBarcode", "dt" => 4),  
                array( "db" => "FMYActive", "dt" => 5),  
                array( "db" => "FMYDeleted", "dt" => 6)  
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            $validation=new class_validation($_POST,"FMY");
            $data=$validation->returnLastVersion();
            extract($data);
            $checkCaseID = $database->return_data2(array(
                "tablesName"=>array("family"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"FMYFamilyCaseID","operation"=>"=","value"=>$FMYFamilyCaseID,"link"=>"and"),
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($checkCaseID>0){
                echo jsonMessages(false,21);
                exit;
            }
            $max = $database->return_data2(array(
                "tablesName"=>array("family"),
                "columnsName"=>array("ifnull(max(FMYCaseNumber),0) as max"),
                "conditions"=>array(
                    array("columnName"=>"FMYCMPFORID","operation"=>"=","value"=>$FMYCMPFORID,"link"=>"and"),
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["max"];
            $data["FMYCaseNumber"]=$max+1;
            $family_id = $database->insert_data2("family",$data);

            $product = $database->return_data2(array(
                "tablesName"=>array("product"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"PDUDeleted","operation"=>"=","value"=>0,"link"=>"")
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            //testData($product);
            for($i=0;$i<count($product);$i++){
                $database->insert_data2("family_product_max",array(
                    "PageName"=>$PageName,
                    "FPMFMYFORID"=>$family_id,
                    "FPMPDUFORID"=>$product[$i]["PDUID"]
                ));
            }
            if ($family_id) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {
            //testData($_POST,0);
            $validation=new class_validation($_POST,"FMY");
            $data=$validation->returnLastVersion();
            extract($data);
            $checkCaseID = $database->return_data2(array(
                "tablesName"=>array("family"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"FMYFamilyCaseID","operation"=>"=","value"=>$FMYFamilyCaseID,"link"=>"and"),
                    array("columnName"=>"FMYID","operation"=>"<>","value"=>$FMYID,"link"=>"and"),
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($checkCaseID>0){
                echo jsonMessages(false,21);
                exit;
            }

            //testData($data,0);
            $res = $database->update_data2(array(
                "tablesName"=>"family",
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
            $validation=new class_validation($_POST,"FMY");
			$data=$validation->returnLastVersion();
            extract($data);	
            $res = $database->return_data2(array(
                "tablesName"=>array("donation"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"DOTDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"DOTFMYFORID","operation"=>"=","value"=>$FMYID,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res>0){
                echo jsonMessages(false,23);
                exit;
            }
            $delete_family = $database->delete_data3(array(
				"tablesName"=>"family",
				"userData"=>$data,
                "conditions"=>array(
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"FMY"
            ));
            $database->delete_data3(array(
                "tablesName"=>"family_product_max",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "foreignKey"=>array("key"=>"FPMFMYFORID","value"=>$FMYID)
                ),
                "conditions"=>array(
                    array("columnName"=>"FPMDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"FPM"
            ));
            if ($delete_family) {	
                echo jsonMessages(true,1);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if($_POST["type"] == "createBarcode"){
            echo createBarcode($database);
        }
        if ($_POST["type"] == "getAmount"){
            echo jsonMessages2(true,$database->return_data3(array(
                "tablesName"=>array("'family_product_max'","'product'"),
                "columnsName"=>array("FPMID","FPMMaxAmount","FPMRemainAmount","PDUName"),
                "conditions"=>array(
                    array("columnName"=>"PDUDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"FPMDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"FPMFMYFORID","operation"=>"=","value"=>$_POST["FMYID"],"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            )));
        }
        if ($_POST["type"] == "setAmount") {
            foreach ($_POST as $key => $value) {
                $id=explode("_",$key);
                if(count($id)==2){
                    $oldValue=$_POST["old_".$id[1]];
                    $oldRemain=$_POST["oldRemain_".$id[1]];
                    if($value>$oldValue){
                        $oldRemain=$oldRemain+($value-$oldValue);
                    }
                    $database->update_data2(array(
                        "tablesName"=>"family_product_max",
                        "userData"=>array(
                            "PageName"=>$_POST["PageName"],
                            "FPMMaxAmount"=>$value,
                            "FPMRemainAmount"=>$oldRemain,
                            "primaryKey"=>array("key"=>"FPMID","value"=>$id[1])
                        ),
                        "conditions"=>array(),
                    ));
                }
            }
            echo jsonMessages(true,1);
            exit;
        }
        if ($_POST["type"] == "barcodeChange") {
            $FMYBarcode=createBarcode($database);
            $res=$database->update_data2(array(
                "tablesName"=>"family",
                "userData"=>array(
                    "PageName"=>$_POST["PageName"],
                    "FMYBarcode"=>$FMYBarcode,
                    "primaryKey"=>array("key"=>"FMYID","value"=>$_POST["FMYID"])
                ),
                "conditions"=>array(),
            ));
            if ($res) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "addCSV"){
            if($_FILES["insertfile"]["type"]!="text/csv" && $_FILES["insertfile"]["type"]!="application/vnd.ms-excel"){
                echo jsonMessages(false,16);
                exit;
            }
            require_once('../../API/assets/csv/parsecsv.lib.php');
            $fileSaveName = rand(1000,100000) . "-" . $_FILES["insertfile"]['name'];
            $sourcePath = $_FILES["insertfile"]['tmp_name'];
			$targetPath = "../_general/csv/".$fileSaveName;
            move_uploaded_file($sourcePath,$targetPath) ;
            chmod($targetPath,0750);
            
	        $csv = new parseCSV();
	        $csv->auto("../_general/csv/".$fileSaveName);
            $data=$csv->data;
            //testData($data);
            $newData=array();
            for($i=0;$i<count($data);$i++){
              foreach($data[$i] as $key=>$value){
                  if($key=="﻿FamilyName"){
                    $data[$i]["FamilyName"]  =$value;
                  }
              }
            }
            $family = $database->return_data2(array(
                "tablesName"=>array("family"),
                "columnsName"=>array("FMYFamilyCaseID"),
                "conditions"=>array(
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            //testData($family);
            $camp = $database->return_data2(array(
                "tablesName"=>array("camp"),
                "columnsName"=>array("CMPID","CMPName"),
                "conditions"=>array(
                    array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            $campMax=$database->return_data("
                SELECT
                    CMPName,
                    ifnull(max(FMYCaseNumber),0) as max
                FROM
                    family,camp
                WHERE
                    CMPID=FMYCMPFORID AND
                    CMPDeleted=0 and
                    FMYDeleted=0
                group by
                    FMYCMPFORID
            ","key_all");
            $product = $database->return_data2(array(
                "tablesName"=>array("product"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"PDUDeleted","operation"=>"=","value"=>0,"link"=>"")
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            $errorCount=0;
            $dataLength=count($data);
            for ($i=0; $i < $dataLength ; $i++) { 
                if(empty(trim($data[$i]["FamilyName"])) || empty(trim($data[$i]["CampName"])) || empty(trim($data[$i]["FamilyCaseID"])) || empty(trim($data[$i]["Balance"])) ){
                    //echo 1111;
                    ++$errorCount;
                    continue;
                }
                if(checkDubCaseID($family,$data[$i]["FamilyCaseID"])==1){
                    //echo 2222;
                    ++$errorCount;
                    continue;
                }
                $active="0";
                if(trim($data[$i]["Active"])=="no"){
                    $active=1;
                }
                $FMYCMPFORID=findCampID($camp,$data[$i]["CampName"]);
                if($FMYCMPFORID==0){
                    //echo 3333;
                    ++$errorCount;
                    continue;
                }
                //print_r($campMax);
                $FMYCaseNumber=findNewCaseNumber($campMax,$data[$i]["CampName"]);
                if($FMYCaseNumber==-1){
                    array_push($campMax,array("CMPName"=>$data[$i]["CampName"],"max"=>1));
                }else{
                    ++$campMax[$FMYCaseNumber]["max"];
                }
                $family_id=$database->insert_data2("family",array(
                    "PageName"=>"csv file",
                    "FMYFamilyName"=>$data[$i]["FamilyName"],
                    "FMYCaseNumber"=>($FMYCaseNumber==-1?1:$campMax[$FMYCaseNumber]["max"]),
                    "FMYFamilyNumber"=>empty($data[$i]["FamilyNumber"])?"0":$data[$i]["FamilyNumber"],
                    "FMYFamilyCaseID"=>$data[$i]["FamilyCaseID"],
                    "FMYCMPFORID"=>$FMYCMPFORID,
                    "FMYBarcode"=>createBarcode($database),
                    "FMYActive"=>$active,
                    "FMYNote"=>(empty($data[$i]["Note"])?"":$data[$i]["Note"])
                ));
                for($j=0;$j<count($product);$j++){
                    $FPMMaxAmount=findProductID(json_decode($data[$i]["Balance"],true),$product[$j]["PDUName"]);
                    $database->insert_data2("family_product_max",array(
                        "PageName"=>"csv file",
                        "FPMFMYFORID"=>$family_id,
                        "FPMPDUFORID"=>$product[$j]["PDUID"],
                        "FPMMaxAmount"=>$FPMMaxAmount,
                        "FPMRemainAmount"=>$FPMMaxAmount
                    ));
                }
            }
            if($errorCount==0){
                echo jsonMessages(true,1);
            }else{
                echo jsonMessages2(false,$errorCount);
            }
        }
        if ($_POST["type"] == "addCSVDeactive"){
            if($_FILES["insertfile"]["type"]!="text/csv" && $_FILES["insertfile"]["type"]!="application/vnd.ms-excel"){
                echo jsonMessages(false,16);
                exit;
            }
            require_once('../../API/assets/csv/parsecsv.lib.php');
            $fileSaveName = rand(1000,100000) . "-" . $_FILES["insertfile"]['name'];
            $sourcePath = $_FILES["insertfile"]['tmp_name'];
			$targetPath = "../_general/csv/".$fileSaveName;
            move_uploaded_file($sourcePath,$targetPath) ;
            chmod($targetPath,0777);
            
	        $csv = new parseCSV();
            $csv->auto("../_general/csv/".$fileSaveName);
            $data=$csv->data;
            $newData=array();
            for($i=0;$i<count($data);$i++){
                foreach($data[$i] as $key=>$value){
                    $newData[$i][preg_replace("/[^A-Za-z0-9 ]/", '',$key)]=$value;
                }
            }
            $data=$newData;
            $errorCount=0;
            $dataLength=count($data);
            for ($i=0; $i < $dataLength ; $i++) { 
                if(empty(trim($data[$i]["FamilyCaseID"])) ){
                    ++$errorCount;
                    continue;
                }else{
                   $FMYNote="";
                  if(strlen(trim($data[$i]["Note"]))>0){
                    $FMYNote=" FMYNote=concat(FMYNote,'  ','".trim($data[$i]["Note"])."') ,";
                  }
                    $database->update_data("
                        UPDATE
                            family
                        SET
                            $FMYNote
                            FMYActive=1
                        WHERE
                            FMYFamilyCaseID='".$data[$i]["FamilyCaseID"]."' and
                            FMYDeleted=0
                    ");
                }
            }
            if($errorCount==0){
                echo jsonMessages(true,1);
            }else{
                echo jsonMessages2(false,$errorCount);
            }
        }
        if ($_POST["type"] == "addCSVActive"){
            if($_FILES["insertfile"]["type"]!="text/csv" && $_FILES["insertfile"]["type"]!="application/vnd.ms-excel"){
                echo jsonMessages(false,16);
                exit;
            }
            require_once('../../API/assets/csv/parsecsv.lib.php');
            $fileSaveName = rand(1000,100000) . "-" . $_FILES["insertfile"]['name'];
            $sourcePath = $_FILES["insertfile"]['tmp_name'];
			$targetPath = "../_general/csv/".$fileSaveName;
            move_uploaded_file($sourcePath,$targetPath) ;
            chmod($targetPath,0777);
            
	        $csv = new parseCSV();
            $csv->auto("../_general/csv/".$fileSaveName);
            $data=$csv->data;
            $newData=array();
            for($i=0;$i<count($data);$i++){
                foreach($data[$i] as $key=>$value){
                    $newData[$i][preg_replace("/[^A-Za-z0-9 ]/", '',$key)]=$value;
                }
            }
            $data=$newData;
            for($i=0;$i<count($data);$i++){
                foreach($data[$i] as $key=>$value){
                    if($key=="﻿FamilyCaseID"){
                      $data[$i]["FamilyCaseID"]  =$value;
                    }
                }
            }
            $errorCount=0;
            $dataLength=count($data);
            for ($i=0; $i < $dataLength ; $i++) { 
                if(empty(trim($data[$i]["FamilyCaseID"])) ){
                    ++$errorCount;
                    continue;
                }else{
                   $FMYNote="";
                    if(strlen(trim($data[$i]["Note"]))>0){
                        $FMYNote=" FMYNote=concat(FMYNote,'  ','".trim($data[$i]["Note"])."') ,";
                    }
                    $database->update_data("
                        UPDATE
                            family
                        SET
                            $FMYNote
                            FMYActive=0
                        WHERE
                            FMYFamilyCaseID='".$data[$i]["FamilyCaseID"]."' and
                            FMYDeleted=0
                    ");
                }
            }
            if($errorCount==0){
                echo jsonMessages(true,1);
            }else{
                echo jsonMessages2(false,$errorCount);
            }
        }
    }else{
        header("Location:../");
        exit;
    }
    function createBarcode($database){
        $barcode=mt_rand(100000,999999);
        for(;;){
            $res = $database->return_data2(array(
                "tablesName"=>array("family"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"FMYBarcode","operation"=>"=","value"=>$barcode,"link"=>"and"),
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"row_count"
            ));
            if($res==0){
                break;
            }else{
                $barcode=mt_rand(100000,999999);
            }
        }
        return $barcode;
    }
    function findCampID($camp,$campName){
        for ($i=0; $i < count($camp); $i++) { 
            if($camp[$i]["CMPName"]==trim($campName)){
                return $camp[$i]["CMPID"];
            }
        }
        return 0;
    }
    function checkDubCaseID($family,$caseID){
        $familyLength=count($family);
        for ($i=0; $i < $familyLength; $i++) { 
            if($family[$i]["FMYFamilyCaseID"]==trim($caseID)){
                echo $family[$i]["FMYFamilyCaseID"]."___";
                echo trim($caseID);
                return 1;
            }
        }
        return 0;
    }
    function findNewCaseNumber($camp,$campName){
        for ($i=0; $i < count($camp); $i++) { 
            if($camp[$i]["CMPName"]==trim($campName)){
                return $i;
            }
        }
        return -1;
    }
    function findProductID($product,$productName){
        for ($i=0; $i < count($product); $i++) { 
            if($product[$i][0]==trim($productName)){
                return $product[$i][1];
            }
        }
        return 0;
    }
?>
    