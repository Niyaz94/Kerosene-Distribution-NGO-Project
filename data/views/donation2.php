<?php 
    $fileName=__FILE__;
    include_once "header.php";
    if($_SESSION["ADMProfileType"]==2){
        $ADMCampID="0";
    }else{
        $ADMCampID=empty($_SESSION["ADMCampID"])?"-1":$_SESSION["ADMCampID"];
    }
    echo '<input type="hidden" name="ADMCampID" id="ADMCampID" value="'.$ADMCampID.'">';
    echo '<input type="hidden" name="currentDate" id="currentDate" value="'.date("Y-m-d").'">';
?>
<div class="panel panel-flat">
    <table class="table" id="datatabledonationView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Family Name</th>
                <th class="multi_lang">Case ID</th>
                <th class="multi_lang">Camp Name</th>
                <th class="multi_lang">Barcode</th>
                <th class="multi_lang">State</th>
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
<div id="insertManualyModal" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <form class="form-horizontal"  name="insertManualyForm" method="post" id="insertManualyForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Insert Manually</h4>
                </div>
                <div class="modal-body" id="insertManualyBody">	
                    <div class="col-sm-12">
                        <div class="form-group"><?php
                            echo input1("","number","Barcode Number","barcodeNumber","required","icon-barcode2","","","","min=100000 max=999999");
                        ?></div>
                    </div>  
                </div>
                <div class="modal-footer"><?php
				    echo button2("insertManualySave","button","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("insertManualyCancel","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"');
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
<script type="text/javascript" src="controllers/donation2.js?random=<?php echo uniqid(); ?>"></script>
    