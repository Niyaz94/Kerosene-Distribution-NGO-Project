<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"])){
        
        if ($_POST["type"] == "getData") {	
            //testData($_POST,0);
            extract($_POST);
            $res = $database->return_data3(array(
                "tablesName"=>is_array($table)?$table:array($table),
                "columnsName"=>json_decode($columns,true),
                "conditions"=>json_decode($condition,true),
                "others"=>"",
                "returnType"=>"key"
            ));
            //testData($res,0);
            if($res){
                echo html_entity_decode(jsonMessages2(true,$res));
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "delete") {	
            $validation=new class_validation($_POST,$_POST["symbol"]);
			$data=$validation->returnLastVersion();
            extract($data);	
            //testData($data,0);
			$res = $database->delete_data2(array(
				"tablesName"=>$_POST["table"],
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
        if ($_POST['type']=="returnShopNumber") {
            extract($_POST);
			$result=$database->return_data("
				SELECT
                    SOPID,
                    SOPNumber,
                    SOPArea,
                    SOPFloor,
                    case 
                        SOPCategory
                    when 
                        0 then 'Shop'
                    when 
                        1 then 'Pergola'
                    when 
                        2 then 'Office'
                    end as SOPCategory
				FROM
                    shop
				WHERE
                    SOPDeleted=0 AND
                    $condition
                    SOPNumber like '%$SOPNumber%' 
                limit 10
            ","key_all");			
			if(count($result)>0){
				echo json_encode($result);
				exit;
			}else{
				$result=array(array("SOPID"=>0,"SOPNumber"=>"Shop Not Found","SOPArea"=>0,"SOPFloor"=>0,"SOPCategory"=>"Not Found"));
				echo json_encode($result);
				exit;
			}   		 
        }
        if ($_POST['type']=="returnCapitalCategory") {
            extract($_POST);
			$result=$database->return_data("
				SELECT
                    CTYID,
                    CTYName,
                    case 
                        CTYUse
                    when 
                        0 then 'Use In Expense'
                    when 
                        1 then 'Not Use In Expense'
                    when 
                        2 then '-----'
                    end as CTYUse,
                    case 
                        CTYType
                    when 
                        0 then 'Income'
                    when 
                        1 then 'Expense'
                    end as CTYType
				FROM
                    capitaltype
				WHERE
                    CTYDeleted=0 AND
                    CTYID<>1 AND
                    $condition
                    CTYName like '%$CTYName%' 
                limit 10
            ","key_all");			
			if(count($result)>0){
				echo json_encode($result);
				exit;
			}else{
				$result=array(array("CTYID"=>0,"CTYName"=>"Not Fount Category","CTYUse"=>"Not Found","CTYType"=>"Not Found"));
				echo json_encode($result);
				exit;
			}   		 
        }
        if ($_POST['type']=="returnCustomerID") {
            extract($_POST);
			$result=$database->return_data("
				SELECT
                    CUSID,
                    CUSName,
                    CUSAddress,
                    CUSPhone
				FROM
                    customer
				WHERE
                    CUSDeleted=0 AND
                    CUSName like '%$SOPNumber%' 
                limit 10
            ","key_all");			
			if(count($result)>0){
				echo json_encode($result);
				exit;
			}else{
				$result=array(array("CUSID"=>0,"CUSName"=>"Customer Not Found","CUSAddress"=>"","CUSPhone"=>""));
				echo json_encode($result);
				exit;
			}   		 
        }
    }else{
        header("Location:../");
        exit;
    }
?>