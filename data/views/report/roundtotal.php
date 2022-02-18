<?php 
    $fileName=__FILE__;
    include_once "header.php";
    $res = $database->return_data2(array(
        "tablesName"=>array("round"),
        "columnsName"=>array("RNDID","RNDNumber"),
        "conditions"=>array(
            array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>"")
        ),
        "others"=>"",
        "returnType"=>"key_all"
    ));
    for($i=0;$i<count($res);++$i){
        $roundIDs[$res[$i]["RNDID"]]=$res[$i]["RNDNumber"];
    }
    $res = $database->return_data2(array(
        "tablesName"=>array("camp"),
        "columnsName"=>array("CMPID","CMPName"),
        "conditions"=>array(
            array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>"")
        ),
        "others"=>"",
        "returnType"=>"key_all"
    ));
?>
<style>
    table td,table th{
        text-align:center;
    }
</style>
<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="heading-elements">
			<ul class="icons-list">
	    		<li><a data-action="collapse"></a></li>
	    		<li><a data-action="reload"></a></li>
	    	</ul>
		</div>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input2("col-sm-6",$roundIDs,"Round Number","roundID","","icon-history",0,"select2Class");
                    ?>
                    <div class="form-group has-feedback has-feedback-left form-group-material col-sm-6">
                        <label class="control-label multi_lang"></label>
                        <select class="form-control multi_lang select2Class" multiple="multiple" data-width="100%"  name="campID[]" id="campID" ><?php
                            for($i=0;$i<count($res);++$i){
                                echo '<option value="'.$res[$i]["CMPID"].'" selected="selected">'.$res[$i]["CMPName"].'</option>';
                            }
                        ?></select>
                        <div class="form-control-feedback">
                            <i class="icon-home9 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="text-right"><?php
                echo button2("return_data","button","Report","icon-book","btn btn-warning btn-xlg btn-labeled btn-labeled-right");
                echo button2("pdf_data","button","PDF File","icon-file-pdf","btn btn-success btn-xlg btn-labeled btn-labeled-right");
            ?></div>
        </form> 
    </div>
</div>
<div class="panel panel-flat">
	<div class="panel-heading">
		<div class="heading-elements">
			<ul class="icons-list">
	    		<li><a data-action="collapse"></a></li>
	    		<li><a data-action="reload"></a></li>
	    	</ul>
		</div>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-framed table-sm">
				<thead>
                    <tr class="border-double bg-blue">
                        <th>#</th>
						<th width="15%">Date</th>
						<th>Round Number</th>
						<th>Camp</th>
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
				<tbody id="reportBody">
					
				</tbody>
                <tfoot>
					<tr class="border-double bg-blue">
                        <th>#</th>
						<th width="15%">Date</th>
						<th width="10%">Round Number</th>
						<th>Camp</th>
						<th># of Liter Per HH</th>
						<th># of Entitled (Registered) HH</th>
                        <th># of Scanned Vouchers by System</th>
						<th># of Not Scanned Vouchers</th>
						<th># of Liters Distributed (Per Scanned vouchers)</th>
						<th># of Families Received Kerosene</th>
						<th># of Families Received Kerosene</th>
						<th># of Families not Scanned but Received Kerosene (Manual)</th>
						<th>Program</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<script>
    $(document).ready(function () {
        generalConfig();
        $("#return_data").on("click",function(){
            $.ajax({
                url: "models/_report.php",
                type: "POST",
                dataType:"json",
                data: {
                    "type":"returnRoundTotal",
                    "roundID":$("#roundID").val(),
                    "campID":$("#campID").val().join(","),
                },
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    detail=JSON.parse(res.detail);
                    $("#reportBody").empty();

                    for (let index = 0; index < detail.length; index++) {
                        if(index==0){
                            $("#reportBody").append(`
                            <tr>
                                <td>${index+1}</td>
                                <td rowspan="${detail.length}">${res.RNDStartDate +" - "+ res.RNDEndDate }</td>
                                <td rowspan="${detail.length}">${res.RNDNumber}</td>
                                <td>${detail[index].CMPName}</td>
                                <td>${Number(detail[index]["total_liter"])/Number(detail[index]["total_give"])}</td>
                                <td>${detail[index]["total_family"]}</td>
                                <td>${detail[index]["total_give"]}</td>
                                <td>${Number(detail[index]["total_family"])-Number(detail[index]["total_give"])}</td>
                                <td>${detail[index]["total_liter"]}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>${detail[index].CMPNote}</td>
                            </tr>
                        `);
                        }else{
                            $("#reportBody").append(`
                                <tr>
                                    <td>${index+1}</td>
                                    <td>${detail[index].CMPName}</td>
                                    <td>${Number(detail[index]["total_liter"])/Number(detail[index]["total_give"])}</td>
                                    <td>${detail[index]["total_family"]}</td>
                                    <td>${detail[index]["total_give"]}</td>
                                    <td>${Number(detail[index]["total_family"])-Number(detail[index]["total_give"])}</td>
                                    <td>${detail[index]["total_liter"]}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>${detail[index].CMPNote}</td>
                                </tr>
                            `);
                        }
                    }
                },
                fail: function (err){
                },
                always:function(){
                }
            });
        });
        $("#pdf_data").on("click",function(){
            window.open(`pdf/report_round_total.php?roundID=${$("#roundID").val()}&campID=${$("#campID").val().join(",")}`);
        });
    });
</script>                      