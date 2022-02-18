<?php
    require_once "header.php";

    ini_set('max_execution_time', 1200);
    ini_set("pcre.backtrack_limit", "1000000000");

    $style=file_get_contents('../_general/style/pdf/report/report_all_round.css');
    $mpdf =new \Mpdf\Mpdf([
        "tempDir" => __DIR__ . "/tmp",
        'mode' => 'utf-8', 
        'format' => 'A4', 
        'default_font_size' => '0', 
        'default_font' => 'Arial',
        'margin_left' => '4',
        'margin_right' => '4',
        'margin_top' => '50', 
        'margin_bottom' => '12', 
        'margin_header' => '0', 
        'margin_footer' => '10', 
        'orientation' => 'P'
    ]); 
    $mpdf->setHTMLHeader("
        <div>
            <div style='text-align:center;padding-top: 10px;'>
                <img src='../_general/image/pdf/logo.jpg' width='300px' heigth='180px' alt=''>
            </div>
            <h2 style='text-align:center;padding-bottom:0px;margin-bottom:0px;'>
                Blumont Kerosene Distribution Family Detail 2019 – 2020
            </h2>           
        </div>
        <hr>
    ");	
    $mpdf->setFooter('|{PAGENO} of {nb}|');
    $mpdf->WriteHTML($style,1);	
    extract($_GET);
   
    $family=$database->return_data("
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
            DDTPDUFORID=1 AND #kerosene id
            FMYFamilyCaseID='".$caseID."'
    ","key_all");

    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th>V. No</th>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Barcode</th>
                    <th>Camp</th>
                    <th>RN</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
    ';
    for($i=0;$i<count($family);$i++){
        $table.='
            <tr>
                <td >'.($i+1).'</td>
                <td >'.$family[$i]["FMYFamilyName"].'</td>
                <td >'.$family[$i]["FMYCaseNumber"].'</td>
                <td >'.$family[$i]["FMYBarcode"].'</td>
                <td >'.$family[$i]["campName"].'</td>
                <td >'.$family[$i]["roundNumber"].'</td>
                <td >'.$family[$i]["DDTQty"].'</td>
            </tr>
        ';
    }
    $table.='
        </tbody>
        </table>
    ';
    $mpdf->WriteHTML('
       '.$table.'
    ',2);	

    $mpdf->Output();
?>