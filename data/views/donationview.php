
<?php 
    $fileName=__FILE__;
    include_once "header.php";
    if(!isset($_GET["fmyid"]) || !is_numeric($_GET["fmyid"])){
        echo '<script>window.location="starter.php";</script>';
    }else{
        $countFamily = $database->return_data2(array(
            "tablesName"=>array("family"),
            "columnsName"=>array("*"),
            "conditions"=>array(
                array("columnName"=>"FMYID","operation"=>"=","value"=>$_GET["fmyid"],"link"=>"and "),
                array("columnName"=>"FMYDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"row_count"
        ));
        if($countFamily!=1){
            echo '<script>window.location="starter.php";</script>';
        }else{
            echo '
                <input type="hidden" name="FMYID" id="FMYID" value="'.$_GET["fmyid"].'">
            ';

        }
    }
?>
<div id="editdonationviewModal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-full">
        <form class="form-horizontal"  name="editdonationviewForm" method="post" id="editdonationviewForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="height: 300px !important;overflow: scroll;">
                        <table class="table table-striped table-bordered table-condensed">
                            <thead class="thead-dark"><tr>
                                <th scope="col">ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Product Type</th>
                                <th scope="col">Product Number</th>
                            </tr></thead>
                            <tbody id="donationviewTable">
                            </tbody>
                        </table>
                    </div>	
                    
                </div>
                <div class="modal-footer"><?php
				    echo button2("canceldonationviewFormModal","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
		        ?></div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-flat">
    <table class="table" id="datatabledonationviewView">
        <thead>
            <tr>
                <th class="multi_lang">ID</th>
                <th class="multi_lang">Family Name</th>
                <th class="multi_lang">Round Number</th>
                <th class="multi_lang">Create By</th>
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
<script type="text/javascript" src="controllers/donationview.js?random=<?php echo uniqid(); ?>"></script>
    