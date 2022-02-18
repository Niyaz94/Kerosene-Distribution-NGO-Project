<?php
    require_once "header.php";

    ini_set('max_execution_time', 1200);
    ini_set("pcre.backtrack_limit", "1000000000");

    $style=file_get_contents('../_general/style/pdf/report/report_family_per_round.css');
    $mpdf =new \Mpdf\Mpdf([
        "tempDir" => __DIR__ . "/tmp",
        'mode' => 'utf-8', 
        'format' => 'A4', 
        'default_font_size' => '0', 
        'default_font' => 'Arial',
        'margin_left' => '4',
        'margin_right' => '4',
        'margin_top' => '40', 
        'margin_bottom' => '25', 
        'margin_header' => '0', 
        'margin_footer' => '5', 
        'orientation' => 'P'
    ]); 
    $mpdf->setHTMLHeader("
        <div>
            <div style='text-align:center;padding-top: 10px;'>
                <img src='../_general/image/pdf/logo.jpg' width='200px' heigth='100px' alt=''>
            </div>
            <h3 style='text-align:center;padding-bottom:0px;margin-bottom:0px;'>
                Blumont Kerosene Voucher Distribution  2019 – 2020
            </h3>           
        </div>
        <hr>
    ");	
    $mpdf->WriteHTML($style,1);	
    extract($_GET);
    $FMYRegisterDate="";
    if(/*$startDate!=$endDate &&*/ $startDate<=$endDate){
        $FMYRegisterDate="substring_index(substring_index(FMYRegisterDate,' ',1),' ',-1) between '".$startDate."' and '".$endDate."' and ";
    }
    $FMYCMPFORID="";
    if($campID!=0){
        $FMYCMPFORID="FMYCMPFORID=$campID and ";
    }
    $family=$database->return_data("
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
            FMYCaseNumber between ".$_GET["startNumber"]." and ".$_GET["endNumber"]." and
            FMYDeleted=0 and
            FMYActive=0
    ","key_all");

    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th width="10%">V. No</th>
                    <th width="20%">Camp Name</th>
                    <th width="30%">Family Name</th>
                    <th width="20%">Case ID</th>
                    <th width="20%">Date</th>
                    <th width="20%">Signature</th>
                </tr>
            </thead>
            <tbody>
    ';

    for($i=0;$i<count($family);$i++){
        $table.='
            <tr>
                <td >'.$family[$i]["FMYCaseNumber"].'</td>
                <td >'.$family[$i]["CMPName"].'</td>
                <td >'.$family[$i]["FMYFamilyName"].'</td>
                <td >'.$family[$i]["FMYFamilyCaseID"].'</td>
                <td >'.($family[$i]["give_donation"]==0?"":$family[$i]["give_donation"]).'</td>
                <td></td>
            </tr>
        ';
    }
    $table.='
        </tbody>
        </table>
    ';
    $mpdf->setHTMLFooter('
        <table id="bottomTable" style="">
            <tr>
                <td width="30%" style="text-align:left;">Sign</td>
                <td width="30%" style="text-align:left;">Sign</td>
                <td width="40%" style="text-align:left;">Sign</td>
            </tr>
            <tr>
                <td width="30%" style="text-align:left;">Name</td>
                <td width="30%" style="text-align:left;">Name</td>
                <td width="40%" style="text-align:left;">Name</td>
            </tr>
            <tr>
                <td width="30%" style="text-align:left;">Position</td>
                <td width="30%" style="text-align:left;">Position</td>
                <td width="40%" style="text-align:left;">Position</td>
            </tr>
        </table>
    ');
    $mpdf->WriteHTML('
       '.$table.'
    ',2);	
    
    /*$mpdf->AddPage();
    $mpdf->WriteHTML('
        <htmlpagefooter name="LastPageFooter">
            <table id="bottomTable" style="" name="LastPageFooter">
                <tr>
                    <td width="30%" style="text-align:left">Name</td>
                    <td width="30%" style="text-align:left;color:blue">Name</td>
                    <td width="40%" style="text-align:left;color:red">Name</td>
                </tr>
                <tr>
                    <td width="30%" style="text-align:left">Distribution Assist.</td>
                    <td width="30%" style="text-align:left;color:blue;">M&E Officer</td>
                    <td width="40%" style="text-align:left;color:red;">Contractor Representative</td>
                </tr>
            </table>
        </htmlpagefooter>
        <sethtmlpagefooter name="LastPageFooter" value="1" />
    ',2);*/	
    
    $mpdf->Output();
?>
