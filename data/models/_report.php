<?php
    include_once "../_general/backend/_header.php";
    include_once "_reportClass.php";
    $_report=new _reportClass($database);
    if (isset($_POST["type"]) || isset($_GET["type"])){   
        if ($_POST["type"] == "returnDataForFamily"){
            extract($_POST);
            $FMYActive="";
            if($active==1){
                $FMYActive="FMYActive=0 and ";
            }else if($active==2){
                $FMYActive="FMYActive=1 and ";
            }
            $FMYRegisterDate="";
            if(/*$startDate!=$endDate &&*/ $startDate<=$endDate){
                $FMYRegisterDate="substring_index(substring_index(FMYRegisterDate,' ',1),' ',-1) between '".$startDate."' and '".$endDate."' and ";
            }
            $FMYCMPFORID="";
            if($campID!=0){
                $FMYCMPFORID="FMYCMPFORID=$campID and ";
            }
            echo json_encode($database->return_data("
                SELECT
                    family.*,
                    CMPName
                FROM
                    family,
                    camp
                WHERE
                    $FMYActive
                    $FMYCMPFORID
                    $FMYRegisterDate
                    CMPID=FMYCMPFORID and
                    FMYDeleted=0
            ","key_all"));
        }
        if ($_POST["type"] == "returnDataForDistribution"){
            echo $_report->report1($_POST);
        }
        if ($_POST["type"] == "returnDataForFamilyPerRound"){
            extract($_POST);
            if($roundID<1){
                echo jsonMessages(false,1);
                exit;
            }
            $FMYRegisterDate="";
            if(/*$startDate!=$endDate &&*/ $startDate<=$endDate){
                $FMYRegisterDate="substring_index(substring_index(FMYRegisterDate,' ',1),' ',-1) between '".$startDate."' and '".$endDate."' and ";
            }
            $FMYCMPFORID="";
            if($campID!=0){
                $FMYCMPFORID="FMYCMPFORID=$campID and ";
            }
            echo json_encode($database->return_data("
                SELECT
                    FMYFamilyName,
                    FMYCaseNumber,
                    FMYFamilyCaseID,
                    CMPName,
                    ifnull(
                        (
                            SELECT
                                substring_index(substring_index(DOTRegisterDate,' ',1),' ',-1)
                            FROM
                                donation
                            WHERE
                                DOTFMYFORID=FMYID and
                                DOTType=1 and
                                DOTRNDFORID=$roundID and
                                DOTDeleted=0
                        )
                    ,0) as give_donation
                FROM
                    family,
                    camp
                WHERE
                    $FMYCMPFORID
                    $FMYRegisterDate
                    CMPID=FMYCMPFORID and
                    FMYDeleted=0 and
                    FMYActive=0
            ","key_all"));
        }
        if ($_POST["type"] == "returnDataForNotReceived"){
            //testData($_POST);
            extract($_POST);
            $DOTRNDFORID="";
            if($roundID!=0){
                $DOTRNDFORID="DOTRNDFORID=$roundID and ";
            }
            $FMYCMPFORID="";
            if($campID!=0){
                $FMYCMPFORID="FMYCMPFORID=$campID and ";
            }
            echo json_encode($database->return_data("
                SELECT
                    family.*,
                    CMPName
                FROM
                    family,
                    camp
                WHERE
                    FMYDeleted=0 AND
                    $FMYCMPFORID
                    CMPID=FMYCMPFORID AND
                    FMYActive=0 AND
                    CMPDeleted=0 AND
                    FMYID not in (
                        select 
                            DOTFMYFORID 
                        from 
                            donation
                        WHERE
                            $DOTRNDFORID
                            DOTType=1 and
                            DOTDeleted=0
                    )
            ","key_all"));
        }
        if ($_POST["type"] == "returnRoundDetail"){
            echo json_encode($database->return_data("
                SELECT
                    FMYCaseNumber,
                    (
                        SELECT
                            CMPName
                        FROM
                            camp
                        WHERE
                            CMPDeleted=0 AND
                            CMPID=FMYCMPFORID
                    ) as campName,
                    (
                        SELECT
                            RNDNumber
                        FROM
                            round
                        WHERE
                            RNDID=DOTRNDFORID AND
                            RNDDeleted=0
                    ) as roundNumber,
                    FMYFamilyName,
                    FMYBarcode,
                    DDTQty
                FROM
                    family,
                    donation,
                    donation_detail
                WHERE
                    FMYID=DOTFMYFORID AND
                    DOTID=DDTDOTFORID AND
                    DOTType=1 and
                    FMYDeleted=0 AND
                    FMYActive=0 and
                    DDTPDUFORID=1 AND #kerosene id
                    FMYFamilyCaseID='".$_POST["caseID"]."'
            ","key_all"));
        }
        if ($_POST["type"] == "returnRoundTotal"){
            extract($_POST);
            $round_detail = $database->return_data2(array(
                "tablesName"=>array("round"),
                "columnsName"=>array("RNDNumber","RNDStartDate","RNDEndDate"),
                "conditions"=>array(
                    array("columnName"=>"RNDID","operation"=>"=","value"=>$roundID,"link"=>"and"),
                    array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
			));
            $family_total=$database->return_data("
                SELECT
                    FMYCMPFORID as CMPID,
                    count(*) as total_family
                FROM
                    family
                WHERE
                    FMYDeleted=0  and
                    FMYCMPFORID in ($campID) and
                    substring_index(substring_index(FMYRegisterDate,' ',1),' ',-1) <= '".$round_detail["RNDEndDate"]."'
                group BY
                    FMYCMPFORID
            ","key_all");
			$detail=$database->return_data("
                SELECT
                    CMPID,
                    CMPName,
                    CMPNote,
                    count(DOTID) total_give,
                    sum(DDTQty) total_liter
                FROM
                    donation,
                    donation_detail,
                    family,
                    camp
                WHERE
                    DOTFMYFORID=FMYID AND
                    DDTDOTFORID=DOTID AND
                    FMYCMPFORID=CMPID and
                    DDTDeleted=0 AND
                    DOTDeleted=0 AND
                    FMYDeleted=0 AND
                    #FMYActive=0 AND
                    DOTType=1 and
                    CMPDeleted=0 AND
                    DDTPDUFORID=1 AND
                    DOTRNDFORID=$roundID AND
                    CMPID in ($campID) and
                    substring_index(substring_index(FMYRegisterDate,' ',1),' ',-1) <= '".$round_detail["RNDEndDate"]."'
                group by 
                    CMPID
            ","key_all");
            for ($i=0,$iL=count($detail); $i < $iL; $i++) { 
                $detail[$i]["CMPNote"]=html_entity_decode($detail[$i]["CMPNote"]);
                for ($j=0,$jL=count($family_total); $j < $jL; $j++) { 
                    if($detail[$i]["CMPID"]==$family_total[$j]["CMPID"]){
                        $detail[$i]["total_family"]=$family_total[$j]["total_family"];
                        break;
                    }
                }
            }
            $round_detail["detail"]=json_encode($detail);
            echo json_encode($round_detail);
        }
    }else{
        header("Location:../");
        exit;
    }
?>