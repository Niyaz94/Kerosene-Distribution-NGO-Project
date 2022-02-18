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
                    CMPName,
                    CMPID,
                    ifnull(DOTID,0) as rowcolor,
                    ifnull(DOTType,2) as distribution_type
                from
                    family,
                    camp,
                    donation
                where
                    $extraCondition
                    CMPID=FMYCMPFORID and
                    DOTFMYFORID=FMYID AND
                    DOTRNDFORID=CMPRNDFORID AND
                    FMYDeleted=0 and
                    CMPDeleted=0 AND
                    DOTDeleted=0
            ) as table1";
            $primaryKey = "FMYID";
            $where="FMYDeleted=0";
            $columns =  array(
                array( "db" => "FMYID", "dt" => 0 ),  
                array( "db" => "FMYFamilyName", "dt" => 1 ),  
                array( "db" => "FMYFamilyCaseID", "dt" => 2 ),  
                array( "db" => "CMPName", "dt" => 3 ),  
                array( "db" => "FMYBarcode", "dt" => 4 ),  
                array( "db" => "distribution_type", "dt" => 5 ),  
                array( "db" => "rowcolor", "dt" =>6) 
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "acceptDonation"){
            if(!in_array($_POST["CMPID"],explode(",",$_SESSION["ADMCampID"]))){
                echo jsonMessages(false,27);
                exit;
            }
            extract($_POST);
            //return all info for the current round of the camp the contain that family
            $round = $database->return_data2(array(
                "tablesName"=>array("round"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"RNDID","operation"=>"=","value"=>$currentRound,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            //if this round doesn't exist
            if(empty($round)){
                echo jsonMessages(false,17);
                exit;
            }
            //***************************************************************************************************
            //check date and time of the round
            
            //check the data and time of the current round, if it's okey or not
            if(strtotime($round["RNDStartTime"])>strtotime($round["RNDEndTime"]) || strtotime(date("G:i:s"))<strtotime($round["RNDStartTime"]) || strtotime(date("G:i:s"))>strtotime($round["RNDEndTime"]) ){
                echo jsonMessages(false,22);
                exit;
            }
            $currentDate=Date("Y-m-d");
            //check if the user enter wrong data for the round or not
            if($round["RNDEndDate"]<=$round["RNDStartDate"]){
                echo jsonMessages(false,19);
                exit;
            }
            if($currentDate<$round["RNDStartDate"] || $currentDate>$round["RNDEndDate"]){
                echo jsonMessages(false,20);
                exit;
            }
            //***************************************************************************************************

            //check if this family already take the kerosene or not
            $current_donation = $database->return_data2(array(
                "tablesName"=>array("donation"),
                "columnsName"=>array("DOTID"),
                "conditions"=>array(
                    array("columnName"=>"DOTType","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"DOTDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"DOTFMYFORID","operation"=>"=","value"=>$FMYID_UIZP,"link"=>"and"),
                    array("columnName"=>"DOTRNDFORID","operation"=>"=","value"=>$currentRound,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            //if it take then stop the process
            if(!is_array($current_donation) || !isset($current_donation["DOTID"]) || $current_donation["DOTID"]<1){
                echo jsonMessages(false,18);
                exit;
            }
            
            //return product information
            $product=$database->return_data("
                SELECT
                    PDUID,
                    PDUType,
                    PDDItemNumber as PDUQty
                FROM
                    product,
                    product_detail
                WHERE
                    PDDPDUFORID=PDUID AND
                    PDUDeleted=0 AND
                    PDUActive=0 AND
                    PDDDeleted=0 AND
                    PDDItemNumber>0 AND
                    PDDCMPFORID=$CMPID
            ","key_all");
            $family_product_max=$database->return_data("
                SELECT
                    FPMID,FPMPDUFORID,FPMRemainAmount
                FROM
                    family_product_max
                WHERE
                    FPMFMYFORID=$FMYID_UIZP and
                    FPMRemainAmount>0 and
                    FPMDeleted=0
            ","key_all");
            if(count($product)==0 || count($family_product_max)==0){
                echo jsonMessages(false,14);
                exit;
            }
            for($i=0;$i<count($product);++$i){
                $index=findInMatrix($family_product_max,"FPMPDUFORID",$product[$i]["PDUID"]);
                $familyRemain=$family_product_max[$index]["FPMRemainAmount"];
                $remain=$insert=0;
                if($familyRemain>=$product[$i]["PDUQty"]){
                    $remain=$familyRemain-$product[$i]["PDUQty"];
                    $insert=$product[$i]["PDUQty"];
                }else{
                    $remain=0;
                    $insert=$familyRemain;
                }
                $database->update_data2(array(
                    "tablesName"=>"family_product_max",
                    "userData"=>array(
                        "PageName"=>$PageName,
                        "FPMRemainAmount"=>$remain,
                        "primaryKey"=>array("key"=>"FPMID","value"=>$family_product_max[$index]["FPMID"])
                    ),
                    "conditions"=>array()
                ));
                $database->insert_data2("donation_detail",array(
                    "DDTDOTFORID"=>$current_donation["DOTID"],
                    "PageName"=>$PageName,
                    "DDTPDUFORID"=>$product[$i]["PDUID"],
                    "DDTType"=>$product[$i]["PDUType"],
                    "DDTQty"=>$insert
                ));
            }

            $donation_id =$database->update_data2(array(
                "tablesName"=>"donation",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "DOTType"=>1,
                    "primaryKey"=>array("key"=>"DOTID","value"=>$current_donation["DOTID"])
                ),
                "conditions"=>array()
            ));
            if ($current_donation["DOTID"]>0) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        } 
        if($_POST["type"] == "searchByBarcode"){
            $res = $database->return_data2(array(
                "tablesName"=>array("family"),
                "columnsName"=>array("FMYID","FMYCMPFORID","FMYActive","FMYNote"),
                "conditions"=>array(
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"FMYBarcode","operation"=>"=","value"=>$_POST["barcode"],"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            if(!is_array($res)){
                echo jsonMessages(false,1);
            }else if(empty($res) || count($res)>1){
                echo jsonMessages(false,15);
            }else{
                $amount=$database->return_data('
                    SELECT
                        PDDItemNumber as amount
                    FROM
                        product,
                        product_detail
                    WHERE
                        PDUDeleted=0 AND
                        PDDDeleted=0 AND
                        PDDPDUFORID=PDUID AND
                        PDDCMPFORID='.$res[0]["FMYCMPFORID"].' AND
                        (PDUName="kerosene" OR PDUName="Kerosene")
                ',"key");
                if(empty($amount["amount"])){
                    $amount=0;
                }
                $res[0]["currentRound"] = $database->return_data2(array(
                    "tablesName"=>array("camp"),
                    "columnsName"=>array("CMPRNDFORID"),
                    "conditions"=>array(
                        array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                        array("columnName"=>"CMPID","operation"=>"=","value"=>$res[0]["FMYCMPFORID"],"link"=>"")
                    ),
                    "others"=>"",
                    "returnType"=>"key"
                ))["CMPRNDFORID"];
                $res[0]["amount"]=$amount["amount"];
                $res[0]["FMYNote"]=html_entity_decode($res[0]["FMYNote"]);
                echo jsonMessages2(true,$res[0]);
            }
        }
        if ($_POST["type"] == "acceptMultiDonation"){
            //testData($_POST);
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
            $failRows="";
            for ($k=0,$kl=count($data); $k < $kl; $k++) {  
                $round = $database->return_data2(array(
                    "tablesName"=>array("round"),
                    "columnsName"=>array("*"),
                    "conditions"=>array(
                        array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                        array("columnName"=>"RNDNumber","operation"=>"=","value"=>$data[$k]["RoundNumber"],"link"=>""),
                    ),
                    "others"=>"",
                    "returnType"=>"key"
                ));
                if(empty($round)){
                    $failRows.="error_1,";
                    continue;
                }
                $family = $database->return_data2(array(
                    "tablesName"=>array("family"),
                    "columnsName"=>array("*"),
                    "conditions"=>array(
                        array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                        array("columnName"=>"FMYFamilyCaseID","operation"=>"=","value"=>$data[$k]["FamilyCaseID"],"link"=>""),
                    ),
                    "others"=>"",
                    "returnType"=>"key"
                ));
                if(empty($family)){
                    $failRows.="error_4,";
                    continue;
                }
                $donation_id = $database->return_data2(array(
                    "tablesName"=>array("donation"),
                    "columnsName"=>array("DOTID"),
                    "conditions"=>array(
                        array("columnName"=>"DOTDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                        array("columnName"=>"DOTType","operation"=>"=","value"=>0,"link"=>"and"),
                        array("columnName"=>"DOTFMYFORID","operation"=>"=","value"=>$family["FMYID"],"link"=>"and"),
                        array("columnName"=>"DOTRNDFORID","operation"=>"=","value"=>$round["RNDID"],"link"=>""),
                    ),
                    "others"=>"",
                    "returnType"=>"key"
                ))["DOTID"];
                if($donation_id<1){
                    $failRows.="error_2,";
                    continue;
                }
                //return product information
                $product=$database->return_data("
                    SELECT
                        PDUID,
                        PDUType,
                        PDDItemNumber as PDUQty
                    FROM
                        product,
                        product_detail
                    WHERE
                        PDDPDUFORID=PDUID AND
                        PDUDeleted=0 AND
                        PDUActive=0 AND
                        PDDDeleted=0 AND
                        PDDItemNumber>0 AND
                        PDDCMPFORID=".$family["FMYCMPFORID"]."
                ","key_all");
                $family_product_max=$database->return_data("
                    SELECT
                        FPMID,FPMPDUFORID,FPMRemainAmount
                    FROM
                        family_product_max
                    WHERE
                        FPMFMYFORID=".$family["FMYID"]." and
                        FPMRemainAmount>0 and
                        FPMDeleted=0
                ","key_all");
                if(count($product)==0 || count($family_product_max)==0){
                    $failRows.="error_3,";
                    continue;
                }
                $database->update_data2(array(
                    "tablesName"=>"donation",
                    "userData"=>array(
                        "PageName"=>$_POST["PageName"],
                        "DOTType"=>1,
                        "primaryKey"=>array("key"=>"DOTID","value"=>$donation_id)
                    ),
                    "conditions"=>array()
                ));
                for($i=0;$i<count($product);++$i){
                    $index=findInMatrix($family_product_max,"FPMPDUFORID",$product[$i]["PDUID"]);
                    $familyRemain=$family_product_max[$index]["FPMRemainAmount"];
                    $remain=$insert=0;
                    if($familyRemain>=$product[$i]["PDUQty"]){
                        $remain=$familyRemain-$product[$i]["PDUQty"];
                        $insert=$product[$i]["PDUQty"];
                    }else{
                        $remain=0;
                        $insert=$familyRemain;
                    }
                    $database->update_data2(array(
                        "tablesName"=>"family_product_max",
                        "userData"=>array(
                            "PageName"=>$_POST["PageName"],
                            "FPMRemainAmount"=>$remain,
                            "primaryKey"=>array("key"=>"FPMID","value"=>$family_product_max[$index]["FPMID"])
                        ),
                        "conditions"=>array()
                    ));
                    $database->insert_data2("donation_detail",array(
                        "DDTDOTFORID"=>$donation_id,
                        "PageName"=>$_POST["PageName"],
                        "DDTPDUFORID"=>$product[$i]["PDUID"],
                        "DDTType"=>$product[$i]["PDUType"],
                        "DDTQty"=>$insert
                    ));
                }
            }
            //echo $failRows;
            //exit;
            echo output(strlen($failRows)>0?0:1,2,28);
        }
    }else{
        header("Location:../");
        exit;
    }
    function addingTodate($date,$year=0,$month=0,$day=0){
        return date("Y-m-d",strtotime("+".$year." year +".$month." month +".$day." day",strtotime($date)));
    }
    function findInMatrix($matrix,$key,$value){
        for ($i=0; $i < count($matrix); $i++) { 
            if($matrix[$i][$key]==$value){
                return $i;
            }
        }
        return -1;
    }
?>  