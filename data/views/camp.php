
<?php 
    $fileName=__FILE__;
    include_once "header.php";
    $round = $database->return_data2(array(
        "tablesName"=>array("round"),
        "columnsName"=>array("RNDID","RNDNumber"),
        "conditions"=>array(
            array("columnName"=>"RNDDeleted","operation"=>"=","value"=>0,"link"=>"")
        ),
        "others"=>"",
        "returnType"=>"key_all"
    ));
    $roundIDs=array();
    for($i=count($round)-1;$i>=0;--$i){
        $roundIDs[$round[$i]["RNDID"]]="Round ".$round[$i]["RNDNumber"];
    }
?>
<div id="addcampCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-6","text","Camp Name","CMPName_ISZN",""," icon-pencil3");
                    echo input2("col-sm-6",$roundIDs,"Current Round","CMPRNDFORID_IHZN","required","icon-history",0,"select2Class");
                ?></div>
                <div class="form-group"><?php
					echo input3("col-sm-12","","CMPNote_IAZN","editors");
				?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("savecampFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelcampFormCollapse","#addcampCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editcampModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editcampForm" method="post" id="editcampForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Camp</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="CMPID_UIZP" id="CMPID_UIZP" value="">
                    <div class="form-group"><?php 
                        echo input1("col-sm-6","text","Camp Name","CMPName_USRN",""," icon-pencil3");
                        echo input2("col-sm-6",$roundIDs,"Current Round","CMPRNDFORID_UHRN","required","icon-history",0,"select2Class");
                    ?></div>
                    <div class="form-group"><?php
                        echo input3("col-sm-12","","CMPNote_UARN","editors");
                    ?></div>
                </div>
                <div class="modal-footer"><?php
				    echo button2("savecampFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelcampFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatablecampView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Camp Name</th>
                <th class="multi_lang">Active Round</th>
                <th class="multi_lang" width="40%">Camp Note</th>
                <th class="multi_lang">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="controllers/camp.js?random=<?php echo uniqid(); ?>"></script>
    