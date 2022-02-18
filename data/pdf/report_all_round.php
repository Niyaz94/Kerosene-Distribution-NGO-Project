<?php
    require_once "header.php";

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
                Blumont Kerosene Distribution Program 2019 - 2020
            </h2>           
        </div>
        <hr>
    ");	
    $mpdf->setFooter('|{PAGENO} of {nb}|');
    $mpdf->WriteHTML($style,1);	

    $data=json_decode($_report->report1($_GET,"FMYCaseNumber between ".$_GET["startNumber"]." and ".$_GET["endNumber"]." and "),true);

    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th width="7%">#</th>
                    <th width="8%">V.No</th>
                    <th width="14%">Camp</th>
                    <th width="20%">Name</th>
                    <th width="12%">Case ID</th>
                    <th width="14%">Date</th>
                    <th width="7%">#Ltr</th>
                    <th width="6%">RN</th>
                    <th width="12%">Barcode</th>
                </tr>
            </thead>
            <tbody>
    ';
    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    $total_litter=0;
    for($i=0,$il=count($data);$i<$il;$i++){
        $table.='
            <tr>
                <td style="font-size:11px">'.($i+1).'</td>
                <td style="font-size:11px">'.$data[$i]["FMYCaseNumber"].'</td>
                <td style="font-size:11px">'.$data[$i]["CMPName"].'</td>
                <td style="font-size:11px">'.$data[$i]["FMYFamilyName"].'</td>
                <td style="font-size:11px">'.$data[$i]["FMYFamilyCaseID"].'</td>
                <td style="font-size:11px">'.$data[$i]["donation_detail"].'</td>
                <td style="font-size:11px">'.$data[$i]["DDTQty"].'</td>
                <td style="font-size:11px">'.$_GET["roundText"].'</td>
                <td style="font-size:11px">
                    <div style="width:100px;">'.$data[$i]["FMYBarcode"].'</div>
                </td>
            </tr>
        ';
        $total_litter+=$data[$i]["DDTQty"];
    }
    $table.='
        </tbody>
        <tfoot>
			<tr class="border-double bg-blue">
                <th colspan=6>Total Litter</th>
                <th colspan=3 >'.$total_litter.'</th>
			</tr>
		</tfoot>
        </table>
    ';
    $mpdf->WriteHTML('
       '.$table.'
    ',2);	

    $mpdf->Output();
?>
