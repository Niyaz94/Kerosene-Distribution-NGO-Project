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
                    echo input2("col-sm-6",$campIDs,"Camp Name","campID",""," icon-home9",0,"select2Class");
                    echo input2("col-sm-6",array(0=>"All Family",1=>"Active Family",2=>"Not Active Family"),"Active Family","active",""," icon-pencil7",0,"select2Class");
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
						<th class="col-lg-1">V. No</th>
						<th class="col-lg-2">Camp</th>
						<th class="col-lg-2">Name</th>
						<th class="col-lg-2">Case ID</th>
						<th class="col-lg-1">Date</th>
                        <th class="col-lg-1">#Ltr</th>
						<th class="col-lg-1">R Number</th>                        
						<th class="col-lg-2">Finger Print</th>
					</tr>
				</thead>
				<tbody id="reportBody">
					
				</tbody>
                <tfoot>
					<tr class="border-double bg-blue">
                        <th class="col-lg-1">V. No</th>
						<th class="col-lg-2">Camp</th>
						<th class="col-lg-2">Name</th>
                        <th class="col-lg-2">Case ID</th>
                        <th class="col-lg-1">Date</th>
                        <th class="col-lg-1">#Ltr</th>
						<th class="col-lg-1">R Number</th>                                                
						<th class="col-lg-2">Finger Print</th>
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
                    "type":"returnDataForFamily",
                    "campID":$("#campID").val(),
                    "active":$("#active").val(),
                    "startDate":$("#startDate").val(),
                    "endDate":$("#endDate").val()
                },
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    $("#reportBody").empty();
                    for (let index = 0; index < res.length; index++) {
                        $("#reportBody").append(`
                            <tr>
                                <td>${res[index]["FMYCaseNumber"]}</td>
                                <td>${res[index]["CMPName"]}</td>
                                <td>${res[index]["FMYFamilyName"]}</td>
                                <td>${res[index]["FMYFamilyCaseID"]}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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
            window.open(`pdf/report_family_detail.php?campID=${$("#campID").val()}&startDate=${$("#startDate").val()}&endDate=${$("#endDate").val()}&active=${$("#active").val()}&startNumber=${$("#startNumber").val()}&endNumber=${$("#endNumber").val()}`);
        });
    });
</script>                      