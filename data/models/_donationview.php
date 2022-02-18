<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $table = "(
                select
                    donation.*,
                    FMYID,
                    FMYFamilyName,
                    (
                        SELECT
                            ADMUsername
                        FROM
                            system_log,
                            admin
                        WHERE
                            ADMID=LOGCreateBy AND
                            LOGTable='donation' AND
                            LOGAction='Insert' AND
                            LOGRowID=DOTID
                        order by LOGID desc
                        limit 1
                    ) as ADMUsername,
                    (
                        SELECT
                            RNDNumber
                        FROM
                            round
                        WHERE
                            RNDID=DOTRNDFORID AND
                            RNDDeleted=0
                    ) as RNDNumber
                from
                    donation,
                    family
                where
                    FMYID=DOTFMYFORID and
                    FMYID=".$_GET["FMYID"]." and
                    DOTDeleted=0 and
                    FMYDeleted=0
            ) as table1";
            $primaryKey = "DOTID";
            $where="";
            $columns =  array(
                array( "db" => "DOTID", "dt" => 0 ),  
                array( "db" => "FMYFamilyName", "dt" => 1 ),  
                array( "db" => "RNDNumber", "dt" => 2,"formatter"=>function($d,$row){
                    return "Round ".$d;
                }),  
                array( "db" => "ADMUsername", "dt" => 3),  
                array( "db" => "FMYID", "dt" => 4 ),  
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "delete"){
            extract($_POST);
            $validation=new class_validation($_POST,"DOT");
			$data=$validation->returnLastVersion();
            extract($data);	
            $donation_detail = $database->return_data2(array(
                "tablesName"=>array("donation_detail"),
                "columnsName"=>array("DDTID","DDTPDUFORID","DDTQty"),
                "conditions"=>array(
                    array("columnName"=>"DDTDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"DDTDOTFORID","operation"=>"=","value"=>$DOTID,"link"=>"")
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            $family_product_max = $database->return_data2(array(
                "tablesName"=>array("family_product_max"),
                "columnsName"=>array("FPMID","FPMPDUFORID","FPMRemainAmount"),
                "conditions"=>array(
                    array("columnName"=>"FPMDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"FPMFMYFORID","operation"=>"=","value"=>$FMYID,"link"=>"")
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            $delete_donation = $database->delete_data2(array(
				"tablesName"=>"donation",
				"userData"=>$data,
				"conditions"=>array()
            ));
            for ($i=0; $i < count($donation_detail); $i++) { 
                $database->delete_data3(array(
                    "tablesName"=>"donation_detail",
                    "userData"=>array(
                        "PageName"=>$PageName,
                        "primaryKey"=>array("key"=>"DDTID","value"=>$donation_detail[$i]["DDTID"])
                    ),
                    "conditions"=>array(
                        array("columnName"=>"DDTDeleted","operation"=>"=","value"=>0,"link"=>"and")
                    ),
                    "symbol"=>"DDT"
                ));
                for ($j=0; $j < count($family_product_max); $j++) { 
                    if($family_product_max[$j]["FPMPDUFORID"]==$donation_detail[$i]["DDTPDUFORID"]){
                        $database->update_data2(array(
                            "tablesName"=>"family_product_max",
                            "userData"=>array(
                                "PageName"=>$_POST["PageName"],
                                "FPMRemainAmount"=>($family_product_max[$j]["FPMRemainAmount"]+$donation_detail[$i]["DDTQty"]),
                                "primaryKey"=>array("key"=>"FPMID","value"=>$family_product_max[$j]["FPMID"])
                            ),
                            "conditions"=>array(),
                        ));
                    }
                }

            }
            if ($delete_donation) {	
                echo jsonMessages(true,1);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if($_POST["type"] == "getData"){
            //testData($_POST,0);
            //PROBLEM
            echo jsonMessages2(
                true,
                $database->return_data("
                    SELECT
                        DDTID,
                        CASE 
                            WHEN DDTType = 0 THEN 'Quantity' 
                            WHEN DDTType = 1 THEN 'Meter' 
                            WHEN DDTType = 2 THEN 'Liter'
                            WHEN DDTType = 3 THEN 'Packet'
                        END AS DDTType,
                        DDTQty,
                        PDUName
                    FROM
                        donation_detail,
                        product
                    WHERE
                        PDUID=DDTPDUFORID and
                        DDTDeleted=0 and 
                        DDTDOTFORID=".$_POST["DOTID_UIZP"]."
                ","key_all")
            );
        } 
        if ($_POST["type"] == "delete"){
            extract($_POST);
            $update_family = $database->update_data2(array(
				"tablesName"=>"family",
				"userData"=>array(
					"PageName"=>$PageName,
                    "FMYNextDonaton"=>"$DOTStartDate",
					"primaryKey"=>array("key"=>"FMYID","value"=>$FMYID)
				),
				"conditions"=>array()
            ));
            $validation=new class_validation($_POST,"DOT");
			$data=$validation->returnLastVersion();
            extract($data);	
            //testData($_POST,0);
            //testData($data,0);
            $delete_donation = $database->delete_data2(array(
				"tablesName"=>"donation",
				"userData"=>$data,
				"conditions"=>array()
            ));
            $database->delete_data3(array(
                "tablesName"=>"donation_detail",
                "userData"=>array(
                    "PageName"=>$PageName,
                    "foreignKey"=>array("key"=>"DDTDOTFORID","value"=>$DOTID)
                ),
                "conditions"=>array(
                    array("columnName"=>"DDTDeleted","operation"=>"=","value"=>0,"link"=>"and")
                ),
                "symbol"=>"DDT"
            ));
            if ($delete_donation) {	
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
    