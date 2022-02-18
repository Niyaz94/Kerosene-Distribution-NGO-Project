<?php
	class _reportClass{
        private $database;
		public function __construct($database){
            $this->database=$database;
        }
        function report1($data,$extra=""){
            extract($data);
            $DOTRNDFORID="";
            if($roundID!=0){
                $DOTRNDFORID="DOTRNDFORID=$roundID and ";
            }

            $PendingState="";
            if($dState==0){
                $PendingState="DOTType=0 and ";
            }else if($dState==1){
                $PendingState="DOTType=1 and ";
            }


            $FMYRegisterDate="";
            if(/*$startDate!=$endDate &&*/ $startDate<=$endDate){
                $FMYRegisterDate="substring_index(substring_index(DOTRegisterDate,' ',1),' ',-1) between '".$startDate."' and '".$endDate."' and ";
            }
            $FMYCMPFORID="";
            if($campID!=0){
                $FMYCMPFORID="FMYCMPFORID=$campID and ";
            }
            return json_encode($this->database->return_data("
                SELECT
                    family.*,
                    substring_index(substring_index(DOTRegisterDate,' ',1),' ',-1) as donation_detail,
                    CMPName,
                    ifnull((
                        select
                            DDTQty
                        from
                            donation_detail
                        where
                            DDTDOTFORID=DOTID and
                            DDTDeleted=0
                        limit 1
                    ),0) as DDTQty
                FROM
                    family,
                    camp,
                    donation
                WHERE
                    $PendingState
                    $DOTRNDFORID
                    $FMYCMPFORID
                    $FMYRegisterDate
                    $extra
                    CMPID=FMYCMPFORID and
                    FMYID=DOTFMYFORID and
                    DOTDeleted=0 and
                    FMYDeleted=0 
                    #and
                    #FMYActive=0
            ","key_all"));
        }
        function report2($data){
            extract($data);
            $all_data=$this->database->return_data("
                SELECT
                    PDUManufacture,
                    PDUBarcode,
                    PDUItemName,
                    PDUUnitName,
                    sum(RCDQty) as qty,
                    ifnull(GROUP_CONCAT(RCDNoteDetail SEPARATOR ' '),'') as note,
                    0 as total_cars
                FROM
                    receive,
                    receive_detail,
                    product
                WHERE
                    RIVDeleted=0 AND
                    RCDDeleted=0 AND
                    PDUDeleted=0 AND
                    PDUID=RCDPDUFORID AND
                    RCDRIVFORID=RIVID AND
                    RIVDate between '".$startDate."' and '".$endDate."' 
                group BY
                    PDUManufacture,
                    PDUID,
                    RCDNoteDetail
            ","key_all");
            $data_total=$this->database->return_data("
                SELECT
                    PDUManufacture,
                    COUNT(DISTINCT RIVID)  AS total
                FROM
                    receive,
                    receive_detail,
                    product
                WHERE
                    RCDDeleted = 0 AND 
                    RIVDeleted = 0 AND 
                    PDUDeleted = 0 AND
                    RCDRIVFORID = RIVID AND 
                    RCDPDUFORID = PDUID AND 
                    RIVType = 0 AND
                    RIVDate between '".$startDate."' and '".$endDate."' 
                GROUP BY
                    PDUManufacture
            ","key_all");
            for($i=0,$iL=count($data_total); $i < $iL; $i++) { 
                for ($j=0,$jL=count($all_data); $j < $jL; $j++) { 
                    if($all_data[$j]["PDUManufacture"]==$data_total[$i]["PDUManufacture"]){
                        $all_data[$j]["total_cars"]=$data_total[$i]["total"];
                        break;
                    }

                }
            }
            return json_encode($this->count_from_matrix($all_data,"PDUManufacture"));
        }
        function report3($data){
            extract($data);
            $all_data=$this->database->return_data("
                SELECT
                    PDUManufacture,
                    PDUClassification,
                    PDUItemCode,
                    PDUItemSize,
                    PDUItemColor,
                    sum(RCDQty) as qty,
                    0 as total_cars,
                    ifnull(GROUP_CONCAT(RCDNoteDetail SEPARATOR ' '),'') as note
                FROM
                    receive,
                    receive_detail,
                    product
                WHERE
                    RIVDeleted=0 AND
                    RCDDeleted=0 AND
                    PDUDeleted=0 AND
                    PDUID=RCDPDUFORID AND
                    RCDRIVFORID=RIVID AND
                    RIVDate between '".$startDate."' and '".$endDate."' 
                group BY
                    PDUManufacture,
                    PDUClassification,
                    PDUItemCode,
                    PDUItemSize,
                    PDUItemColor
                order by PDUManufacture
            ","key_all");
            $data_total=$this->database->return_data("
                SELECT
                    PDUManufacture,
                    COUNT(DISTINCT RIVID)  AS total
                FROM
                    receive,
                    receive_detail,
                    product
                WHERE
                    RCDDeleted = 0 AND 
                    RIVDeleted = 0 AND 
                    PDUDeleted = 0 AND
                    RCDRIVFORID = RIVID AND 
                    RCDPDUFORID = PDUID AND 
                    RIVType = 0 AND
                    RIVDate between '".$startDate."' and '".$endDate."' 
                GROUP BY
                    PDUManufacture
            ","key_all");
            for ($i=0,$iL=count($data_total); $i < $iL; $i++) { 
                for ($j=0,$jL=count($all_data); $j < $jL; $j++) { 
                    if($all_data[$j]["PDUManufacture"]==$data_total[$i]["PDUManufacture"]){
                        $all_data[$j]["total_cars"]=$data_total[$i]["total"];
                        break;
                    }

                }
            }
            return json_encode($this->count_from_matrix($all_data,"PDUManufacture"));
        }
        function report4($data){
            extract($data);
            $data=$this->database->return_data("
                SELECT
                    RIVCarNumber,
                    RIVDate,
                    RIVBorderDate,
                    0 as total_cars
                FROM
                    receive
                WHERE
                    RIVDeleted=0 AND
                    RIVType=0 AND
                    RIVDate between '".$startDate."' and '".$endDate."' AND
                    RIVDate is not NULL
                order by RIVDate
            ","key_all");
            $data_total=$this->database->return_data("
                SELECT
                    RIVDate,
                    count(*) as total
                FROM
                    receive
                WHERE
                    RIVDeleted=0 AND
                    RIVType=0 AND
                    RIVDate between '".$startDate."' and '".$endDate."' 
                group BY
                    RIVDate
            ","key_all");
            for ($i=0,$iL=count($data_total); $i < $iL; $i++) { 
                for ($j=0,$jL=count($data); $j < $jL; $j++) { 
                    if($data[$j]["RIVDate"]==$data_total[$i]["RIVDate"]){
                        $data[$j]["total_cars"]=$data_total[$i]["total"];
                        break;
                    }
                }
            }
            return json_encode($this->count_from_matrix($data,"RIVDate"));
        }
        function report5($data){
            extract($data);
            $all_data=$this->database->return_data("
                SELECT
                    RIVID,
                    RIVCarNumber,
                    PDUManufacture,
                    PDUClassification,
                    PDUItemCode,
                    PDUItemSize,
                    PDUItemColor,
                    (SELECT IVTName FROM inventory WHERE IVTID=RCDIVTFORID and IVTDeleted=0) as IVTName,
                    RCDQty as qty,
                    RCDNoteDetail as note
                FROM
                    receive,
                    receive_detail,
                    product
                WHERE
                    RIVDeleted=0 AND
                    RCDDeleted=0 AND
                    PDUDeleted=0 AND
                    PDUID=RCDPDUFORID AND
                    RCDRIVFORID=RIVID AND
                    RIVDate between '".$startDate."' and '".$endDate."' 
                order by 
                    RIVID,PDUManufacture
            ","key_all");
            $check_repeat=array();
            for ($i=0,$iL=count($all_data); $i < $iL; $i++) { 
                if(in_array($all_data[$i]["RIVID"],$check_repeat)){
                    $all_data[$i]["total_carNumber"]=0;
                }else{
                    $count=0;
                    for ($j=0,$jL=count($all_data); $j < $jL; $j++) { 
                        if($all_data[$i]["RIVID"]==$all_data[$j]["RIVID"]){
                            ++$count;
                        }
                    }
                    $all_data[$i]["total_carNumber"]=$count;
                    array_push($check_repeat,$all_data[$i]["RIVID"]);
                } 
            }
            return json_encode($this->count_from_matrix($all_data,"PDUManufacture"));
           
        }
        function report6($data){
            extract($data);   
            $invoice_number=$this->database->return_data("
                SELECT
                    SLLID
                FROM
                    sell
                WHERE
                    SLLDeleted=0 AND
                    SLLYear=".$selected_year." and 
                    SLLINVNumber=".(is_numeric($invoice_id)?$invoice_id:0)." 
            ","key")["SLLID"];
            $sell_detail=$this->database->return_data("
                SELECT
                    PDUItemCode,
                    PDUItemName,
                    SLDID,
                    SLDQty,
                    SLDGive,
                    SLDNotGive,
                    SLDDateDetail
                FROM
                    product,
                    sell_detail
                WHERE
                    PDUItemID=SLDPDUFORID and
                    SLDSLLFORID=".(is_numeric($invoice_number)?$invoice_number:0)." and
                    SLDDeleted=0 and
                    PDUDeleted=0
                    
            ","key_all");
            $admin=$this->database->return_data("
                SELECT
                    ADMID,
                    ADMUsername
                FROM
                    admin
                WHERE
                    ADMDeleted=0
            ","key_all");
            $inventory=$this->database->return_data("
                SELECT
                    IVTID,
                    IVTName
                FROM
                    inventory
                WHERE
                    IVTDeleted=0
            ","key_all");
            $all_data=array();
            for ($i=0,$iL=count($sell_detail); $i < $iL; $i++) { 
               if($sell_detail[$i]["SLDGive"]>0){
                   $all_data=array_merge($all_data,$this->rows_for_report6($sell_detail[$i],$admin,$inventory));
               }
            }
            usort($all_data,function ($e1, $e2) { 
                return strtotime($e1['date']) - strtotime($e2['date']); 
            });
            return json_encode($all_data);
        }
        function report7(){
            include_once "../_general/backend/database_sqlsrv.php";
            $db_sqlsrv=new class_database_sqlsrv();
            $product_server=$db_sqlsrv->return_data("
                select  
                    itm.fld_item_id as Item_ID,
                    itm.fld_item_code as Barcode,
                    sett.fld_manufacturer as Manufacture,
                    sett.fld_classification as Classification,
                    sett.fld_code as Item_Code,
                    sett.fld_size as Item_Size,
                    sett.fld_color as Item_Color,
                    itm.fld_item_name as Item_Name,
                    units.fld_unit_scale as Unit_Scale,
                    unit2.fld_unit_name as Unit_Name
                from 
                    tbl_store_items itm
                LEFT JOIN 
                    tbl_store_item_all_units units ON itm.fld_item_id = units.fld_item_id_ref
                left join 
                    tbl_store_units unit2 on units.fld_unit_id_ref = unit2.fld_unit_id
                left join 
                    tbl_store_item_setting sett on fld_item_id=sett.fld_item_id_ref
            ","key_all");
            for ($i=0,$il=count($product_server); $i < $il; $i++) { 
                if($product_server[$i]["Unit_Name"]=="كارتون" || $product_server[$i]["Unit_Name"]=="باكت"){
                    for ($j=0; $j < $il; $j++) { 
                        if($i!=$j && $product_server[$i]["Item_ID"]==$product_server[$j]["Item_ID"]){
                            array_splice($product_server,$j,1);
                            $il=$il-1;
                            if($j<$i){
                                --$i;
                            }
                            break;
                        }
                    }
                }
            }

            $product_detail=$this->database->return_data("
                SELECT
                    *
                FROM
                    product
                WHERE
                    PDUDeleted=0
            ","key_all");
            $difference=array();
            for ($i=0,$iL=count($product_detail); $i < $iL; $i++) { 
                for ($j=0,$jL=count($product_server); $j <$jL ; $j++) {
                    if($product_server[$j]["Item_ID"]==$product_detail[$i]["PDUItemID"]){
                        $add=array();
                        if($product_detail[$i]["PDUBarcode"]!=$product_server[$j]["Barcode"]){
                            array_push($add,array("name"=>"barcode","old"=>$product_detail[$i]["PDUBarcode"],"new"=>$product_server[$j]["Barcode"]));
                        } 
                        if($product_detail[$i]["PDUManufacture"]!=$product_server[$j]["Manufacture"]){
                            array_push($add,array("name"=>"manufacture","old"=>$product_detail[$i]["PDUManufacture"],"new"=>$product_server[$j]["Manufacture"]));
                        }
                        if($product_detail[$i]["PDUClassification"]!=$product_server[$j]["Classification"]){
                            array_push($add,array("name"=>"classification","old"=>$product_detail[$i]["PDUClassification"],"new"=>$product_server[$j]["Classification"]));
                        } 
                        if($product_detail[$i]["PDUItemCode"]!=$product_server[$j]["Item_Code"]){
                            array_push($add,array("name"=>"code","old"=>$product_detail[$i]["PDUItemCode"],"new"=>$product_server[$j]["Item_Code"]));
                        } 
                        if($product_detail[$i]["PDUItemSize"]!=$product_server[$j]["Item_Size"]){
                            array_push($add,array("name"=>"size","old"=>$product_detail[$i]["PDUItemSize"],"new"=>$product_server[$j]["Item_Size"]));
                        } 
                        if($product_detail[$i]["PDUItemColor"]!=$product_server[$j]["Item_Color"]){
                            array_push($add,array("name"=>"color","old"=>$product_detail[$i]["PDUItemColor"],"new"=>$product_server[$j]["Item_Color"]));
                        }
                        if($product_detail[$i]["PDUItemName"]!=$product_server[$j]["Item_Name"]){
                            array_push($add,array("name"=>"name","old"=>$product_detail[$i]["PDUItemName"],"new"=>$product_server[$j]["Item_Name"]));
                        } 
                        if($product_detail[$i]["PDUUnitScale"]!=$product_server[$j]["Unit_Scale"]){
                            array_push($add,array("name"=>"scale","old"=>$product_detail[$i]["PDUUnitScale"],"new"=>$product_server[$j]["Unit_Scale"]));
                        } 
                        if($product_detail[$i]["PDUUnitName"]!=$product_server[$j]["Unit_Name"]){
                            array_push($add,array("name"=>"unit","old"=>$product_detail[$i]["PDUUnitName"],"new"=>$product_server[$j]["Unit_Name"]));
                        } 
                        if(count($add)>0){
                            array_push($add,array("itemName"=>$product_detail[$i]["PDUItemName"]));
                            $difference[$product_detail[$i]["PDUItemID"]]=$add;
                            break;
                        }
                    } 
                }
            }

            return json_encode($difference);

        }
        function report8($data){
            extract($data);
            $all_data=$this->database->return_data("
                SELECT
                    PDUManufacture,
                    PDUBarcode,
                    PDUItemName,
                    PDUUnitName,
                    sum(RCDQty) as qty,
                    ifnull(GROUP_CONCAT(RCDNoteDetail SEPARATOR ' '),'') as note,
                    0 as total_cars,
                    (
                        SELECT
                            IVTName
                        FROM
                            inventory
                        WHERE
                            IVTID=RCDIVTFORID AND
                            IVTDeleted=0
                    ) as inventory_name
                FROM
                    receive,
                    receive_detail,
                    product
                WHERE
                    RIVDeleted=0 AND
                    RCDDeleted=0 AND
                    PDUDeleted=0 AND
                    PDUID=RCDPDUFORID AND
                    RCDRIVFORID=RIVID AND 
                    RIVDate between '".$startDate."' and '".$endDate."'
                group BY
                    PDUManufacture,
                    PDUID,
                    RCDIVTFORID

                    ORDER BY
                    inventory_name
            ","key_all");
            $data_total=$this->database->return_data("
                SELECT
                    PDUManufacture,
                    COUNT(DISTINCT RIVID)  AS total
                FROM
                    receive,
                    receive_detail,
                    product
                WHERE
                    RCDDeleted = 0 AND 
                    RIVDeleted = 0 AND 
                    PDUDeleted = 0 AND
                    RCDRIVFORID = RIVID AND 
                    RCDPDUFORID = PDUID AND 
                    RIVType = 0 AND
                    RIVDate between '".$startDate."' and '".$endDate."' 
                GROUP BY
                    PDUManufacture

                    

            ","key_all");
            for($i=0,$iL=count($data_total); $i < $iL; $i++) { 
                for ($j=0,$jL=count($all_data); $j < $jL; $j++) { 
                    if($all_data[$j]["PDUManufacture"]==$data_total[$i]["PDUManufacture"]){
                        $all_data[$j]["total_cars"]=$data_total[$i]["total"];
                        break;
                    }

                }
            }
            return json_encode($this->count_from_matrix($all_data,"PDUManufacture"));
        }
        function report9($data){
            extract($data);

            $conditions="";
            $extra="";
            if($inv_name!=0){
                $conditions=" RCDIVTFORID in ($inv_name) AND ";
                $extra="(
                    SELECT
                        IVTName
                    FROM
                        inventory
                    WHERE
                        IVTDeleted=0 AND
                        IVTID=RCDIVTFORID
                ) as inv_name,";
            }else{
                $extra="'All' as inv_name,";
            }
            
            $all_data=$this->database->return_data("
                SELECT
                    product.*,
                    $extra
                    sum(RCDQtRemain) as qty
                FROM
                    product,
                    receive_detail,
                    receive
                WHERE
                    $conditions
                    PDUDeleted=0 AND
                    RCDDeleted=0 AND
                    RIVDeleted=0 AND
                    ".($name!="All"?" PDUItemName='$name' AND ":"")."
                    ".($barcode!="All"?" PDUBarcode='$barcode' AND ":"")."
                    ".($color!="All"?" PDUItemColor='$color' AND ":"")."
                    ".($scale!="All"?" PDUUnitScale='$scale' AND ":"")."
                    ".($unit!="All"?" PDUUnitName='$unit' AND ":"")."
                    ".($manufacture!="All"?" PDUManufacture='$manufacture' AND ":"")."
                    ".($classification!="All"?" PDUClassification='$classification' AND ":"")."
                    ".($code!="All"?" PDUItemCode='$code' AND ":"")."
                    ".($size!="All"?" PDUItemSize='$size' AND ":"")."
                    PDUID=RCDPDUFORID AND
                    RIVID=RCDRIVFORID AND
                    substring_index(substring_index(RCDRegisterDate,' ',1),' ',-1) between '".$startDate."' and '".$endDate."' 

                group by
                    PDUItemID
            ","key_all");
           
            return json_encode($all_data);
        }
        function report100($data){
            extract($_POST);
            return json_encode($this->database->return_data("
                SELECT
                    admin.*
                FROM
                    admin
                WHERE                
                    ADMProfileType=$typeUser and
                    ADMDeleted=$stateUser
            ","key_all"));
        }
        function count_from_matrix($data,$key){
            $repeat=array();
            for ($i=0,$iL=count($data); $i < $iL; $i++) { 
                if (in_array($data[$i][$key],$repeat)) {
                    $data[$i]["column_length"]=0;
                    continue;
                }
                $counter=0;
                for ($j=$i+1,$jL=count($data); $j < $jL; $j++) {
                     if($data[$i][$key]==$data[$j][$key]){
                        ++$counter;
                     }
                }
                $data[$i]["column_length"]=++$counter;
                array_push($repeat,$data[$i][$key]);
            }
            return $data;
        }
        function rows_for_report6($sell_detail,$admin,$inventory){
            $rows=array();

            if($sell_detail["SLDNotGive"]>0){
                $not_given=$this->database->return_data("
                    SELECT
                        LOGCreateBY,SLDCreateAt
                    FROM
                        system_log,
                        system_log_detail
                    WHERE
                        SLDForID=LOGID AND
                        LogColumnName='SLDNotGive' AND
                        LOGTable='sell_detail' AND
                        LOGRowID=".$sell_detail["SLDID"]."
                    ORDER BY
                        SLDCreateAt DESC
                    limit 1
                ","key");
               
            }
            $SLDDateDetail=json_decode($sell_detail["SLDDateDetail"],true);
            $qty=$sell_detail["SLDQty"];
            $check_counter=0;
            for ($i=0,$iL=count($SLDDateDetail); $i < $iL; $i++) { 
                if($sell_detail["SLDNotGive"]>0 && $not_given["LOGCreateBY"]>$SLDDateDetail[$i]["date"]){
                    ++$check_counter;
                    $rows[count($SLDDateDetail)]["user"]=$this->find_username($admin,$not_given["LOGCreateBY"]);
                    $rows[count($SLDDateDetail)]["store"]="";
                    $rows[count($SLDDateDetail)]["item_code"]=$sell_detail["PDUItemCode"];
                    $rows[count($SLDDateDetail)]["item_name"]=$sell_detail["PDUItemName"];
                    $rows[count($SLDDateDetail)]["date"]=$not_given["SLDCreateAt"];
                    $rows[count($SLDDateDetail)]["cancel"]=$sell_detail["SLDNotGive"];
                    $rows[count($SLDDateDetail)]["qty"]=$qty;
                    $rows[count($SLDDateDetail)]["out"]=0;
                    $qty=$qty-$sell_detail["SLDNotGive"];
                    $rows[count($SLDDateDetail)]["remain"]=$qty;
                }
                $rows[$i]["user"]=$this->find_username($admin,$SLDDateDetail[$i]["ADMID"]);
                $rows[$i]["store"]=$this->find_store($inventory,$SLDDateDetail[$i]["INVID"]);
                $rows[$i]["item_code"]=$sell_detail["PDUItemCode"];
                $rows[$i]["item_name"]=$sell_detail["PDUItemName"];
                $rows[$i]["date"]=$SLDDateDetail[$i]["date"];
                $rows[$i]["cancel"]=0;
                $rows[$i]["qty"]=$qty;
                $rows[$i]["out"]=$SLDDateDetail[$i]["total"];
                $qty=$qty-$SLDDateDetail[$i]["total"];
                $rows[$i]["remain"]=$qty;
            }
            if($sell_detail["SLDNotGive"]>0 && $check_counter==0){
                $rows[count($SLDDateDetail)]["user"]=$this->find_username($admin,$not_given["LOGCreateBY"]);
                $rows[count($SLDDateDetail)]["store"]="";
                $rows[count($SLDDateDetail)]["item_code"]=$sell_detail["PDUItemCode"];
                $rows[count($SLDDateDetail)]["item_name"]=$sell_detail["PDUItemName"];
                $rows[count($SLDDateDetail)]["date"]=$not_given["SLDCreateAt"];
                $rows[count($SLDDateDetail)]["cancel"]=$sell_detail["SLDNotGive"];
                $rows[count($SLDDateDetail)]["qty"]=$qty;
                $rows[count($SLDDateDetail)]["out"]=0;
                $qty=$qty-$sell_detail["SLDNotGive"];
                $rows[count($SLDDateDetail)]["remain"]=$qty;
            }
            return $rows;
        }
        function find_username($user,$id){
            for ($i=0,$iL=count($user); $i < $iL; $i++) { 
                if($user[$i]["ADMID"]==$id){
                    return $user[$i]["ADMUsername"];
                }
            }
            return "User Not Found";
        }
        function find_store($inventory,$id){
            for ($i=0,$iL=count($inventory); $i < $iL; $i++) { 
                if($inventory[$i]["IVTID"]==$id){
                    return $inventory[$i]["IVTName"];
                }
            }
            return "Store Not Found";
        }
		function __destruct() {
			$this->fileContaent=null;
		}
	}
?>