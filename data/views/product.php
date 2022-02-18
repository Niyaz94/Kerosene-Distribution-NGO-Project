
<?php 
    $fileName=__FILE__;
    include_once "header.php";
    $userPermission=json_decode($_SESSION["userPermission"],true);
    if($_SESSION["ADMProfileType"]==2){
        $ADMCampID="0";
    }else{
        $ADMCampID=$_SESSION["ADMCampID"];
    }
    echo '<input type="hidden" name="ADMCampID" id="ADMCampID" value="'.$ADMCampID.'">';
?>
<div id="addproductCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addproductForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    $res = $database->return_data2(array(
                        "tablesName"=>array("camp"),
                        "columnsName"=>array("CMPID","CMPName"),
                        "conditions"=>array(
                            //array("columnName"=>"CMPID","operation"=>($_SESSION["ADMProfileType"]==2?"nin":"in"),"value"=>$ADMCampID,"link"=>"and"),
                            array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>"")
                        ),
                        "others"=>"",
                        "returnType"=>"key_all"
                    ));
                    $campIDs=array();
                    for($i=0;$i<count($res);++$i){
                        $campIDs[$res[$i]["CMPID"]]=$res[$i]["CMPName"];
                    }
                    echo input4("col-sm-6",$campIDs,"Camp Name","PDDCMPFORID_INZN","required"," icon-pencil7",0,"multiselect",'selected="selected"');
                    echo input1("col-sm-6","text","Item Name","PDUName_ISZN","required"," icon-pencil7");
                ?></div>
                <div class="form-group"><?php 
                    echo input2("col-sm-6",array(""=>"",0=>"Quantity",1=>"Meter",2=>"Liter",3=>"Packet"),"Item Type","PDUType_IHZN","required"," icon-pencil7",0,"select2Class");
                    echo input2("col-sm-6",array(""=>"",0=>"active",1=>"not active"),"Active Item","PDUActive_IHZN",""," icon-pencil7",0,"select2Class");
                ?></div>
                 <div class="form-group"><?php
                    echo input3("col-sm-12","","PDUNote_IAZN","editors");
                ?></div>
            </div>
           
           
            <div class="text-right"><?php
                echo button2("saveproductFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelproductFormCollapse","#addproductCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editproductModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editproductForm" method="post" id="editproductForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Item</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="PDUID_UIZP" id="PDUID_UIZP" value="">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            $res = $database->return_data2(array(
                                "tablesName"=>array("camp"),
                                "columnsName"=>array("CMPID","CMPName"),
                                "conditions"=>array(
                                    //array("columnName"=>"CMPID","operation"=>($_SESSION["ADMProfileType"]==2?"nin":"in"),"value"=>$ADMCampID,"link"=>"and"),
                                    array("columnName"=>"CMPDeleted","operation"=>"=","value"=>0,"link"=>"")
                                ),
                                "others"=>"",
                                "returnType"=>"key_all"
                            ));
                            $campIDs=array();
                            for($i=0;$i<count($res);++$i){
                                $campIDs[$res[$i]["CMPID"]]=$res[$i]["CMPName"];
                            }
                            echo input4("col-sm-6",$campIDs,"Camp Name","PDDCMPFORID_UNRN","required"," icon-pencil7",0,"multiselect",'');
                            echo input1("col-sm-6","text","Item Name","PDUName_USRN","required"," icon-pencil7");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input2("col-sm-6",array(""=>"",0=>"Quantity",1=>"Meter",2=>"Liter",3=>"Packet"),"Item Type","PDUType_UHRN","required"," icon-pencil7",0,"select2Class");
                            echo input2("col-sm-6",array(""=>"",0=>"active",1=>"not active"),"Active Item","PDUActive_UHRN",""," icon-pencil7",0,"select2Class");
                        ?></div>
                        <div class="form-group"><?php
                            echo input3("col-sm-12","","PDUNote_UARN","editors");
                        ?></div>
                    </div>
                       
                </div>
                <div class="modal-footer"><?php
				    echo button2("saveproductFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelproductFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div id="editproductAmountModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal"  name="editproductAmountForm" method="post" id="editproductAmountForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Item Amount</h4>
                </div>
                <div class="modal-body" id="addToAmountBody">	

                </div>
                <div class="modal-footer"><?php
				    echo button2("saveproductAmountFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelproductAmountFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatableproductView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Item Name</th>
                <th class="multi_lang">Item Type</th>
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
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="controllers/product.js?random=<?php echo uniqid(); ?>"></script>