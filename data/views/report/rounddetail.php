<?php 
    $fileName=__FILE__;
    include_once "header.php";
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
                    echo input1("col-sm-12","text","Family Case ID","caseID","required","icon-tree6");
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
						<th>V. No</th>
						<th>Family Name</th>
						<th>Family Number</th>
						<th>Barcode</th>
						<th>Camp Name</th>
						<th>Round Number</th>
						<th>Kerosene Amount</th>
					</tr>
				</thead>
				<tbody id="reportBody">
					
				</tbody>
                <tfoot>
					<tr class="border-double bg-blue">
                        <th>V. No</th>
                        <th>Family Name</th>
						<th>Family Number</th>
						<th>Barcode</th>
						<th>Camp Name</th>
						<th>Round Number</th>
						<th>Kerosene Amount</th>
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
                    "type":"returnRoundDetail",
                    "caseID":$("#caseID").val()
                },
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    $("#reportBody").empty();

                    $("#reportBody")
                    for (let index = 0; index < res.length; index++) {
                        $("#reportBody").append(`
                            <tr>
                                <td>${index+1}</td>
                                <td>${res[index]["FMYFamilyName"]}</td>
                                <td>${res[index]["FMYCaseNumber"]}</td>
                                <td>${res[index]["FMYBarcode"]}</td>
                                <td>${res[index]["campName"]}</td>
                                <td>${res[index]["roundNumber"]}</td>
                                <td>${res[index]["DDTQty"]}</td>
                            </tr>
                        `);
                    }
                },
                fail: function (err){
                },
                always:function(){
                }
            });
        });
        $("#pdf_data").on("click",function(){
            window.open(`pdf/report_round_detail.php?caseID=${$("#caseID").val()}`);
        });
    });
</script>                      