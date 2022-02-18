<?php
    require_once "header.php";
    error_reporting();
    $style=file_get_contents('../_general/style/pdf/familyBarcode.css');

    ini_set('max_execution_time', 1200);
    ini_set("pcre.backtrack_limit", "1000000000");

    $mpdf =new \Mpdf\Mpdf([
        "tempDir" => __DIR__ . "/tmp",
        'mode' => 'utf-8', 
        'format' => 'A4', 
        'default_font_size' => '0', 
        'default_font' => 'Arial',
        'margin_left' => '4',
        'margin_right' => '4',
        'margin_top' => '5', 
        'margin_bottom' => '2', 
        'margin_header' => '5', 
        'margin_footer' => '2', 
        'orientation' => 'P'
    ]); 
    $mpdf->setFooter('|{PAGENO} of {nb}|');
    $mpdf->shrink_tables_to_fit=1;

    $whereDate="";
    if($_GET["start"]==$_GET["end"] && date("Y-m-d")==$_GET["end"]){
        $whereDate.=" ";
    }else{
       $whereDate.='substring_index(substring_index(FMYRegisterDate," ",1)," ",-1) between "'.$_GET["start"].'" and "'.$_GET["end"].'" and '; 
    }

    if(empty($_GET["campid"])){
       $whereDate.=" "; 
    }else{
       $whereDate.=" FMYCMPFORID=".$_GET["campid"]." and ";
    }	
    $mpdf->WriteHTML($style,1);	

     $family = $database->return_data('
        select
            family.*,
            CMPName
        from
            family,
            camp   
        where
            FMYCMPFORID=CMPID and
            FMYDeleted=0 and
            FMYCaseNumber between '.$_GET["startNumber"].' and '.$_GET["endNumber"].' and
            '.$whereDate.'
            CMPDeleted=0 
    ','key_all');

    $table='';
    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

    for($i=0;$i<count($family);$i=$i+2){
       
        $secondPart="";
        if(isset($family[$i+1]["CMPName"])){
            $barcode2='<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($family[$i+1]["FMYBarcode"], $generator::TYPE_CODE_128,2,80)) . '">';
            $secondPart='
                <table id="meanTable">
                    <colgroup>
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <tr>
                        <td style="text-align:center;font-size:24px;" colspan=10>Kerosene Distribution Card</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;" colspan=3>Camp Location : </td>
                        <td style="text-align:left;padding-left:10px;color:red;font-size:18px;" colspan=7>'.$family[$i+1]["CMPName"].'</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;" colspan=3>Full Name : </td>
                        <td style="text-align:left;padding-left:10px;color:red;font-size:18px;" colspan=7>'.$family[$i+1]["FMYFamilyName"].'</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;" colspan=3>Case_ID : </td>
                        <td style="text-align:left;padding-left:10px;color:red;font-size:18px;" colspan=7>'.$family[$i+1]["FMYFamilyCaseID"].'</td>
                    </tr>
                    <tr>
                        <td style="text-align:right;" colspan=3>Family Number : </td>
                        <td style="text-align:left;padding-left:10px;color:red;font-size:18px;" colspan=7>'.$family[$i+1]["FMYFamilyNumber"].'</td>
                    </tr>
                    <tr>
                        <td colspan=2 style="vertical-align:bottom;text-align:center;">NO. '.$family[$i+1]["FMYCaseNumber"].'</td>
                        <td colspan=8 style="text-align:center;">
                            <div>'.$barcode2.'</div>
                            <div style="width:100px;">'.$family[$i+1]["FMYBarcode"].'</div>
                        </td> 
                    </tr>
                </table>
            ';
        }

        $barcode='<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($family[$i]["FMYBarcode"], $generator::TYPE_CODE_128,2,80)) . '">';
        $table.='
            <div style="overflow:hidden;">
                <div style="float:left;width:50%;height:100px;">
                    <table id="meanTable">
                        <colgroup>
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <tr>
                            <td style="text-align:center;font-size:24px;" colspan=10>Kerosene Distribution Card</td>
                        </tr>
                        <tr>
                            <td style="text-align:right;" colspan=3>Camp Location : </td>
                            <td style="text-align:left;padding-left:10px;color:red;font-size:18px;" colspan=7>'.$family[$i]["CMPName"].'</td>
                        </tr>
                        <tr>
                            <td style="text-align:right;" colspan=3>Full Name : </td>
                            <td style="text-align:left;padding-left:10px;color:red;font-size:18px;" colspan=7>'.$family[$i]["FMYFamilyName"].'</td>
                        </tr>
                        <tr>
                            <td style="text-align:right;" colspan=3>Case_ID : </td>
                            <td style="text-align:left;padding-left:10px;color:red;font-size:18px;" colspan=7>'.$family[$i]["FMYFamilyCaseID"].'</td>
                        </tr>
                        <tr>
                            <td style="text-align:right;" colspan=3>Family Number : </td>
                            <td style="text-align:left;padding-left:10px;color:red;font-size:18px;" colspan=7>'.$family[$i]["FMYFamilyNumber"].'</td>
                        </tr>
                        <tr>
                            <td colspan=2 style="vertical-align:bottom;text-align:center;">NO. '.$family[$i]["FMYCaseNumber"].'</td>
                            <td colspan=8 style="text-align:center;">
                                <div>'.$barcode.'</div>
                                <div style="width:100px;">'.$family[$i]["FMYBarcode"].'</div>
                            </td> 
                        </tr>
                    </table>
                </div>
                <div style="float:right;width:50%;height:100px;">
                    '.$secondPart.'
                </div>
            </div>
            <div style="padding-bottom:15px;"></div>
        ';
    }
    $mpdf->WriteHTML('
       '.$table.'
    ',2);	

    $mpdf->Output();
?>
