<?php
    require_once "header.php";

    ini_set('max_execution_time', 1200);
    ini_set("pcre.backtrack_limit", "1000000000");

    $style=file_get_contents('../_general/style/pdf/report/report_round_total.css');
    $mpdf =new \Mpdf\Mpdf([
        "tempDir" => __DIR__ . "/tmp",
        'mode' => 'utf-8', 
        'format' => 'A4', 
        'default_font_size' => '0', 
        'default_font' => 'Arial',
        'margin_left' => '4',
        'margin_right' => '4',
        'margin_top' => '50', 
        'margin_bottom' => '2', 
        'margin_header' => '0', 
        'margin_footer' => '0', 
        'orientation' => 'L'
    ]); 
    $mpdf->setHTMLHeader("
        <div>
            <div style='text-align:center;padding-top: 10px;'>
                <img src='../_general/image/pdf/logo.jpg' width='300px' heigth='180px' alt=''>
            </div>
            <h2 style='text-align:center;padding-bottom:0px;margin-bottom:0px;'>
                Kerosene Round Distribution Report
            </h2>           
        </div>
        <hr>
    ");	
    $mpdf->setFooter('|{PAGENO} of {nb}|');
    $mpdf->WriteHTML($style,1);	
    extract($_GET);
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
            FMYDeleted=0 and
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
            FMYActive=0 AND
            DOTType=1 and
            CMPDeleted=0 AND
            DDTPDUFORID=1 AND
            DOTRNDFORID=$roundID and
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
    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th>#</th>
                    <th width="15%">Date</th>
                    <th >Round Number</th>
                    <th width="10%">Camp</th>
                    <th># of Liter Per HH</th>
                    <th># of Entitled (Registered) HH</th>
                    <th># of Scanned Vouchers by System</th>
                    <th># of Not Scanned Vouchers</th>
                    <th># of Liters Distributed (Per Scanned vouchers)</th>
                    <th># of Families Received Kerosene</th>
                    <th># of Families Scanned but not Received Kerosene  (Manual)</th>
                    <th># of Families not Scanned but Received Kerosene (Manual)</th>
                    <th>Program</th>
                </tr>
            </thead>
            <tbody>
    ';
    for ($index = 0,$iL=count($detail); $index <$iL; $index++) {
        if($index==0){
            $table.='
                <tr>
                    <td>'.($index+1).'</td>
                    <td rowspan="'.count($detail).'" style="font-size:12px;">'.$round_detail["RNDStartDate"] .' - '. $round_detail["RNDEndDate"] .'</td>
                    <td rowspan="'.count($detail).'">'.$round_detail["RNDNumber"].'</td>
                    <td>'.$detail[$index]["CMPName"].'</td>
                    <td>'.$detail[$index]["total_liter"]/$detail[$index]["total_give"].'</td>
                    <td>'.$detail[$index]["total_family"].'</td>
                    <td>'.$detail[$index]["total_give"].'</td>
                    <td>'.($detail[$index]["total_family"]-$detail[$index]["total_give"]).'</td>
                    <td>'.$detail[$index]["total_liter"].'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>'.$detail[$index]["CMPNote"].'</td>
                </tr>
            ';
        }else{
            $table.=' 
                <tr>
                    <td>'.($index+1).'</td>
                    <td>'.$detail[$index]["CMPName"].'</td>
                    <td>'.$detail[$index]["total_liter"]/$detail[$index]["total_give"].'</td>
                    <td>'.$detail[$index]["total_family"].'</td>
                    <td>'.$detail[$index]["total_give"].'</td>
                    <td>'.($detail[$index]["total_family"]-$detail[$index]["total_give"]).'</td>
                    <td>'.$detail[$index]["total_liter"].'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>'.$detail[$index]["CMPNote"].'</td>
                </tr>
            ';
        }
    }
    $table.='
        </tbody>
        </table>
    ';
    $mpdf->WriteHTML('
       '.$table.'
    ',2);	
    /*$mpdf->setHTMLFooter('
        <table id="bottomTable" style="">
            <tr>
                <td width="50%" style="text-align:center;">Omar Amjad Khorsheed</td>
                <td width="50%" style="text-align:center;">Hazhar Jabbar</td>
            </tr>
            <tr>
                <td width="50%" style="text-align:center;">Program Coordinator</td>  
                <td width="50%" style="text-align:center;">Acting Program Manager</td>
            </tr>
            <tr>
                <td width="50%" style="text-align:center;">BPRM Program</td>
                <td width="50%" style="text-align:center;">SIS Program</td>
            </tr>
        </table>
    ');*/

    $mpdf->Output();
?>