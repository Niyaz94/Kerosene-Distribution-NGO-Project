<?php
	include_once "../_general/backend/_header.php";
	if (isset($_POST['type']) || isset($_GET['type'])){
		if ($_POST["type"] == "given"){
			$round = $database->return_data2(array(
                "tablesName"=>array("round"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
			));
			//return family total in system
			$familyTotal = $database->return_data2(array(
                "tablesName"=>array("family"),
                "columnsName"=>array("count(*) as total"),
                "conditions"=>array(
                    array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"FMYActive","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
			))["total"];
			
			$donation_total=$database->return_data("
				SELECT
					RNDNumber,
					count(*) as total,
					sum(DDTQty) as total_liter
				FROM
					family,
					donation,
					product,
					donation_detail,
					round
				WHERE
					DOTDeleted=0 AND
					RNDDeleted=0 AND
					DDTDeleted=0 AND
					FMYDeleted=0 AND
					FMYActive=0 and
					DDTDOTFORID=DOTID AND
					DDTPDUFORID=PDUID AND
					DOTFMYFORID=FMYID AND
					(PDUName='Kerosene' || PDUName='kerosene') and
					RNDID=DOTRNDFORID
				group BY
					DOTRNDFORID
			","key_all");
			$title=$data1=$data2=$data3=array();
			for ($i=0; $i < count($round); $i++) { 
				array_push($title,"Round ".$round[$i]["RNDNumber"]);
				$count=0;
				for ($j=0; $j < count($donation_total); $j++) { 
					if($donation_total[$j]["RNDNumber"]==$round[$i]["RNDNumber"]){
						array_push($data1,$donation_total[$j]["total"]);
						array_push($data2,($familyTotal-$donation_total[$j]["total"]));
						array_push($data3,$donation_total[$j]["total_liter"]);
						++$count;
						break;
					}
				}
				if($count==0){
					array_push($data1,0);
					array_push($data3,0);
					array_push($data2,$familyTotal);
				}
			}


			$family_total_per_camp=$database->return_data("
				SELECT
					CMPID,
					CMPName,
					count(FMYID) as total
				FROM
					family,
					camp
				WHERE
					FMYCMPFORID=CMPID AND
					FMYDeleted=0 AND
					FMYActive=0 and
					CMPDeleted=0
				GROUP BY
					CMPID
			","key_all");
			/*$RNDID = $database->return_data2(array(
                "tablesName"=>array("round"),
                "columnsName"=>array("ifnull(RNDID,0) as RNDID"),
                "conditions"=>array(
                    array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>"and"),
                    array("columnName"=>"RNDActive","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ))["RNDID"];
            if(empty($RNDID)){
                $RNDID=0;
            }*/
			$family_total_per_camp_donation=$database->return_data("
				SELECT
					CMPID,
					count(FMYID) as total_received
				FROM
					family,
					donation,
					camp
				WHERE
					FMYID=DOTFMYFORID AND
					FMYCMPFORID=CMPID AND
					DOTRNDFORID=CMPRNDFORID AND
					FMYDeleted=0 AND
					DOTDeleted=0 AND
					CMPDeleted=0 and
					FMYActive=0
				GROUP BY
					CMPID
			","key_all");
			for ($i=0; $i < count($family_total_per_camp); $i++) { 
				$count=0;
				for ($j=0; $j < count($family_total_per_camp_donation); $j++) { 
					if($family_total_per_camp[$i]["CMPID"]==$family_total_per_camp_donation[$j]["CMPID"]){
						$family_total_per_camp[$i]["total_received"]=$family_total_per_camp_donation[$j]["total_received"];
						$family_total_per_camp[$i]["total"]=$family_total_per_camp[$i]["total"]-$family_total_per_camp_donation[$j]["total_received"];
						++$count;
						break;
					}
				}
				if($count==0){
					$family_total_per_camp[$i]["total_received"]=0;
				}
			}
			echo json_encode(array($title,$data1,$data2,$data3,$family_total_per_camp));
		}
	}else{
		header("Location:../");
		exit;
	}
?>
