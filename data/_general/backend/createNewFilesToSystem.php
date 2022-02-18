<?php
	class _createNewFilesToSystem{
        public static function createNewFile($filePermissionData){
            for($i=0;$i<count($filePermissionData);$i++){
                extract($filePermissionData[$i]);
                if($create=="yes" && $page_type=="normal"){
if(!file_exists("views/".$page.".php")){
    $fp = fopen("views/".$page.".php", 'a+');
    fwrite($fp,'
        <?php 
            $fileName=__FILE__;
            include_once "header.php";
        ?>
        <div id="add'.$page.'Collapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
            <div class="panel-body">
                <form class="form-horizontal" method="post" id="add'.$page.'Form" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <div class="form-group"><?php 
                            echo input1("","text","Phone","ADMPhoneNumber_IPZ","","icon-phone-plus");
                            echo input2("",array(""=>"",0=>"shop",1=>"pergola",2=>"office"),"Shop Category","SOPCategory_IHZN","required","icon-graph",0,"select2Class");
                            echo input1("","text","Agreement Start Date","AGRDateStart_IDZN","required"," icon-calendar","","","dateStyle");
                            echo input1("","text","Payment Start Date","AGRPaymentStart_IKZN","required"," icon-calendar","","","dateMonthandYear");
                            echo input2("",array(0=>"permision",1=>"admin"),"Profile Type","ADMProfileType_ICZ","","icon-unlocked2");
                        ?></div>
                    </div>  
                    <div class="text-right"><?php
                        echo button2("save'.$page.'FormCollapse","submit","Save","icon-floppy-disk","btn btn-primary btn-labeled btn-labeled-right");
                        echo button3("cancel'.$page.'FormCollapse","#add'.$page.'Collapse","Close","icon-cross","btn btn-labeled btn-labeled-right bg-danger heading-btn",\'data-toggle="collapse"\')
                    ?></div>
                </form> 
            </div>
        </div>
        <h4>Hello World</h4>
        <div id="edit'.$page.'Modal" class="modal fade" style="display: none;">
            <div class="modal-dialog modal-full">
                <form class="form-horizontal"  name="edit'.$page.'Form" method="post" id="edit'.$page.'Form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
                            <h4 class="modal-title multi_lang">____________</h4>
                        </div>
                        <div class="modal-body">	
                            <input type="hidden" name="'.$symbol.'ID_UIZP" id="'.$symbol.'ID_UIZP" value="">
                            
                        </div>
                        <div class="modal-footer"><?php
						    echo button2("save'.$page.'FormModal","submit","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
						    echo button2("cancel'.$page.'FormModal","button","Close","icon-cross","btn btn-warning",\'data-dismiss="modal"\')
				        ?></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-flat">
            <table class="table" id="datatable'.$page.'View">
                <thead>
                    <tr>
                        <th class="multi_lang"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <script type="text/javascript" src="controllers/'.$page.'.js?random=<?php echo uniqid(); ?>"></script>
    ');
    fclose($fp);
    chmod("views/".$page.".php", 0777);
}
if(!file_exists("models/_".$page.".php")){
    $fp = fopen("models/_".$page.".php", 'a+');
    fwrite($fp,'
        <?php
            include_once "../_general/backend/_header.php";
            if (isset($_POST["type"]) || isset($_GET["type"])){
                if( isset($_GET["type"]) && ($_GET["type"] == "load")){
                    $table = "";
                    $primaryKey = "";
                    $where="";
                    $columns =  array(
                        array( "db" => "", "dt" => 0 ),  
                    );
                    echo json_encode(
                        SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
                    );
                    exit;
                }
                if ($_POST["type"] == "create") {	
                    //testData($_POST,0);
                    $validation=new class_validation($_POST,"'.$symbol.'");
                    $data=$validation->returnLastVersion();
                    extract($data);
                    $res = $database->return_data2(array(
                        "tablesName"=>array("'.$page.'"),
                        "columnsName"=>array("*"),
                        "conditions"=>array(
                            array("columnName"=>"columnName","operation"=>"=","value"=>0,"link"=>"and"),
                        ),
                        "others"=>"",
                        "returnType"=>"row_count"
                    ));
                    if($res>0){
                        echo jsonMessages(false,1);
                        exit;
                    }
                    $res = $database->insert_data2("'.$page.'",$data);
                    if ($res) {	
                        echo jsonMessages(true,2);
                        exit;
                    }else{
                        echo jsonMessages(false,1);
                        exit;
                    }
                }
                if ($_POST["type"] == "update") {
                    //testData($_POST,0);
                    $validation=new class_validation($_POST,"'.$symbol.'");
                    $data=$validation->returnLastVersion();
                    extract($data);
                    $res = $database->return_data2(array(
                        "tablesName"=>array("'.$page.'"),
                        "columnsName"=>array("*"),
                        "conditions"=>array(
                            array("columnName"=>"'.$symbol.'Deleted","operation"=>"=","value"=>0,"link"=>"and"),
                            array("columnName"=>"'.$symbol.'ID","operation"=>"!=","value"=>$'.$symbol.'ID,"link"=>"")
                        ),
                        "others"=>"",
                        "returnType"=>"row_count"
                    ));
                    if($res>0){
                        echo jsonMessages(false,1);
                        exit;
                    }
                    $res = $database->update_data2(array(
                        "tablesName"=>"'.$page.'",
                        "userData"=>$data,
                        "conditions"=>array()
                    ));
                    if ($res) {
                        echo jsonMessages(true,1);
                        exit;
                    }else{
                        echo jsonMessages(false,1);
                        exit;
                    }
                }
            }else{
                header("Location:../");
                exit;
            }
        ?>
    ');
    fclose($fp);
    chmod("models/_".$page.".php", 0777);
}
if(!file_exists("_general/notes/".$page)){
    $fp = fopen("_general/notes/".$page, 'a+');
    fwrite($fp,'
    ');
    fclose($fp);
    chmod("_general/notes/".$page, 0777);
}
if(!file_exists("controllers/".$page.".js")){
    $fp = fopen("controllers/".$page.".js", 'a+');
    fwrite($fp, '
        $(document).ready(function () {
            $("#add'.$page.'Form").on("submit", function (e) {
                e.preventDefault();
                $("#save'.$page.'FormCollapse").attr("disabled", true);       
                var formData = new FormData($(this)[0]);
                formData.append("PageName",$("#PageName").val());
                formData.append("",CKEDITOR.instances[""].getData());
                formData.append("type","create");
                $.ajax({
                    url: "models/_'.$page.'.php",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        res=JSON.parse(res);
                        if(res.is_success == true){
                            $("#add'.$page.'Form")[0].reset();
                            CKEDITOR.instances[""].setData(""); 
                            $("#datatable'.$page.'View").DataTable().ajax.reload(null, false);
                            $("#add'.$page.'Collapse").collapse("hide");
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        }else{
                            oneAlert("error","Error!!!",res.data)
                        }
                        $("#save'.$page.'FormCollapse").attr("disabled",false);
                    },
                    fail: function (err){
                        oneAlert("error","Error!!!",res.data)
                        $("#save'.$page.'FormCollapse").attr("disabled",false);
                    },
                    always:function(){
                        console.log("complete");
                    }
                });
            });
            $("#edit'.$page.'Form").on("submit", function (e) {
                e.preventDefault();
                $("#save'.$page.'FormModal").attr("disabled", true);
                var formData = new FormData($(this)[0]);
                formData.append("PageName",$("#PageName").val());
                formData.append("type","update");
                formData.append("",CKEDITOR.instances[""].getData());
                $.ajax({
                    url: "models/_'.$page.'.php",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    dataType: "json",
                    complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if(res.is_success == true){
                            $("#edit'.$page.'Form")[0].reset();
                            CKEDITOR.instances[""].setData(""); 
                            $("#datatable'.$page.'View").DataTable().ajax.reload(null, false);
                            $("#edit'.$page.'Modal").modal("toggle");
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        }else{
                          oneAlert("error","Error!!!",res.data);
                        }
                        $("#save'.$page.'FormModal").attr("disabled",false);
                    },
                    fail: function (err){
                        oneAlert("error","Error!!!",res.data)
                        $("#save'.$page.'FormModal").attr("disabled",false);
                    },
                    always:function(){
                        console.log("complete");
                    }
                });
            });
            addingExtenton();
            table = $("#datatable'.$page.'View").DataTable({
                buttons: {
                    buttons: dtButtons()
                },
                lengthMenu: [
                    [10, 25, 50, 100],
                    ["10", "25","50","100"]
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "models/_'.$page.'.php",
                    "data": function (d) {
                        d.type = "load";
                    }
                },
                drawCallback: function () {
                    tooltip1("");
                },
                "columnDefs": [
                    {
                        "targets": 6,
                        "data": null,
                        "render": function (data, type, row) {
                            return returnTablButtons(
                                JSON.parse($("#pageInfo").val()),
                                JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),
                                {},
                                {
                                    "'.$symbol.'ID":row[0]
                                },
                                "table"
                            );
                        }
                    },{
                        "targets": 4,
                        "render": function (data, type, row) {
                            if(data==0){
                                return `<span class="label label-block label-flat border-info text-slate-800" style="padding:6%">Shop</span>`;
                            }else{
                                return data;
                            }
                        }
                    }
                ],
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                },
                "order": [
                    [0, "desc"]
                ],
                "displayLength": 25,
                initComplete: function () {
                    $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),{},{},"header"));
                }
            });
            generalConfigDatatable(table,"#datatable'.$page.'View");
            generalConfig(); 
        });
        function delete'.$page.'('.$symbol.'ID) {
            deletedRow("#datatable'.$page.'View",{
                "PageName":$("#PageName").val(),
                "'.$symbol.'ID_UIZP": '.$symbol.'ID,
                "'.$symbol.'Deleted_UIZ":1,
                "table":"'.$page.'",
                "symbol":"'.$symbol.'"
            });
        }
        function edit'.$page.'('.$symbol.'ID) {
            $("#'.$symbol.'ID_UIZP").val(Number('.$symbol.'ID));
            getDataFromServer("edit'.$page.'Form","\''.$page.'\'");  
            $("#edit'.$page.'Modal").modal("toggle");
        }  
    ');
    fclose($fp);
    chmod("controllers/".$page.".js", 0777);
}
if(!file_exists("_general/style/".$page.".css")){
    $fp = fopen("_general/style/".$page.".css", 'a+');
    fwrite($fp, '
        h4{
            color:red;
            background-color:black;
            font-size:50px;
            text-align:center;
            margin:20px;
            padding:20px;
        }
        #datatable'.$page.'View th{
            text-align:center;
            font-size: 12px;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }
    ');
    fclose($fp);
    chmod("_general/style/".$page.".css", 0777);
}
                }

            }
        }
        public static function createNewReport($filePermissionData){
            for($i=0;$i<count($filePermissionData);$i++){
                extract($filePermissionData[$i]);
                if($create=="yes" && $page_type=="report"){
                    if(!file_exists("views/report/".$page.".php")){
                        $fp = fopen("views/report/".$page.".php", 'a+');
                        fwrite($fp,'
                            <style>
                            
                            </style>
        
                            <script>
                                $(document).ready(function () {
                                    function test(){
                                        $.ajax({
                                            url: "models/_'.$page.'.php",
                                            type: "POST",
                                            data: formData,
                                            complete: function () {
                                                oneCloseLoader("#"+$(this).parent().id,"self");
                                            },
                                            beforeSend: function () {
                                                oneOpenLoader("#"+$(this).parent().id,"self","dark");
                                            },
                                            success: function (res) {
                                            },
                                            fail: function (err){
                                            },
                                            always:function(){
                                            }
                                        });
                                    }
                                });
                            </script>
                        ');
                        fclose($fp);
                        chmod("../views/report/".$page.".php", 0777);
                    } 
                }
                
            }
            
        }

	}
?>