<?php
    require_once "header.php";

    ini_set('max_execution_time', 1200);
    ini_set("pcre.backtrack_limit", "1000000000");
    $style=file_get_contents('../_general/style/pdf/report/report_notreceived.css');
    $mpdf =new \Mpdf\Mpdf([
        "tempDir" => __DIR__ . "/tmp",
        'mode' => 'utf-8', 
        'format' => 'A4', 
        'default_font_size' => '0', 
        'default_font' => 'Arial',
        'margin_left' => '4',
        'margin_right' => '4',
        'margin_top' => '50', 
        'margin_bottom' => '10', 
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
                Blumont kerosene distribution 2019-2020 non-receiver list
            </h2>           
        </div>
        <hr>
    ");	
    $mpdf->setFooter('|{PAGENO} of {nb}|');
    $mpdf->WriteHTML($style,1);	
    extract($_GET);
    $DOTRNDFORID="";
    if($roundID!=0){
        $DOTRNDFORID="DOTRNDFORID=$roundID and ";
    }
    $FMYRegisterDate="";
    $FMYCMPFORID="";
    if($campID!=0){
        $FMYCMPFORID="FMYCMPFORID=$campID and ";
    }
   
    $family=$database->return_data("
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
            FMYCaseNumber between ".$_GET["startNumber"]." and ".$_GET["endNumber"]." and
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
    ","key_all");

    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th width="7%">#</th>
                    <th width="8%">V.No</th>
                    <th width="20%">Camp</th>
                    <th width="20%">Name</th>
                    <th width="17%">Case ID</th>
                    <th width="10%">RN</th>
                    <th width="16%">Barcode</th>
                </tr>
            </thead>
            <tbody>
    ';
    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    for($i=0;$i<count($family);$i++){
        $table.='
            <tr>
                <td >'.($i+1).'</td>
                <td >'.$family[$i]["FMYCaseNumber"].'</td>
                <td >'.$family[$i]["CMPName"].'</td>
                <td >'.$family[$i]["FMYFamilyName"].'</td>
                <td >'.$family[$i]["FMYFamilyCaseID"].'</td>
                <td >'.$roundText.'</td>
                <td >
                    <div style="width:100px;">'.$family[$i]["FMYBarcode"].'</div>
                </td>
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
