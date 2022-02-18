
<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<div id="addroundCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addroundForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input1("col-sm-4","number","Round Number","RNDNumber_IIZN","required","icon-history","1","","","min=1");
                    echo input1("col-sm-4","text","Start Date","RNDStartDate_IDZN","required"," icon-calendar","","","dateStyle");
                    echo input1("col-sm-4","text","End Date","RNDEndDate_IDZN","required"," icon-calendar","","","dateStyle");
                ?></div>
                <div class="form-group"><?php 
                    echo input1("col-sm-6","text","Start Date","RNDStartTime_IDZN","required"," icon-calendar","","","justTimeStyle");
                    echo input1("col-sm-6","text","End Date","RNDEndTime_IDZN","required"," icon-calendar","","","justTimeStyle");
                ?></div>
                <div class="form-group"><?php
                    echo input3("col-sm-12","","RNDNote_IAZN","editors");
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("saveroundFormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                echo button3("cancelroundFormCollapse","#addroundCollapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",'data-toggle="collapse"')
            ?></div>
        </form> 
    </div>
</div>
<div id="editroundModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editroundForm" method="post" id="editroundForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                    <h4 class="modal-title multi_lang">Edit Round</h4>
                </div>
                <div class="modal-body">	
                    <input type="hidden" name="RNDID_UIZP" id="RNDID_UIZP" value="">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("col-sm-4","number","Round Number","RNDNumber_UIRN","required","icon-history","1","","","min=1");
                            echo input1("col-sm-4","text","Start Date","RNDStartDate_UDRN","required"," icon-calendar","","","dateStyle");
                            echo input1("col-sm-4","text","End Date","RNDEndDate_UDRN","required"," icon-calendar","","","dateStyle");
                        ?></div>
                        <div class="form-group"><?php 
                            echo input1("col-sm-6","text","Start Date","RNDStartTime_UDRN","required"," icon-calendar","","","justTimeStyle");
                            echo input1("col-sm-6","text","End Date","RNDEndTime_UDRN","required"," icon-calendar","","","justTimeStyle");
                        ?></div>
                        <div class="form-group"><?php
                            echo input3("col-sm-12","","RNDNote_UARN","editors");
                        ?></div>
                    </div>  
                    
                </div>
                <div class="modal-footer"><?php
				    echo button2("saveroundFormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				    echo button2("cancelroundFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatableroundView">
        <thead>
            <tr>
                <th class="multi_lang">#</th>
                <th class="multi_lang">Round Number</th>
                <th class="multi_lang">Start Date</th>
                <th class="multi_lang">ENd Date</th>
                <th class="multi_lang">Start Time</th>
                <th class="multi_lang">ENd Time</th>
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
<script type="text/javascript" src="controllers/round.js?random=<?php echo uniqid(); ?>"></script>
    