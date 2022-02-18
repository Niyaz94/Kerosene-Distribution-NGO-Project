<?php
    require_once "header.php";
    ini_set('max_execution_time', 1200);
    ini_set("pcre.backtrack_limit", "1000000000");

    $style=file_get_contents('../_general/style/pdf/report/report_family_detail.css');
    $mpdf =new \Mpdf\Mpdf([
        "tempDir" => __DIR__ . "/tmp",
        'mode' => 'utf-8', 
        'format' => 'A4', 
        'default_font_size' => '0', 
        'default_font' => 'Arial',
        'margin_left' => '1',
        'margin_right' => '1',
        'margin_top' => '40', 
        'margin_bottom' => '25', 
        'margin_header' => '0', 
        'margin_footer' => '2', 
        'orientation' => 'P'
    ]); 
    $mpdf->setHTMLHeader("
        <div>
            <div style='text-align:center;padding-top: 10px;'>
                <img src='../_general/image/pdf/logo.jpg' width='200px' heigth='100px' alt=''>
            </div>
            <h3 style='text-align:center;padding-bottom:0px;margin-bottom:0px;'>
                Blumont Kerosene Distribution Program 2019 – 2020 Contractor List
            </h3>           
        </div>
        <hr>
    ");	
    $mpdf->WriteHTML($style,1);	
    extract($_GET);
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
    $family=$database->return_data("
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
            FMYCaseNumber between ".$_GET["startNumber"]." and ".$_GET["endNumber"]." and
            FMYDeleted=0
    ","key_all");

    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th width="10%">V. No</th>
                    <th width="15%">Camp</th>
                    <th width="15%">Name</th>
                    <th width="15%">Case ID</th>
                    <th width="10%">Date</th>
                    <th width="10%">#ltr</th>
                    <th width="10%">RN</th>
                    <th width="15%">Finger Print</th>
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
                <td ></td>
                <td ></td>
                <td ></td>
                <td ></td>
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
                <td width="20%" style="text-align:left;">Sign</td>
                <td width="20%" style="text-align:left;">Sign</td>
                <td width="30%" style="text-align:left;">Sign</td>
                <td width="30%" style="text-align:left;">Sign</td>
            </tr>
            <tr>
                <td width="20%" style="text-align:left;">Name</td>
                <td width="20%" style="text-align:left;">Name</td>
                <td width="30%" style="text-align:left;">Name</td>
                <td width="30%" style="text-align:left;">Name</td>
            </tr>
            <tr>
                <td width="20%" style="text-align:left;font-size:12px;">Position: Camp Manager</td>
                <td width="20%" style="text-align:left;font-size:12px;">Position: M&E Rep.</td>
                <td width="30%" style="text-align:left;font-size:12px;">Position: Finance Rep</td>
                <td width="30%" style="text-align:left;font-size:12px;">Position: Contractor Rep.</td>
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
                    <td width="33%" style="text-align:left">Sign</td>
                    <td width="33%" style="text-align:left">Sign</td>
                    <td width="34%" style="text-align:left">Sign</td>
                </tr>
                <tr>
                    <td width="33%" style="text-align:left">Name</td>
                    <td width="33%" style="text-align:left">Name</td>
                    <td width="34%" style="text-align:left">Name</td>
                </tr>
                <tr>
                    <td width="33%" style="text-align:left;">Position</td>
                    <td width="33%" style="text-align:left;">Position</td>
                    <td width="34%" style="text-align:left;">Position</td>
                </tr>
            </table>
        </htmlpagefooter>
        <sethtmlpagefooter name="LastPageFooter" value="1" />
    ',2);*/

    $mpdf->Output();
?>
