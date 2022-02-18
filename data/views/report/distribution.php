<?php 
    $fileName=__FILE__;
    include_once "header.php";
    $res = $database->return_data2(array(
        "tablesName"=>array("camp"),
        "columnsName"=>array("CMPID","CMPName"),
        "conditions"=>array(
            array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>"")
        ),
        "others"=>"",
        "returnType"=>"key_all"
    ));
    $campIDs=array(0=>"All Camps");
    for($i=0;$i<count($res);++$i){
        $campIDs[$res[$i]["CMPID"]]=$res[$i]["CMPName"];
    }
    $res = $database->return_data2(array(
        "tablesName"=>array("round"),
        "columnsName"=>array("RNDID","RNDNumber"),
        "conditions"=>array(
            array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>"")
        ),
        "others"=>"",
        "returnType"=>"key_all"
    ));
    $roundIDs=array(0=>"Please Select Round");
    for($i=0;$i<count($res);++$i){
        $roundIDs[$res[$i]["RNDID"]]=$res[$i]["RNDNumber"];
    }
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
                    echo input2("col-sm-4",$campIDs,"Camp Name","campID","","icon-home9",0,"select2Class");
                    echo input2("col-sm-4",$roundIDs,"Camp Name","roundID","","icon-history",0,"select2Class");
                    echo input2("col-sm-4",array("0"=>"Pending","1"=>"Receive","2"=>"Both"),"Distribution State","dState","","icon-history",0,"select2Class");
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-6","text","Start Date","startDate","required"," icon-calendar","","","dateStyle");
                    echo input1("col-sm-6","text","Start End","endDate","required"," icon-calendar","","","dateStyle");
                ?></div>
                <div class="form-group"><?php
                    echo input1("col-sm-6","number","Family Number","startNumber","required","icon-tree6","0","","","min=0");
                    echo input1("col-sm-6","number","Family Number","endNumber","required",  "icon-tree6","0","","","min=0");
                ?></div>
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
						<th width="5%">#</th>
						<th width="7%">V.No</th>
						<th width="18%">Camp</th>
						<th width="20%">Name</th>
						<th width="10%">Case ID</th>
                        <th width="10%">Date</th>
                        <th width="5%">#Ltr</th>
                        <th width="5%">RN</th>
                        <th width="20%">Barcode</th>
					</tr>
				</thead>
				<tbody id="reportBody">
					
				</tbody>
                <tfoot>
					<tr class="border-double bg-blue">
                        <th colspan=6>Total Litter</th>
                        <th colspan=3 id="total_litter"></th>
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
            if($("#roundID").val()>0){
                $.ajax({
                    url: "models/_report.php",
                    type: "POST",
                    dataType:"json",
                    data: {
                        "type":"returnDataForDistribution",
                        "campID":$("#campID").val(),
                        "roundID":$("#roundID").val(),
                        "startDate":$("#startDate").val(),
                        "endDate":$("#endDate").val(),
                        "dState":$("#dState").val()
                    },
                    complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        $("#reportBody").empty();
                        var total_litter=0;
                        for (let index = 0; index < res.length; index++) {
                            $("#reportBody").append(`
                                <tr>
                                    <td>${index+1}</td>
                                    <td>${res[index]["FMYCaseNumber"]}</td>
                                    <td>${res[index]["CMPName"]}</td>
                                    <td>${res[index]["FMYFamilyName"]}</td>
                                    <td>${res[index]["FMYFamilyCaseID"]}</td>
                                    <td>${res[index]["donation_detail"]}</td>
                                    <td>${res[index]["DDTQty"]}</td>
                                    <td>${$("#roundID option:selected").text()}</td>
                                    <td>
                                        ${res[index]["FMYBarcode"]}
                                                                       
                                    </td>
                                </tr>
                            `);
                            total_litter+=Number(res[index]["DDTQty"]);
                            //readBarcode(res[index]["FMYID"],);
                        }
                        $("#total_litter").text(total_litter);
                    },
                    fail: function (err){
                    },
                    always:function(){
                    }
                });
            }else{
                oneAlert("error","Error!!!","Please Select Round")
            }
        });
        $("#pdf_data").on("click",function(){
            if($("#roundID").val()>0){
                window.open(`pdf/report_all_round.php?campID=${$("#campID").val()}&startDate=${$("#startDate").val()}&endDate=${$("#endDate").val()}&roundID=${$("#roundID").val()}&roundText=${$("#roundID option:selected").text()}&startNumber=${$("#startNumber").val()}&endNumber=${$("#endNumber").val()}&dState=${$("#dState").val()}`);
            }else{
                oneAlert("error","Error!!!","Please Select Round")
            }
        });
    });
</script>