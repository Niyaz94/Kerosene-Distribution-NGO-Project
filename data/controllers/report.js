
        $(document).ready(function () {
            $("#addreportForm").on("submit", function (e) {
                e.preventDefault();
                $("#savereportFormCollapse").attr("disabled", true);       
                var formData = new FormData($(this)[0]);
                formData.append("PageName",$("#PageName").val());
                formData.append("",CKEDITOR.instances[""].getData());
                formData.append("type","create");
                $.ajax({
                    url: "models/_report.php",
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
                            $("#addreportForm")[0].reset();
                            CKEDITOR.instances[""].setData(""); 
                            $("#datatablereportView").DataTable().ajax.reload(null, false);
                            $("#addreportCollapse").collapse("hide");
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        }else{
                            oneAlert("error","Error!!!",res.data)
                        }
                        $("#savereportFormCollapse").attr("disabled",false);
                    },
                    fail: function (err){
                        oneAlert("error","Error!!!",res.data)
                        $("#savereportFormCollapse").attr("disabled",false);
                    },
                    always:function(){
                        console.log("complete");
                    }
                });
            });
            $("#editreportForm").on("submit", function (e) {
                e.preventDefault();
                $("#savereportFormModal").attr("disabled", true);
                var formData = new FormData($(this)[0]);
                formData.append("PageName",$("#PageName").val());
                formData.append("type","update");
                formData.append("",CKEDITOR.instances[""].getData());
                $.ajax({
                    url: "models/_report.php",
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
                            $("#editreportForm")[0].reset();
                            CKEDITOR.instances[""].setData(""); 
                            $("#datatablereportView").DataTable().ajax.reload(null, false);
                            $("#editreportModal").modal("toggle");
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        }else{
                          oneAlert("error","Error!!!",res.data);
                        }
                        $("#savereportFormModal").attr("disabled",false);
                    },
                    fail: function (err){
                        oneAlert("error","Error!!!",res.data)
                        $("#savereportFormModal").attr("disabled",false);
                    },
                    always:function(){
                        console.log("complete");
                    }
                });
            });
            addingExtenton();
            table = $("#datatablereportView").DataTable({
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
                    "url": "models/_report.php",
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
                                    "REPID":row[0]
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
            generalConfigDatatable(table,"#datatablereportView");
            generalConfig(); 
        });
        function deletereport(REPID) {
            deletedRow("#datatablereportView",{
                "PageName":$("#PageName").val(),
                "REPID_UIZP": REPID,
                "REPDeleted_UIZ":1,
                "table":"report",
                "symbol":"REP"
            });
        }
        function editreport(REPID) {
            $("#REPID_UIZP").val(Number(REPID));
            getDataFromServer("editreportForm","'report'");  
            $("#editreportModal").modal("toggle");
        }  
    