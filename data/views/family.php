
<?php 
    $fileName=__FILE__;
    include_once "header.php";
    if($_SESSION["ADMProfileType"]==2){
        $ADMCampID="0";
    }else{
        $ADMCampID=empty($_SESSION["ADMCampID"])?"-1":$_SESSION["ADMCampID"];
    }
    echo '<input type="hidden" name="ADMCampID" id="ADMCampID" value="'.$ADMCampID.'">';
    $res = $database->return_data2(array(
        "tablesName"=>array("camp"),
        "columnsName"=>array("CMPID","CMPName"),
        "conditions"=>array(
            array("columnName"=>"CMPID","operation"=>($_SESSION["ADMProfileType"]==2?"nin":"in"),"value"=>$ADMCampID,"link"=>"and"),
            array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>"")
        ),
        "others"=>"",
        "returnType"=>"key_all"
    ));
    $campIDs=array(""=>"");
    for($i=0;$i<count($res);++$i){
        $campIDs[$res[$i]["CMPID"]]=$res[$i]["CMPName"];
    }
?>
<div id="addfamilyCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addfamilyForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input2("col-sm-6",$campIDs,"Camp Name","FMYCMPFORID_IHZN",""," icon-home9",0,"select2Class");
                    echo input1("col-sm-6","number","Barcode Number","FMYBarcode_IIZN",""," icon-barcode2","","readonly");
                ?></div>
                <div class="form-group"><?php
                    echo input1("col-sm-6","text","Family Name","FMYFamilyName_ISZN","required"," icon-man-woman");
                    echo input1("col-sm-6","number","Family Number","FMYFamilyNumber_IIZN","required"," icon-tree6","0","","","min=0");
                ?></div> 
                <div class="form-group"><?php 
                    echo input2("col-sm-6",array(""=>"",0=>"active",1=>"not active"),"Active Family","FMYActive_IHZN",""," icon-pencil7",0,"select2Class");
                    echo input1("col-sm-6","text","Family Case ID","FMYFamilyCaseID_ISZN","required"," icon-key");
                ?></div>
                <div class="form-group"><?php
                    echo input3("col-sm-12","","FMYNote_IAZN","editors");
                ?></div>
            </div> 
            <div class="text-right"><?php
                echo button2("savefamilyFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelfamilyFormCollapse","#addfamilyCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editfamilyModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editfamilyForm" method="post" id="editfamilyForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Family</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="FMYID_UIZP" id="FMYID_UIZP" value="">
                        <div class="col-sm-12">
                            <div class="form-group"><?php 
                                echo input2("col-sm-6",$campIDs,"Camp Name","FMYCMPFORID_UHRN",""," icon-home9",0,"select2Class");
                                echo input1("col-sm-6","number","Barcode Number","FMYBarcode_UIRN",""," icon-barcode2","","readonly");
                            ?></div>
                            <div class="form-group"><?php
                                echo input1("col-sm-6","text","Family Name","FMYFamilyName_USRN","required"," icon-man-woman");
                                echo input1("col-sm-6","number","Family Number","FMYFamilyNumber_UIRN","required"," icon-tree6","0","","","min=0");
                            ?></div>
                            <div class="form-group"><?php 
                                echo input2("col-sm-6",array(""=>"",0=>"active",1=>"not active"),"Active Family","FMYActive_UHRN",""," icon-pencil7",0,"select2Class");
                                echo input1("col-sm-6","text","Family Case ID","FMYFamilyCaseID_USRN","required"," icon-key");
                            ?></div>
                            <div class="form-group"><?php
                                echo input3("col-sm-12","","FMYNote_UARN","editors");
                            ?></div>
                        </div>  
                </div>
                <div class="modal-footer"><?php
				    echo button2("savefamilyFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelfamilyFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="showFamilyBarcodeModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal"  name="showFamilyBarcodeForm" method="post" id="showFamilyBarcodeForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Show Family Barcode</h4>
                </div>
                <div class="modal-body">	
                    <div class="col-sm-12">
                        <div class="form-group"><?php
                            echo input2("col-sm-4",$campIDs,"Camp Name","campid",""," icon-home9",0,"select2Class");
                            echo input1("col-sm-4","text","Start Date","start_date","required"," icon-calendar","","","dateStyle");
                            echo input1("col-sm-4","text","End Date","end_date","required"," icon-calendar","","","dateStyle");
                        ?></div>
                        <div class="form-group"><?php
                            echo input1("col-sm-6","number","Family Number","startNumber","required","icon-tree6","0","","","min=0");
                            echo input1("col-sm-6","number","Family Number","endNumber","required",  "icon-tree6","0","","","min=0");
                        ?></div>
                    </div>  
                </div>
                <div class="modal-footer"><?php
				    echo button2("showFamilyBarcodeSave","button","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("showFamilyBarcodeCancel","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="insertCSVModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal"  name="insertCSVForm" method="post" id="insertCSVForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Insert Family Name</h4>
                </div>
                <div class="modal-body">	
                    <div class="col-sm-12">
                        <div class="form-group"><?php
                            echo file3("","insertfile","Add CSV File",'');
                        ?></div>
                    </div>  
                </div>
                <div class="modal-footer"><?php
				    echo button2("insertCSVSave","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("insertCSVCancel","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="insertCSVActiveModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal"  name="insertCSVActiveForm" method="post" id="insertCSVActiveForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Insert Family Name</h4>
                </div>
                <div class="modal-body">	
                    <div class="col-sm-12">
                        <div class="form-group"><?php
                            echo file3("","insertfile","Add CSV File",'');
                        ?></div>
                    </div>  
                </div>
                <div class="modal-footer"><?php
				    echo button2("insertCSVActiveSave","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("insertCSVActiveCancel","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="insertCSVDeactiveModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal"  name="insertCSVDeactiveForm" method="post" id="insertCSVDeactiveForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Deactive Family</h4>
                </div>
                <div class="modal-body">	
                    <div class="col-sm-12">
                        <div class="form-group"><?php
                            echo file3("","insertfile","Add CSV File",'');
                        ?></div>
                    </div>  
                </div>
                <div class="modal-footer"><?php
				    echo button2("insertCSVDeactiveSave","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("insertCSVDeactiveCancel","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="editfamilymaxamountModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal"  name="editfamilymaxamountForm" method="post" id="editfamilymaxamountForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Family Balance</h4>
                </div>
                <div class="modal-body" id="editfamilymaxamountBody">	

                </div>
                <div class="modal-footer"><?php
				    echo button2("editfamilymaxamountSave","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("editfamilymaxamountCancel","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"');
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatablefamilyView">
        <thead>
            <tr>
                <th class="multi_lang" width="5%">ID</th>
                <th class="multi_lang">Family Name</th>
                <th class="multi_lang">Case ID</th>
                <th class="multi_lang">Camp Name</th>
                <th class="multi_lang" id="BarcodeReader">Barcode</th>
                <th class="multi_lang">Active</th>
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
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="controllers/family.js?random=<?php echo uniqid(); ?>"></script>
    